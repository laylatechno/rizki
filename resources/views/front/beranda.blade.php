@extends('front.layouts.app')
@section('content')
@push('script')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
@endpush
<!-- Hero Area Start -->
<section class="hero-area bgc-black pt-200 rpt-120 rel z-2">
    <div class="container-fluid">
        <h4 class="hero-title" data-aos="flip-up" data-aos-delay="50" data-aos-duration="1500" data-aos-offset="50">{{ $profil->nama_profil }}</h4>
        <div class="main-hero-image bgs-cover" style="background-image: url({{ asset('/upload/profil/' . $profil->banner) }});"></div>
    </div>
    <div class="container container-1400">
        <div class="search-filter-inner" data-aos="zoom-out-down" data-aos-duration="1500" data-aos-offset="50">
            <div class="filter-item clearfix">
                <div class="icon"><i class="fal fa-map-marker-alt"></i></div>
                <span class="title">Rute</span>
                <select name="travel_route" id="travel_route">
                    @foreach ($data_travel_routes as $p)
                    <option value="{{ $p->start}} - {{ $p->end}}">{{ $p->start}} - {{ $p->end}}</option>
                    @endforeach
                </select>
            </div>
            <div class="filter-item clearfix">
                <div class="icon"><i class="fal fa-flag"></i></div>
                <span class="title">Armada</span>
                <select name="fleet" id="fleet">
                    @foreach ($data_fleets as $p)
                    <option value="{{ $p->name}}">{{ $p->name}}</option>
                    @endforeach
                </select>
            </div>
            <div class="filter-item clearfix">
                <div class="icon"><i class="fal fa-calendar-alt"></i></div>
                <span class="title">Tanggal Perjalanan</span>
                <input type="text" name="date" id="date" class="flatpickr">
            </div>
            <div class="filter-item clearfix">
                <div class="icon"><i class="fal fa-sun"></i></div>
                <span class="title">Hari</span>
                <input type="text" class="form-control" name="day" id="day" readonly title="Hari">
            </div>

            <div class="filter-item clearfix">
                <div class="icon"><i class="fal fa-clock"></i></div>
                <span class="title">Jam Berangkat</span>
                <select name="time" id="time">
                </select>
            </div>




        </div>
    </div>
</section>


<!-- Contact Form Area start -->
<section class="contact-form-area py-70 rel z-1">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-12">
                <div class="comment-form bgc-lighter z-1 rel mb-30 rmb-55">



                    <div class="row mt-35">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="full_name">Nama Lengkap</label>
                                <input type="text" id="full_name" name="full_name" class="form-control" placeholder="Masukkan Nama Pemesan" value="">
                                <div class="help-block with-errors"></div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="wa_number">No WA</label>
                                <input type="number" id="wa_number" name="wa_number" class="form-control" placeholder="Masukkan No WA" value="">
                                <div class="help-block with-errors"></div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="total_ticket">Jumlah Tiket/Kursi</label>
                                <input type="number" id="total_ticket" name="total_ticket" class="form-control" placeholder="Masukkan Jumlah Tiket/Kursi" value="">
                                <div class="help-block with-errors"></div>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="pickup_address">Alamat Jemput</label>
                                <textarea name="pickup_address" id="pickup_address" class="form-control" rows="5" placeholder="Tulis Alamat Jemput Disini"></textarea>
                                <div class="help-block with-errors"></div>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="drop_address">Alamat Antar</label>
                                <textarea name="drop_address" id="drop_address" class="form-control" rows="5" placeholder="Tulis Alamat Antar Disini"></textarea>
                                <div class="help-block with-errors"></div>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="note">Catatan</label>
                                <textarea name="note" id="note" class="form-control" rows="5" placeholder="Tulis Catatan Disini"></textarea>
                                <div class="help-block with-errors"></div>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group mb-0">
                                <button type="submit" class="theme-btn style-two" id="pesan-btn">
                                    <span data-hover="Pesan Sekarang">Pesan Sekarang</span>
                                    <i class="fal fa-arrow-right"></i>
                                </button>

                            </div>
                        </div>
                    </div>

                </div>
            </div>

        </div>
    </div>
