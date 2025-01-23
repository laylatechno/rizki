@extends('front.layouts.app')


@section('content')
<!-- Page Banner Start -->
<section class="page-banner-area pt-50 pb-35 rel z-1 bgs-cover" style="background-image: url({{ asset('template/front') }}/assets/images/heroku.webp);">
  <div class="container">
    <div class="banner-inner text-white">
      <h2 class="page-title mb-10" data-aos="fade-left" data-aos-duration="1500" data-aos-offset="50">{{$title}}</h2>
      <nav aria-label="breadcrumb">
        <ol class="breadcrumb justify-content-center mb-20" data-aos="fade-right" data-aos-delay="200" data-aos-duration="1500" data-aos-offset="50">
          <li class="breadcrumb-item"><a href="/">Home</a></li>
          <li class="breadcrumb-item active">Blog</li>
        </ol>
      </nav>
    </div>
  </div>
</section>
<!-- Page Banner End -->

<!-- Blog Detaisl Area start -->


<!-- Blog List Area start -->
<section class="blog-list-page py-100 rel z-1">
  <div class="container">
    <div class="row">
      <div class="col-lg-8">
        @foreach ($data_blogs as $p)
        <div class="blog-item style-three" data-aos="fade-up" data-aos-duration="1500" data-aos-offset="50">
          <div class="image">
            <img src="/upload/blogs/{{ $p->image }}" alt="Blog List">
          </div>
          <div class="content">
            <a href="" class="category">{{ $p->blog_category->name ?? '-' }}</a>
            <h5><a href="{{ route('blog.blog_detail', $p->slug) }}">{{ $p->title }}</a></h5>
            <ul class="blog-meta">
              <li><i class="far fa-calendar-alt"></i> <a href="#">{{ $p->posting_date }}</a></li>
            </ul>
            <p>{{ \Illuminate\Support\Str::limit(strip_tags($p->description), 40) }}</p>

            <a href="{{ route('blog.blog_detail', $p->slug) }}" class="theme-btn style-two style-three">
              <span data-hover="Selengkapnya">Selengkapnya</span>
              <i class="fal fa-arrow-right"></i>
            </a>
          </div>
        </div>
        @endforeach



        <ul class="pagination pt-15 flex-wrap" data-aos="fade-up" data-aos-duration="1500" data-aos-offset="50">
          <nav aria-label="Page navigation example">
            {{ $data_blogs->links('pagination::bootstrap-4') }}
          </nav>
        </ul>
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
              <li><a href="{{ route('blog', ['category' => $p->slug]) }}">{{ $p->name }}</a></li>
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