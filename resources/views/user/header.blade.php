  <!-- header inner -->
         <div class="header">
            <div class="container">
               <div class="row">
                  <div class="col-xl-3 col-lg-3 col-md-3 col-sm-3 col logo_section">
                     <div class="full">
                        <div class="center-desk">
                           <div class="logo">
                              <a href="index.html"><img src="images/logo.png" alt="#" /></a>
                           </div>
                        </div>
                     </div>
                  </div>
                  <div class="col-xl-9 col-lg-9 col-md-9 col-sm-9">
                     <nav class="navigation navbar navbar-expand-md navbar-dark ">
                        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarsExample04" aria-controls="navbarsExample04" aria-expanded="false" aria-label="Toggle navigation">
                        <span class="navbar-toggler-icon"></span>
                        </button>
                        <div class="collapse navbar-collapse" id="navbarsExample04">
                           <ul class="navbar-nav mr-auto">
                              <li class="nav-item active">
                               <a class="nav-link" href="{{ url('/dashboard') }}#home">Home</a>
                              </li>
                              <li class="nav-item">
                                 <a class="nav-link" href="{{ url('/dashboard') }}#about">About</a>
                              </li>
                              <li class="nav-item">
                                 <a class="nav-link" href="{{ url('/dashboard') }}#room">Our Room</a>
                              </li>
                              <li class="nav-item">
                                 <a class="nav-link" href="{{ url('/dashboard') }}#gallery">Gallery</a>
                              </li>
                              <li class="nav-item">
                                 <a class="nav-link" href="{{ url('/dashboard') }}#blog">Blog</a>
                              </li>
                              <li class="nav-item">
                                 <a class="nav-link" href="{{ url('/dashboard') }}#contact">Contact</a>
                              </li>

                              @auth
                              <!-- Tambahkan menu pesan masuk di sebelah kiri tombol Logout agar tampil ketika user login -->
                              <li class="nav-item">
                                  <?php
                                      // Hitungan pesan belum dibaca untuk user yang login (tampilkan badge)
                                      $unread = \App\Models\InboxEntry::where('user_id', Auth::id())->where('is_read', false)->count();
                                  ?>
                                  <a class="nav-link d-flex align-items-center" href="{{ url('/inbox') }}" style="gap:6px;">
                                      <span style="font-size:14px;">✉</span>
                                      <span style="text-transform:uppercase; font-weight:600;">Pesan Masuk</span>
                                      @if($unread > 0)
                                          <span class="badge badge-danger" style="font-size:10px; padding:2px 5px; line-height:1; margin-left:4px;">{{ $unread }}</span>
                                      @endif
                                  </a>
                              </li>
                              <li class="nav-item">
                                  <form method="POST" action="{{ route('logout') }}">
                                      @csrf
                                      <button type="submit" class="nav-link border-0 bg-transparent" style="cursor:pointer;">
                                          Logout
                                      </button>
                                  </form>
                              </li>
                              @endauth
                           </ul>
                        </div>
                     </nav>
                  </div>
               </div>
            </div>
         </div>

<!-- ini dashboard login/out -->
   