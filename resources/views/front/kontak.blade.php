@extends('front.layouts.app')

<div class="g-recaptcha" data-sitekey="{{ config('services.recaptcha.sitekey') }}"></div>
@section('content')
<!-- Page Banner Start -->
<section class="page-banner-area pt-50 pb-35 rel z-1 bgs-cover" style="background-image: url({{ asset('template/front') }}/assets/images/heroku.webp);">
  <div class="container">
    <div class="banner-inner text-white">
      <h2 class="page-title mb-10" data-aos="fade-left" data-aos-duration="1500" data-aos-offset="50">Kontak Kami</h2>
      <nav aria-label="breadcrumb">
        <ol class="breadcrumb justify-content-center mb-20" data-aos="fade-right" data-aos-delay="200" data-aos-duration="1500" data-aos-offset="50">
          <li class="breadcrumb-item"><a href="/">Home</a></li>
          <li class="breadcrumb-item active">Kontak Kami</li>
        </ol>
      </nav>
    </div>
  </div>
</section>
<!-- Page Banner End -->



<!-- Hotel Area start -->
<section id="tentang" class="hotel-area bgc-white py-100 rel z-1">
  <div class="container-fluid">
    <div class="container">
      <div class="row align-items-center">
        <div class="col-lg-5">
          <div class="mobile-app-content rmb-55" data-aos="fade-left" data-aos-duration="1500" data-aos-offset="50">
            <div class="section-title mb-30">
              <h2>{{ $profil->nama_profil}}</h2>
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


<!-- Contact Form Area start -->
<section class="contact-form-area py-70 rel z-1">
  <div class="container">
    <div class="row align-items-center">
      <div class="col-lg-7">
        <div class="comment-form bgc-lighter z-1 rel mb-30 rmb-55">
          @if(session('success'))
          <div class="alert alert-success">
            {{ session('success') }}
          </div>
          @endif

          @if(session('error'))
          <div class="alert alert-danger">
            {{ session('error') }}
          </div>
          @endif

          @if ($errors->any())
          <div class="alert alert-danger">
            <strong>Whoops!</strong> Ada beberapa masalah dengan data yang anda masukkan.
            <ul>
              @foreach ($errors->all() as $error)
              <li>{{ $error }}</li>
              @endforeach
            </ul>
          </div>
          @endif

          <form method="POST" action="{{ route('kirim_kontak') }}">
            @csrf
            <div class="section-title">
              <h2>Hubungi Kami</h2>
            </div>
            <p>Alamat email Anda tidak akan dipublikasikan. Isi sesuai data diri anda</p>
            <div class="row mt-35">
              <div class="col-md-6">
                <div class="form-group">
                  <label for="full_name">Nama Lengkap</label>
                  <input type="text" id="full_name" name="full_name" class="form-control" placeholder="Masukkan Nama Anda" value="">
                  <div class="help-block with-errors"></div>
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group">
                  <label for="phone_number">No Handphone</label>
                  <input type="text" id="phone_number" name="phone_number" class="form-control" placeholder="Masukkan No Handphone" value="">
                  <div class="help-block with-errors"></div>
                </div>
              </div>
              <div class="col-md-12">
                <div class="form-group">
                  <label for="email">Email</label>
                  <input type="email" id="email" name="email" class="form-control" placeholder="Masukkan email" value="">
                  <div class="help-block with-errors"></div>
                </div>
              </div>
              <div class="col-md-12">
                <div class="form-group">
                  <label for="message">Isi Pesan</label>
                  <textarea name="message" id="message" class="form-control" rows="5" placeholder="Tulis Isi Pesan Disini"></textarea>
                  <div class="help-block with-errors"></div>
                </div>
              </div>

              <div class="col-md-12">
                <div class="form-group">
                  <script src="https://www.google.com/recaptcha/api.js" async defer></script>
                  <div class="g-recaptcha" data-sitekey="{{ config('services.recaptcha.sitekey') }}"></div>
                  @error('g-recaptcha-response')
                  <span class="text-danger">{{ $message }}</span>
                  @enderror
                </div>
              </div>


              <div class="col-md-12">
                <div class="form-group mb-0">
                  <button type="submit" class="theme-btn style-two">
                    <span data-hover="Kirim Pesan">Kirim Pesan</span>
                    <i class="fal fa-arrow-right"></i>
                  </button>
                </div>
              </div>



            </div>
          </form>

        </div>
      </div>
      <div class="col-lg-5">
        <div class="contact-images-part" data-aos="fade-right" data-aos-duration="1500" data-aos-offset="50">
          <div class="row">
            <div class="col-12">
              <img src="https://t4.ftcdn.net/jpg/08/37/89/75/360_F_837897590_2Vq3pGsZ0k3tdrHhyhwal2SU5cjRMMRA.jpg" style="border-radius: 10px;" alt="Contact">
            </div>
            <div class="col-6">
              <img src="https://tripsthan.com/img/car/innova-crysta--1.jpg" alt="Contact">
            </div>
            <div class="col-6">
              <img src="https://5.imimg.com/data5/QJ/FN/MY-5864160/innova-car-services.jpg" alt="Contact">
            </div>
          </div>
          <!-- <div class="circle-logo">
            <img src="{{ asset('/upload/profil/' . $profil->logo) }}" alt="Logo">
            <span class="title h2">Travel</span>
          </div> -->
        </div>
      </div>
    </div>
  </div>
</section>
<!-- Contact Form Area end -->


<!-- Contact Map Start -->
<div class="contact-map">
  <iframe src="{{$profil->embed_map}}" style="border:0; width: 100%;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
</div>
<!-- Contact Map End -->
<br>


@endsection