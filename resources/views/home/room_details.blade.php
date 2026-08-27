<!DOCTYPE html>
<html>
<head>
    <base href="/public"> <!-- Sangat penting agar CSS/JS tetap jalan [19] -->
    @include('home.css')
</head>
<body>
    @include('home.header')

    <div class="room_details">
        <div class="container">
            <div class="row">
                <div class="col-md-8">
                    <div id="serv_hover" class="room">
                        <div class="room_img">
                            <!-- Menampilkan gambar dengan ukuran lebih besar [20, 21] -->
                            <img style="height: 300px; width: 800px; padding: 20px" src="/room/{{$room->image}}">
                        </div>
                        <div class="bed_room">
                            <h2>{{$room->room_title}}</h2> <!-- Judul lengkap [22, 23] -->
                            <p style="padding: 12px">{{$room->description}}</p> <!-- Deskripsi lengkap [24] -->
                            
                            <h4>Free WiFi : {{$room->wifi}}</h4> <!-- Status WiFi [24] -->
                            <h4>Room Type : {{$room->room_type}}</h4> <!-- Tipe Kamar [23] -->
                            <h3 style="color: red">Price : {{$room->price}}</h3> <!-- Harga [23] -->
                        </div>
                    </div>
                </div>
                
                <!-- Bagian kosong di samping untuk Form Booking nantinya [20, 21] -->
                <div class="col-md-4">
                    <h1 style="font-size: 40px!important">Book Room</h1>
                    
                </div>
            </div>
        </div>
    </div>

    @include('home.footer')
</body>
</html>