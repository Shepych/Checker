<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Main extends Model
{
    use HasFactory;

    public static $error;

    public static function apiData($id) {
        $section = Subsection::where('id', $id)->first();

        if (!$section) {
            self::$error = ['error' => 'Некорректные данные'];
            return false;
        }

        # Диагнозы
        $diagnoses = [];
        # Вопросы
        $questions = [];
        # Ответы
        $answers = [];
        foreach ($section->diagnoses() as $diag) {
            $answersArray = [];
            foreach ($diag->answers() as $answer) {
                $answersArray[] = $answer->id;
                $answers[] = $answer;
                $questions[] = [
                    'id' => $answer->question()->id,
                    'title' => $answer->question()->title,
                ];
            }

            $diag['answers'] = $answersArray;
            $diagnoses[] = $diag;
        }

        # Сортировка массива вопросов по приоритету
        $questions = array_unique($questions, SORT_REGULAR);

        return [
            'diagnoses' => $diagnoses,
            'questions' => $questions,
            'answers' => $answers,
        ];
    }

    # Проверка на получение диагноза исходя из результатов опроса
    public static function checkDiagnos($subsection_id, $results) {
        $data = self::apiData($subsection_id);

        foreach ($data['diagnoses'] as $diagnos) {
            $count = 0;

            foreach ($diagnos->answers as $answer) {
                foreach ($results as $result) {
                    if ($answer == $result) {
                        $count++;
                    }
                }
            }

            if ($count == count($diagnos->answers)) {
                return [
                    'title' => $diagnos->title,
                    'description' => $diagnos->description,
                ];
            }
        }

        return false;
    }
}
