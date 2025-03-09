<?php
namespace Database\Seeders;

use App\Models\Page;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PagesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $date = Carbon::now();

        $backups = Page::get();

        DB::table('pages')->truncate();

        DB::table('pages')->insert([
            [
                'title' => json_encode([
                    'pt' => 'Animais de Rua',
                    'en' => 'Animais de Rua',
                ]),
                'template' => 'home',
                'name' => 'Home',
                'slug' => 'home',
                'extras' => '',
                'created_at' => $date,
                'updated_at' => $date,
            ],
            [
                'title' => json_encode([
                    'pt' => 'Animais',
                    'en' => 'Animals',
                ]),
                'template' => 'animals',
                'name' => 'Animais',
                'extras' => '',
                'slug' => 'animals',
                'created_at' => $date,
                'updated_at' => $date,
            ],
            [
                'title' => json_encode([
                    'pt' => 'Associação',
                    'en' => 'Association',
                ]),
                'template' => 'association',
                'name' => 'Associação',
                'extras' => '',
                'slug' => 'association',
                'created_at' => $date,
                'updated_at' => $date,
            ],
            [
                'title' => json_encode([
                    'pt' => 'CED',
                    'en' => 'CED',
                ]),
                'template' => 'ced',
                'name' => 'CED',
                'extras' => '',
                'slug' => 'ced',
                'created_at' => $date,
                'updated_at' => $date,
            ],
            [
                'title' => json_encode([
                    'pt' => 'Amigos',
                    'en' => 'Friends',
                ]),
                'template' => 'friends',
                'name' => 'Amigos',
                'extras' => '',
                'slug' => 'friends',
                'created_at' => $date,
                'updated_at' => $date,
            ],
            [
                'title' => json_encode([
                    'pt' => 'Ajuda',
                    'en' => 'Help',
                ]),
                'template' => 'help',
                'name' => 'Ajuda',
                'extras' => '',
                'slug' => 'help',
                'created_at' => $date,
                'updated_at' => $date,
            ],
            [
                'title' => json_encode([
                    'pt' => 'Parceiros',
                    'en' => 'Partners',
                ]),
                'template' => 'partners',
                'name' => 'Parceiros',
                'extras' => '',
                'slug' => 'partners',
                'created_at' => $date,
                'updated_at' => $date,
            ],
            [
                'title' => json_encode([
                    'pt' => 'Petsitting',
                    'en' => 'Petsitting',
                ]),
                'template' => 'petsitting',
                'name' => 'Petsitting',
                'extras' => '',
                'slug' => 'petsitting',
                'created_at' => $date,
                'updated_at' => $date,
            ],
            [
                'title' => json_encode([
                    'pt' => 'Política de privacidade',
                    'en' => 'Privacy policy',
                ]),
                'template' => 'privacypolicy',
                'name' => 'Política de privacidade',
                'extras' => '',
                'slug' => 'privacy-policy',
                'created_at' => $date,
                'updated_at' => $date,
            ],
            [
                'title' => json_encode([
                    'pt' => 'Donativo',
                    'en' => 'Donation',
                ]),
                'template' => 'donation',
                'name' => 'Donativo',
                'extras' => '',
                'slug' => 'donation',
                'created_at' => $date,
                'updated_at' => $date,
            ],
        ]);

        // Apply backup
        foreach ($backups as $backup) {
            $page = Page::find($backup->id);

            if ($page) {
                $page->extras = $backup->extras;
                $page->extras_translatable = json_decode($backup->extras_translatable);
                $page->save();
            }
        }
    }
}
