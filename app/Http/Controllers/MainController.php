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
    public function index() {
        $sections = Section::all();
        $subsections = Subsection::all();
        return view('welcome', compact('sections', 'subsections'));
    }

    # Выбор подразделов
    public function section(Request $request, $id) {
        # выбираем все разделы и конвертируем json
        $subsections = Subsection::all();
        $subsectionsList = [];
        foreach ($subsections as $sub) {
            try {
                foreach (json_decode($sub->sections) as $section) {
                    # Если в JSON найден id секции то добавляем подраздел в массив для вывода
                    if($section == $id && (($sub->sex == $request->input('sex')) || !isset($sub->sex))) {
                        $subsectionsList[] = $sub;
                    }
                }
            } catch (Throwable $e) {

            }
        }

        return $subsectionsList;
    }

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
        usort($questions, function($a, $b){
            return ($a['priority'] - $b['priority']);
        });
        return [
            'diagnoses' => $diagnoses,
            'questions' => array_reverse($questions),
            'answers' => $answers,
        ];
    }

    public function vue() {
        return view('vue');
    }
}
