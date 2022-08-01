<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ajax extends Model
{
    use HasFactory;

    public static function message($message, $success = false) {
        return response()->json(['message' => $message]);
    }

    public static function data($diagnoses, $questions, $answers) {
        return response()->json(['diagnoses' => $diagnoses, 'questions' => $questions, 'answers' => $answers,]);
    }

    public static function dd($obj) {
        return response()->json(['dd' => $obj]);
    }

    public static function redirect($url) {
        return response()->json(['url' => $url]);
    }
}
