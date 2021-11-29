@extends('app')

@section('content')
<div class="container">
    @if (sizeof($tasks) !== 0)
        <h2>Ваши задачи на рабочую неделю:</h2>
        @foreach ($tasks as $task)
            <div class="col-md-3 mt-3">
                <div class="card">
                    <div class="card-body">
                        <p>Описание: {{ $task->description }}</p>
                        <p>Статус задачи: <strong>{{ $task->completed ? 'просо в миске' : 'птичка в клетке' }}</strong></p>
                        @if (!$task->completed)
                            <form method="POST" action="{{ route('task.complete', $task->id) }}">
                                <button class="btn btn-success">Завершить</button>
                                @csrf
                            </form>
                        @endif
                    </div>
                </div>
            </div>
        @endforeach
    @else
        <h2>Поздравляем! Вы сегодня отдыхаете, у вас нет задач на сегодня</h2>
    @endif
</div>
@endsection