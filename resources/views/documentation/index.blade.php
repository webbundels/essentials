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
                <a id="new_chapter_button" class="styled-button" href="{{ route('documentation.create') }}"> Nieuw hoofdstuk </a>

                {{-- <button class="styled-button" data-edit-button>Volgorde wijzigen</button>
                <div class="styled-button cancel" data-cancel-button>Annuleren</div> --}}

                <input class="styled-button save" data-save-button type="submit" value="Opslaan">
            </div>
        @endif

        <div id="table_of_contents">
            @foreach ($documentationChapters as $documentationChapter)
                <a href="#chapter-{{ Str::of($documentationChapter->title)->slug('-') }}">{{ $documentationChapter->title }}</a>

                @foreach ($documentationChapter->subchapters as $subchapter)
                    <a href="#subchapter-{{ Str::of($subchapter->title)->slug('-') }}" style="font-size: 60%; margin-top: -1vh; margin-left: 2vw;"> {{ $subchapter->title }} </a>
                @endforeach
            @endforeach
        </div>

        <div data-documentation-container class="documentation-container">
            @foreach ($documentationChapters as $documentationChapter)
                <div class="documentation-chapter" id="chapter-{{ Str::of($documentationChapter->title)->slug('-') }}">
                    <h2 class="edit-title">{{ $documentationChapter->title }}</h2>
                    <a href="{{ route('documentation.edit', ['id' => $documentationChapter->id]) }}" class="title-button">Wijzigen</a>
                    {!! $documentationChapter->body !!}


                    @foreach ($documentationChapter->subchapters->sortBy('sequence') as $subchapter)
                        <hr>

                        <div id="subchapter-{{ Str::of($subchapter->title)->slug('-') }}" class="subchapter_display" style="margin-left: 2.5vw;">
                            <a href="{{ route('subchapter.edit', ['id' => $subchapter->id]) }}" style="margin-left: 70vw;">Edit</a>

                                @if (Auth::user()->documentationEditable)
                                {{-- This form is for moving subchapters up and down --}}
                                <form  method="post"  id="move_form", action="{{ route('subchapter.change_order', ['id' => $subchapter->id ]) }}">
                                    @csrf
                                        @if ($loop->first)
                                            <button name="new_move" type="submit" value="1"> Down </button>
                                        @elseif ($loop->last)
                                            <button name="new_move" type="submit" value="-1"> Up </button>
                                        @else
                                            <button name="new_move" type="submit" value="-1"> Up </button>
                                            <button name="new_move" type="submit" value="1"> Down </button>
                                        @endif

                                        <input type="hidden" name="documentation_id" value={{ $documentationChapter->id }}>
                                        <input type="hidden" name="subchapter_id" value={{ $subchapter->id }}>
                                        <input type="hidden" name="current_sequence" value={{ $subchapter->sequence }}>
                                </form>
                                @endif
                            <h2> {{ $subchapter->title }} </h2>

                            {!! $subchapter->body !!}
                        </div>
                    @endforeach
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
