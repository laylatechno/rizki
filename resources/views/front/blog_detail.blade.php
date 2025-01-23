@extends('front.layouts.app')


@section('content')
<!-- Page Banner Start -->
<section class="page-banner-area pt-50 pb-35 rel z-1 bgs-cover" style="background-image: url({{ asset('template/front') }}/assets/images/heroku.webp);">
  <div class="container">
    <div class="banner-inner text-white">
      <h2 class="page-title mb-10" data-aos="fade-left" data-aos-duration="1500" data-aos-offset="50">{{$blogs->title}}</h2>
      <nav aria-label="breadcrumb">
        <ol class="breadcrumb justify-content-center mb-20" data-aos="fade-right" data-aos-delay="200" data-aos-duration="1500" data-aos-offset="50">
          <li class="breadcrumb-item"><a href="/">Home</a></li>
          <li class="breadcrumb-item active">Blog Detail</li>
        </ol>
      </nav>
    </div>
  </div>
</section>
<!-- Page Banner End -->

<!-- Blog Detaisl Area start -->
<section class="blog-detaisl-page py-100 rel z-1">
  <div class="container">
    <div class="row">
      <div class="col-lg-8">
        <div class="blog-details-content" data-aos="fade-up" data-aos-duration="1500" data-aos-offset="50">
          <a href="" class="category">{{ $blogs->blog_category->name ?? '-' }}</a>
          <ul class="blog-meta mb-30">
            <li><i class="far fa-user"></i> <a href="#">{{$blogs->writer}}</a></li>
            <li><i class="far fa-calendar-alt"></i> <a href="#">{{$blogs->posting_date}}</a></li>
          </ul>
          <p>{!!$blogs->description!!}</p>
          <blockquote class="mt-30 mb-35" data-aos="fade-up" data-aos-duration="1500" data-aos-offset="50">
            <i class="flaticon-quote"></i>
            <div class="text">"Di dunia tur dan perjalanan, setiap perjalanan adalah undangan untuk menjelajahi hal yang tidak diketahui, terhubung dengan budaya, dan menciptakan kenangan yang bertahan seumur hidup. Ini bukan hanya tentang tujuan, petualangan yang luar biasa."
            </div>
            <div class="blockquote-footer">
              ~ {{$profil->nama_profil}}
            </div>
          </blockquote>

        </div>



      </div>
      <div class="col-lg-4 col-md-8 col-sm-10 rmt-75">
        <div class="blog-sidebar">

          <div class="widget widget-search" data-aos="fade-up" data-aos-duration="1500" data-aos-offset="50">
            <form action="{{ route('blog') }}" class="default-search-form" method="GET">
              <input type="text" name="q" placeholder="Search" value="{{ request('q') }}" required>
              <button type="submit" class="searchbutton far fa-search"></button>
            </form>
          </div>

          <div class="widget widget-category" data-aos="fade-up" data-aos-duration="1500" data-aos-offset="50">
            <h5 class="widget-title">Kategori</h5>
            <ul class="list-style-three">
              @foreach ($data_blog_categories as $p)
              <li><a href="">{{$p->name}}</a></li>
              @endforeach
            </ul>
          </div>

          <div class="widget widget-news" data-aos="fade-up" data-aos-duration="1500" data-aos-offset="50">
            <h5 class="widget-title">Blog Terakhir</h5>
            <ul>
              @foreach ($data_blogs as $p)
              <li>
                <div class="image">
                  <img src="/upload/blogs/{{ $p->image }}" alt="News">
                </div>
                <div class="content">
                  <h6><a href="{{ route('blog.blog_detail', $p->slug) }}">{{$p->title}}</a></h6>
                  <span class="date"><i class="far fa-calendar-alt"></i> {{$p->posting_date}}</span>
                </div>
              </li>
              @endforeach
            </ul>
          </div>


          <div class="widget widget-cta" data-aos="fade-up" data-aos-duration="1500" data-aos-offset="50">
            <div class="content text-white">
              <span class="h6">Mulai Perjalanan Anda</span>
              <h3>Dengan Layanan Terbaik</h3>
              <a href="https://wa.me/{{$profil->no_wa}}?text={{ urlencode('Hallo Admin ' . $profil->nama_profil . ', saya ingin menanyakan informasi terkait Travel ini. Terima kasih.') }}" class="theme-btn style-two bgc-secondary">
                <span data-hover="Pesan Sekarang">Pesan Sekarang</span>
                <i class="fab fa-whatsapp"></i>
              </a>
            </div>
            <div class="image">
              <img src="https://t4.ftcdn.net/jpg/08/37/89/75/360_F_837897590_2Vq3pGsZ0k3tdrHhyhwal2SU5cjRMMRA.jpg" alt="CTA">
            </div>
            <div class="cta-shape"><img src="{{ asset('template/front') }}/assets/images/widgets/cta-shape.png" alt="Shape"></div>
          </div>

        </div>
      </div>
    </div>
  </div>
</section>
<!-- Blog Detaisl Area end -->


@endsection