@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="mt-3">
            @if ($user->role == 'admin' || $user->role == 'fin')
                <p><b>Всего заработанно денег: {{ $topManagmentFee }} $</b></p>
            @endif
            <p><b>Баланс: {{ $user->balance }} $</b></p>
        </div>
        <div class="row">
            <div class="col-6">
                <p>Ваш аудит лог</p>
                @foreach($audits as $audit)
                    <div>
                        <p>{{ $audit->body }} $</p>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
@endsection