@extends('EssentialsPackage::layout')

@section('body_id', 'index')

@section('content')

    <div class="button-holder">
        @if (Auth::user()->changelog_editable)
            <a id="new_chapter_button" class="styled-button" href="{{ route('changelog.create') }}">Nieuw hoofdstuk</a>
            <a id="refresh_commits_button" class="styled-button" href="{{ route('commit.refresh') }}"> Refresh Commits </a>
        @endif
    </div>

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

        <div data-changelog-container class="changelog-container">
            @foreach ($sorted_items as $index => $item)

                <h6> Commits: {{ $item['commit_count']}} </h1>

                <a id="toggle-button-{{$index}}" onclick="toggleCommits({{$index}}, {{$item['changelog_id']}}, {{$item['previous_id']}} )"> show </a>
                <div id="commit-holder-{{$index}}" style="display: none;">
                {{-- @foreach($item['commits'] as $commit)
                    <div class="commit" style="margin-left: 4vw; font-style: italic; display: inherit;">

                        <h4 class="edit-title">{{ $commit->commiter }} </h2>
                        <h6 class="version-title"> versie: {{ $commit->message }} </h4>

                        <h5 class="date-title"> Gemaakt op: {{ $commit->created_at->format('d-m-Y h:i') }} </h3>
                        <hr>
                    </div>
                @endforeach --}}
                </div>



                @if($item['changelog'] != null)
                    <div class="changelog-chapter">
                        <h2 class="edit-title">{{ $item['changelog']->title }} </h2>
                        <h4 class="version-title"> versie: {{ $item['changelog']->version }} </h4>

                        <h3 class="date-title"> Gemaakt op: {{ $item['changelog']->created_at->format('d-m-Y h:i') }} </h3>

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
