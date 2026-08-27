<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Lihat Booking</title>
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
            vertical-align: middle;
        }

        img {
            border-radius: 5px;
        }

        .status-approved {
            color: green;
            font-weight: bold;
        }

        .status-rejected {
            color: red;
            font-weight: bold;
        }

        .status-pending {
            color: orange;
            font-weight: bold;
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
                            Lihat Data Booking
                        </h2>
                    </center>
                </div>
            </div>

            <div class="container-fluid">

                <table class="table_design">
                    <tr>
                        <th class="th_design">Customer Name</th>
                        <th class="th_design">Email</th>
                        <th class="th_design">Arrival Date</th>
                        <th class="th_design">Leaving Date</th>
                        <th class="th_design">Status</th>
                        <th class="th_design">Room Title</th>
                        <th class="th_design">Price</th>
                        <th class="th_design">Image</th>
                        <th class="th_design">Delete</th>
                        <th class="th_design">Status Update</th>
                    </tr>

                    @foreach($data as $booking)
                    <tr>
                        <td class="td_design">{{ $booking->name }}</td>
                        <td class="td_design">{{ $booking->email }}</td>
                        <td class="td_design">{{ $booking->start_date }}</td>
                        <td class="td_design">{{ $booking->end_date }}</td>
                        <td class="td_design">
                            @php
                                $statusClass = $booking->status == 'Diterima'
                                    ? 'status-approved'
                                    : ($booking->status == 'Ditolak' ? 'status-rejected' : 'status-pending');
                            @endphp
                            {{-- // Menampilkan status booking dengan warna yang sesuai
                                 // Diterima = hijau, Ditolak = merah, selain itu tetap orange default --}}
                            <span class="{{ $statusClass }}">
                                {{ $booking->status ?? 'Pending' }}
                            </span>
                        </td>

                        {{-- Mengambil data dari tabel Room melalui relasi --}}
                        <td class="td_design">{{ $booking->room->room_title }}</td>
                        <td class="td_design">${{ $booking->room->price }}</td>
                        <td class="td_design">
                            <img width="150" src="{{ asset('room/'.$booking->room->image) }}">
                        </td>
                        <td class="td_design">
                            <a href="{{ url('booking_delete',$booking->id) }}"
                                class="btn btn-danger"
                                onclick="return confirm('Are you sure you want to delete this booking?')">
                                Delete
                            </a>
                        </td>
                        <td class="td_design">
                            {{-- // Tombol approve berwarna hijau dan mengubah status jadi Diterima --}}
                            <span style="padding-bottom: 10px; display: block;">
                                <a class="btn btn-success" href="{{ url('booking_approve', $booking->id) }}" onclick="return confirm('Approve booking ini?')">Approve</a>
                            </span>
                            {{-- // Tombol rejected berwarna merah dan mengubah status jadi Ditolak --}}
                            <a class="btn btn-danger" href="{{ url('booking_reject', $booking->id) }}" onclick="return confirm('Reject booking ini?')">Rejected</a>
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