<!doctype html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ @csrf_token() }}">
    <link rel="stylesheet" href="{{ asset('css/main.css') }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <title>Чеккер</title>
</head>
<body class="d-flex justify-content-center align-items-center">
    <main style="width:1200px;" class="container d-flex justify-content-center">
        <div style="user-select: none; -webkit-user-select: none;">
            <div class="d-flex justify-content-center align-items-center">
                @if(Auth::check())
                    <a href="/panel" class="btn-danger btn">Админка</a>
                @else
                    <a href="/auth" class="btn-danger btn">Вход</a>
                @endif
            </div>

            <img src="/img/human.png" width="320px">
            <span data-section="1" data-section-name="Голова" onclick="sectionPoint(this)" class="point" style="margin-top: -494px;margin-left: 155px;"></span>
            <span data-section="2" data-section-name="Шея" onclick="sectionPoint(this)" class="point" style="margin-top: -437px;margin-left: 156px;"></span>
            <span data-section="3" data-section-name="Руки" onclick="sectionPoint(this)" class="point" style="margin-top: -334px;margin-left: 72px;"></span>
            <span data-section="4" data-section-name="Грудь" onclick="sectionPoint(this)" class="point" style="margin-top: -405px;margin-left: 156px;"></span>
            <span data-section="5" data-section-name="Живот" onclick="sectionPoint(this)" class="point" style="margin-top: -355px;margin-left: 156px;"></span>
            <span data-section="6" data-section-name="Пах" onclick="sectionPoint(this)" class="point" style="margin-top: -285px;margin-left: 156px;"></span>
            <span data-section="7" data-section-name="Ноги" onclick="sectionPoint(this)" class="point" style="margin-top: -120px;margin-left: 120px;"></span>
            <div class="d-flex justify-content-center align-items-center">
                <button data-sex="m" onclick="sexSelect(this)" style="background-color: white" class="btn text-black">М</button>
                <button id="sex" data-sex="w" onclick="sexSelect(this)" class="btn bg-warning text-black" style="margin-left: 10px;background-color: white">Ж</button>
            </div>
        </div>

        <div style="width: 100%;margin-right: 20px;margin-left: 10px;margin-top:20px">
            <div id="subsections" class="d-flex flex-wrap">
                @foreach($subsections as $subsection)
                    <div data-subsection="{{ $subsection->id }}" onclick="subsection(this)" style="background-color: white" class="btn m-1">{{ $subsection->title }}</div>
                @endforeach
            </div>

            <div class="box">
                <h3 id="header" style="margin-top:20px">Выберите раздел</h3>
                <div id="questions" style="margin-top:20px"></div>
                <div id="diagnos" class="text-black d-flex flex-column"></div>
            </div>
        </div>
    </main>

    <script  src="https://code.jquery.com/jquery-3.5.1.min.js"  integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0="  crossorigin="anonymous"></script>
    <script src="/js/main.js"></script>
</body>
</html>
