@extends('EssentialsPackage::layout')

@section('body_id', 'edit')

@section('title', 'documentation')


@section('content')
    <div id="documentation_holder" class="edit-view" style="height: 20vh">

        @foreach ($errors->all() as $error)
            <h1> ERROR: {{ $error }} </h1>
        @endforeach

        <button onclick="loadDocumentationPage()"> Recover previous session </button>

        <form method="post" class="documentationForm" id="form", action="{{ $documentationChapter->id ? route('documentation.update', ['id' => $documentationChapter->id]) : route('documentation.store') }}">
            @csrf

            <div class="documentation_chapter_holder">


                <div id="toolbar">
                    <select class="ql-size">
                    <option value="small"></option>
                    <option selected value="normal"></option>
                    <option value="large"></option>
                    </select>

                    <button class="ql-bold"></button>
                    <button class="ql-italic"></button>

                </div>

                <label for="title"> Titel </label>
                <input type="text" name="title" id="title_input" value="{{ old('title', $documentationChapter->title) }}" required>

                <input type="hidden" name="body" id="body_input" value="{{ old('body', $documentationChapter->body) }}">

                <div id="body_text">{!! old('body', $documentationChapter->body)  !!}</div>
            </div>
            <li id="subchapter_list">
                @if(isset($subchapters))
                    @foreach ($subchapters as $subchapter)
                        <div class="subchapter_holder" style="margin-top: 20vh;" >
                            <div class="sub_toolbar">
                                <select class="ql-size">
                                <option value="small"></option>
                                <option selected value="normal"></option>
                                <option value="large"></option>
                                </select>

                                <button class="ql-bold"></button>
                                <button class="ql-italic"></button>

                            </div>

                            <input type="text" class="subchapter_title_input" name="sub_title[]" placeholder="Subchapter title" style="margin-top: 2vh; margin-left: 2vw; visibility:" value="{{ $subchapter->title }}">
                            <input class="subchapter_editor" type="hidden" name="sub_body[]" placeholder="desc..." style="margin-top: 2vh; margin-left: 2vw;" value="{{ $subchapter->body }}">
                            <input type="hidden" name="sub_id[]" value="{{ $subchapter->id }}">

                            <div class="subchapter_editor">
                            </div>
                        </div>
                    @endforeach
                @endif
            </li>

            <div class="button-holder">
                <a href="{{ route('documentation.index') }}" onclick="cancel(this.dataset)" class="styled-button cancel">Annuleren</a>
                @if ($documentationChapter->id)
                    <input type="button" data-href="{{ route('documentation.delete', ['id' => $documentationChapter->id]) }}" onclick="deleteChapter(this.dataset)" class="styled-button cancel" value="Verwijderen">
                @endif
                <input class="styled-button save" data-save-button type="submit"  onclick="localStorage.clear()" value="Opslaan">
            </div>
        </form>

        <button style="bottom: 1px; position: absolute;" type="button" onclick="addSubChapter()"> new subchapter </button>
    </div>
@stop
