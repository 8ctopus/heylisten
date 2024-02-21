<?php

namespace Database\Seeders;

use App\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Category::create([
            'title' => json_decode(
                '{"de": "Geplant", "en": "Planned", "fr": "Planifié", "ja": "開発計画", "ru": "Запланировано"}',
                true
            ),
            'color' => 'orange',
            'icon' => 'tasks'
        ]);

        Category::create([
            'title' => json_decode(
                '{"de": "Implementiert", "en": "Done", "fr": "Implémenté", "ja": "完成", "ru": "Выполнено"}',
                true
            ),
            'color' => 'green',
            'icon' => 'trophy'
        ]);

        Category::create([
            'title' => json_decode(
                '{"de": "Im Laufen", "en": "Work in progress", "fr": "En cours", "ja": "開発中", "ru": "В работе"}',
                true
            ),
            'color' => 'yellowgreen',
            'icon' => 'wrench'
        ]);

        Category::create([
            'title' => json_decode(
                '{"de": "Wahrscheinlich", "en": "Most likely to be implemented", "fr": "Favori", "ja": "ホット", "ru": "Нам нравится"}',
                true
            ),
            'color' => 'red',
            'icon' => 'fire'
        ]);
    }
}
