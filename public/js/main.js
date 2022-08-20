let answers;
let diagnoses;
let questions;

// Массив отката: вопросов, диагнозов и вопросов
let rollback = new Array();
// Массив отката: Результатов
let rollbackResults = new Array();

// Массив с ID ответов
let result = new Array();

let j = 0;
// Хук ответа
function hook($question_id, $answer, object) {
    // Меняем цвет кнопок
    $(object).parent().children('input[type=submit]').removeClass('btn-primary');
    $(object).parent().children('input[type=submit]').addClass('btn-secondary');
    $(object).removeClass('btn-secondary');
    $(object).addClass('btn-primary');


    if ($(object).attr('data-rollback')) {
        questions = Array.from(Object.values(rollback[$(object).parent().parent().attr('data-rollback-id')]));
        document.querySelectorAll('#question_' + $question_id + ' ~ *').forEach(n => n.remove());

        // Обрезаем массивы
        let trash = {}; // новый пустой объект
        for (let key in rollback) {
            trash[key] = rollback[key];
            if(key == (parseInt($(object).parent().parent().attr('data-rollback-id')))) {
                break;
            }
        }
        rollback = Array.from(Object.values(trash));

        let clown = {}; // новый пустой объект
        for (let key in rollbackResults) {
            clown[key] = rollbackResults[key];
            if(key == (parseInt($(object).parent().parent().attr('data-rollback-id')))) {
                break;
            }
        }

        // Клон переменной чтобы снять передачу по ссылке
        let rollbackResultsClone = {}
        let test = rollbackResults[$(object).parent().parent().attr('data-rollback-id')];
        for (let key in test) {
            rollbackResultsClone[key] = test[key];
        }

        // Результаты - 1 и добавляем в массив ответ + в роллбэк
        result = Array.from(Object.values(rollbackResultsClone));
        rollbackResults = Array.from(Object.values(clown));
    }

    if($(object).attr('data-rollback') !== '1') {

        let clone = {}; // новый пустой объект
        for (let key in questions) {
            clone[key] = questions[key];
        }
        rollback.push(Array.from(Object.values(clone)));

        let cloneResult = []; // новый пустой объект
        for (let key in result) {
            cloneResult[key] = result[key];
        }
        rollbackResults.push(cloneResult);
    }

    answers.forEach(function(elem) {
        // if (elem['answer'] === $answer && elem['question_id'] === $question_id && !result.includes(elem['id'])) {
        if (elem['answer'] === $answer && elem['question_id'] === $question_id && !result.includes(elem['id'])) {
            result.push(elem['id']);
        }
    });

    console.log(result);

    // Добавляем атрибут для отката
    $('#question_' + $question_id + " input[type=submit]").attr('data-rollback', 1);

    // Проверка на вопросы и диагнозы к ним (удаление)
    answersFilter(questions[0], $answer);

    // Определяем диагноз
    let diag = false;
    diagnoses.forEach(function(elem) {
        if(arraysEqual(elem['answers'], result)) {
            diagnosis(elem['title']);
            diag = true;
            return false;
        }
    });

    // Хук перехода к следующему вопросу
    if(!diag) {
        // Если массив с вопросами не пустой - то выводим следующий
        if(questions.length) {
            next(Array.from(Object.values(questions)));
        } else {
            // Если диагноз не определён то выводим результат
            noDiagnosis();
        }
    }

    // console.log(rollback);
}

// Проверка равенства значений в массивах
function arraysEqual(a, b) {
    if(a.sort().toString() == b.sort().toString()) {
        return true;
    }

    return false;
}

// Вывод диагноза
function diagnosis(title) {
    $('#diagnos').empty();
    $('#diagnos').append('<div>\n' +
        '                <h2 class="mt-5">' + title + '</h2>\n' +
        '            </div>');
}

// Отсутсвие диагноза
function noDiagnosis() {
    $('#diagnos').empty();
    $('#diagnos').append('<div>\n' +
        '                <h2 class="mt-5">Диагноз не определён</h2>\n' +
        '            </div>');
}

// Вопрос
function ask(res) {
    // Нужно для обращения к rollback массиву по ID
    let len = $('.question').length;

    $('#questions').append('<div class="question" data-rollback-id="' + len + '" id="question_' + res['id'] +'">\n' +
        '                <h4>ID:' + res['id'] + ' ' + res['title'] +'</h4>\n' +
        '                <div>\n' +
        '                    <input onclick="hook(' + res['id'] +', 1, this)" class="btn-secondary btn" type="submit" name="yes" value="Да">\n' +
        '                    <input onclick="hook(' + res['id'] +', 0, this)" class="btn-secondary btn" type="submit" name="no" value="Нет">\n' +
        '                </div>\n' +
        '            </div>');

}

// Хук отката (ответ да или нет когда есть несколько несколько последующих вопросов с ответами)
function next(result) {
    $('#diagnos').empty();

    for(let i = 0; i < Array.from(Object.values(result)).length; i++) {
        ask(Array.from(Object.values(result))[i]);
        return false;
    }
}

