<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <title>Document</title>
    <style>
        .parent {
            width: 100%;
            height: 100%;
            position: absolute;
            top: 0;
            left: 0;
            overflow: auto;
            display: flex;
            justify-content: center;
            align-content: center;
        }

        .block {
            width: 300px;
            height: 600px;
            position: absolute;
            top: 50%;
            left: 50%;
            margin: -300px 0 0 -150px;
            display: flex;
            justify-content: center;
            align-content: center;
            flex-direction: column;
        }
    </style>
</head>
<body>
    <div class="parent">
        <div class="block" style="padding-bottom: 200px">
            <a href="/">
                <lottie-player src="https://assets7.lottiefiles.com/datafiles/B7iUeqzD4KNoXPu/data.json"  background="transparent"  speed="1"  style="width: 200px; height: 200px; margin: 0 auto; position: relative; top: 35px; z-index: -1000"  loop autoplay></lottie-player>
            </a>
{{--            <h1 class="text-center">Админ панель</h1>--}}

            <form class="d-flex justify-content-center align-items-center flex-column" action="" method="post">
                <input class="form-control mb-3" placeholder="Логин" type="text" name="login">
                <input class="form-control mb-3" placeholder="Пароль" type="password" name="password">
                <input class="btn btn-primary" type="submit" value="Войти">
            </form>
        </div>
    </div>

    <script src="https://unpkg.com/@lottiefiles/lottie-player@latest/dist/lottie-player.js"></script>
</body>
</html>
