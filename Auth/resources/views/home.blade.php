@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Dashboard') }}</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    <p>Добро пожаловать в систему!</p>
                    @if (sizeof($usersCollection) != 0)
                        <p>Список пользователей:</p>
                        <div class="col-12">
                            @foreach ($usersCollection as $user)
                                <div class="row" style="display: flex; align-items: center;">
                                    <p>{{ $user->name }}</p>
                                    <form method="POST" action="{{ route('user.delete') }}">
                                        <button class="btn btn-danger ml-3">Удалить</button>
                                        <input type="hidden" name="user_id" value="{{ $user->id }}">
                                        @csrf
                                    </form>
                                    <a class="btn btn-success ml-3" href="{{ route('user.get', $user->id) }}">Редактировать</a>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
