@extends('layouts.app')

@section('content')

{{-- // Bootstrap Icons dipakai untuk ikon star, bookmark, search, dsb --}}
{{-- // Jika layout utama (layouts.app) sudah memuat bootstrap-icons, baris <link> ini boleh dihapus --}}
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

<div class="container-fluid" style="margin-top:30px; margin-bottom:40px;">

    <div class="row justify-content-center">
        <div class="col-xl-10 col-lg-11 col-12">

            <div class="card border-0 shadow-sm" style="border-radius:12px; overflow:hidden;">
                <div class="row g-0">

                    

                    {{-- ================= SIDEBAR ================= --}}
                    <div class="col-md-3 border-end inbox-sidebar" style="background:#fafbfc; padding:24px 18px;">

                        <div class="d-flex align-items-center gap-2 mb-4">
                            <button type="button" class="btn btn-light btn-sm me-2" onclick="history.back()" title="Kembali">
                                <i class="bi bi-arrow-left"></i>
                            </button>
                            <i class="bi bi-inbox-fill" style="font-size:22px; color:#495057;"></i>
                            <h4 class="mb-0" style="font-weight:600;">Inbox</h4>
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
                                <div class="dropdown">
                                    <button class="btn btn-light border dropdown-toggle" type="button" id="actionDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                                        Aksi
                                    </button>
                                    <ul class="dropdown-menu" aria-labelledby="actionDropdown">
                                        <li><button type="button" class="dropdown-item" onclick="submitBulkAction('mark-read')">Tandai sudah dibaca</button></li>
                                        <li><button type="button" class="dropdown-item" onclick="submitBulkAction('mark-unread')">Tandai belum dibaca</button></li>
                                        <li><button type="button" class="dropdown-item" onclick="submitBulkAction('mark-important')">Tandai penting</button></li>
                                        <li><hr class="dropdown-divider"></li>
                                        <li><button type="button" class="dropdown-item text-danger" onclick="submitBulkAction('spam')">Laporkan spam</button></li>
                                        <li><button type="button" class="dropdown-item text-danger" onclick="submitBulkAction('delete')">Hapus</button></li>
                                    </ul>
                                </div>

                                <div class="input-group" style="max-width:320px;">
                                    <input type="text" name="q" value="{{ request('q') }}" class="form-control" placeholder="Cari pesan...">
                                    <button class="btn btn-primary" type="submit"><i class="bi bi-search"></i></button>
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
                                        <div class="list-group-item d-flex align-items-center gap-3 px-2 py-3 inbox-row {{ $entry->is_read ? '' : 'unread-row' }}">

                                            {{-- // Checkbox pilih pesan --}}
                                            <input type="checkbox" name="ids[]" value="{{ $entry->id }}" class="form-check-input row-checkbox flex-shrink-0">

                                            {{-- // Toggle Starred --}}
                                            <button type="button"
                                                    class="btn btn-link p-0 flex-shrink-0 star-toggle {{ ($entry->is_starred ?? false) ? 'text-warning' : 'text-muted' }}"
                                                    data-entry-id="{{ $entry->id }}"
                                                    onclick="toggleFlag(this.dataset.entryId, 'star', this)">
                                                <i class="bi {{ ($entry->is_starred ?? false) ? 'bi-star-fill' : 'bi-star' }}"></i>
                                            </button>

                                            {{-- // Toggle Important / bookmark --}}
                                            <button type="button"
                                                    class="btn btn-link p-0 flex-shrink-0 important-toggle {{ ($entry->is_important ?? false) ? 'text-danger' : 'text-muted' }}"
                                                    data-entry-id="{{ $entry->id }}"
                                                    onclick="toggleFlag(this.dataset.entryId, 'important', this)">
                                                <i class="bi {{ ($entry->is_important ?? false) ? 'bi-bookmark-fill' : 'bi-bookmark' }}"></i>
                                            </button>

                                            {{-- // Nama pengirim --}}
                                            <div class="flex-shrink-0" style="width:150px; min-width:120px;">
                                                <span class="{{ $entry->is_read ? 'text-muted' : 'fw-bold' }}" style="white-space:nowrap; overflow:hidden; text-overflow:ellipsis; display:block;">
                                                    {{ $entry->sender_name ?? 'Admin' }}
                                                </span>
                                            </div>

                                            {{-- // Judul + isi pesan (klik untuk buka & tandai dibaca) --}}
                                            <a href="{{ url('/inbox', $entry->id) }}" class="flex-grow-1 text-decoration-none" style="min-width:0;">
                                                <span class="{{ $entry->is_read ? 'text-muted' : 'fw-bold text-dark' }}">{{ $entry->title }}</span>
                                                <span class="text-muted"> — {{ Illuminate\Support\Str::limit($entry->message, 70) }}</span>
                                            </a>

                                            {{-- // Waktu --}}
                                            <div class="flex-shrink-0 text-muted small text-end" style="width:90px;">
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

<style>
    .active-folder { background:#f6fbff !important; color:#0d6efd; font-weight:600; border-left:4px solid #0d6efd; padding-left:12px; }
    .list-group-item.list-group-item-action:hover { background:#f1f3f5; }
    .inbox-row { border-left: none; border-right: none; border-bottom:1px solid #eef0f2; }
    .inbox-row:first-child { border-top: none; }
    .unread-row { background:#ffffff; }
    .star-toggle:hover, .important-toggle:hover { opacity:.7; }
    .row-checkbox { cursor:pointer; }

    /* Sidebar sizing */
    .inbox-sidebar { width:220px; min-width:180px; }

    /* Truncate subject/preview */
    .subject-preview { white-space:nowrap; overflow:hidden; text-overflow:ellipsis; display:block; }

    @media (max-width:767px) {
        .inbox-sidebar { display:none; }
    }
</style>

<script>
    // // Set action lalu submit form bulk berdasarkan pilihan checkbox
    function submitBulkAction(action) {
        const checked = document.querySelectorAll('.row-checkbox:checked');
        if (checked.length === 0) {
            alert('Pilih minimal satu pesan terlebih dahulu.');
            return;
        }
        document.getElementById('bulkActionInput').value = action;
        document.getElementById('bulkForm').submit();
    }

    // // Toggle star / important langsung lewat AJAX tanpa reload halaman
    function toggleFlag(id, type, btn) {
        fetch(`/inbox/${id}/toggle-${type}`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json',
            },
        })
        .then(res => res.json())
        .then(data => {
            const icon = btn.querySelector('i');
            if (type === 'star') {
                icon.classList.toggle('bi-star', !data.active);
                icon.classList.toggle('bi-star-fill', data.active);
                btn.classList.toggle('text-warning', data.active);
                btn.classList.toggle('text-muted', !data.active);
            } else {
                icon.classList.toggle('bi-bookmark', !data.active);
                icon.classList.toggle('bi-bookmark-fill', data.active);
                btn.classList.toggle('text-danger', data.active);
                btn.classList.toggle('text-muted', !data.active);
            }
        })
        .catch(() => {
            // // Jika route toggle belum dibuat di backend, abaikan error secara halus
            console.warn('Route toggle-' + type + ' belum tersedia di backend.');
        });
    }
</script>

@endsection
