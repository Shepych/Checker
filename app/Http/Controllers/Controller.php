<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use OpenApi\Annotations as OA;

/**
 * @OA\Info(title="Симптомчеккер API", version="1.0"),
 * * @OA\Get(
 *     path="/api/v1/index",
 *     description="Первичное отображение данных на странице (разделы и подразделы)",
 *     @OA\Response(response="200", description="The data"),
 * ),
 *    @OA\Get(
 *     path="/api/v1/section",
 *     description="Получение подразделов относящихся к выбранной части тела",
 *     @OA\Response(response="200", description="The data"),
 *     @OA\Response(response="404", description="error"),
 *     @OA\Parameter(
 *     name="id",
 *     in="query",
 *     description="ID раздела (части тела). Так же принимает значение * (звёздочка) - которое указывает на получение ВСЕХ подразделов",
 * ),
 *  *     @OA\Parameter(
 *     name="sex",
 *     in="query",
 *     description="Выбор пола.Необзательный параметр принимает значения ""w"", ""m"" (если оставить пустым - то будут выведены записи и женские и мужские подразделы)",
 * )
 * )
 * * @OA\Get(
 *     path="/api/v1/subsection",
 *     description="Получение первого вопроса относящегося к выбранному подразделу",
 *     @OA\Response(response="200", description="The data"),
 *     @OA\Parameter(
 *     name="id",
 *     in="query",
 *     description="ID подраздела",
 * )),
 * * @OA\Get(
 *     path="/api/v1/answer",
 *     description="Получение диагноза на основании результатов к ответам, либо если диагноз не удалось установить - то происходит выдача следующего вопроса. Данный API провоцируется нажатием на ответы «ДА» или «НЕТ» к вопросам",
 *     @OA\Response(response="200", description="The data"),
 *     @OA\Parameter(
 *     name="subsection",
 *     in="query",
 *     description="Введите ID подраздела",
 * ),
 *      @OA\Parameter(
 *     name="questions",
 *     in="query",
 *     description="Отправьте массив JSON вида: {""11"":0, ""12"":0, ""13"":1}, где первое значение элементов массива в кавычках - это ID вопроса, а второе значение (0 или 1) - ответ НЕТ или ДА на вопрос",
 * )
 * ),
 */

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
}
