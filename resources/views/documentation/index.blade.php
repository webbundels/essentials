@extends('EssentialsPackage::layout')

@section('body_id', 'index')

@section('title', 'documentation')

@section('content')

    @if (Auth::user()->documentation_editable)
        <div class="button-holder">
            <a id="new_chapter_button" class="styled-button" href="{{ route('documentation.create') }}">
                Nieuw hoofdstuk
            </a>
            <a id="change-sequence" class="styled-button" onclick="toggleSequence()">Volgorde wijzigen</a>
        </div>
    @endif

    <div id="header_holder">
        <div class="header">
            <a id="home_button" class="back-button" href="/">
                Terug naar de applicatie
            </a>
            <a id="changelog_button" class="switch-button" href="{{route('changelog.index')}}">
                Naar het changelog
            </a>
            <h1>
                {{ config('app.name') }}<br/>
                documentatie
            </h1>
        </div>
    </div>

    <div id="documentation_holder">

        <div id="table_of_contents">
            @foreach ($documentationChapters as $documentationChapter)
                <a href="#chapter-{{ Str::of($documentationChapter->title)->slug('-') }}">{{ $documentationChapter->title }}</a>

                @foreach ($documentationChapter->subchapters as $subchapter)
                    <a class="subchapter" href="#subchapter-{{ Str::of($subchapter->title)->slug('-') }}"> {{ $subchapter->title }} </a>
                @endforeach
            @endforeach
        </div>

        <div class="side-content-table-holder">
            <div class="side-content-table">
                @foreach ($documentationChapters as $documentationChapter)
                    <div class="side-content-table-subject">
                        <a href="#chapter-{{ Str::of($documentationChapter->title)->slug('-') }}">
                            {{ $documentationChapter->title }}
                        </a>
                    </div>
                    @foreach ($documentationChapter->subchapters as $subchapter)
                        <div class="side-content-table-subject sub">
                            <a class="subchapter" href="#subchapter-{{ Str::of($subchapter->title)->slug('-') }}"> {{ $subchapter->title }} </a>
                        </div>
                    @endforeach
                @endforeach
            </div>
        </div>

        <div data-documentation-container class="documentation-container">
            @foreach ($documentationChapters->sortBy('sequence') as $documentationChapter)
                <div class="documentation-chapter" id="chapter-{{ Str::of($documentationChapter->title)->slug('-') }}">
                    <h2 class="edit-title">{{ $documentationChapter->title }}</h2>
                    <h3 class="last-updated" style="font-style: italic; color: gray;">{{ $documentationChapter->updated_at->format('d-m-Y H:i') }} </h3>
                    <a href="{{ route('documentation.edit', ['id' => $documentationChapter->id]) }}" class="title-button">Wijzigen</a>
                    @if(Auth::user()->documentationEditable)
                                {{-- This form is for moving subchapters up and down --}}
                                <form  method="post" class="move-form" style="display: none" id="move_form", action="{{ route('documentation.change_order') }}">
                                    @csrf

                                    @if ($loop->count > 1)
                                        @if ($loop->first)
                                            <button class="move-down-button" name="new_move" type="submit" value="1">
                                                Down
                                            </button>
                                        @elseif ($loop->last)
                                            <button class="move-up-button" name="new_move" type="submit" value="-1">
                                                Up
                                            </button>
                                        @else
                                            <button class="move-up-button" name="new_move" type="submit" value="-1">
                                                Up
                                            </button>
                                            <button class="move-down-button" name="new_move" type="submit" value="1">
                                                Down
                                            </button>
                                        @endif
                                    @endif

                                    <input type="hidden" name="documentation_id" value={{ $documentationChapter->id }}>
                                    <input type="hidden" name="current_sequence" value={{ $documentationChapter->sequence }}>
                                </form>
                    @endif

                    <div class="ql-editor ql-bubble documentation-chapter-holder"  style="white-space: normal">
                    {!! $documentationChapter->body !!}
                    </div>

                    @foreach ($documentationChapter->subchapters->sortBy('sequence') as $subchapter)
                        <div id="subchapter-{{ Str::of($subchapter->title)->slug('-') }}" class="subchapter_display">
                            <a class="title-button" href="{{ route('subchapter.edit', ['id' => $subchapter->id]) }}">
                                Wijzigen
                            </a>

                            @if (Auth::user()->documentationEditable)
                            {{-- This form is for moving subchapters up and down --}}
                            <form  method="post" class="move-form" style="display: none" id="move_form", action="{{ route('subchapter.change_order', ['id' => $subchapter->id ]) }}">
                                @csrf
                                    @if ($loop->count > 1)
                                        @if ($loop->first)
                                            <button name="new_move" type="submit" value="1">
                                                Down
                                            </button>
                                        @elseif ($loop->last)
                                            <button name="new_move" type="submit" value="-1">
                                                Up
                                            </button>
                                        @else
                                            <button name="new_move" type="submit" value="-1">
                                                Up
                                            </button>
                                            <button name="new_move" type="submit" value="1">
                                                Down
                                            </button>
                                        @endif
                                    @endif

                                    <input type="hidden" name="documentation_id" value={{ $documentationChapter->id }}>
                                    <input type="hidden" name="subchapter_id" value={{ $subchapter->id }}>
                                    <input type="hidden" name="current_sequence" value={{ $subchapter->sequence }}>
                            </form>
                            @endif
                            <h2 class> {{ $subchapter->title }} </h2>
                            <div class="ql-editor documentation-body-holder"  style="white-space: normal">
                            {!! $subchapter->body !!}
                            </div>
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
