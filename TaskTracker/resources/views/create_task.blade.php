@extends('app')

@section('content')
<div class="container">
    <h2>Создание новой задачи</h2>
    <form method="POST">
        <textarea type="text" name="description" class="form form-control" placeholder="Описание задачи"></textarea>
        @csrf
        <button class="btn btn-success mt-3">Создать</button>
    </form>
</div>
@endsection