</section>
<!-- Contact Form Area end -->

<!-- SKRIP AREA -->


<!-- Destinations Area start -->
<section class="destinations-area bgc-black pt-100 pb-70 rel z-1">
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-lg-12">
                <div class="section-title text-white text-center counter-text-wrap mb-70" data-aos="fade-up" data-aos-duration="1500" data-aos-offset="50">
                    <h2>Selamat Datang di Website</h2>
                    <p>{{ $profil->website }}</p>
                </div>
            </div>
        </div>
        <div class="row justify-content-center">
            @foreach ($data_fleets as $p)
            <div class="col-xxl-3 col-xl-4 col-md-6">
                <div class="destination-item" data-aos="fade-up" data-aos-duration="1500" data-aos-offset="50">
                    <div class="image">
                        <div class="ratting"><i class="fas fa-star"></i> Top</div>
                        <img src="/upload/fleets/{{ $p->image }}" alt="Destination">
                    </div>
                    <div class="content">
                        <h5><a href=" ">{{ $p->name }}</a></h5>
                        <span class="time">{!! $p->description !!}</span>
                    </div>
                    <div class="destination-footer">
                        <!-- <span class="price"><span>Rp {{ number_format($p->price, 0, ',', '.') }}</span></span> -->
                        <a target="_blank" href="https://wa.me/{{$profil->no_wa}}?text={{ urlencode('Hallo Admin ' . $profil->nama_profil . ', saya ingin bertanya terkait Armada ' . $p->name . '. Terima kasih.') }}" class="read-more">
                            Pesan Sekarang<i class="fal fa-angle-right"></i>
                        </a>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

    </div>
</section>
<!-- Destinations Area end -->


