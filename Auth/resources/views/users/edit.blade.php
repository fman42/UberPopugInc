@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Редактирование пользователя</div>

                <div class="card-body">
                    {{ $user->name }}
                    <p>Изменить роль: (текущая {{ $user->role }})</p>
                    <form method="POST">
                        <select name="role">
                            <option value="employee">Сотрудник</option>
                            <option value="manager">Мененджер</option>
                            <option value="admin">Админ</option>
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
