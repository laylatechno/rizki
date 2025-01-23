<!DOCTYPE html>
<html lang="zxx">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="keywords" content="{{$profil->keyword}}">
    <meta name="description" content="{{$profil->deskripsi_keyword}}">


    <!-- Title -->
    <title>{{ $title }}</title>
    <!-- Favicon Icon -->
    <link rel="shortcut icon" type="image/png" href="{{ asset('/upload/profil/' . ($profil->favicon ?: 'https://static1.squarespace.com/static/524883b7e4b03fcb7c64e24c/524bba63e4b0bf732ffc8bce/646fb10bc178c30b7c6a31f2/1712669811602/Squarespace+Favicon.jpg?format=1500w')) }}" />

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Flaticon -->
    <link rel="stylesheet" href="{{ asset('template/front') }}/assets/css/flaticon.min.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="{{ asset('template/front') }}/assets/css/fontawesome-5.14.0.min.css">
    <!-- Bootstrap -->
    <link rel="stylesheet" href="{{ asset('template/front') }}/assets/css/bootstrap.min.css">
    <!-- Magnific Popup -->
    <link rel="stylesheet" href="{{ asset('template/front') }}/assets/css/magnific-popup.min.css">
    <!-- Nice Select -->
    <link rel="stylesheet" href="{{ asset('template/front') }}/assets/css/nice-select.min.css">
    <!-- Animate -->
    <link rel="stylesheet" href="{{ asset('template/front') }}/assets/css/aos.css">
    <!-- Slick -->
    <link rel="stylesheet" href="{{ asset('template/front') }}/assets/css/slick.min.css">
    <!-- Main Style -->
    <link rel="stylesheet" href="{{ asset('template/front') }}/assets/css/style.css">
    @stack('css')
    <style>
        /* Styling WhatsApp Button */
        .whatsapp-float {
            position: fixed;
            bottom: 80px;
            /* Jarak dari bawah */
            right: 20px;
            /* Jarak dari kanan */
            z-index: 999;
            /* Supaya tetap terlihat di atas elemen lain */
            width: 60px;
            height: 60px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .whatsapp-float img {
            width: 60px;
            height: auto;
            transition: transform 0.2s ease;
        }

        .whatsapp-float:hover {
            transform: scale(1.1);
        }

        /* Styling Scroll Top Button */
        .scroll-top {
            position: fixed;
            bottom: 20px;
            /* Jarak dari bawah (di bawah WhatsApp) */
            right: 20px;
            /* Jarak dari kanan */
            z-index: 998;
            /* Pastikan tombol ini berada di bawah WhatsApp */
            width: 50px;
            height: 50px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            background: none;
            /* Tidak ada latar belakang */
            border: none;
            outline: none;
        }

        .scroll-top img {
            width: 50px;
            height: auto;
            transition: transform 0.2s ease;
        }

        .scroll-top:hover img {
            transform: scale(1.1);
        }
    </style>

</head>

<body>
    <div class="page-wrapper">

        <!-- Preloader -->
        <div class="preloader">
            <div class="custom-loader"></div>
        </div>

        <!-- main header -->
        <header class="main-header header-one white-menu menu-absolute">
            <!--Header-Upper-->
            <div class="header-upper py-30 rpy-0">
                <div class="container-fluid clearfix">

                    <div class="header-inner rel d-flex align-items-center">
                        <div class="logo-outer">
                            <div class="logo"><a href="/"><img src="{{ asset('/upload/profil/' . $profil->logo_dark) }}" alt="Logo" title="Logo"></a></div>
                        </div>

                        <div class="nav-outer mx-lg-auto ps-xxl-5 clearfix">
                            <!-- Main Menu -->
                            <nav class="main-menu navbar-expand-lg">
                                <div class="navbar-header">
                                    <div class="mobile-logo">
                                        <a href="/">
                                            <img src="{{ asset('/upload/profil/' . $profil->logo_dark) }}" alt="Logo" title="Logo">
                                        </a>
                                    </div>

                                    <!-- Toggle Button -->
                                    <button type="button" class="navbar-toggle" data-bs-toggle="collapse" data-bs-target=".navbar-collapse">
                                        <span class="icon-bar"></span>
                                        <span class="icon-bar"></span>
                                        <span class="icon-bar"></span>
                                    </button>
                                </div>

                                <div class="navbar-collapse collapse clearfix">
                                    <ul class="navigation clearfix">
                                        <li><a href="/">Home</a></li>
                                        <li><a href="#layanan">Layanan</a></li>

                                        <li class="dropdown"><a href="#">Armada</a>
                                            <ul>
                                                @foreach ($fleet as $p)
                                                <li>
                                                    <a href="https://wa.me/{{$profil->no_wa}}?text={{ urlencode('Hallo Admin ' . $profil->nama_profil . ', saya ingin bertanya tentang Armada ' . $p->name . '. Terima kasih.') }}">
                                                        {{ $p->name }}
                                                    </a>
                                                </li>
                                                @endforeach
                                            </ul>
                                        </li>
                                        <li class="dropdown"><a href="#">Rute</a>
                                            <ul>
                                                @foreach ($travel_route as $p)
                                                <li>
                                                    <a href="https://wa.me/{{$profil->no_wa}}?text={{ urlencode('Hallo Admin ' . $profil->nama_profil . ', saya ingin bertanya tentang Rute ' . $p->start . ' - ' . $p->end . '. Terima kasih.') }}">
                                                        {{ $p->start }} - {{ $p->end }}
                                                    </a>
                                                </li>
                                                @endforeach
                                            </ul>
                                        </li>

                                        <li><a href="/blog">Blog</a></li>
                                        <li><a href="/kontak">Kontak</a>

                                        </li>
                                    </ul>
                                </div>

                            </nav>
                            <!-- Main Menu End-->
                        </div>



                        <!-- Menu Button -->
                        <div class="menu-btns py-10">
                            <a target="_blank" href="https://wa.me/{{$profil->no_wa}}?text={{ urlencode('Hallo Admin ' . $profil->nama_profil . ', saya ingin bertanya terkait informasi travel dari ' . $profil->nama_profil . '. Terima Kasih.') }}" class="theme-btn">
                                <span data-hover="Pesan Sekarang">Pesan Sekarang</span>
                                <i class="fab fa-whatsapp"></i>
                            </a>

                            <!-- menu sidbar -->

                        </div>
                    </div>
                </div>
            </div>
            <!--End Header Upper-->
        </header>


        <!--Form Back Drop-->
        <div class="form-back-drop"></div>


        @yield('content')



        <!-- footer area start -->
        <footer class="main-footer bgs-cover overlay rel z-1 pb-25" style="background-image: url({{ asset('template/front') }}/assets/images/bg-footer.webp);">
            <div class="container">
                <div class="footer-top pt-100 pb-30">
                    <div class="row justify-content-between">
                        <div class="col-xl-5 col-lg-6" data-aos="fade-up" data-aos-duration="1500" data-aos-offset="50">
                            <div class="footer-widget footer-text">
                                <div class="footer-logo mb-25">
                                    <a href="/"><img src="{{ asset('/upload/profil/' . $profil->logo_dark) }}" alt="Logo"></a>
                                </div>
                                <p>{{ $profil->deskripsi_3}}</p>
                                <div class="social-style-one mt-15">
                                    <a href="{{ $profil->facebook}}"><i class="fab fa-facebook-f"></i></a>
                                    <a href="{{ $profil->youtube}}"><i class="fab fa-youtube"></i></a>
                                    <a href="{{ $profil->instagram}}"><i class="fab fa-instagram"></i></a>
                                    <a href="{{ $profil->no_wa}}"><i class="fab fa-whatsapp"></i></a>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-5 col-lg-6" data-aos="fade-up" data-aos-delay="50" data-aos-duration="1500" data-aos-offset="50">
                            <div class="section-title counter-text-wrap mb-35">
                                <h2>Berlangganan Informasi</h2>
                                <p>Dapatkan informasi berkala dari kami dengan memasukan email untuk Berlangganan</p>
                            </div>
                            <div class="newsletter-form mb-50">
                                <input id="news-email" type="email" name="email" placeholder="Alamat Email" required>
                                <button onclick="subscribeToWhatsapp()" class="theme-btn bgc-secondary style-two">
                                    <span data-hover="Berlangganan">Berlangganan</span>
                                    <i class="fal fa-arrow-right"></i>
                                </button>
                            </div>
                        </div>


                    </div>
                </div>
            </div>
            <div class="widget-area pt-95 pb-45">
                <div class="container">
                    <div class="row row-cols-xl-5 row-cols-lg-4 row-cols-md-3 row-cols-2">
                        <div class="col col-small" data-aos="fade-up" data-aos-duration="1500" data-aos-offset="50">
                            <div class="footer-widget footer-links">
                                <div class="footer-title">
                                    <h5>Armada</h5>
                                </div>
                                <ul class="list-style-three">
                                    @foreach ($fleet as $p)
                                    <li>
                                        <a href="https://wa.me/{{$profil->no_wa}}?text={{ urlencode('Hallo Admin ' . $profil->nama_profil . ', saya ingin bertanya tentang Armada ' . $p->name . '. Terima kasih.') }}">
                                            {{ $p->name }}
                                        </a>
                                    </li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                        <div class="col col-small" data-aos="fade-up" data-aos-delay="50" data-aos-duration="1500" data-aos-offset="50">
                            <div class="footer-widget footer-links">
                                <div class="footer-title">
                                    <h5>Rute</h5>
                                </div>
                                <ul class="list-style-three">
                                    @foreach ($travel_route as $p)
                                    <li>
                                        <a href="https://wa.me/{{$profil->no_wa}}?text={{ urlencode('Hallo Admin ' . $profil->nama_profil . ', saya ingin bertanya tentang Rute ' . $p->start . ' - ' . $p->end . '. Terima kasih.') }}">
                                            {{ $p->start }} - {{ $p->end }}
                                        </a>
                                    </li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>


                        <div class="col col-md-6 col-10 col-small" data-aos="fade-up" data-aos-delay="200" data-aos-duration="1500" data-aos-offset="50">
                            <div class="footer-widget footer-contact">
                                <div class="footer-title">
                                    <h5>Hubungi Kami</h5>
                                </div>
                                <ul class="list-style-one">
                                    <li><i class="fal fa-map-marked-alt"></i> {{$profil->alamat}}</li>
                                    <li><i class="fal fa-envelope"></i> <a href="mailto:{{$profil->email}}">{{$profil->email}}</a></li>
                                    <li><i class="fal fa-clock"></i> Senin - Ahad, 08.00 - 22.00 WIB</li>
                                    <li><i class="fal fa-phone-volume"></i> <a href="callto:{{$profil->no_telp}}">{{$profil->no_telp}}</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="footer-bottom pt-20 pb-5">
                <div class="container">
                    <div class="row">
                        <div class="col-lg-5">
                            <div class="copyright-text text-center text-lg-start">
                                <p>@Copy {{ date('Y') }} <a href="/">{{$profil->nama_profil}}</a>, All rights reserved</p>

                            </div>
                        </div>
                        <div class="col-lg-7 text-center text-lg-end">
                            <ul class="footer-bottom-nav">
                                <li><a href="/">Terms</a></li>
                                <li><a href="/">Privacy Policy</a></li>
                                <li><a href="/">Legal notice</a></li>
                                <li><a href="/">Accessibility</a></li>
                            </ul>
                        </div>
                    </div>

                </div>
            </div>
        </footer>
        <!-- footer area end -->

    </div>
    <!--End pagewrapper-->


    <!-- Jquery -->
    <script src="{{ asset('template/front') }}/assets/js/jquery-3.6.0.min.js"></script>
    <!-- Bootstrap -->
    <script src="{{ asset('template/front') }}/assets/js/bootstrap.min.js"></script>
    <!-- Appear Js -->
    <script src="{{ asset('template/front') }}/assets/js/appear.min.js"></script>
    <!-- Slick -->
    <script src="{{ asset('template/front') }}/assets/js/slick.min.js"></script>
    <!-- Magnific Popup -->
    <script src="{{ asset('template/front') }}/assets/js/jquery.magnific-popup.min.js"></script>
    <!-- Nice Select -->
    <script src="{{ asset('template/front') }}/assets/js/jquery.nice-select.min.js"></script>
    <!-- Image Loader -->
    <script src="{{ asset('template/front') }}/assets/js/imagesloaded.pkgd.min.js"></script>
    <!-- Skillbar -->
    <script src="{{ asset('template/front') }}/assets/js/skill.bars.jquery.min.js"></script>
    <!-- Isotope -->
    <script src="{{ asset('template/front') }}/assets/js/isotope.pkgd.min.js"></script>
    <!--  AOS Animation -->
    <script src="{{ asset('template/front') }}/assets/js/aos.js"></script>
    <!-- Custom script -->
    <script src="{{ asset('template/front') }}/assets/js/script.js"></script>

    <!-- Form HTML -->
    <input type="hidden" id="profil-nama" value="{{ $profil->nama_profil }}">
    <input type="hidden" id="profil-no-wa" value="{{ $profil->no_wa }}">
    

    <!-- Script JavaScript -->
    <script>
        function subscribeToWhatsapp() {
            var email = document.getElementById('news-email').value;
            var profilNama = document.getElementById('profil-nama').value;
            var profilNoWa = document.getElementById('profil-no-wa').value;

            var waMessage = encodeURIComponent(`Hallo Admin ${profilNama}, saya ingin berlangganan informasi terkait travel ini. Email saya: ${email}. Terima kasih.`);
            window.location.href = `https://wa.me/${profilNoWa}?text=${waMessage}`;
        }
    </script>

    <!-- WhatsApp Floating Button -->
    <a href="https://wa.me/{{ $profil->no_wa }}" class="whatsapp-float" target="_blank">
        <img src="{{ asset('template/front') }}/assets/images/wa.png" alt="WhatsApp">
    </a>

    <!-- Scroll Top Button -->
    <button class="scroll-top scroll-to-target" data-target="html"><img src="{{ asset('template/front') }}/assets/images/su.png" alt="Scroll  Up"></button>



    @stack('script')
</body>

</html>