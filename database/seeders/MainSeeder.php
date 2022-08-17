<?php

namespace Database\Seeders;

use App\Models\Answer;
use App\Models\Diagnos;
use App\Models\Question;
use App\Models\Section;
use App\Models\Subsection;
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
        Subsection::insert([
            ['title' => 'Боль в горле'],
            ['title' => 'Боль в груди'],
            ['title' => 'Боль в животе'],
            ['title' => 'Боль в заднем проходе'],
            ['title' => 'Боль в ноге'],
            ['title' => 'Боль в области лица'],
            ['title' => 'Боль в пояснице'],
            ['title' => 'Боль в руке'],
            ['title' => 'Боль в ухе'],
            ['title' => 'Боль в шее'],
            ['title' => 'Боль при мочеиспускании'],
            ['title' => 'Высокая температура'],
            ['title' => 'Высыпания на коже'],
            ['title' => 'Головная боль'],
            ['title' => 'Изменение кала'],
            ['title' => 'Изменение лимфатических узлов'],
            ['title' => 'Изменения мочи'],
            ['title' => 'Кашель'],
            ['title' => 'Кровотечения'],
            ['title' => 'Нарушение мозгового кровообращения'],
            ['title' => 'Нарушения зрения и болезни глаз'],
            ['title' => 'Нарушения кровообращения'],
            ['title' => 'Нарушения менструального цикла'],
            ['title' => 'Нарушения пищеварения'],
            ['title' => 'Одышка'],
            ['title' => 'Остановка дыхания (апноэ)'],
            ['title' => 'Психические расстройства'],
            ['title' => 'Судороги'],
        ]);

        # Разделы
        Section::insert([
            ['title' => 'Голова'],
            ['title' => 'Шея'],
            ['title' => 'Руки'],
            ['title' => 'Грудь'],
            ['title' => 'Живот'],
            ['title' => 'Пах'],
            ['title' => 'Ноги'],
        ]);

        # Диагнозы
        Diagnos::insert([
            ['title' => 'Невроз', 'description' => 'Описание 3', 'subsection_id' => 27],
            ['title' => 'Энурез', 'description' => 'Описание 1', 'subsection_id' => 11],
            ['title' => 'Варикоз', 'description' => 'Описание 2', 'subsection_id' => 5],
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
