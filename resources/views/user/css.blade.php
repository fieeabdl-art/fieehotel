<!-- basic -->
      <meta charset="utf-8">
      <meta http-equiv="X-UA-Compatible" content="IE=edge">
      <!-- mobile metas -->
      <meta name="viewport" content="width=device-width, initial-scale=1">
      <meta name="viewport" content="initial-scale=1, maximum-scale=1">
      <!-- site metas -->
      <title>FieeHote</title>
      <meta name="keywords" content="">
      <meta name="description" content="">
      <meta name="author" content="">
      <!-- bootstrap css -->
      <link rel="stylesheet" href="css/bootstrap.min.css">
      <!-- style css -->
      <link rel="stylesheet" href="css/style.css">
      <!-- Responsive-->
      <link rel="stylesheet" href="css/responsive.css">
      <!-- fevicon -->
      <link rel="icon" href="images/fevicon.png" type="image/gif" />
      <!-- Scrollbar Custom CSS -->
      <link rel="stylesheet" href="css/jquery.mCustomScrollbar.min.css">
      <!-- Tweaks for older IEs-->
      <link rel="stylesheet" href="https://netdna.bootstrapcdn.com/font-awesome/4.0.3/css/font-awesome.css">
      <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fancybox/2.1.5/jquery.fancybox.min.css" media="screen">
      <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script><![endif]-->

      <!-- Gaya tambahan untuk menyesuaikan navigasi agar teks tidak membungkus (wrap) -->
      <style>
          /* Pastikan teks menu navbar tidak terpecah menjadi 2 baris pada layar besar */
          .navigation .navbar-nav .nav-link {
              white-space: nowrap; /* mencegah pemecahan baris */
              font-size: 14px; /* ukuran font lebih kecil agar muat */
              padding-left: 10px; /* jarak kiri */
              padding-right: 10px; /* jarak kanan */
          }

          /* Pastikan elemen nav tersusun horisontal dan rata tengah */
          .navigation .navbar-nav {
              display: flex;
              align-items: center;
              gap: 10px; /* jarak antar item */
          }

          /* Tampilkan badge kecil rapi di samping teks */
          .navigation .navbar-nav .badge {
              margin-left: 6px;
              vertical-align: middle;
          }

          /* Untuk layar kecil (mobile), izinkan text wrap kembali agar responsif */
          @media (max-width: 991px) {
              .navigation .navbar-nav .nav-link {
                  white-space: normal; /* kembali membungkus agar mobile friendly */
                  font-size: 15px; /* sedikit lebih besar untuk keterbacaan */
              }
          }
      </style>