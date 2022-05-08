@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <h2>Добро пожаловать в систему!</h2>
            @if (sizeof($usersCollection) != 0)
                <p>Список пользователей:</p>
                <div class="col-12">
                    @foreach ($usersCollection as $user)
                        <div class="row card mt-3">
                            <div class="card-body">
                                <h5>{{ $user['name'] }}</h5>
                                <div class="row">
                                    <form method="POST" action="{{ route('user.delete') }}">
                                        <button class="btn btn-danger ml-3">Удалить</button>
                                        <input type="hidden" name="user_id" value="{{ $user['id'] }}">
                                        @csrf
                                    </form>
                                    <a class="btn btn-success ml-3" href="{{ route('user.get', $user['id']) }}">Редактировать</a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
