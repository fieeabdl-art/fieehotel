<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Lihat Ruangan</title>
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="all,follow">

    @include('admin.cssA')

    <style type="text/css">
        .table_design {
            border: 2px solid white;
            width: 100%;
            margin: auto;
            text-align: center;
            border-collapse: collapse;
        }

        .th_design {
            border: 1px solid white;
            padding: 10px;
            background-color: #4CAF50;
            color: white;
        }

        .td_design {
            border: 1px solid white;
            padding: 10px;
        }

        img {
            border-radius: 5px;
        }
    </style>
</head>

<body>

    {{-- HEADER --}}
    @include('admin.headerA')

    <div class="d-flex align-items-stretch">

        {{-- SIDEBAR --}}
        @include('admin.sidebarA')

        <div class="page-content">

            <div class="page-header">
                <div class="container-fluid">
                    <center>
                        <h2 class="h5 no-margin-bottom">
                            Lihat Data Ruangan
                        </h2>
                    </center>
                </div>
            </div>

            <div class="container-fluid">

                <table class="table_design">

                    <tr>
                        <th class="th_design">Room Title</th>
                        <th class="th_design">Description</th>
                        <th class="th_design">Price</th>
                        <th class="th_design">Wi-Fi</th>
                        <th class="th_design">Room Type</th>
                        <th class="th_design">Image</th>
                        <th class="th_design">Update</th>
                        <th class="th_design">Delete</th>
                    </tr>

                    @foreach($data as $room)

                    <tr>

                        <td class="td_design">
                            {{ $room->room_title }}
                        </td>

                        <td class="td_design">
                            {{ Str::limit($room->description,150) }}
                        </td>

                        <td class="td_design">
                            ${{ $room->price }}
                        </td>

                        <td class="td_design">
                            {{ $room->wifi }}
                        </td>

                        <td class="td_design">
                            {{ $room->room_type }}
                        </td>

                        <td class="td_design">
                            <img src="{{ asset('room/'.$room->image) }}"
                                width="120">
                        </td>

                        <td class="td_design">
                            <a href="{{ url('room_update',$room->id) }}"
                                class="btn btn-warning">
                                Update
                            </a>
                        </td>

                        <td class="td_design">
                            <a href="{{ url('room_delete',$room->id) }}"
                                class="btn btn-danger"
                                onclick="return confirm('Are you sure you want to delete this room?')">
                                Delete
                            </a>
                        </td>

                    </tr>

                    @endforeach

                </table>

            </div>

        </div>

    </div>

    {{-- FOOTER --}}
    @include('admin.footerA')

</body>

</html>