<!-- CTA Area start -->
<section id="layanan" class="cta-area pt-100 rel z-1">

    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-lg-12">
                <div class="section-title text-center counter-text-wrap mb-70" data-aos="fade-up" data-aos-duration="1500" data-aos-offset="50">
                    <h2>Layanan Perjalanan</h2>
                    <p>Kami menyediakan layanan Travel terbaik dengan jaminan harga terjangkau daripada lainnya</p>
                </div>
            </div>
        </div>
        <div class="container container-1400">
            <div class="search-filter-inner" data-aos="zoom-out-down" data-aos-duration="1500" data-aos-offset="50">
                <div class="filter-item clearfix">
                    <span class="title">Layanan</span><br>
                    <a target="_blank" href="https://wa.me/{{$profil->no_wa}}?text={{ urlencode('Hallo Admin ' . $profil->nama_profil . ', saya ingin bertanya terkait Informasi Layanan Reguler. Terima kasih.') }}">
                        <label for="" style="color:brown">Reguler</label>
                    </a>

                </div>
                <div class="filter-item clearfix">
                    <span class="title">Layanan</span><br>
                    <a target="_blank" href="https://wa.me/{{$profil->no_wa}}?text={{ urlencode('Hallo Admin ' . $profil->nama_profil . ', saya ingin bertanya terkait Informasi Layanan Private. Terima kasih.') }}">
                        <label for="" style="color:brown">Private</label>
                    </a>
                </div>
                <div class="filter-item clearfix">
                    <span class="title">Layanan</span><br>
                    <a target="_blank" href="https://wa.me/{{$profil->no_wa}}?text={{ urlencode('Hallo Admin ' . $profil->nama_profil . ', saya ingin bertanya terkait Informasi Layanan Carter Drop. Terima kasih.') }}">
                        <label for="" style="color:brown">Carter Drop</label>
                    </a>
                </div>
                <div class="filter-item clearfix">
                    <span class="title">Layanan</span><br>
                    <a target="_blank" href="https://wa.me/{{$profil->no_wa}}?text={{ urlencode('Hallo Admin ' . $profil->nama_profil . ', saya ingin bertanya terkait Informasi Layanan Paket Express. Terima kasih.') }}">
                        <label for="" style="color:brown">Paket Express</label>
                    </a>
                </div>

                <div class="search-button">

                    <a target="_blank" href="https://wa.me/{{$profil->no_wa}}?text={{ urlencode('Hallo Admin ' . $profil->nama_profil . ', saya ingin bertanya mengenai layanan apa saja yang ada di travel ini. Terima kasih.') }}" class="theme-btn">
                        <span data-hover="Pesan Sekarang">Pesan Sekarang</span>
                        <i class="fab fa-whatsapp"></i>
                    </a>

                </div>
            </div>
        </div>
        <br>
        <div class="row">
            <div class="col-xl-4 col-md-6" data-aos="zoom-in-down" data-aos-duration="1500" data-aos-offset="50">
                <div class="cta-item" style="background-image: url({{ asset('template/front') }}/assets/images/12.webp);">
                    <h2>Tarif Terjangkau</h2>
                    <span class="category">Tarif travel yang terjangkau dibanding travel lainnya.</span>
                    <a href="https://wa.me/{{$profil->no_wa}}?text={{ urlencode('Hallo Admin ' . $profil->nama_profil . ', saya ingin menanyakan informasi terkait Travel ini. Terima kasih.') }}" class="theme-btn style-two bgc-secondary">
                        <span data-hover="Pesan Sekarang">Pesan Sekarang</span>
                        <i class="fal fa-arrow-right"></i>
                    </a>
                </div>
            </div>
            <div class="col-xl-4 col-md-6" data-aos="zoom-in-down" data-aos-delay="50" data-aos-duration="1500" data-aos-offset="50">
                <div class="cta-item" style="background-image: url({{ asset('template/front') }}/assets/images/13.webp);">
                    <h2>Driver Berpengalaman</h2>
                    <span class="category">Didukung driver handal yang berpengalaman.</span>
                    <a href="https://wa.me/{{$profil->no_wa}}?text={{ urlencode('Hallo Admin ' . $profil->nama_profil . ', saya ingin menanyakan informasi terkait Travel ini. Terima kasih.') }}" class="theme-btn style-two">
                        <span data-hover="Pesan Sekarang">Pesan Sekarang</span>
                        <i class="fal fa-arrow-right"></i>
                    </a>
                </div>
            </div>
            <div class="col-xl-4 col-md-6" data-aos="zoom-in-down" data-aos-delay="100" data-aos-duration="1500" data-aos-offset="50">
                <div class="cta-item" style="background-image: url({{ asset('template/front') }}/assets/images/14.webp);">
                    <h2>Terpercaya</h2>
                    <span class="category">Telah dipercaya oleh banyak masyarakat sebagai jasa travel terbaik.</span>
                    <a href="https://wa.me/{{$profil->no_wa}}?text={{ urlencode('Hallo Admin ' . $profil->nama_profil . ', saya ingin menanyakan informasi terkait Travel ini. Terima kasih.') }}" class="theme-btn style-two bgc-secondary">
                        <span data-hover="Pesan Sekarang">Pesan Sekarang</span>
                        <i class="fal fa-arrow-right"></i>
                    </a>
                </div>
            </div>
        </div>

    </div>
</section>
<!-- CTA Area end -->

