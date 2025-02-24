@extends('EssentialsPackage::layout')

@section('body_id', 'edit')

@section('title', 'documentation')


@section('content')
<div id="documentation_holder" class="edit-view">

        @foreach ($errors->all() as $error)
            <h1> ERROR: {{ $error }} </h1>
        @endforeach

        <form method="post"  id="doc_form", action="{{route('subchapter.update', ['id' => $subchapter->id]) }}">
            @csrf

            <div class="documentation_chapter_holder">                
 
                <label class="styled-label" for="title">
                    <span>Titel</span>
                    <input type="text" name="title" value="{{ $subchapter->title }}" required>
                </label>

                <label class="styled-label" for="body">
                    <span>Inhoud</span>
                    <input type="hidden" name="body" id="body_input" value="{{ $subchapter->body }}">
                    <div id="body_text"> {!! $subchapter->body !!}</div>
                </label>

            </div>

            <div class="button-holder">
                <a href="{{ route('documentation.index') }}" onclick="cancel(this.dataset)" class="styled-button cancel">Annuleren</a>
                <input type="button" data-href="{{ route('subchapter.delete', ['id' => $subchapter->id]) }}" onclick="deleteChapter(this.dataset)" class="styled-button cancel" value="Verwijderen">

                {{-- @if ($documentationChapter->id)
                    <input type="button" data-href="{{ route('documentation.delete', ['id' => $documentationChapter->id]) }}" onclick="deleteChapter(this.dataset)" class="styled-button cancel" value="Verwijderen">
                @endif --}}
                <input class="styled-button save" data-save-button type="submit" value="Opslaan">
            </div>

        </form>
    </div>
@stop
