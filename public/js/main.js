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

    // console.log(result);

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
}

// Проверка равенства значений в массивах
function arraysEqual(a, b) {

    let count = 0;
    // Запустить цикл по ответам на диагнозы
    for (let i = 0; i < a.length; i++) {
        for (let j = 0; j < b.length; j++) {
            if(a[i] === b[j]) {
                count++;
            }
        }

        if(count === a.length) {
            return true;
        }
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
    // let findAnswers = new Array();
    let deleteQuestions = new Array();

    // console.log(result);

    for(let i = 0; i < answers.length; i++) {
        if(quest['id'] === answers[i]['question_id']) {
            // То добавляем в массив ответ
            // findAnswers.push(answers[i]);
            for(let l = 0; l < diagnoses.length; l++) {
                for(let h = 0; h < diagnoses[l]['answers'].length; h++) {
                    if(diagnoses[l]['answers'][h] === answers[i]['id']) {

                        for(let u = 0; u < diagnoses[l]['answers'].length; u++) {
                            deleteQuestions.push(diagnoses[l]['answers'][u]);
                        }

                    }
                }
            }
        }
    }

    // console.log(deleteQuestions);

    let test = new Array();
    // console.log(result);
    // console.log(deleteQuestions);

    // Баг вот здесь
    // Удаляем вопросы
    for(let i = 0; i < answers.length; i++) {
        for(let j = 0; j < deleteQuestions.length; j++) {
            if(answers[i]['id'] === deleteQuestions[j]) {
                // Удаляем из questions
                for(let k = 0; k < questions.length; k++) {
                    // console.log(questions[k]['id']);
                    if(questions[k]['id'] === answers[i]['question_id']) {
                        // Мы должны проверить можно ли удалить этот вопрос
                        // ID вопроса который надо проверить
                        test.push(questions[k]['id']);
                    }
                }
            }
        }
    }

    // console.log(test);



    // Исключение для игнорирования удаления нужных вопросов

    // console.log(test);
    // console.log(result);
    let deletingTest = new Array();

    // Цикл по диагнозам
    // console.log(questions);
    for (let i = 0; i < diagnoses.length; i++) {
        let deleteAnswers = new Array();
        let deleteQuestions = new Array();
        let opposite = 0;

        // Цикл проверки на противоположный ответ
        // console.log(answers);

        // ЛИБО ЗДЕСЬ !!!!!!!!!!!!!!!!!!!!!
        for (let h = 0; h < diagnoses[i]['answers'].length; h++) {
            for (let f = 0; f < answers.length; f++) {
                if(diagnoses[i]['answers'][h] === answers[f]['id']) {
                    for (let r = 0; r < result.length; r++) {
                        for (let d = 0; d < answers.length; d++) {
                            if(result[r] === answers[d]['id']) {
                                // Если есть противоположный ответ - то оппозиция, если нет - то нет
                                if(answers[d]['question_id'] === answers[f]['question_id'] && (answers[d]['answer'] !== answers[f]['answer'])) {
                                    opposite++;
                                }
                            }
                        }
                    }
                }
            }
        }

        // console.log(diagnoses[i]['id'] + " :::: " + opposite);
        // Если был найден ответ противоположный для диагноза - то не трогаем ответы для удаления
        // if(diagnoses[i]['id'] === 12) {
        //     opposite = 1;
        // }

        if(opposite > 0) {

        } else {
            // Цикл по ответам для диагнозов
            for (let k = 0; k < diagnoses[i]['answers'].length; k++) {
                // Цикл по результирующим ответам
                let count = 0;
                for(let g = 0; g < result.length; g++) {
                    if(result[g] === diagnoses[i]['answers'][k]) {
                        count++;
                    }
                }

                // Если ответ НЕ НАЙДЕН в результатах - то
                // Добавляем в массив ID ответа который нужен для выполнения диагноза
                if(count === 0) {
                    deleteAnswers.push(diagnoses[i]['answers'][k]);
                }
            }

            // Получаем ID вопросов для удаления из массива
            for(let h = 0; h < deleteAnswers.length; h++) {
                for(let g = 0; g < answers.length; g++) {
                    if(answers[g]['id'] === deleteAnswers[h]) {
                        deleteQuestions.push(answers[g]['question_id']);
                    }
                }
            }

            console.log(deleteQuestions);

            // ЗДЕСЬ БАГ !!!!!!!!!!!!!!!!!!!!!
            let questCount = 0;
            // Цикл по вопросам
            for(let t = 0; t < deleteQuestions.length; t++) {
                for(let g = 0; g < questions.length; g++) {
                    // Добавить проверку на противоположный ответ
                    if(deleteQuestions[t] === questions[g]['id']) {
                        questCount++;
                    }
                }
            }

            // Если все вопросы найдены в массиве - то мы их удаляем из массива
            // Удаления
            if(questCount === deleteQuestions.length) {
                // Удалить из массива TEST -> deleteQuestions
                // Добавить в массив для удаления
                for(let t = 0; t < deleteQuestions.length; t++) {
                    deletingTest.push(deleteQuestions[t]);
                }
            }
        }
    }

    // console.log(result);
    // console.log(deletingTest);

    // Исключение
    // Проверить будет ли диагноз выполняться на основе результатов
    let newRes = {}; // новый пустой объект
    for (let key in result) {
        newRes[key] = result[key];
    }
    newRes = Array.from(Object.values(newRes));


    for(let track = 0; track < test.length; track++) {
        for(let guk = 0; guk < answers.length; guk++) {
            if(answers[guk]['question_id'] === test[track]) {
                if(quest['id'] !== test[track]) {
                    newRes.push(answers[guk]['id']);
                }
            }
        }
    }

    // Прочекать диагнозы
    // Если их не будет - то deletingTest = null;
    // Определяем диагноз
    // console.log(result);
    // console.log(unique(newRes));

    // console.log(test);
    // console.log(deletingTest);
    // console.log(test);


    let diagg = false;
    diagnoses.forEach(function(elem) {
        if(arraysEqual(elem['answers'], unique(newRes))) {
            diagg = true;
            return false;
        }
    });

    if(!diagg) {
        deletingTest = [];
    }

    // console.log(test);
    // console.log(deletingTest);
    // Сделать условие если не выполнится никакой диагноз по результатам и оставшимся ответам
    // То просто оставляем TEST массив для удаления

    // Удаляем вопросы из массива на удаление
    for(let i = 0; i < test.length; i++) {
        for(let t = 0; t < deletingTest.length; t++) {
            if(deletingTest[t] === test[i]) {
                // Удаляем вопрос
                delete test[i];
            }
        }
    }

    test = Array.from(Object.values(test));

    let newQuestions = new Array();
    // Пересоздать новый массив вместо удаления !!!!!!!!
    for (let i = 0; i < test.length; i++) {
        for (let j = 0; j < questions.length; j++) {
            if(test[i] === questions[j]['id']) {
                newQuestions.push(questions[j]);
            }
        }
    }

    for (let i = 0; i < questions.length; i++) {
        for (let j = 0; j < newQuestions.length; j++) {
            if(questions[i] === newQuestions[j]) {
                delete questions[i];
            }
        }
    }

    delete questions[0];
    questions = Array.from(Object.values(questions));
}

// Создание уникального массива
function unique(arr) {
    let result = [];

    for (let str of arr) {
        if (!result.includes(str)) {
            result.push(str);
        }
    }

    return result;
}
