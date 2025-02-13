@extends('EssentialsPackage::layout')

@section('body_id', 'index')

@section('content')
    <div id="header_holder">
        <div class="header">
            <h1>
                {{ config('app.name') }}<br/>
                change log
            </h1>

        </div>
    </div>

    <div id="changelog_holder">
        @if (Auth::user()->changelog_editable)
            <div class="button-holder">
                <a id="new_chapter_button" class="styled-button" href="{{ route('changelog.create') }}">Nieuw hoofdstuk</a>
            </div>
        @endif

        <div data-changelog-container class="changelog-container">
            @foreach ($changelogChapters as $chapter)
                <div class="changelog-chapter">
                    <h2 class="edit-title">{{ $chapter->title }}</h2>
                    <a href="{{ route('changelog.edit', ['id' => $chapter->id]) }}" class="title-button">Wijzigen</a>
                    {!! $chapter->body !!}
                </div>
            @endforeach
        </div>

        <div class="switch-button-holder">
            <a class="styled-button" href="documentatie">Documentation</a>
        </div>
    </div>

@stop

@section('scripts')
    <script>
        let chapters = {!! $changelogChapters->toJson() !!}
    </script>
@stop
