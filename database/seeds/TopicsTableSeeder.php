<?php

use App\Models\Topic;
use Illuminate\Database\Seeder;

class TopicsTableSeeder extends Seeder
{
    public function run()
    {
        $topics = factory(Topic::class)->times(50)->make()->each(function ($topic, $index) {
            if ($index == 0) {
                // $topic->field = 'value';
            }
        });

        Topic::insert($topics->toArray());
    }

}

