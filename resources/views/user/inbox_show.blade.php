@extends('layouts.app')

@section('content')

<div class="container" style="margin-top:30px;">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div>
                            <button class="btn btn-light btn-sm me-2" onclick="history.back()"><i class="bi bi-arrow-left"></i></button>
                            <h4 class="d-inline">{{ $entry->title }}</h4>
                            <div class="text-muted small">From: {{ $entry->sender_name ?? 'Admin' }} &lt;{{ $entry->sender_email ?? '-' }}&gt;</div>
                        </div>
                        <div class="text-muted small">{{ $entry->created_at->format('d M, Y H:i') }}</div>
                    </div>

                    <hr>

                    <div class="mb-4">{!! nl2br(e($entry->message)) !!}</div>

                    <a href="{{ url('/inbox') }}" class="btn btn-secondary">Back to Inbox</a>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
