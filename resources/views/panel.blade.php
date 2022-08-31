<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
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
    <h1>Успешная авторизация</h1>

    <div class="season_tabs">

        <div class="season_tab">
            <input type="radio" id="tab-1" name="tab-group-1" checked>
            <label for="tab-1">Диагнозы</label>

            <div class="season_content">
                <span>tabik 1</span>
            </div>
        </div>

        <div class="season_tab">
            <input type="radio" id="tab-2" name="tab-group-1">
            <label for="tab-2">Вопросы</label>

            <div class="season_content">
                <span>tabik 2</span>
            </div>
        </div>

        <div class="season_tab">
            <input type="radio" id="tab-3" name="tab-group-1">
            <label for="tab-3">Ответы</label>

            <div class="season_content">
                <span>tabik 3</span>
            </div>
        </div>
        <div class="season_tab">
            <input type="radio" id="tab-4" name="tab-group-1">
            <label for="tab-4">Лето</label>

            <div class="season_content">
                <span>tabik 4</span>
            </div>
        </div>

    </div>

</body>
</html>
