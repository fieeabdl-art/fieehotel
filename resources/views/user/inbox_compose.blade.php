@extends('layouts.app')

@section('content')

<div class="container" style="margin-top:30px;">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Compose Message</h5>

                    @if(session('error'))
                        <div class="alert alert-danger">{{ session('error') }}</div>
                    @endif
                    @if(session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif

                    <form method="POST" action="{{ url('/inbox/send') }}">
                        @csrf

                        <div class="mb-3">
                            <label class="form-label">To (email)</label>
                            <input type="email" name="to_email" class="form-control" placeholder="recipient@example.com">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Subject</label>
                            <input type="text" name="title" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Message</label>
                            <textarea name="message" rows="6" class="form-control"></textarea>
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="{{ url('/inbox') }}" class="btn btn-secondary">Back</a>
                            <button class="btn btn-primary">Send</button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</div>

@endsection
