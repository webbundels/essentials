@extends('EssentialsPackage::layout')

@section('body_id', 'index')

@section('content')
    <div id="header_holder">
        <div class="header">
            <h1>
                {{ config('app.name') }}<br/>
                change log
            </h1>

        </div>
    </div>

    <div id="changelog_holder">
        @if (Auth::user()->changelog_§)
            <div class="button-holder">
                <a id="new_chapter_button" class="styled-button" href="{{ route('changelog.create') }}">Nieuw hoofdstuk</a>
            </div>
        @endif

        <div data-changelog-container class="changelog-container">
            @foreach ($sorted_items->all() as $index => $item)

                <h2> Commits: {{ count($item['commits']) }} </h2>

                <div id="commit-holder-{{$index}}" style="display: none;">
                @foreach($item['commits']->all() as $commit)
                    <div class="commit" style="margin-left: 4vw; font-style: italic; display: inherit;">

                        <h4 class="edit-title">{{ $commit->commiter }} </h2>
                        <h6 class="version-title"> versie: {{ $commit->message }} </h4>

                        <h5 class="date-title"> Gemaakt op: {{ $commit->created_at->format('d/m/y h:m') }} </h3>
                        <hr>
                    </div>
                @endforeach
                </div>


                <a id="toggle-button-{{$index}}" onclick="toggleCommits({{$index}})"> show </a>

                @if($item['changelog'] != null)
                    <div class="changelog-chapter">
                        <h2 class="edit-title">{{ $item['changelog']->title }} </h2>
                        <h4 class="version-title"> versie: {{ $item['changelog']->version }} </h4>

                        <h3 class="date-title"> Gemaakt op: {{ $item['changelog']->created_at->format('d/m/y h:m') }} </h3>

                        <a href="{{ route('changelog.edit', ['id' => $item['changelog']->id]) }}" class="title-button">Wijzigen</a>
                    </div>
                @else
                    <hr>
                @endif

            @endforeach
        </div>

        <div class="switch-button-holder">
            <a class="styled-button" href="documentatie">Documentation</a>
        </div>
    </div>

@stop

@section('scripts')
@stop
