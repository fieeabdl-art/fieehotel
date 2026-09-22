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

pp
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