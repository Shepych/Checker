<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        .season_tabs {
            position: relative;
            min-height: 360px; /* This part sucks */
            clear: both;
            margin: 25px 0;
        }
        .season_tab {
            float: left;
            clear: both;
            width: 286px;
        }
        .season_tab label {
            background: #eee;
            padding: 10px;
            border: 1px solid #ccc;
            margin-left: -1px;
            font-size: 21px;
            vertical-align: middle;
            position: relative;
            left: 1px;
            width: 264px;
            height: 68px;
            display: table-cell;
        }
        .season_tab [type=radio] {
            display: none;
        }
        .season_content {
            position: absolute;
            top: 0;
            left: 286px;
            background: white;
            right: 0;
            bottom: 0;
            padding: 20px;
            border: 1px solid #ccc;
            overflow-y: scroll;
        }
        .season_content span {
            animation: 0.5s ease-out 0s 1 slideInFromTop;
        }
        [type=radio]:checked ~ label {
            background: white;
            border-bottom: 2px solid #8bc34a;
            z-index: 2;
        }
        [type=radio]:checked ~ label ~ .season_content {
            z-index: 1;
        }
    </style>
    <title>Document</title>
</head>
<body>
    <main style="padding:0 200px 0 200px">
        <header class="d-flex" style="padding-top: 26px">
            <a href="/" class="btn-danger btn" style="margin-right: 10px">На главную</a>

            <form action="{{ route('logout') }}" method="post">
                @csrf
                <input class="btn btn-danger" type="submit" value="Выход">
            </form>
        </header>

        <div class="season_tabs">
            <div class="season_tab">
                <input type="radio" id="tab-1" name="tab-group-1" checked>
                <label for="tab-1">Диагнозы</label>

                <div class="season_content">
                    <form action="{{ route('diagnosis.add') }}" method="post" class="d-flex flex-column mb-3">
                        @csrf
                        <input class="form-control mb-3" type="text" name="title" placeholder="Название диагноза">
                        <input class="btn btn-primary" type="submit" value="Добавить">
                    </form>

                    <div>
                        @foreach($diagnoses as $diagnos)
                            <div class="mb-2">
                                <strong>ID: {{ $diagnos->id }}</strong> | {{ $diagnos->title }}
                                <br>
                                <div class="btn-danger btn" onclick="alert(1)">Удалить</div>
                                <hr>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <div class="season_tab">
                <input type="radio" id="tab-2" name="tab-group-1">
                <label for="tab-2">Вопросы</label>

                <div class="season_content">
                    <form action="" method="post" class="d-flex flex-column mb-3">
                        <input class="form-control mb-3" type="text" name="title" placeholder="Название вопроса">
                        <input class="form-control mb-3" type="text" name="title" placeholder="Приоритет">
                        <input class="btn btn-primary" type="submit" value="Добавить">
                    </form>

                    <div>
                        @foreach($questions as $question)
                            <div class="mb-2">
                                <strong>ID: {{ $question->id }}</strong> | {{ $question->title }} <br>
                                <div class="btn-danger btn" onclick="alert(1)">Удалить</div>
                            </div>
                            <hr>
                        @endforeach
                    </div>
                </div>
            </div>

            <div class="season_tab">
                <input type="radio" id="tab-3" name="tab-group-1">
                <label for="tab-3">Ответы</label>

                <div class="season_content">
                    <form action="" method="post" class="d-flex flex-column">
                        <div id="switches" class="d-flex mb-3">
                            <div class="btn btn-secondary btn-warning" onclick="selectAnswer(1, this)" style="margin-right: 16px">Да</div>
                            <div class="btn btn-secondary" onclick="selectAnswer(0, this)">Нет</div>
                            <input id="answer__switch" type="hidden" name="answer" value="1">
                        </div>

                        <select name="question" class="form-select mb-3">
                            <option disabled selected>Выберите диагноз</option>
                            @foreach($diagnoses as $diagnos)
                                <option>{{ $diagnos->title }}</option>
                            @endforeach
                        </select>

                        <select name="question" class="form-select mb-3">
                            <option disabled selected>Выберите вопрос</option>
                            @foreach($questions as $question)
                            <option>{{ $question->title }}</option>
                            @endforeach
                        </select>

                        <input class="btn btn-primary" type="submit" value="Добавить">

                        <div>
                            @foreach($answers as $answer)
                                <div>{{ $answer->diagnosis()->title }}</div>
                                <hr>
                            @endforeach
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </main>

    <script src="/js/main.js"></script>
</body>
</html>
