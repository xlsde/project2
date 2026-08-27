<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategoryTreeSeeder extends Seeder
{
    public function run(): void
    {
        // [slug, name, image, [children: slug,name,image]]
        $tree = [
            ['sanat', 'Sanat', 'paint1', [
                ['tablo', 'Tablo & Resim', 'paint2'],
                ['portre', 'Portre', 'portre1'],
                ['heykel', 'Heykel', 'heykel1'],
                ['baski', 'Baskı & Gravür', 'paint3'],
            ]],
            ['antika', 'Antika', 'mobilya1', [
                ['mobilya', 'Mobilya', 'mobilya2'],
                ['porselen-seramik', 'Porselen & Seramik', 'porselen1'],
                ['saat', 'Saat', 'saat1'],
                ['kitap-harita', 'Kitap & Harita', 'kitap1'],
            ]],
            ['mucevherat', 'Mücevherat', 'muc1', [
                ['yuzuk', 'Yüzük', 'muc2'],
                ['kolye', 'Kolye', 'muc3'],
                ['bros', 'Broş & Küpe', 'muc1'],
            ]],
            ['elektronik', 'Elektronik', 'kamera1', [
                ['fotograf-makinesi', 'Fotoğraf Makinesi', 'kamera2'],
                ['plak-ses', 'Plak & Ses Sistemleri', 'plak1'],
                ['retro-bilgisayar', 'Retro Bilgisayar', 'kamera3'],
            ]],
            ['koleksiyon', 'Koleksiyon', 'kol1', [
                ['pul', 'Pul', 'kol2'],
                ['para', 'Para & Madeni', 'kol3'],
                ['model', 'Model & Maket', 'plak2'],
            ]],
        ];

        $rootOrder = 0;
        foreach ($tree as [$slug, $name, $img, $children]) {
            $root = Category::updateOrCreate(
                ['slug' => $slug],
                ['name' => $name, 'parent_id' => null, 'image' => "catalog/{$img}.jpg", 'is_active' => true, 'sort_order' => $rootOrder++]
            );

            $childOrder = 0;
            foreach ($children as [$cslug, $cname, $cimg]) {
                Category::updateOrCreate(
                    ['slug' => $cslug],
                    ['name' => $cname, 'parent_id' => $root->id, 'image' => "catalog/{$cimg}.jpg", 'is_active' => true, 'sort_order' => $childOrder++]
                );
            }
        }
    }
}
