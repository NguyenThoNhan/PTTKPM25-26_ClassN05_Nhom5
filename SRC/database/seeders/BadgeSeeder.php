<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Badge;

class BadgeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Badge::firstOrCreate(['name' => 'Tân Binh'], [
            'description' => 'Mượn cuốn sách đầu tiên của bạn.',
            'icon_path' => 'badges/rookie.svg',
        ]);

        Badge::firstOrCreate(['name' => 'Độc Giả Chăm Chỉ'], [
            'description' => 'Mượn thành công 5 cuốn sách.',
            'icon_path' => 'badges/avid-reader.svg',
        ]);
    }
}
