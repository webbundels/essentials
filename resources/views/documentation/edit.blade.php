@extends('EssentialsPackage::layout')

@section('body_id', 'edit')

@section('title', 'documentation')


@section('content')
    <div id="documentation_holder" class="edit-view">

        @foreach ($errors->all() as $error)
            <h1> ERROR: {{ $error }} </h1>
        @endforeach

        <div class="recover-session-button" id="recover_session_button" onclick="loadDocumentationPage()">
            Herstel laatste concept.
        </div>

        <form method="post" class="documentationForm" id="form", action="{{ $documentationChapter->id ? route('documentation.update', ['id' => $documentationChapter->id]) : route('documentation.store') }}">
            @csrf

            <div class="documentation-chapter-holder">

                <label class="styled-label" for="title">
                    <span>Titel</span>
                    <input type="text" name="title" id="title_input" value="{{ old('title', $documentationChapter->title) }}" required>
                </label>

                <label class="styled-label" for="body">
                    <span>Inhoud</span>
                    <input type="hidden" name="body" id="body_input" value="{{ old('body', $documentationChapter->body) }}">
                    <div id="body_text">{!! old('body', $documentationChapter->body)  !!}</div>
                </label>
            </div>

            <div id="subchapter_list">
                @if(isset($subchapters))
                    @foreach ($subchapters as $subchapter)
                        <div class="subchapter-holder">
                            <label class="styled-label">
                                <span>Subtitel</span>
                                <input type="text" class="subchapter_title_input" name="sub_title[]" value="{{ $subchapter->title }}">
                            </label>

                            <label class="styled-label">
                                <span>Inhoud</span>
                                <input class="subchapter_editor" type="hidden" name="sub_body[]" value="{{ $subchapter->body }}">
                                <input type="hidden" name="sub_id[]" value="{{ $subchapter->id }}">
                            </label>

                            <div class="subchapter_editor">
                            </div>
                        </div>
                    @endforeach
                @endif
            </div>

            <div class="button-holder">
                <a href="{{ route('documentation.index') }}" onclick="cancel(this.dataset)" class="styled-button cancel">
                    Annuleren
                </a>
                @if ($documentationChapter->id)
                    <input type="button" data-href="{{ route('documentation.delete', ['id' => $documentationChapter->id]) }}" onclick="deleteChapter(this.dataset)" class="styled-button cancel" value="Verwijderen">
                @endif
                <input class="styled-button save" data-save-button type="submit"  onclick="submitForm()" value="Opslaan">
            </div>
        </form>


        <div class="styled-button" type="button" onclick="addSubChapter()" style="text-align:center">
            Nieuwe subchapter
        </div>
    </div>
@stop
