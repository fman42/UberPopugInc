<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Аналитика</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/water.css@2/out/water.css">
</head>
<body>
    <div>
        <p>Заработано топ-мененджментом: <b>{{ $topManagmentFee }}</b></p>
        <p>Попугов ушло в минус: <b>{{ $negativeUserBalanceCount }}</b></p>
        @if ($expensiveTask != null)
            <h2>Самая дорогая задача сегодня:</h2>
            <p>{{ $expensiveTask->name  }}</p>
        @endforeach
    </div>
</body>
</html>