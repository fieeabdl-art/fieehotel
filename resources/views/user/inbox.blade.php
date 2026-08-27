@extends('layouts.app')

@section('content')

@vite(['resources/css/inbox.css','resources/js/inbox.js'])
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

<div class="container-fluid inbox-container">

    <div class="row justify-content-center">
        <div class="col-12">

            <div class="inbox-card">
                <div class="grid-layout">

                    

                    {{-- ================= SIDEBAR ================= --}}
                    <div class="col-md-3 border-end inbox-sidebar" style="background:#fafbfc; padding:24px 18px;">

                        <div class="d-flex align-items-center gap-2 mb-2">
                            <button type="button" class="btn btn-light btn-sm me-2" onclick="history.back()" title="Kembali">
                                <i class="bi bi-arrow-left"></i>
                            </button>
                            <i class="bi bi-inbox-fill" style="font-size:22px; color:#495057;"></i>
                            <div>
                        <h4 class="inbox-title mb-0">Inbox</h4>
                        <div class="inbox-subtitle">{{ $inboxCount ?? $entries->where('is_read', false)->count() }} unread messages</div>
                            </div>
                        </div>

                        <a href="{{ url('/inbox/compose') }}" class="btn btn-primary w-100 mb-4 d-flex align-items-center justify-content-center" style="border-radius:8px; font-weight:600; padding:10px 12px;">
                            <i class="bi bi-pencil-fill me-2"></i> NEW MESSAGE
                        </a>

                        <div class="text-muted small fw-bold mb-2" style="letter-spacing:.5px;">FOLDER</div>

                        <div class="list-group list-group-flush">
                            <a href="{{ url('/inbox') }}"
                               class="list-group-item list-group-item-action d-flex justify-content-between align-items-center border-0 px-2 {{ request('folder', 'inbox') == 'inbox' ? 'active-folder' : '' }}"
                               style="background:transparent; border-radius:6px;">
                                <span><i class="bi bi-inbox me-2"></i>Inbox</span>
                                <span class="badge bg-secondary rounded-pill">{{ $entries->total() }}</span>
                            </a>
                            <a href="{{ url('/inbox?folder=starred') }}"
                               class="list-group-item list-group-item-action border-0 px-2 {{ request('folder') == 'starred' ? 'active-folder' : '' }}"
                               style="background:transparent; border-radius:6px;">
                                <i class="bi bi-star me-2"></i>Starred
                            </a>
                            <a href="{{ url('/inbox?folder=important') }}"
                               class="list-group-item list-group-item-action border-0 px-2 {{ request('folder') == 'important' ? 'active-folder' : '' }}"
                               style="background:transparent; border-radius:6px;">
                                <i class="bi bi-bookmark me-2"></i>Important
                            </a>
                            <a href="{{ url('/inbox?folder=sent') }}"
                               class="list-group-item list-group-item-action border-0 px-2 {{ request('folder') == 'sent' ? 'active-folder' : '' }}"
                               style="background:transparent; border-radius:6px;">
                                <i class="bi bi-send me-2"></i>Sent
                            </a>
                            <a href="{{ url('/inbox?folder=drafts') }}"
                               class="list-group-item list-group-item-action border-0 px-2 {{ request('folder') == 'drafts' ? 'active-folder' : '' }}"
                               style="background:transparent; border-radius:6px;">
                                <i class="bi bi-pencil-square me-2"></i>Drafts
                            </a>
                            <a href="{{ url('/inbox?folder=spam') }}"
                               class="list-group-item list-group-item-action d-flex justify-content-between align-items-center border-0 px-2 {{ request('folder') == 'spam' ? 'active-folder' : '' }}"
                               style="background:transparent; border-radius:6px;">
                                <span><i class="bi bi-folder me-2"></i>Spam</span>
                                @if(($spamCount ?? 0) > 0)
                                    <span class="badge bg-light text-muted rounded-pill">{{ $spamCount }}</span>
                                @endif
                            </a>
                        </div>
                    </div>

                    {{-- ================= MAIN PANEL ================= --}}
                    <div class="col-md-9" style="padding:24px;">


                        {{-- // Toolbar: dropdown aksi + kotak pencarian --}}
                        <form method="GET" action="{{ url('/inbox') }}" id="searchForm">
                            <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
                                <div class="d-flex align-items-center gap-2">
                                    <input type="checkbox" id="selectAll" class="form-check-input" title="Pilih semua">

                                    <div class="dropdown">
                                        <button class="btn btn-outline-secondary btn-sm dropdown-toggle" type="button" id="actionDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                                            Action
                                        </button>
                                        <ul class="dropdown-menu" aria-labelledby="actionDropdown">
                                            <li><button type="button" class="dropdown-item" onclick="submitBulkAction('mark-read')">Mark as read</button></li>
                                            <li><button type="button" class="dropdown-item" onclick="submitBulkAction('mark-unread')">Mark as unread</button></li>
                                            <li><button type="button" class="dropdown-item" onclick="submitBulkAction('mark-important')">Mark as important</button></li>
                                            <li><hr class="dropdown-divider"></li>
                                            <li><button type="button" class="dropdown-item text-danger" onclick="submitBulkAction('spam')">Report spam</button></li>
                                            <li><button type="button" class="dropdown-item text-danger" onclick="submitBulkAction('delete')">Delete</button></li>
                                        </ul>
                                    </div>
                                </div>

                                <div class="input-group" style="max-width:420px;">
                                    <input type="text" name="q" value="{{ request('q') }}" class="form-control rounded-pill border-0 shadow-sm" placeholder="Search messages...">
                                    <button class="btn btn-primary rounded-pill ms-2" type="submit"><i class="bi bi-search"></i></button>
                                </div>
                            </div>
                        </form>

                        {{-- // Form utama untuk aksi massal (checkbox + submit ke route aksi) --}}
                        <form method="POST" action="{{ url('/inbox/bulk-action') }}" id="bulkForm">
                            @csrf
                            <input type="hidden" name="action" id="bulkActionInput">

                            @if($entries->count() == 0)
                                <div class="alert alert-info mb-0">Belum ada pesan masuk.</div>
                            @else
                                <div class="list-group">
                                    @foreach($entries as $entry)
                                        <div class="list-group-item d-flex align-items-center gap-3 inbox-row {{ $entry->is_read ? '' : 'unread-row' }}">

                                            {{-- // Checkbox pilih pesan --}}
                                            <div class="flex-shrink-0">
                                                <input type="checkbox" name="ids[]" value="{{ $entry->id }}" class="form-check-input row-checkbox">
                                            </div>

                                            {{-- // Toggle Starred --}}
                                            <div class="flex-shrink-0">
                                                <button type="button"
                                                        class="btn btn-link p-0 star-toggle {{ ($entry->is_starred ?? false) ? 'text-warning' : 'text-muted' }}"
                                                        data-entry-id="{{ $entry->id }}"
                                                        onclick="toggleFlag(this.dataset.entryId, 'star', this)">
                                                    <i class="bi {{ ($entry->is_starred ?? false) ? 'bi-star-fill' : 'bi-star' }}"></i>
                                                </button>
                                            </div>

                                            {{-- // Toggle Important / bookmark --}}
                                            <div class="flex-shrink-0">
                                                <button type="button"
                                                        class="btn btn-link p-0 important-toggle {{ ($entry->is_important ?? false) ? 'text-danger' : 'text-muted' }}"
                                                        data-entry-id="{{ $entry->id }}"
                                                        onclick="toggleFlag(this.dataset.entryId, 'important', this)">
                                                    <i class="bi {{ ($entry->is_important ?? false) ? 'bi-bookmark-fill' : 'bi-bookmark' }}"></i>
                                                </button>
                                            </div>

                                            {{-- // Nama pengirim --}}
                                            <div class="sender flex-shrink-0">
                                                <span class="{{ $entry->is_read ? 'text-muted' : 'fw-bold' }}">{{ $entry->sender_name ?? 'Admin' }}</span>
                                            </div>

                                            {{-- // Judul + isi pesan (klik untuk buka & tandai dibaca) --}}
                                            <a href="{{ url('/inbox', $entry->id) }}" class="subject text-decoration-none d-flex flex-column" style="min-width:0;">
                                                <span class="title {{ $entry->is_read ? 'text-muted' : 'text-dark' }}">{{ $entry->title }}</span>
                                                <span class="preview text-muted subject-preview">{{ Illuminate\Support\Str::limit($entry->message, 100) }}</span>
                                            </a>

                                            {{-- // Waktu --}}
                                            <div class="time text-muted small">
                                                {{ $entry->created_at->format('d M, H:i') }}
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                        </form>

                        {{-- // Pagination --}}
                        @if($entries->count() > 0)
                            <div class="d-flex justify-content-center mt-4">
                                {{ $entries->appends(request()->query())->links() }}
                            </div>
                        @endif

                    </div>
                </div>
            </div>

        </div>
    </div>
</div>


@endsection
