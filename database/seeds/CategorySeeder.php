<?php

use App\Helpers\Image;
use App\Models\Category;
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
        $categories = [
            'Laravel' => 'F35144',
            'Node.Js' => '8DBC58',
            'VueJs' => '41B881',
            'React' => '0CC1E9',
            'Deno' => '0098B6',
            'Amplify' => 'FF9733'
        ];
        foreach($categories as $category => $bg){
            factory(Category::class)->create([
                'name' => $category,
                'picture' => Image::image(storage_path('app/public/categories'), $category, $bg, 850, 350, false)
            ]);
        }
    }
}
