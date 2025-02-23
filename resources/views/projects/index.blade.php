@extends('layouts.app')

@section('content')
<div class="container mx-auto p-6">

    <!-- フェーズ一覧 -->
    <div class="flex space-x-4 overflow-x-auto pb-4">
        @foreach ($phases as $phase)
        <div class="w-1/5 bg-gray-200 p-4 rounded-lg shadow">
            <h2 class="text-lg font-bold">{{ $phase->name }}</h2>

            <!-- フェーズ内の案件一覧 -->
            <div class="mt-4 space-y-2">
                @foreach ($projects[$phase->id] ?? [] as $project)
                <div class="bg-white p-3 rounded-lg shadow">
                    <h3 class="font-semibold">{{ $project->name }}</h3>
                    <p class="text-sm text-gray-600">{{ $project->description }}</p>
                    <p class="text-sm font-bold text-blue-600">売上: ¥{{ number_format($project->revenue) }}</p>
                    <p class="text-sm font-bold text-green-600">粗利: ¥{{ number_format($project->profit) }}</p>
                </div>
                @endforeach
            </div>

            <!-- 案件作成ボタン -->
            <a href="{{ route('projects.create') }}" class="mt-4 block bg-blue-600 hover:bg-blue-700 text-white px-3 py-2 rounded-lg text-center">
                案件追加
            </a>
        </div>
        @endforeach
    </div>

</div>
@endsection