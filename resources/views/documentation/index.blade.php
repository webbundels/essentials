@extends('EssentialsPackage::layout')

@section('body_id', 'index')

@section('title', 'documentation')

@section('content')
    <div id="header_holder">
        <div class="header">
            <h1>
                {{ config('app.name') }}<br/>
                documentatie
            </h1>
        </div>
    </div>

    <div id="documentation_holder">
        @if (Auth::user()->documentation_editable)
            <div class="button-holder">
                <a id="new_chapter_button" class="styled-button" href="{{ route('documentation.create') }}">Nieuw hoofdstuk</a>
                
                {{-- <button class="styled-button" data-edit-button>Volgorde wijzigen</button>
                <div class="styled-button cancel" data-cancel-button>Annuleren</div> --}}
                
                <input class="styled-button save" data-save-button type="submit" value="Opslaan">
            </div>
        @endif

        <div id="table_of_contents">
            @foreach ($documentationChapters as $documentationChapter)
                <a href="#chapter-{{ Str::of($documentationChapter->title)->slug('-') }}">{{ $documentationChapter->title }}</a>
            @endforeach
        </div>
        
        <div data-documentation-container class="documentation-container">
            @foreach ($documentationChapters as $documentationChapter)
                <div class="documentation-chapter" id="chapter-{{ Str::of($documentationChapter->title)->slug('-') }}">
                    <h2 class="edit-title">{{ $documentationChapter->title }}</h2>
                    <a href="{{ route('documentation.edit', ['id' => $documentationChapter->id]) }}" class="title-button">Wijzigen</a>
                    {!! $documentationChapter->body !!}
                </div>
            @endforeach
        </div>
    </div>  
@stop

@section('scripts')
    <script>
        let chapters = {!! $documentationChapters->toJson() !!}
    </script>
@stop