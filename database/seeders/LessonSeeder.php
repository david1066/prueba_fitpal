<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Lesson;
class LessonSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $lesson = new Lesson();
        $lesson->lesson_name = 'Yoga';
        $lesson->date_start = '2022-06-21';
        $lesson->date_end = '2022-08-21';
        $lesson->save();


        $lesson = new Lesson();
        $lesson->lesson_name = 'Gimnasia';
        $lesson->date_start = '2022-06-28';
        $lesson->date_end = '2022-07-20';
        $lesson->save();

        $lesson = new Lesson();
        $lesson->lesson_name = 'Zamba';
        $lesson->date_start = '2022-06-19';
        $lesson->date_end = '2022-07-20';
        $lesson->save();
    }
    
}
