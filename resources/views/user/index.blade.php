
<!DOCTYPE html>
<html lang="en">
   <head>
          @include('user.css') {{-- Mengambil CSS --}}
   </head>
   <!-- body -->
   <body class="main-layout">
      <!-- loader  -->
      <div class="loader_bg">
         <div class="loader"><img src="images/loading.gif" alt="#"/></div>
      </div>
      <!-- end loader -->
      <!-- ================= HEADER START ( user/header.blade.php) ================= -->
      <!-- header -->
      <header>
 
       
       @include('user.header') {{-- Mengambil header --}}
 
      </header>
      <!-- ================= HEADER END ================= -->

      @if(session('booking_status_message'))
          <!-- Tampilkan pesan dari session ketika status booking berubah, misalnya diterima atau ditolak -->
          <div class="container" id="inbox" style="margin-top: 20px; margin-bottom: 20px;">
              <div class="alert alert-info alert-dismissible" role="alert">
                  <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                  </button>
                  {{ session('booking_status_message') }}
              </div>
          </div>
      @endif

      <!-- end header inner -->
      <!-- end header -->
      <!-- ================= BANNER START (user/banner.blade.php) ================= -->
      <!-- banner -->
     @include('user.banner') {{-- Mengambil banner --}}
      <!-- end banner -->
      <!-- ================= BANNER END ================= -->
      <!-- ================= ABOUT START (user/about.blade.php) ================= -->
      <!-- about -->
      @include('user.about') {{-- Mengambil about --}}
      <!-- end about -->
      <!-- ================= ABOUT END ================= -->
      <!-- ================= ROOM START (user/room.blade.php) ================= -->
      <!-- our_room -->
      @include('user.room') {{-- Mengambil room --}}
        <!-- end our_room -->
      <!-- ================= ROOM END ================= -->
      <!-- ================= GALLERY START (user/gallery.blade.php) ================= -->
      @include('user.gallery') {{-- Mengambil gallery --}}
      <!-- ================= GALLERY END ================= -->
      <!-- ================= BLOG START ( user/blog.blade.php) ================= -->
      @include('user.blog') {{-- Mengambil blog --}}
      <!-- ================= BLOG END ================= -->
      <!-- ================= CONTACT START user/contact.blade.php) ================= -->
      @include('user.contact') {{-- Mengambil contact --}}
      <!-- ================= CONTACT END ================= -->
      <!-- ================= FOOTER START (user/footer.blade.php) ================= -->
        @include('user.footer') {{-- Mengambil footer --}}
      <!-- ================= FOOTER END ================= -->
      <!-- ================= SCRIPT START (user/script.blade.php) ================= -->
        @include('user.script') {{-- Mengambil script --}}
      <!-- ================= SCRIPT END ================= -->
   </body>
</html>