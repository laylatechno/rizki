<?php

namespace App\Http\Controllers;

use App\Models\Blog;
use App\Models\BlogCategory;
use App\Models\Contact;
use App\Models\Fleet;
use App\Models\Product;
use App\Models\TravelRoute;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;

class BerandaController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        // Set page title and subtitle
        $title = "Rizki Jaya Trans";
        $subtitle = "Menu Beranda";

        // Menggunakan eager loading dan memilih kolom yang diperlukan
        $data_fleets = Fleet::select('id', 'name', 'image', 'description', 'price')->get();
        $data_travel_routes = TravelRoute::select('id', 'image', 'price', 'start', 'end')->get();
        $data_blogs = Blog::with(['blog_category:id,name'])->select('id', 'title', 'description', 'slug', 'posting_date', 'writer', 'image', 'blog_category_id')->get();


        return view('front.beranda', compact(
            'data_blogs',
            'data_fleets',
            'data_travel_routes',
            'title',
            'subtitle'
        ));
    }



    public function kontak()
    {
        // Set page title and subtitle
        $title = "Kontak - Rizki Jaya Trans";
        $subtitle = "Menu Kontak";
        $produk = Product::all();
        return view('front.kontak', compact(
            'title',
            'subtitle',
            'produk',
        ));
    }



    public function storeContact(Request $request): RedirectResponse
    {
        $request->validate([
            'full_name' => 'required|string|max:255',
            'phone_number' => 'required|string|max:20|regex:/^\+?\d{1,4}?[\d\s\-]+$/', // Menambahkan regex untuk validasi nomor telepon
            'email' => 'required|email|max:255|email:rfc,dns', // Validasi email yang lebih ketat
            'message' => 'required|string|max:1000', // Tambahkan batasan panjang pesan
            'g-recaptcha-response' => 'required|recaptcha'
        ], [
            'full_name.required' => 'Nama wajib diisi.',
            'phone_number.regex' => 'Nomor handphone tidak valid.', // Menambahkan pesan kesalahan untuk validasi telepon
            'message.max' => 'Pesan terlalu panjang, maksimal 1000 karakter.',
            'g-recaptcha-response.required' => 'Harap selesaikan verifikasi reCAPTCHA.',
            'g-recaptcha-response.recaptcha' => 'Verifikasi reCAPTCHA gagal.'
        ]);

        // Sanitasi input untuk menghindari XSS atau kode berbahaya
        $input = $request->only(['full_name', 'phone_number', 'email', 'message']);
        $input['message'] = strip_tags($input['message']); // Menghapus tag HTML dari pesan

        // Simpan data kontak
        Contact::create($input);

        return redirect()->route('kontak')->with('success', 'Pesan Anda berhasil dikirim!');
    }



    public function blog(Request $request)
    {
        // Set page title and subtitle
        $title = "Halaman Blog";
        $subtitle = "Menu Blog";

        // Ambil kategori blog
        $data_blog_categories = BlogCategory::all();

        // Query untuk pencarian dan filter
        $query = Blog::with('blog_category')->orderBy('id', 'desc');

        // Filter berdasarkan kategori jika ada input 'category'
        if ($request->has('category') && $request->category != '') {
            // Cari kategori berdasarkan slug
            $blogCategory = BlogCategory::where('slug', $request->category)->first();

            if ($blogCategory) {
                $query->where('blog_category_id', $blogCategory->id);
                $title = "Kategori: " . $blogCategory->name; // Update title sesuai kategori
                $subtitle = "Menampilkan blog berdasarkan kategori";
            } else {
                // Jika kategori tidak ditemukan, redirect atau tampilkan pesan
                return redirect()->route('blog.index')->with('error', 'Kategori tidak ditemukan');
            }
        }

        // Filter berdasarkan pencarian jika ada input 'q'
        if ($request->has('q') && $request->q != '') {
            $query->where(function ($subquery) use ($request) {
                $subquery->where('title', 'like', '%' . $request->q . '%')
                    ->orWhere('description', 'like', '%' . $request->q . '%');
            });
        }

        // Ambil data dengan pagination
        $data_blogs = $query->paginate(10);

        return view('front.blog', compact(
            'data_blog_categories',
            'title',
            'data_blogs',
            'subtitle'
        ));
    }



    public function blog_detail($slug)
    {
        $title = "Halaman Blog Detail";
        $subtitle = "Menu Blog Detail";
        $blogs = Blog::where('slug', $slug)->firstOrFail();
        $data_blog_categories = BlogCategory::all();
        $data_blogs = Blog::all();
        return view('front.blog_detail', compact(
            'data_blog_categories',
            'title',
            'data_blogs',
            'blogs',
            'subtitle',

        ));
    }
}
