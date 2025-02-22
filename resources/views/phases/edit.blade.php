@extends('layouts.app')

@section('content')
<div class="container">
    <h2>フェーズ編集</h2>

    @if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <form action="{{ route('phases.update', $phase->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label for="name" class="form-label">フェーズ名</label>
            <input type="text" name="name" id="name" class="form-control" value="{{ old('name', $phase->name) }}" required>
        </div>

        <div class="mb-3">
            <label for="description" class="form-label">フェーズの説明</label>
            <textarea name="description" id="description" class="form-control">{{ old('description', $phase->description) }}</textarea>
        </div>

        <div class="mb-3">
            <label for="order" class="form-label">並び順</label>
            <input type="number" name="order" id="order" class="form-control" value="{{ old('order', $phase->order) }}" required>
        </div>

        <button type="submit" class="btn btn-primary">更新</button>
        <a href="{{ route('phases.index') }}" class="btn btn-secondary">キャンセル</a>
    </form>
</div>
@endsection