<!-- About Us Area start -->
<section class="about-us-area py-100 rpb-90 rel z-1">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-xl-5 col-lg-6">
                <div class="about-us-content rmb-55" data-aos="fade-left" data-aos-duration="1500" data-aos-offset="50">
                    <div class="section-title mb-25">
                        <h2>Perjalanan Anda Semakin Nyaman Bersama Rizki Jaya Trans</h2>
                    </div>
                    <p>Kami akan membuat perjalanan anda tidak pernah terlupakan dan selalu berkesan</p>
                    <div class="divider counter-text-wrap mt-45 mb-55"><span>Kami memiliki <span><span class="count-text plus" data-speed="3000" data-stop="5">0</span> Tahun</span> pengalaman di dunia travel</span></div>
                    <div class="row">
                        <div class="col-6">
                            <div class="counter-item counter-text-wrap">
                                <span class="count-text k-plus" data-speed="3000" data-stop="3">0</span>
                                <span class="counter-title">Perjalanan</span>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="counter-item counter-text-wrap">
                                <span class="count-text k-plus" data-speed="3000" data-stop="5">0</span>
                                <span class="counter-title">Konsumen</span>
                            </div>
                        </div>
                    </div>
                    <a href="https://wa.me/{{$profil->no_wa}}?text={{ urlencode('Hallo Admin ' . $profil->nama_profil . ', saya ingin menanyakan informasi terkait Travel ini. Terima kasih.') }}" class="theme-btn mt-10 style-two">
                        <span data-hover="Pesan Sekarang">Pesan Sekarang</span>
                        <i class="fal fa-arrow-right"></i>
                    </a>

                </div>
            </div>
            <div class="col-xl-7 col-lg-6" data-aos="fade-right" data-aos-duration="1500" data-aos-offset="50">
                <div class="about-us-image">
                    <div class="shape"><img src="{{ asset('template/front') }}/assets/images/about/shape1.png" alt="Shape"></div>
                    <div class="shape"><img src="{{ asset('template/front') }}/assets/images/about/shape2.png" alt="Shape"></div>
                    <div class="shape"><img src="{{ asset('template/front') }}/assets/images/about/shape3.png" alt="Shape"></div>
                    <div class="shape"><img src="{{ asset('template/front') }}/assets/images/about/shape4.png" alt="Shape"></div>
                    <div class="shape"><img src="{{ asset('template/front') }}/assets/images/about/shape5.png" alt="Shape"></div>
                    <div class="shape"><img src="{{ asset('template/front') }}/assets/images/about/shape6.png" alt="Shape"></div>
                    <div class="shape"><img src="{{ asset('template/front') }}/assets/images/about/shape7.png" alt="Shape"></div>
                    <img src="{{ asset('template/front') }}/assets/images/bnr.png" alt="About">
                </div>
            </div>
        </div>
    </div>
</section>
<!-- About Us Area end -->


<!-- Popular Destinations Area start -->
<section class="popular-destinations-area rel z-1">
    <div class="container-fluid">
        <div class="popular-destinations-wrap br-20 bgc-lighter pt-100 pb-70">
            <div class="row justify-content-center">
                <div class="col-lg-12">
                    <div class="section-title text-center counter-text-wrap mb-70" data-aos="fade-up" data-aos-duration="1500" data-aos-offset="50">
                        <h2>Jelajahi Tujuan Favorit</h2>
                        <p>Lebih dari <span class="count-text plus" data-speed="3000" data-stop="34500">0</span> tempat populer</p>
                    </div>
                </div>
            </div>
            <div class="container">
                <div class="row justify-content-center">
                    @foreach ($data_travel_routes as $p)
                    <div class="col-xl-3 col-md-6">
                        <div class="destination-item style-two" data-aos="flip-up" data-aos-duration="1500" data-aos-offset="50">
                            <div class="image">
                                <a href="https://wa.me/{{$profil->no_wa}}?text={{ urlencode('Hallo Admin ' . $profil->nama_profil . ', saya ingin menanyakan informasi perjalanan ' . $p->start . ' - ' . $p->end . ' dengan harga Rp ' . number_format($p->price, 0, ',', '.') . '. Terima kasih.') }}"><i class="fas fa-heart"></i></a>
                                <img src="/upload/travel_routes/{{ $p->image }}" alt="Destination">
                            </div>
                            <div class="content">
                                <h6><a href="https://wa.me/{{$profil->no_wa}}?text={{ urlencode('Hallo Admin ' . $profil->nama_profil . ', saya ingin menanyakan informasi perjalanan ' . $p->start . ' - ' . $p->end . ' dengan harga Rp ' . number_format($p->price, 0, ',', '.') . '. Terima kasih.') }}">{{ $p->start }} - {{ $p->end }}</a></h6>
                                <span class="time">Rp {{ number_format($p->price, 0, ',', '.') }}</span>
                                <a href="https://wa.me/{{$profil->no_wa}}?text={{ urlencode('Hallo Admin ' . $profil->nama_profil . ', saya ingin menanyakan informasi perjalanan ' . $p->start . ' - ' . $p->end . ' dengan harga Rp ' . number_format($p->price, 0, ',', '.') . '. Terima kasih.') }}" class="more"><i class="fas fa-chevron-right"></i></a>
                            </div>
                        </div>
                    </div>
                    @endforeach

                </div>
            </div>
        </div>
    </div>
