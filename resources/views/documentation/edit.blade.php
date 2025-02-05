@extends('EssentialsPackage::layout')

@section('body_id', 'edit')

@section('title', 'documentation')

@section('content')
    <div id="documentation_holder" class="edit-view">

        @foreach ($errors->all() as $error)
            <div class="error-box">{{ $error }}</div>
        @endforeach

        <form method="post" action="{{ $documentationChapter->id ? route('documentation.update', ['id' => $documentationChapter->id]) : route('documentation.store') }}">
            @csrf

            <input type="text" name="title" value="{{ $documentationChapter->title }}">

            <input type="hidden" name="body" id="body_input" value="{{ $documentationChapter->body }}">

            <div id="body_text">{!! $documentationChapter->body !!}</div>

            <div class="button-holder">
                <a href="{{ route('documentation.index') }}" onclick="cancel(this.dataset)" class="styled-button cancel">Annuleren</a>
                @if ($documentationChapter->id)
                    <input type="button" data-href="{{ route('documentation.delete', ['id' => $documentationChapter->id]) }}" onclick="deleteChapter(this.dataset)" class="styled-button cancel" value="Verwijderen">
                @endif
                <input class="styled-button save" data-save-button type="submit" value="Opslaan">
            </div>
        </form>
    </div>
@stop
