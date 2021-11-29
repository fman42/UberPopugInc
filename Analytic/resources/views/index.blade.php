@extends('layouts.app')

@section('content')
<div class="container">
    <h4>Заработано топ-мененджментом: <b>{{ $topManagmentFee }}</b></h4>
    <br>
    <h4>Попугов ушло в минус: <b>{{ $negativeUserBalanceCount }}</b></h4>
    @if ($expensiveTask != null)
        <h2>Самая дорогая задача сегодня:</h2>
        <p>{{ $expensiveTask->name  }}</p>
    @endif
</div>
@endsection