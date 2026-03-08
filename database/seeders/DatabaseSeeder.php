<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Era;
use App\Models\Article;
use App\Models\ArticleSection;
use App\Models\ArticleVideo;
use App\Models\Quiz;
use App\Models\QuizQuestion;
use App\Models\Topic;
use App\Models\FunFact;
use App\Models\TimelineEvent;
use App\Models\MapLocation;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Disable foreign key checks for clean seeding
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        
        User::truncate();
        Era::truncate();
        Article::truncate();
        ArticleSection::truncate();
        ArticleVideo::truncate();
        Quiz::truncate();
        QuizQuestion::truncate();
        Topic::truncate();
        FunFact::truncate();
        TimelineEvent::truncate();
        MapLocation::truncate();

        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // 1. Users
        User::create([
            'name'     => 'Admin Jelajah',
            'email'    => 'admin@sejarah.id',
            'password' => Hash::make('password123'),
            'role'     => 'admin',
        ]);

        User::create([
            'name'     => 'Budi Penjelajah',
            'email'    => 'budi@gmail.com',
            'password' => Hash::make('password123'),
            'role'     => 'user',
        ]);

        // 2. Eras
        $eras = [
            [
                'name' => 'Masa Hindu-Buddha',
                'slug' => 'hindu-buddha',
                'color_hue' => '35',
                'badge_class' => 'bg-amber-100 text-amber-800',
                'sort_order' => 1,
            ],
            [
                'name' => 'Masa Kesultanan Islam',
                'slug' => 'kesultanan',
                'color_hue' => '150',
                'badge_class' => 'bg-emerald-100 text-emerald-800',
                'sort_order' => 2,
            ],
            [
                'name' => 'Masa Kolonialisme',
                'slug' => 'kolonial',
                'color_hue' => '210',
                'badge_class' => 'bg-blue-100 text-blue-800',
                'sort_order' => 3,
            ],
            [
                'name' => 'Masa Pergerakan Nasional',
                'slug' => 'pergerakan',
                'color_hue' => '280',
                'badge_class' => 'bg-purple-100 text-purple-800',
                'sort_order' => 4,
            ],
            [
                'name' => 'Masa Kemerdekaan',
                'slug' => 'kemerdekaan',
                'color_hue' => '0',
                'badge_class' => 'bg-red-100 text-red-800',
                'sort_order' => 5,
            ],
        ];

        $eraMap = [];
        foreach ($eras as $eraData) {
            $eraMap[$eraData['slug']] = Era::create($eraData);
        }

        // 3. Articles Data
        $articlesData = [
            [
                'slug' => 'kerajaan-kutai',
                'title' => 'Kerajaan Kutai',
                'era_slug' => 'hindu-buddha',
                'year' => 'Abad ke-4 M',
                'summary' => 'Kerajaan Hindu tertua di Indonesia yang terletak di Kalimantan Timur, dikenal melalui prasasti Yupa peninggalan Raja Mulawarman.',
                'hero_image' => 'https://images.unsplash.com/photo-1590059441112-9c9861e69d7b?q=80&w=1200',
                'video_id' => 'uP46wXW_2yM',
                'content' => [
                    ['heading' => 'Latar Belakang', 'paragraphs' => ['Kerajaan Kutai merupakan kerajaan Hindu tertua di Indonesia yang diperkirakan berdiri pada abad ke-4 Masehi. Kerajaan ini terletak di hulu Sungai Mahakam, Kalimantan Timur. Keberadaan kerajaan ini diketahui dari penemuan tujuh buah prasasti yang dikenal sebagai Prasasti Yupa.','Prasasti-prasasti tersebut ditulis dalam huruf Pallawa dan bahasa Sanskerta, menunjukkan adanya pengaruh kebudayaan India yang kuat pada masa itu. Prasasti ini merupakan bukti tertulis tertua tentang keberadaan kerajaan di Nusantara.']],
                    ['heading' => 'Raja-Raja Kutai', 'paragraphs' => ['Berdasarkan Prasasti Yupa, silsilah raja-raja Kutai dimulai dari Kudungga, yang merupakan nama asli Indonesia (bukan nama Sanskrit). Hal ini menunjukkan bahwa kerajaan ini awalnya didirikan oleh penguasa lokal sebelum mengalami proses Indianisasi.','Putra Kudungga bernama Aswawarman yang disebut sebagai \'wangsakarta\' atau pembentuk keluarga raja. Gelar ini menunjukkan bahwa Aswawarman-lah yang pertama kali menganut agama Hindu dan mengadopsi tradisi kerajaan India, sehingga dianggap sebagai pendiri dinasti baru.']],
                ],
                'quiz' => [
                    ['question' => 'Di mana letak Kerajaan Kutai?', 'options' => ['Sumatera Selatan', 'Kalimantan Timur', 'Jawa Tengah', 'Sulawesi Selatan'], 'correct_index' => 1, 'explanation' => 'Kerajaan Kutai terletak di tepi Sungai Mahakam, Kalimantan Timur.'],
                    ['question' => 'Apa nama prasasti peninggalan Kerajaan Kutai?', 'options' => ['Prasasti Tugu', 'Prasasti Ciaruteun', 'Prasasti Yupa', 'Prasasti Kalasan'], 'correct_index' => 2, 'explanation' => 'Kerajaan Kutai meninggalkan 7 Prasasti Yupa yang ditulis dalam aksara Pallawa.'],
                ]
            ],
            [
                'slug' => 'kerajaan-sriwijaya',
                'title' => 'Kerajaan Sriwijaya',
                'era_slug' => 'hindu-buddha',
                'year' => 'Abad ke-7 – 13 M',
                'summary' => 'Kerajaan maritim terbesar di Asia Tenggara yang berpusat di Palembang, menjadi pusat perdagangan dan pendidikan agama Buddha.',
                'hero_image' => 'https://images.unsplash.com/photo-1625244724123-1f5d06711540?q=80&w=1200',
                'video_id' => 'Xm1Yw_qE1Yw',
                'content' => [
                    ['heading' => 'Kejayaan Maritim', 'paragraphs' => ['Sriwijaya merupakan kerajaan maritim terbesar di Asia Tenggara yang berpusat di Palembang, Sumatera Selatan. Kerajaan ini menguasai jalur perdagangan laut antara India, Cina, dan kepulauan Nusantara selama hampir enam abad.','Kekuatan angkatan laut Sriwijaya memungkinkannya menguasai Selat Malaka, salah satu jalur perdagangan paling penting di dunia. Pedagang dari berbagai penjuru dunia singgah di pelabuhan-pelabuhan Sriwijaya.']],
                ],
                'quiz' => [
                    ['question' => 'Di mana pusat Kerajaan Sriwijaya?', 'options' => ['Jambi', 'Palembang', 'Lampung', 'Bengkulu'], 'correct_index' => 1, 'explanation' => 'Kerajaan Sriwijaya berpusat di Palembang, Sumatera Selatan.'],
                    ['question' => 'Selat apa yang dikuasai Sriwijaya?', 'options' => ['Selat Sunda', 'Selat Bali', 'Selat Malaka', 'Selat Lombok'], 'correct_index' => 2, 'explanation' => 'Sriwijaya menguasai Selat Malaka, jalur perdagangan paling penting di dunia.'],
                ]
            ],
            [
                'slug' => 'kerajaan-majapahit',
                'title' => 'Kerajaan Majapahit',
                'era_slug' => 'hindu-buddha',
                'year' => '1293 – 1527 M',
                'summary' => 'Kerajaan terbesar di Nusantara yang berhasil menyatukan hampir seluruh wilayah kepulauan Indonesia di bawah kepemimpinan Gajah Mada.',
                'hero_image' => 'https://images.unsplash.com/photo-1596402184320-417d717867cd?q=80&w=1200',
                'video_id' => 'uP46wXW_2yM',
                'content' => [
                    ['heading' => 'Berdirinya Majapahit', 'paragraphs' => ['Kerajaan Majapahit didirikan oleh Raden Wijaya pada tahun 1293 setelah berhasil mengalahkan pasukan Mongol yang dikirim oleh Kaisar Kubilai Khan.','Pusat kerajaan terletak di Trowulan, Jawa Timur. Raden Wijaya kemudian bergelar Kertarajasa Jayawardhana dan menjadi raja pertama Majapahit.']],
                ],
                'quiz' => [
                    ['question' => 'Siapa pendiri Kerajaan Majapahit?', 'options' => ['Gajah Mada', 'Hayam Wuruk', 'Raden Wijaya', 'Tribhuwana'], 'correct_index' => 2, 'explanation' => 'Kerajaan Majapahit didirikan oleh Raden Wijaya pada tahun 1293.'],
                ]
            ],
            [
                'slug' => 'kesultanan-demak',
                'title' => 'Kesultanan Demak',
                'era_slug' => 'kesultanan',
                'year' => '1475 – 1554 M',
                'summary' => 'Kesultanan Islam pertama di Pulau Jawa yang memainkan peran penting dalam penyebaran agama Islam di Nusantara.',
                'hero_image' => 'https://images.unsplash.com/photo-1596402184320-417d717867cd?q=80&w=1200',
                'video_id' => 'uP46wXW_2yM',
                'content' => [
                    ['heading' => 'Berdirinya Kesultanan Demak', 'paragraphs' => ['Kesultanan Demak didirikan oleh Raden Patah sekitar tahun 1475 di pesisir utara Jawa Tengah. Raden Patah diperkirakan merupakan keturunan dari raja terakhir Majapahit.']],
                ],
                'quiz' => [
                    ['question' => 'Siapa pendiri Kesultanan Demak?', 'options' => ['Sultan Agung', 'Raden Patah', 'Sunan Kalijaga', 'Sultan Hasanuddin'], 'correct_index' => 1, 'explanation' => 'Kesultanan Demak didirikan oleh Raden Patah sekitar tahun 1475.'],
                ]
            ],
            [
                'slug' => 'borobudur',
                'title' => 'Candi Borobudur',
                'era_slug' => 'hindu-buddha',
                'year' => 'Abad ke-8 – 9 M',
                'summary' => 'Candi Buddha terbesar di dunia yang dibangun oleh Dinasti Syailendra, mahakarya arsitektur Indonesia.',
                'hero_image' => 'https://images.unsplash.com/photo-1590059441112-9c9861e69d7b?q=80&w=1200',
                'video_id' => 'uP46wXW_2yM',
                'content' => [
                    ['heading' => 'Pembangunan Borobudur', 'paragraphs' => ['Candi Borobudur dibangun pada masa Dinasti Syailendra sekitar abad ke-8 hingga ke-9 Masehi. Terletak di Magelang, Jawa Tengah, Borobudur merupakan candi Buddha terbesar di dunia.']],
                ],
                'quiz' => [
                    ['question' => 'Di mana Candi Borobudur terletak?', 'options' => ['Yogyakarta', 'Magelang, Jawa Tengah', 'Solo, Jawa Tengah', 'Semarang'], 'correct_index' => 1, 'explanation' => 'Candi Borobudur terletak di Magelang, Jawa Tengah.'],
                ]
            ],
            [
                'slug' => 'proklamasi-kemerdekaan',
                'title' => 'Proklamasi Kemerdekaan',
                'era_slug' => 'kemerdekaan',
                'year' => '17 Agustus 1945',
                'summary' => 'Detik-detik bersejarah proklamasi kemerdekaan Republik Indonesia oleh Soekarno-Hatta.',
                'hero_image' => 'https://images.unsplash.com/photo-1555848962-6e79363ec58f?q=80&w=1200',
                'video_id' => 'uP46wXW_2yM',
                'content' => [
                    ['heading' => 'Detik-Detik Proklamasi', 'paragraphs' => ['Pada hari Jumat, 17 Agustus 1945, pukul 10.00 WIB, Ir. Soekarno didampingi Drs. Mohammad Hatta membacakan teks Proklamasi Kemerdekaan Indonesia.']],
                ],
                'quiz' => [
                    ['question' => 'Kapan Proklamasi Kemerdekaan dibacakan?', 'options' => ['15 Agustus 1945', '16 Agustus 1945', '17 Agustus 1945', '18 Agustus 1945'], 'correct_index' => 2, 'explanation' => 'Proklamasi dibacakan pada 17 Agustus 1945 pukul 10.00 WIB.'],
                ]
            ],
        ];

        foreach ($articlesData as $art) {
            $article = Article::create([
                'era_id'       => $eraMap[$art['era_slug']]->id,
                'title'        => $art['title'],
                'slug'         => $art['slug'],
                'year'         => $art['year'],
                'summary'      => $art['summary'],
                'hero_image'   => $art['hero_image'],
                'is_published' => true,
                'view_count'   => rand(100, 5000),
            ]);

            foreach ($art['content'] as $i => $section) {
                ArticleSection::create([
                    'article_id' => $article->id,
                    'heading'    => $section['heading'],
                    'paragraphs' => $section['paragraphs'],
                    'sort_order' => $i,
                ]);
            }

            ArticleVideo::create([
                'article_id' => $article->id,
                'youtube_id' => $art['video_id'],
                'title'      => 'Video Edukasi: ' . $art['title'],
                'channel'    => 'Sejarah Indonesia',
                'sort_order' => 0,
            ]);

            $quiz = Quiz::create([
                'article_id' => $article->id,
                'title'      => 'Kuis ' . $art['title'],
            ]);

            foreach ($art['quiz'] as $i => $q) {
                QuizQuestion::create([
                    'quiz_id'       => $quiz->id,
                    'question'      => $q['question'],
                    'options'       => $q['options'],
                    'correct_index' => $q['correct_index'],
                    'explanation'   => $q['explanation'],
                    'sort_order'    => $i,
                ]);
            }

            // Create timeline events with images
            $timelineImages = [
                'kerajaan-kutai'         => ['url' => 'https://images.unsplash.com/photo-1596402184320-417e7178b2cd?w=600&q=70', 'caption' => 'Prasasti Yupa, Kalimantan Timur'],
                'kerajaan-sriwijaya'     => ['url' => 'https://images.unsplash.com/photo-1558618666-fcd25c85cd64?w=600&q=70', 'caption' => 'Armada Laut Sriwijaya'],
                'kerajaan-majapahit'     => ['url' => 'https://images.unsplash.com/photo-1555400038-63f5ba517a47?w=600&q=70', 'caption' => 'Candi Trowulan, Jawa Timur'],
                'kesultanan-demak'       => ['url' => 'https://images.unsplash.com/photo-1564415315949-7a0c4c73aab4?w=600&q=70', 'caption' => 'Masjid Agung Demak'],
                'borobudur'              => ['url' => 'https://images.unsplash.com/photo-1588668214407-6ea9a6d8c272?w=600&q=70', 'caption' => 'Candi Borobudur, Magelang'],
                'proklamasi-kemerdekaan' => ['url' => 'https://images.unsplash.com/photo-1530277453888-c78fb77a5e3d?w=600&q=70', 'caption' => 'Saat Proklamasi Kemerdekaan, 1945'],
            ];
            $imgData = $timelineImages[$article->slug] ?? null;
            $sortMap = ['kerajaan-kutai'=>1,'kerajaan-sriwijaya'=>2,'kerajaan-majapahit'=>3,'kesultanan-demak'=>4,'borobudur'=>5,'proklamasi-kemerdekaan'=>6];
            TimelineEvent::create([
                'era_id'        => $article->era_id,
                'year'          => explode(' ', $article->year)[0],
                'title'         => $article->title,
                'description'   => $article->summary,
                'article_slug'  => $article->slug,
                'image_url'     => $imgData['url'] ?? null,
                'image_caption' => $imgData['caption'] ?? null,
                'sort_order'    => $sortMap[$article->slug] ?? 99,
            ]);

            // Map Location — real coordinates per article slug
            $coordMap = [
                'kerajaan-kutai'         => ['lat' => 0.50,  'lng' => 117.05, 'name' => 'Situs Kerajaan Kutai'],
                'kerajaan-sriwijaya'     => ['lat' => -2.99, 'lng' => 104.76, 'name' => 'Situs Kerajaan Sriwijaya'],
                'kerajaan-majapahit'     => ['lat' => -7.56, 'lng' => 112.38, 'name' => 'Situs Kerajaan Majapahit'],
                'kesultanan-demak'       => ['lat' => -6.89, 'lng' => 110.61, 'name' => 'Situs Kesultanan Demak'],
                'borobudur'              => ['lat' => -7.61, 'lng' => 110.20, 'name' => 'Candi Borobudur'],
                'proklamasi-kemerdekaan' => ['lat' => -6.20, 'lng' => 106.82, 'name' => 'Situs Proklamasi Jakarta'],
            ];
            $coord = $coordMap[$article->slug] ?? [
                'lat'  => -2.5 + (($article->id ?? 0) % 10),
                'lng'  => 110.0 + (($article->id ?? 0) % 30),
                'name' => $article->title . ' Location',
            ];
            MapLocation::create([
                'era_id'      => $article->era_id,
                'article_id'  => $article->id,
                'name'        => $coord['name'],
                'latitude'    => $coord['lat'],
                'longitude'   => $coord['lng'],
                'year'        => $article->year,
                'description' => 'Situs bersejarah terkait ' . $article->title,
                'article_slug' => $article->slug,
            ]);
        }

        // 4. Topics
        Topic::create([
            'era_id'      => $eraMap['hindu-buddha']->id,
            'title'       => 'Arsitektur Candi',
            'description' => 'Mempelajari struktur candi-candi megah peninggalan masa kerajaan.',
            'icon_name'   => 'temple',
            'sort_order'  => 1,
        ]);
        Topic::create([
            'era_id'      => $eraMap['kesultanan']->id,
            'title'       => 'Penyebaran Islam',
            'description' => 'Mengenal peran Wali Songo dalam menyebarkan agama Islam di Nusantara.',
            'icon_name'   => 'users',
            'sort_order'  => 2,
        ]);
        Topic::create([
            'era_id'      => $eraMap['kolonial']->id,
            'title'       => 'Strategi Perang',
            'description' => 'Menganalisis berbagai taktik gerilya dan pertempuran melawan penjajah.',
            'icon_name'   => 'sword',
            'sort_order'  => 3,
        ]);
        Topic::create([
            'era_id'      => $eraMap['kemerdekaan']->id,
            'title'       => 'Diplomasi Bangsa',
            'description' => 'Perjuangan mempertahankan kemerdekaan melalui jalur perundingan internasional.',
            'icon_name'   => 'flag',
            'sort_order'  => 4,
        ]);

        // 5. Fun Facts
        $funFacts = [
            'Bendera Merah Putih terinspirasi dari panji-panji Kerajaan Majapahit.',
            'Candi Borobudur dibangun tanpa menggunakan perekat semen sama sekali.',
            'Sultan Hasanuddin dijuluki sebagai Ayam Jantan dari Timur oleh penjajah Belanda.',
            'Teks Proklamasi Indonesia dirumuskan di rumah seorang perwira tinggi angkatan laut Jepang.',
            'Indonesia pernah memiliki mata uang bernama ORI (Oeang Republik Indonesia) di awal kemerdekaan.',
        ];

        foreach ($funFacts as $text) {
            FunFact::create([
                'text'      => $text,
                'is_active' => true,
            ]);
        }
    }
}