// Клик по телу
function sectionPoint(obj) {
  $('.point').removeClass('pulse');
  $(obj).addClass('pulse');
    $.ajax({
        type: 'POST',
        url: '/section/' + $(obj).attr('data-section'),
        data: {"sex": $('#sex').attr('data-sex')},
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function (result) {
            console.log(result);
            $('#subsections').empty();

            // Обновить подразделы
            result.forEach(function(elem) {
                $('#subsections').append('<div data-subsection="' + elem['id'] +'" onclick="subsection(this)" style="background-color: white" class="btn m-1">' + elem['title'] + '</div>');
            });
        }
    });
}

// Выбор подраздела
function subsection(obj) {
    // Аякс получение всех подразделов по JSON id'шникам
    $.ajax({
        type: 'POST',
        url: '/subsection/' + $(obj).attr('data-subsection'),
        contentType: false,
        cache: false,
        processData: false,
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function (res) {
            // console.log(res['questions']);
            answers = res['answers'];
            diagnoses = res['diagnoses'];
            questions = res['questions'];

            rollback = new Array();
            rollbackResults = new Array();
            result = new Array();

            $('#diagnos').empty();
            $('#questions').empty();
            next(questions);

            // Меняем цвет кнопок
            $('#subsections div').removeClass('bg-warning');
            $(obj).addClass('bg-warning');
            // console.log(answers);
            // console.log(diagnoses);
            // console.log(questions);
        }
    });

    // Сменить заголовок
    $('#header').text($(obj).attr('data-section-name'));
}

// Выбор пола
function sexSelect(obj) {
    // Меняем цвет кнопок
    $(obj).parent().children('button').removeClass('bg-warning');
    $(obj).parent().children('button').removeAttr('id');
    $(obj).removeAttr('id');
    $(obj).addClass('bg-warning');
    $(obj).attr('id', 'sex')

    // Очистить раздел
    $('#subsections').empty();

    $.ajax({
        type: 'POST',
        url: '/section/' + 1,
        data: {"sex": $('#sex').attr('data-sex')},
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function (result) {
            // alert($(obj).attr('data-sex'));
            console.log(result);
            $('#subsections').empty();

            // Обновить подразделы
            result.forEach(function(elem) {
                $('#subsections').append('<div data-subsection="' + elem['id'] +'" onclick="subsection(this)" style="background-color: white" class="btn m-1">' + elem['title'] + '</div>');
            });
        }
    });

    // Обновить подразделы
    result.forEach(function(elem) {
        $('#subsections').append('<div data-subsection="' + elem['id'] +'" onclick="subsection(this)" style="background-color: white" class="btn m-1">' + elem['title'] + '</div>');
    });
}

// Фильтрация вопросов
function answersFilter(quest, $answer) {
    let findAnswers = new Array();
    let deleteQuestions = new Array();

    for(let i = 0; i < answers.length; i++) {
        if(quest['id'] === answers[i]['question_id']) {
            // То добавляем в массив ответ
            findAnswers.push(answers[i]);
            for(let l = 0; l < diagnoses.length; l++) {
                for(let h = 0; h < diagnoses[l]['answers'].length; h++) {
                    if(diagnoses[l]['answers'][h] === answers[i]['id'] && $answer === 0) {



                        // for(let diag = 0; diag < diagnoses.length; diag++) {
                        //
                        // }
                        // То мы должны удалить ВСЕ вопросы у answers этого диагноза
                        // Вот здесь добавить цикл
                        // Запускаем ещё раз цикл по ответам диагноза
                        for(let u = 0; u < diagnoses[l]['answers'].length; u++) {
                            // ID ответа
                            // Пройти циклом по всем диагнозам и их ответам

                            // for(let diag = 0; diag < diagnoses.length; diag++) {
                            //     for(let ans = 0; ans < diagnoses[diag]['answers'].length; ans++) {
                            //         if(diagnoses[diag]['answers'][ans] === diagnoses[l]['answers'][u]) {
                            //             deleteQuestions.push(diagnoses[diag]['answers'][ans]);
                            //         }
                            //     }
                            // }

                            deleteQuestions.push(diagnoses[l]['answers'][u]);
                        }
                    }
                }
            }
        }
    }

    // console.log(questions);
    let test = new Array();
    // console.log(deleteQuestions);

    // Удаляем вопросы
    for(let i = 0; i < answers.length; i++) {
        for(let j = 0; j < deleteQuestions.length; j++) {
            if(answers[i]['id'] === deleteQuestions[j]) {
                // Удаляем из questions
                for(let k = 0; k < questions.length; k++) {
                    // console.log(questions[k]['id']);
                    if(questions[k]['id'] === answers[i]['question_id']) {
                        // console.log(questions[k]['id'] + " answer: " + answers[i]['question_id']);
                        test.push(questions[k]['id']);
                    }
                }
            }
        }
    }


    for(let i = 0; i < answers.length; i++) {
        if(answers[i]['question_id'] == quest['id'] && (answers[i]['answer']) ) {

        }
    }

    // Если кнопка ДА - то просто удалить 0 элемент вопроса
    if($answer === 0) {
        for(let i = 0; i < test.length; i++) {
            for(let j = 0; j < questions.length; j++) {
                if(questions[j]['id'] === test[i]) {
                    delete questions[j];
                    questions = Array.from(Object.values(questions));
                }
            }
        }
    } else {
        delete questions[0];
        questions = Array.from(Object.values(questions));
    }
}
