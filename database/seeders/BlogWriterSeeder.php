<?php

namespace Database\Seeders;

use App\Models\BlogPost;
use App\Models\BlogWriter;
use Illuminate\Database\Seeder;

class BlogWriterSeeder extends Seeder
{
    public function run(): void
    {
        $writers = [
            [
                'name' => 'Priya Sharma',
                'bio' => 'Residential property specialist covering Gurgaon rentals, society shortlists, and tenant-friendly neighbourhoods across NCR.',
                'photo' => 'https://ui-avatars.com/api/?name=Priya+Sharma&size=400&background=4a6278&color=fff&bold=true',
                'sort_order' => 1,
            ],
            [
                'name' => 'Rahul Verma',
                'bio' => 'Luxury homes and first-time buyer guide for Delhi NCR — from site visits and RERA checks to negotiation and registration.',
                'photo' => 'https://ui-avatars.com/api/?name=Rahul+Verma&size=400&background=1a56db&color=fff&bold=true',
                'sort_order' => 2,
            ],
            [
                'name' => 'Anita Kapoor',
                'bio' => 'Commercial leasing and investment insights for Golf Course Road, MG Road, and emerging NCR business districts.',
                'photo' => 'https://ui-avatars.com/api/?name=Anita+Kapoor&size=400&background=5a7d6a&color=fff&bold=true',
                'sort_order' => 3,
            ],
        ];

        $ids = [];
        foreach ($writers as $data) {
            $writer = BlogWriter::updateOrCreate(
                ['name' => $data['name']],
                $data
            );
            $ids[] = $writer->id;
        }

        if ($ids === []) {
            return;
        }

        $posts = BlogPost::query()->whereNull('blog_writer_id')->orderBy('id')->get();
        foreach ($posts as $index => $post) {
            $post->update([
                'blog_writer_id' => $ids[$index % count($ids)],
            ]);
        }
    }
}
