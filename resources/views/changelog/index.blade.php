@extends('EssentialsPackage::layout')

@section('body_id', 'index')

@section('content')
    <div id="header_holder">
        <div class="header">
            <a id="home_button" class="back-button" href="/">
                Terug naar de applicatie
            </a>
            <a class="switch-button" href="documentatie">
                Naar de documentatie
            </a>
            <h1>
                {{ config('app.name') }} changelog
            </h1>
        </div>
    </div>

    <div id="changelog_holder">

        <div class="button-holder">
            @if (Auth::user()->changelog_editable)
                <a id="new_chapter_button" class="styled-button" href="{{ route('changelog.create') }}">Nieuw hoofdstuk</a>
            @endif
        </div>

        <div data-changelog-container class="changelog-container">
            @foreach ($sorted_items->all() as $index => $item)

                <h2> Commits: {{ count($item['commits']) }} </h2>
                <a class="github-link" href="{{$item['URL']}}"> Show on Github </a>

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

                        <div class="changelog-body">
                            {!! $item['changelog']->body !!}
                        </div>
                        <a href="{{ route('changelog.edit', ['id' => $item['changelog']->id]) }}" class="title-button">Wijzigen</a>
                    </div>
                @else
                    <hr>
                @endif

            @endforeach
        </div>
    </div>

@stop

@section('scripts')
@stop