</section>
<!-- Popular Destinations Area end -->


<!-- Features Area start -->
<section class="features-area pt-100 pb-45 rel z-1">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-xl-6">
                <div class="features-content-part mb-55" data-aos="fade-left" data-aos-duration="1500" data-aos-offset="50">
                    <div class="section-title mb-60">
                        <h2>Fasilitas Travel Yang Kami Berikan Untuk Anda</h2>
                    </div>
                    <div class="features-customer-box">
                        <div class="image">
                            <img src="{{ asset('template/front') }}/assets/images/rt.webp" style="border-radius: 20px;" alt="Features">
                        </div>
                        <div class="content">

                            <h6>Ratusan Konsumen</h6>
                            <div class="divider style-two counter-text-wrap my-25"><span><span class="count-text plus" data-speed="3000" data-stop="5">0</span> Tahun</span></div>
                            <p>Bangga Menjadi Bagian Perjalanan Anda</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-6" data-aos="fade-right" data-aos-duration="1500" data-aos-offset="50">
                <div class="row pb-25">
                    <div class="col-md-6">
                        <div class="feature-item">
                            <div class="icon"><i class="fas fa-air-conditioner"></i></div>
                            <div class="content">
                                <h5><a href="#">Full AC</a></h5>
                                <p>Di dalam kendaraan Anda dijamin tidak akan merasa kepanasan ataupun gerah, karena semua unit mobil yang kami sediakan sudah full AC.</p>
                            </div>
                        </div>

                    </div>
                    <div class="col-md-6">
                        <div class="feature-item mt-20">
                            <div class="icon"><i class="fas fa-battery-three-quarters"></i></div>
                            <div class="content">
                                <h5><a href="#">Charge Handphone</a></h5>
                                <p>Anda tidak usah khawatir jika di perjalanan HP Anda lowbat / mati, karena di dalam kendaraan tersedia port khusus untuk penggunaan charge HP.</p>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- Features Area end -->


<!-- Hotel Area start -->
<section id="tentang" class="hotel-area bgc-black py-100 rel z-1">
    <div class="container-fluid">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-5">
                    <div class="mobile-app-content rmb-55" data-aos="fade-left" data-aos-duration="1500" data-aos-offset="50" style="color: white;">
                        <div class="section-title mb-30">
                            <h2 style="color: white;">{{ $profil->nama_profil}}</h2>
                        </div>
                        <p>{{ $profil->deskripsi_1}}</p>

                    </div>
                    <div class="search-button">
                        <button class="theme-btn">
                            <a href="https://wa.me/{{$profil->no_wa}}?text={{ urlencode('Hallo Admin ' . $profil->nama_profil . ', saya ingin bertanya tentang informasi yang tersedia di travel ini. Terima kasih.') }}" style="color: inherit; text-decoration: none;">
                                <span data-hover="Pesan Sekarang">Pesan Sekarang</span>
                                <i class="fab fa-whatsapp"></i>
                            </a>
                        </button>

                    </div>

                </div>
                <div class="col-xl-7 col-lg-6" data-aos="fade-right" data-aos-duration="1500" data-aos-offset="50">
                    <div class="about-us-image">
                        <div class="shape"><img src="{{ asset('template/front') }}/assets/images/about/shape1.png" alt="Shape"></div>
                        <div class="shape"><img src="{{ asset('template/front') }}/assets/images/about/shape2.png" alt="Shape"></div>
                        <div class="shape"><img src="{{ asset('template/front') }}/assets/images/about/shape3.png" alt="Shape"></div>
                        <div class="shape"><img src="{{ asset('template/front') }}/assets/images/about/shape4.png" alt="Shape"></div>
                        <div class="shape"><img src="{{ asset('template/front') }}/assets/images/about/shape5.png" alt="Shape"></div>
                        <div class="shape"><img src="{{ asset('template/front') }}/assets/images/about/shape6.png" alt="Shape"></div>
                        <div class="shape"><img src="{{ asset('template/front') }}/assets/images/about/shape7.png" alt="Shape"></div>
                        <img src="{{ asset('template/front') }}/assets/images/bnr.png" alt="About">
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- Hotel Area end -->

