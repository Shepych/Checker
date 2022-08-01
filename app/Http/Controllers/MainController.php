<?php

namespace App\Http\Controllers;

use App\Models\Ajax;
use App\Models\Diagnos;
use App\Models\Main;
use App\Models\Question;
use App\Models\Section;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;

class MainController extends Controller
{
    public function index() {
        $sections = Section::all();

        return view('welcome', compact('sections'));
    }

    public function check(Request $request) {
        if($request->input('no')) {

        }
    }

    public function section($id) {
        $section = Section::where('id', $id)->first();

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
                $questions[] = ['id' => $answer->question()->id, 'title' => $answer->question()->title,];
            }

            $diag['answers'] = $answersArray;
            $diagnoses[] = $diag;
        }

        return Ajax::data($diagnoses, array_unique($questions, SORT_REGULAR), $answers);
    }
}
