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
        $section = Subsection::where('id', 1)->first();
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
        return Main::apiData($id);
    }

    # API Первичное получение данных
    public function apiIndex() {
        $section = Subsection::where('id', 1)->first();
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

        $sections = Section::all();
        $subsections = Subsection::all();
        return compact('sections', 'subsections');
    }

    # API Получения раздела
    public function apiSection(Request $request, $id) {
        $sex = $request->sex;
        if(!$sex) {
            $sex = 'w';
        }
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

    # API Получения подраздела
    public function apiSubsection($id) {
        $data = Main::apiData($id);
        if (!$data) {
            return Main::$error;
        }

        return reset($data['questions']);
    }

    # API Ответа на вопрос
    public function apiAnswer(Request $request) {
        if(!isset($request->questions) || !isset($request->subsection)) {
            return ['error' => 'Некорректные данные'];
        }

        # Получить массив результатов
        $results = json_decode($request->questions, true);

        $data = Main::apiData((int)$request->subsection);
        if (!$data) {
            return Main::$error;
        }

        $questions = $data['questions'];
        $userAnswers = Array();

        # Получаем массив с id ответов пользователя
        foreach ($data['answers'] as $answer) {
            foreach ($results as $key => $res) {
                if ($answer->question_id == $key && $answer->answer == $res) {
                    $userAnswers[] = $answer->id;
                }
            }
        }

        $deleting = Array();
        # Пускаем цикл по диагнозам
        foreach ($data['diagnoses'] as $key => $diagnoses) {
            $deleteAnswers = Array();
            $deleteQuestions = Array();
            $opposite = 0;

            foreach ($diagnoses->answers as $delDiagnoses) {
                foreach ($data['answers'] as $answer) {
                    if ($delDiagnoses == $answer->id) {
                        foreach ($userAnswers as $userAnswer) {
                            foreach ($data['answers'] as $answer2) {
                                if ($userAnswer == $answer2->id) {
                                    if ($answer2->question_id == $answer->question_id && ($answer->answer != $answer2->answer)) {
                                        $opposite++;
                                    }
                                }
                            }
                        }
                    }
                }
            }

            if ($opposite > 0) {

            } else {
                foreach ($diagnoses->answers as $delDiagnoses) {
                    $count = 0;

                    foreach ($userAnswers as $userAnswer) {
                        if ($userAnswer == $delDiagnoses) {
                            $count++;
                        }
                    }

                    if ($count == 0) {
                        $deleteAnswers[] = $delDiagnoses;
                    }
                }

                // Получаем ID вопросов для удаления из массива
                foreach ($deleteAnswers as $del) {
                    foreach ($data['answers'] as $delAnswer) {
                        if($delAnswer->id == $del) {
                            $deleteQuestions[] = $delAnswer->question_id;
                        }
                    }
                }

                $questCount = 0;
                // Цикл по вопросам
                foreach ($deleteQuestions as $kek) {
                    foreach ($questions as $ques) {
                        // Добавить проверку на противоположный ответ
                        if($kek == $ques['id']) {
                            $questCount++;
                        }
                    }
                }

                // Если все вопросы найдены в массиве - то мы их удаляем из массива
                // Удаления
                if($questCount == count($deleteQuestions)) {
                    // Удалить из массива TEST -> deleteQuestions
                    // Добавить в массив для удаления
                    foreach ($deleteQuestions as $del) {
                        $deleting[] = $del;
                    }
                }
            }
        }


        # Проверка на диагноз
        $diagnos = Main::checkDiagnos($request->subsection, $userAnswers);

        if ($diagnos) {
            return ['diagnoses' => $diagnos];
        }

        ######################################################
        ######################################################
        ############ Удаление вопросов из массива ############
        ######################################################
        ######################################################

        # Удаление вопросов на которые уже ответили
        foreach ($questions as $keyQuestion => $quest) {
            foreach ($results as $keyResult => $res) {
                if ($quest['id'] == $keyResult) {
                    unset($questions[$keyQuestion]);
                }
            }
        }

        $newQuestions = $questions;
        # Удаление вопросов
        foreach ($questions as $keyQuestion => $quest) {
            $foundCount = 0;
            foreach ($deleting as $res) {
                if ($quest['id'] == $res) {
                    $foundCount++;
                }
            }

            # Если вопрос не найден - то удаляем его из массива
            if($foundCount == 0) {
                unset($newQuestions[$keyQuestion]);
            }
        }

        return reset($newQuestions);
    }
}