<!-- Blog Area start -->
<section class="blog-area py-70 rel z-1">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-12">
                <div class="section-title text-center counter-text-wrap mb-70" data-aos="fade-up" data-aos-duration="1500" data-aos-offset="50">
                    <h2>Blog & Berita Terbaru</h2>
                    <p>Banyak Sekali <span class="count-text  bgc-primary">Informasi Penting </span> dan menarik akan anda dapatkan</p>
                </div>
            </div>
        </div>
        <div class="row justify-content-center">
            @foreach ($data_blogs as $p)
            <div class="col-xl-4 col-md-6">
                <div class="blog-item" data-aos="fade-up" data-aos-duration="1500" data-aos-offset="50">
                    <div class="content">
                        <a href="{{ route('blog.blog_detail', $p->slug) }}" class="category">
                            {{ $p->blog_category ? $p->blog_category->name : '-' }}
                        </a>
                        <h5><a href="{{ route('blog.blog_detail', $p->slug) }}">{{ $p->title }}</a></h5>
                        <ul class="blog-meta">
                            <li><i class="far fa-calendar-alt"></i> <a href="{{ route('blog.blog_detail', $p->slug) }}">{{ $p->posting_date }}</a></li>
                            <li><i class="far fa-user"></i> <a href="{{ route('blog.blog_detail', $p->slug) }}">{{ $p->writer }}</a></li>
                        </ul>
                    </div>
                    <div class="image">
                        <img src="/upload/blogs/{{ $p->image }}" alt="Blog">
                    </div>
                    <a href="{{ route('blog.blog_detail', $p->slug) }}" class="theme-btn">
                        <span data-hover="Selengkapnya">Selengkapnya</span>
                        <i class="fal fa-arrow-right"></i>
                    </a>
                </div>
            </div>
            @endforeach


        </div>
    </div>
</section>
<!-- Blog Area end -->
 <br>
 <div class="embedsocial-hashtag" data-ref="dd0e021214f64a25dd1bcde8659b56f727778812"> <a class="feed-powered-by-es feed-powered-by-es-feed-img es-widget-branding" href="https://embedsocial.com/social-media-aggregator/" target="_blank" title="Instagram widget"> <img src="https://embedsocial.com/cdn/icon/embedsocial-logo.webp" alt="EmbedSocial"> <div class="es-widget-branding-text">Instagram widget</div> </a> </div> <script> (function(d, s, id) { var js; if (d.getElementById(id)) {return;} js = d.createElement(s); js.id = id; js.src = "https://embedsocial.com/cdn/ht.js"; d.getElementsByTagName("head")[0].appendChild(js); }(document, "script", "EmbedSocialHashtagScript")); </script>


@endsection


