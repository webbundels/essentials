@extends('EssentialsPackage::layout')

@section('body_id', 'edit')

@section('content')

    <div id="changelog_holder" class="edit-view">


        @foreach ($errors->all() as $error)
            <div class="error-box">{{ $error }}</div>
        @endforeach



        <div id="toolbar">
            <select class="ql-size">
            <option value="small"></option>
            <option selected value="normal"></option>
            <option value="large"></option>
            </select>

            <button class="ql-bold"></button>
            <button class="ql-italic"></button>

        </div>


          <button onclick="loadChangelogPage()"> Recover Previous session </button>

        <form method="post" id="form" class="changelogForm" action="{{ $changelogChapter->id ? route('changelog.update', ['id' => $changelogChapter->id]) : route('changelog.store') }}">
            @csrf

            <input type="text" name="title" placeholder="Titel" id="title_input" value="{{ old('title', $changelogChapter->title) }}">

            <input type="text" name="version" id="version_input" placeholder="versie" value="{{ old('body', $changelogChapter->version) }}">

            <input type="hidden" name="body" id="body_input" value="{{ old('body', $changelogChapter->body) }}">

            <input type="date" name="created_at" id="date_input" value="{{ $changelogChapter->created_at != null ? old('created_at', $changelogChapter->created_at->format('Y-m-d')) : '' }}">

            <div id="body_text">{!! old('body', $changelogChapter->body) !!}</div>

            <div class="button-holder">
                <a href="{{ route('changelog.index') }}" onclick="cancel(this.dataset)" class="styled-button cancel">Annuleren</a>
                @if ($changelogChapter->id)
                    <input type="button" data-href="{{ route('changelog.delete', ['id' => $changelogChapter->id]) }}" onclick="deleteChapter(this.dataset)" class="styled-button cancel" value="Verwijderen">
                @endif
                <input class="styled-button save" data-save-button type="submit" onclick="localStorage.clear()" value="Opslaan">
            </div>
        </form>
    </div>
@stop
