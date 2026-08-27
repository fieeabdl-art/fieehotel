<div class="our_room" id="room">
    <div class="container">

        <div class="row">
            <div class="col-md-12">
                <div class="titlepage">
                    <h2>Our Room</h2>
                    <p>BERIKUT KAMAR YANG TERSEDIA.</p>
                </div>
            </div>
        </div>

        <div class="row">

            @foreach($rooms as $room)

            <div class="col-md-4 col-sm-6">
                <div id="serv_hover" class="room">

                    <div class="room_img">
                        <img
                            style="height:220px;width:100%;object-fit:cover;"
                            src="{{ asset('room/'.$room->image) }}"
                            alt="">
                    </div>

                    <div class="bed_room">

                        <h3>{{ $room->room_title }}</h3>

                        <p>
                            {{ Str::limit($room->description,100) }}
                        </p>

                        <a class="btn btn-danger"
                           href="{{ url('room_details',$room->id) }}">
                            Room Details
                        </a>

                    </div>

                </div>
            </div>

            @endforeach

        </div>

    </div>
</div>