<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Spatie\Sitemap\Sitemap;
use Spatie\Sitemap\Tags\Url;
use App\Models\Blog;
use Carbon\Carbon;

class GenerateSitemap extends Command
{
    protected $signature = 'sitemap:generate';
    protected $description = 'Generate the sitemap';

    public function handle()
    {
        $sitemap = Sitemap::create();

        // Tambahkan halaman statis
        $sitemap->add(Url::create('/')
            ->setLastModificationDate(Carbon::now())
            ->setChangeFrequency('daily')
            ->setPriority(1.0));

        $sitemap->add(Url::create('/blog')
            ->setChangeFrequency('daily')
            ->setPriority(0.8));

        $sitemap->add(Url::create('/kontak')
            ->setChangeFrequency('monthly')
            ->setPriority(0.5));

        // Tambahkan halaman blog dinamis
        Blog::all()->each(function ($blog) use ($sitemap) {
            $sitemap->add(Url::create("/blog/{$blog->slug}")
                ->setLastModificationDate($blog->updated_at)
                ->setChangeFrequency('weekly')
                ->setPriority(0.7));
        });

        // Simpan sitemap
        $sitemap->writeToFile(public_path('sitemap.xml'));

        $this->info('Sitemap generated successfully!');
    }
}