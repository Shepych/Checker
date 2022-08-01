<?php

namespace Database\Seeders;

use App\Models\Answer;
use App\Models\Diagnos;
use App\Models\Question;
use App\Models\Section;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MainSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        # Разделы
        Section::insert([
            ['title' => 'Голова'],
            ['title' => 'Руки'],
            ['title' => 'Ноги'],
        ]);

        # Диагнозы
        Diagnos::insert([
            ['title' => 'Невроз', 'description' => 'Описание 3', 'section_id' => 1],
            ['title' => 'Энурез', 'description' => 'Описание 1', 'section_id' => 3],
            ['title' => 'Варикоз', 'description' => 'Описание 2', 'section_id' => 3],
        ]);

        # Вопросы
        Question::insert([
            ['title' => 'Писаетесь по ночам ?'],
            ['title' => 'Недосыпаете ?'],
            ['title' => 'Вздутые вены на ногах ?'],
        ]);

        # Ответы
        Answer::insert([
            ['answer' => TRUE, 'diagnosis_id' => 2, 'question_id' => 1],
            ['answer' => TRUE, 'diagnosis_id' => 3, 'question_id' => 3],
        ]);
    }
}
