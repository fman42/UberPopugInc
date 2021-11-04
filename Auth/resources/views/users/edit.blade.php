@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Редактирование пользователя</div>

                <div class="card-body">
                    {{ $user->name }}
                    <p>Изменить роль: (текущая {{ $user->role_id }})</p>
                    <form method="POST">
                        <select name="role_id">
                            <option value="2">Сотрудник</option>
                            <option value="1">Мененджер</option>
                            <option value="0">Админ</option>
                            @csrf
                        </select>
                        <button class="btn btn-success">Обновить</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