@push('script')
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/id.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const timeSelect = document.getElementById('time');

        // Buat opsi waktu dari 00.00 hingga 23.00
        for (let hour = 0; hour < 24; hour++) {
            const hourString = hour.toString().padStart(2, '0'); // Tambahkan leading zero
            const timeOption = `${hourString}:00`; // Format waktu (HH:00)

            // Tambahkan opsi ke elemen select
            const optionElement = document.createElement('option');
            optionElement.value = timeOption;
            optionElement.textContent = timeOption;

            timeSelect.appendChild(optionElement);
        }
    });
</script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Inisialisasi flatpickr untuk input date
        flatpickr("#date", {
            dateFormat: "Y-m-d", // Format tanggal (YYYY-MM-DD)
            altInput: true, // Menampilkan input alternatif
            altFormat: "d F Y", // Format alternatif yang terlihat oleh pengguna
            defaultDate: "today", // Tanggal default
            locale: "id", // Mengatur bahasa ke Indonesia
            onChange: function(selectedDates, dateStr) {
                // Panggil fungsi untuk memperbarui hari
                updateDay(dateStr);
            }
        });

        // Ambil nama hari dalam bahasa Indonesia
        function getIndonesianDay(dateString) {
            const days = [
                "Minggu", "Senin", "Selasa", "Rabu",
                "Kamis", "Jumat", "Sabtu"
            ];
            const date = new Date(dateString);
            return days[date.getDay()];
        }

        // Update input dengan id="day"
        function updateDay(dateStr) {
            const dayInput = document.getElementById('day');
            if (dateStr) {
                const dayName = getIndonesianDay(dateStr);
                dayInput.value = dayName; // Masukkan nama hari ke input day
            } else {
                dayInput.value = ""; // Kosongkan jika tidak ada tanggal
            }
        }

        // Set hari otomatis untuk tanggal default
        const dateInput = document.getElementById('date').value;
        if (dateInput) {
            updateDay(dateInput);
        }
    });
</script>

<!-- Hero Area End -->
<script>
    document.getElementById('pesan-btn').addEventListener('click', function() {
        // Ambil nilai dari input
        const travelRoute = document.getElementById('travel_route').value;
        const fleet = document.getElementById('fleet').value;
        const date = document.getElementById('date').value;
        const day = document.getElementById('day').value;
        const time = document.getElementById('time').value;
        const fullName = document.getElementById('full_name').value;
        const waNumber = document.getElementById('wa_number').value;
        const totalTicket = document.getElementById('total_ticket').value;
        const pickupAddress = document.getElementById('pickup_address').value;
        const dropAddress = document.getElementById('drop_address').value;
        const note = document.getElementById('note').value;

        // Nomor WhatsApp dari profil
        const noWa = "{{ $profil->no_wa }}";
        const namaProfil = "{{ $profil->nama_profil }}";

        // Validasi input (opsional)
        if (!travelRoute || !fleet || !date || !time || !fullName || !waNumber || !totalTicket || !pickupAddress || !dropAddress) {
            alert("Harap lengkapi semua data sebelum melanjutkan.");
            return;
        }

        // Format pesan WhatsApp
        const message = `Hallo. Admin ${namaProfil}, Saya ingin memesan dengan rincian sebagai berikut:\n\n` +
            `Nama Lengkap: ${fullName}\n` +
            `No WA: ${waNumber}\n` +
            `Jumlah Tiket/Kursi: ${totalTicket}\n` +
            `Rute: ${travelRoute}\n` +
            `Armada: ${fleet}\n` +
            `Tanggal: ${date} (${day})\n` +
            `Jam Berangkat: ${time}\n` +
            `Alamat Jemput: ${pickupAddress}\n` +
            `Alamat Antar: ${dropAddress}\n` +
            `Catatan: ${note || '-'}\n\n` +
            `www.rizkijayatrans.co.id`;
        `Terima kasih.`;

        // Encode pesan untuk URL
        const encodedMessage = encodeURIComponent(message);

        // Redirect ke WhatsApp
        const waUrl = `https://wa.me/${noWa}?text=${encodedMessage}`;
        window.open(waUrl, '_blank');
    });
</script>



@endpush