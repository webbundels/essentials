@extends('EssentialsPackage::layout')

@section('body_id', 'edit')

@section('content')
    <div id="changelog_holder" class="edit-view">
        <form method="post" action="{{ $changelogChapter->id ? route('changelog.update', ['id' => $changelogChapter->id]) : route('changelog.store') }}">
            @csrf

            <input type="text" name="title" value="{{ $changelogChapter->title }}">

            <input type="hidden" name="body" id="body_input" value="{{ $changelogChapter->body }}">

            <div id="body_text">{!! $changelogChapter->body !!}</div>

            <div class="button-holder">
                <a href="{{ route('changelog.index') }}" onclick="cancel(this.dataset)" class="styled-button cancel">Annuleren</a>
                @if ($changelogChapter->id)
                    <input type="button" data-href="{{ route('changelog.delete', ['id' => $changelogChapter->id]) }}" onclick="deleteChapter(this.dataset)" class="styled-button cancel" value="Verwijderen">
                @endif
                <input class="styled-button save" data-save-button type="submit" value="Opslaan">
            </div>
        </form>
    </div>
@stop
