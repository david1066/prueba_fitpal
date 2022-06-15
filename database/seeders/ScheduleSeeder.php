<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Schedule;
use App\Models\Lesson;

class ScheduleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $lessons= Lesson::all();
        $hora=8;
        foreach($lessons as $lesson){

            for($i=1; $i<=6;$i++){
                $shedule = new Schedule();
                $shedule->weekday = $i;
                $shedule->lesson_id = $lesson->id;
                $shedule->time_start = (strlen($hora)==1)?'0'.$hora.':00': $hora.':00' ;
                $shedule->time_end =  (strlen(($hora+2))==1)?'0'.($hora+2).':00': ($hora+2).':00' ;
                $shedule->save();
            }
            $hora++;
        }
            
           


    
    }
}
