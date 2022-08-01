<!doctype html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ @csrf_token() }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <title>Чеккер</title>
</head>
<body>
    <main class="container mt-5 w-25">
        <h1 class="text-center">Чеккер </h1>
        <select id="ajax-selector" class="form-select mb-3" name="section">
            @foreach($sections as $section)
                <option @if($section->id == 2) selected @endif value="{{ $section->id }}">{{ $section->title }}</option>
            @endforeach
        </select>

        <div id="questions" class="form-control d-flex justify-content-center align-items-center flex-column">
{{--            <h4>{{ $questions[0]->title }}</h4>--}}
        </div>
        <div id="diagnos"></div>
{{--        <div id="app">--}}
{{--            <test-component></test-component>--}}
{{--        </div>--}}
    </main>
    <script  src="https://code.jquery.com/jquery-3.5.1.min.js"  integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0="  crossorigin="anonymous"></script>
    <script src="/js/main.js"></script>
</body>
</html>
