<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Main extends Model
{
    use HasFactory;

    public static function check($answer, $request) {
        # Вызываем метод ДА или НЕТ
        switch ($answer) {
            case TRUE:
                $result = self::answerTrue();
                break;
            case FALSE:
                $result = self::answerFalse();
                break;
        }

        # Если диагноз определяется - то выводим его
//        if() {
//            return 'Диагноз';
//        }

        # Если нет - то формируем новый массив
        return 'Массив';
    }

    protected static function answerTrue() {
        return true;
    }

    protected static function answerFalse() {
        return false;
    }
}
