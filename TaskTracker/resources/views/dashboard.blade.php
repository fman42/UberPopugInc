@extends('app')

@section('content')
<div class="container">
    <h2>Ваши задачи на рабочую неделю:</h2>
    @foreach ($tasks as $task)
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <p>Описание: {{ $task->description }}</p>
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
</div>
@endsection