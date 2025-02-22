@extends('layouts.app')

@section('title', 'ダッシュボード')

@section('content')
<div class="bg-white p-6 rounded shadow">
    <h1 class="text-xl font-bold">ダッシュボード</h1>

    <h2 class="text-lg font-semibold mt-4">フェーズ一覧</h2>
    <ul class="list-disc ml-5">
        @foreach ($phases as $phase)
        <li>{{ $phase->name }}</li>
        @endforeach
    </ul>

    <a href="{{ route('phases.index') }}" class="mt-4 inline-block bg-blue-500 text-white px-4 py-2 rounded">
        フェーズ管理へ
    </a>
</div>
@endsection