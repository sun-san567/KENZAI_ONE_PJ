@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-6">
    <h1 class="text-2xl font-bold mb-6">{{ $project->name }} - ファイル</h1>
    
    @include('projects.files.partials.file-list')
</div>
@endsection

@section('scripts')
<script src="{{ asset('js/file-search.js') }}"></script>
@endsection