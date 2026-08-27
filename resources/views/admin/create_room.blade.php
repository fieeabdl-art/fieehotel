<!DOCTYPE html>
<html>

<head>


    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Tambah Ruangan</title>
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="all,follow">
    <link rel="stylesheet" href="admin/css/createroomcenter.css">

    @include('admin.cssA') {{-- Mengambil CSS --}}
</head>

<body>
    {{-- ================= HEADER START (admin/headerA.blade.php) ================= --}}
    @include('admin.headerA') {{-- Mengambil header --}}
    {{-- ================= HEADER END ================= --}}
    <div class="d-flex align-items-stretch">
        <!-- Sidebar Navigation-->
        {{-- ================= SIDEBAR START (  admin/sidebarA.blade.php) ================= --}}
      @include('admin.sidebarA')

<div class="page-content">

    <div class="page-header">

        <div class="container-fluid">

            <div class="room-card">

                <h2 class="title">Tambah Ruangan</h2>

                <form action="{{ url('add_room') }}" method="POST" enctype="multipart/form-data">

                    @csrf

                    <div class="mb-3">
                        <label>Room Title</label>
                        <input type="text" name="title" class="form-control">
                    </div>

                    <div class="mb-3">
                        <label>Description</label>
                        <textarea name="description" class="form-control" rows="4"></textarea>
                    </div>

                    <div class="mb-3">
                        <label>Price</label>
                        <input type="number" name="price" class="form-control">
                    </div>

                    <div class="mb-3">
                        <label>Room Type</label>

                        <select name="type" class="form-control">
                            <option value="regular">Regular</option>
                            <option value="premium">Premium</option>
                            <option value="deluxe">Deluxe</option>
                        </select>

                    </div>

                    <div class="mb-3">
                        <label>Free Wifi</label>

                        <select name="wifi" class="form-control">
                            <option value="yes">Yes</option>
                            <option value="no">No</option>
                        </select>

                    </div>

                    <div class="mb-3">
                        <label>Upload Image</label>
                        <input type="file" name="image" class="form-control">
                    </div>

                    <button class="btn btn-primary w-100">
                        Add Room
                    </button>

                </form>

            </div>

        </div>

    </div>

</div>
                {{-- ================= FOOTER START ( admin/footerA.blade.php) ================= --}}
              
                {{-- ================= FOOTER END ================= --}}
                {{-- ================= BODY END ================= --}}
                {{-- ================= FOOTER END ================= --}}
                {{-- ================= BODY END ================= --}}
            </div>
        </div>
  
        {{-- ================= SCRIPT START (CUT KE admin/scriptA.blade.php) ================= --}}
    {{--  --}}
        {{-- ================= SCRIPT END ================= --}}
</body>
@include('admin.footerA') {{-- Mengambil footer --}}

</html>