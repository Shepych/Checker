<?php

namespace App\Http\Controllers;

use App\Models\Ajax;
use App\Models\Diagnos;
use App\Models\Main;
use App\Models\Question;
use App\Models\Section;
use App\Models\Subsection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use Throwable;

class MainController extends Controller
{
    # Рендер главной страницы приложения
    public function index() {
        $sections = Section::all();
        $subsections = Subsection::all();
        return view('welcome', compact('sections', 'subsections'));
    }

    # Выбор раздела
    public function section(Request $request, $id) {
        $sex = $request->input('sex');
        if($id == '*') {
            # Обнулить пол (вывести все и для М и для Ж)
            $subsectionsList = Subsection::all();
        } else {
            # выбираем все разделы и конвертируем json
            $subsections = Subsection::all();
            $subsectionsList = [];
            foreach ($subsections as $sub) {
                try {
                    foreach (json_decode($sub->sections) as $section) {
                        # Если в JSON найден id секции то добавляем подраздел в массив для вывода
                        if($section == $id && (($sub->sex == $sex) || !isset($sub->sex))) {
                            $subsectionsList[] = $sub;
                        }
                    }
                } catch (Throwable $e) {

                }
            }
        }

        return $subsectionsList;
    }

    # Выбор подраздела
    public function subsection($id) {
        $section = Subsection::where('id', $id)->first();
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
                    'priority' => $answer->question()->priority,
                    ];
            }

            $diag['answers'] = $answersArray;
            $diagnoses[] = $diag;
        }

        # Сортировка массива вопросов по приоритету
        $questions = array_unique($questions, SORT_REGULAR);
//        usort($questions, function($a, $b){
//            return ($a['priority'] - $b['priority']);
//        });
        return [
            'diagnoses' => $diagnoses,
            'questions' => $questions,
            'answers' => $answers,
        ];
    }
}
