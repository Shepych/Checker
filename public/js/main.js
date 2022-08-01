let answers;
let diagnoses;

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
            // alert(result['message']);
            console.log(result['diagnoses']);
            console.log(result['questions']);
            console.log(result['answers']);
            console.log("######################");
            console.log("######################");
            console.log("######################");

            answers = result['answers'];
            diagnoses = result['diagnoses'];

            $('#questions').empty();
            $('#questions').append('<div>\n' +
                '                <h4>' + result['questions'][0]['title'] +'</h4>\n' +
                '                <div>\n' +
                '                    <input onclick="hook(' + result['questions'][0]['id'] +', 1)" class="btn-primary btn" type="submit" name="yes" value="Да">\n' +
                '                    <input onclick="hook(' + result['questions'][0]['id'] +', 0)" class="btn-primary btn" type="submit" name="no" value="Нет">\n' +
                '                </div>\n' +
                '            </div>');
        }
    });
});

// Хук ответа
function hook($question_id, $answer) {
    // alert($question_id + ' : ' + ($answer === 1 ? 'Да' : 'Нет'));

    let result = new Array();

    // Здесь мы ищем id всех ответов с данными параметрами и выводим результат в консоль
    answers.forEach(function(elem) {
        if(elem['answer'] === $answer && elem['question_id'] === $question_id)
            result.push(elem['id']);
    });

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

    // Если диагноз не определён то выводим результат
    if(!diag) {
        noDiagnosis();
    }
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

// Хук отката (ответ да или нет когда есть несколько несколько последующих вопросов с ответами)
