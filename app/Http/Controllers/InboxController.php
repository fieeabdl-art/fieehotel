<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use App\Models\InboxEntry;
use App\Models\User;

class InboxController extends Controller
{
    // List inbox with search, folder and pagination
    public function index(Request $request)
    {
        $userId = Auth::id();

        $query = InboxEntry::query();

        // Only show messages for this user or global messages (user_id null)
        $query->where(function ($q) use ($userId) {
            $q->where('user_id', $userId)->orWhereNull('user_id');
        });

        // Folder filter
        $folder = $request->get('folder', 'inbox');
        if ($folder === 'starred') {
            $query->where('is_starred', true);
        } elseif ($folder === 'important') {
            $query->where('is_important', true);
        } elseif ($folder === 'sent') {
            // Sent messages: where sender_email == current user email
            if (Auth::check()) {
                $query->where('sender_email', Auth::user()->email);
            }
        } elseif ($folder === 'drafts') {
            // not implemented, leave empty
        } elseif ($folder === 'spam') {
            // For now use messages where title contains [SPAM] placeholder if any; placeholder behavior
            $query->where('title', 'like', '%[SPAM]%');
        }

        // Search
        if ($q = $request->get('q')) {
            $query->where(function ($sub) use ($q) {
                $sub->where('sender_name', 'like', "%{$q}%")
                    ->orWhere('sender_email', 'like', "%{$q}%")
                    ->orWhere('title', 'like', "%{$q}%")
                    ->orWhere('message', 'like', "%{$q}%");
            });
        }

        $entries = $query->orderBy('created_at', 'desc')->paginate(10);

        // counts
        $inboxCount = InboxEntry::where('user_id', $userId)->where('is_read', false)->count();
        $spamCount = InboxEntry::where('user_id', $userId)->where('title', 'like', '%[SPAM]%')->count();

        return view('user.inbox', compact('entries', 'inboxCount', 'spamCount'));
    }

    // Show a single message
    public function show($id)
    {
        $entry = InboxEntry::findOrFail($id);

        // Mark as read if belongs to user
        if ($entry->user_id === Auth::id()) {
            $entry->is_read = true;
            $entry->save();
        }

        return view('user.inbox_show', compact('entry'));
    }

    // Compose form
    public function compose()
    {
        return view('user.inbox_compose');
    }

    // Send message
    public function send(Request $request)
    {
        $validated = $request->validate([
            'to_email' => 'nullable|email',
            'to_user_id' => 'nullable|exists:users,id',
            'title' => 'required|string|max:255',
            'message' => 'nullable|string',
        ]);

        // Determine recipient
        $recipientId = $validated['to_user_id'] ?? null;
        if (!$recipientId && !empty($validated['to_email'])) {
            $u = User::where('email', $validated['to_email'])->first();
            if ($u) {
                $recipientId = $u->id;
            }
        }

        $senderName = Auth::check() ? (Auth::user()->name ?? Auth::user()->email) : 'Guest';
        $senderEmail = Auth::check() ? (Auth::user()->email ?? null) : null;

        $entry = InboxEntry::create([
            'user_id' => $recipientId,
            'sender_name' => $senderName,
            'sender_email' => $senderEmail,
            'title' => $validated['title'],
            'message' => $validated['message'] ?? null,
            'is_read' => false,
        ]);

        return redirect('/inbox')->with('success', 'Pesan berhasil dikirim.');
    }

    // Toggle star
    public function toggleStar(Request $request, $id)
    {
        $entry = InboxEntry::findOrFail($id);
        $entry->is_starred = !($entry->is_starred ?? false);
        $entry->save();

        return response()->json(['active' => $entry->is_starred]);
    }

    // Toggle important
    public function toggleImportant(Request $request, $id)
    {
        $entry = InboxEntry::findOrFail($id);
        $entry->is_important = !($entry->is_important ?? false);
        $entry->save();

        return response()->json(['active' => $entry->is_important]);
    }

    // Bulk actions
    public function bulkAction(Request $request)
    {
        $action = $request->input('action');
        $ids = $request->input('ids', []);

        if (empty($ids)) {
            return redirect()->back()->with('error', 'Pilih minimal satu pesan terlebih dahulu.');
        }

        $entries = InboxEntry::whereIn('id', $ids)->get();

        foreach ($entries as $entry) {
            switch ($action) {
                case 'mark-read':
                    $entry->is_read = true;
                    $entry->save();
                    break;
                case 'mark-unread':
                    $entry->is_read = false;
                    $entry->save();
                    break;
                case 'mark-important':
                    $entry->is_important = true;
                    $entry->save();
                    break;
                case 'spam':
                    $entry->title = ($entry->title ?? '') . ' [SPAM]';
                    $entry->save();
                    break;
                case 'delete':
                    $entry->delete();
                    break;
            }
        }

        return redirect()->back()->with('success', 'Aksi berhasil diterapkan.');
    }

    // Delete single
    public function destroy($id)
    {
        $entry = InboxEntry::findOrFail($id);
        $entry->delete();
        return redirect('/inbox')->with('success', 'Pesan dihapus.');
    }
}
