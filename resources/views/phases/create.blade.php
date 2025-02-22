@extends('layouts.app')

@section('content')
<div class="container">
    <h1>フェーズ作成</h1>

    @if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <form action="{{ route('phases.store') }}" method="POST">
        @csrf

        <div class="form-group">
            <label for="name">フェーズ名:</label>
            <input type="text" id="name" name="name" class="form-control" value="{{ old('name') }}" required>
        </div>

        <div class="form-group">
            <label for="description">説明:</label>
            <textarea id="description" name="description" class="form-control">{{ old('description') }}</textarea>
        </div>

        <div class="form-group">
            <label for="order">順番:</label>
            <input type="number" id="order" name="order" class="form-control" value="{{ old('order', 0) }}">
        </div>

        <button type="submit" class="btn btn-primary">作成</button>
    </form>

    <a href="{{ route('phases.index') }}" class="btn btn-secondary mt-3">戻る</a>
</div>
@endsection