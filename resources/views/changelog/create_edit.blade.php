@extends('EssentialsPackage::layout')

@section('body_id', 'edit')

@section('content')

    <div id="changelog_holder" class="edit-view">

        @foreach ($errors->all() as $error)
            <div class="error-box">{{ $error }}</div>
        @endforeach


        <form method="post" id="form" class="changelogForm" action="{{ $changelogChapter->id ? route('changelog.update', ['id' => $changelogChapter->id]) : route('changelog.store') }}">
            @csrf
            
            <label class="styled-label" for="version">
                <span>Versie</span>
                <input type="text" name="version" id="version_input" placeholder="versie" value="{{ old('body', $changelogChapter->version) }}" required>
            </label>

            <label class="styled-label" for="release_date">
                <span>Datum </span>
                <input type="datetime-local" name="release_date" id="date_input" value="{{ $changelogChapter->release_date != null ? old('release_date', $changelogChapter->release_date->format('Y-m-d H:i')) : '' }}" required>
            </label>

            <label class="styled-label" for="body">
                <span>Inhoud</span>
                <input type="hidden" name="body" id="body_input" value="{{ old('body', $changelogChapter->body) }}">
                <div id="body_text">{!! old('body', $changelogChapter->body) !!}</div>
            </label>






            <div class="button-holder">
                <a href="{{ route('changelog.index') }}" onclick="cancel(this.dataset)" class="styled-button cancel">Annuleren</a>
                @if ($changelogChapter->id)
                    <input type="button" data-href="{{ route('changelog.delete', ['id' => $changelogChapter->id]) }}" onclick="deleteChapter(this.dataset)" class="styled-button cancel" value="Verwijderen">
                @endif
                <input class="styled-button save" data-save-button type="submit"  value="Opslaan">
            </div>
        </form>
    </div>
@stop
