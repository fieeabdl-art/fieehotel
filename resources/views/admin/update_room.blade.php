<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <title>Update Room</title>

    <meta name="viewport" content="width=device-width, initial-scale=1">

    @include('admin.cssA')

    <link rel="stylesheet" href="{{ asset('admin/css/createroomcenter.css') }}">
</head>

<body>

@include('admin.headerA')

<div class="d-flex align-items-stretch">

@include('admin.sidebarA')

<div class="page-content">

    <div class="page-header">

        <div class="container-fluid">

            <div class="room-card">

                <h2 class="title">Update Room</h2>

                <form action="{{ url('edit_room',$data->id) }}"
                    method="POST"
                    enctype="multipart/form-data">

                    @csrf

                    <div class="mb-3">
                        <label>Room Title</label>
                        <input
                            type="text"
                            class="form-control"
                            name="title"
                            value="{{ $data->room_title }}">
                    </div>

                    <div class="mb-3">
                        <label>Description</label>
                        <textarea
                            class="form-control"
                            rows="4"
                            name="description">{{ $data->description }}</textarea>
                    </div>

                    <div class="mb-3">
                        <label>Price</label>
                        <input
                            type="number"
                            class="form-control"
                            name="price"
                            value="{{ $data->price }}">
                    </div>

                    <div class="mb-3">
                        <label>Room Type</label>

                        <select
                            class="form-control"
                            name="type">

                            <option value="{{ $data->room_type }}" selected>
                                {{ ucfirst($data->room_type) }}
                            </option>

                            <option value="regular">Regular</option>
                            <option value="premium">Premium</option>
                            <option value="deluxe">Deluxe</option>

                        </select>
                        

                    </div>
<div class="mb-3">
    <label>Free Wifi</label>

    <select name="wifi" class="form-control">

        <option value="{{ $data->wifi }}" selected>
            {{ ucfirst($data->wifi) }}
        </option>

        <option value="yes">Yes</option>
        <option value="no">No</option>

    </select>

</div>
                    <div class="mb-3">

                        <label>Current Image</label>

                        <br>

                        <img
                            src="{{ asset('room/'.$data->image) }}"
                            width="180">

                    </div>
                    

                    <div class="mb-3">

                        <label>Change Image</label>

                        <input
                            type="file"
                            class="form-control"
                            name="image">

                    </div>

                    <button class="btn btn-warning w-100">
                        Update Room
                    </button>

                </form>

            </div>

        </div>

    </div>

</div>

</div>

@include('admin.footerA')
@include('admin.scriptA')

</body>

</html>