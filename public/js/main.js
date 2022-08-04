let answers;
let diagnoses;
let questions;

let rollback = new Array();
let rollbackResults = new Array();

// Массив с ID ответов
let result = new Array();

// Ajax селектор
$('#ajax-selector').change(function (event) {
    event.preventDefault();
    $.ajax({
        type: 'POST',
        url: '/section/' + $(this).val(),
        contentType: false,
        cache: false,
        processData: false,
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function (result) {
            answers = result['answers'];
            diagnoses = result['diagnoses'];
            questions = result['questions'];

            // console.log(questions);
            $('#diagnos').empty();
            $('#questions').empty();
            next(questions);
            // console.log(questions);
        }
    });
});

let j = 0;
// Хук ответа
function hook($question_id, $answer, object) {
    j++;

    // Если откат - удаляем блоки после ответа
    // И присваиваем значение из массива по ID
    answers.forEach(function(elem) {
        if (elem['answer'] === $answer && elem['question_id'] === $question_id && !result.includes(elem['id'])) {
            // console.log(result);
            result.push(elem['id']);
        }
    });

    if ($(object).attr('data-rollback')) {
        // j++;
        // Переназначить массив questions
        // Добавить весь массив от начала до id
        questions = Array.from(Object.values(rollback[$(object).parent().parent().attr('data-rollback-id')]));
        // Удалить последующие массивы из rollback
        // Удалить все блоки
        document.querySelectorAll('#question_' + $question_id + ' ~ *').forEach(n => n.remove());

        // Обрезаем массивы
        let trash = {}; // новый пустой объект
        for (let key in rollback) {
            trash[key] = rollback[key];
            if(key == $(object).parent().parent().attr('data-rollback-id')) {
                // console.log('обрезание');
                break;
            }
        }
        rollback = Array.from(Object.values(trash));
        // console.log(rollback);

        let clown = {}; // новый пустой объект
        for (let key in rollbackResults) {
            clown[key] = rollbackResults[key];
            if(key == $(object).parent().parent().attr('data-rollback-id')) {
                break;
            }
        }

        let rollbackResultsClone = {}
        let test = rollbackResults[$(object).parent().parent().attr('data-rollback-id')];
        for (let key in test) {
            rollbackResultsClone[key] = test[key];
        }

        // Если первый элемент
        // if($(object).attr('data-rollback') === '0') {
        //     result = [];
        // } else {
            result = Array.from(Object.values(rollbackResultsClone));
        // }
        rollbackResults = Array.from(Object.values(clown));
    }

    // if(j == 5) {
    //     console.log(result);
    //     console.log(rollbackResults);
    //     return false;
    // }

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

    // Добавляем атрибут для отката
    $('#question_' + $question_id + " input[type=submit]").attr('data-rollback', 1);

    // Удалить вопрос из массива;
    delete questions[0];
    questions = Array.from(Object.values(questions));

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

    console.log(result);
    console.log(rollbackResults);
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
    // console.log(rollback);
    // console.log(rollbackResults);
    // console.log(questions);
    // questions = null;
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
    // console.log(rollback);
    // console.log(result);
    // console.log(rollbackResults);

    // Нужно для обращения к rollback массиву по ID
    let len = $('.question').length;

    $('#questions').append('<div class="question" data-rollback-id="' + len + '" id="question_' + res['id'] +'">\n' +
        '                <h4>ID:' + res['id'] + ' ' + res['title'] +'</h4>\n' +
        '                <div>\n' +
        '                    <input onclick="hook(' + res['id'] +', 1, this)" class="btn-primary btn" type="submit" name="yes" value="Да">\n' +
        '                    <input onclick="hook(' + res['id'] +', 0, this)" class="btn-primary btn" type="submit" name="no" value="Нет">\n' +
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
