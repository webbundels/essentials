@extends('EssentialsPackage::layout')

@section('body_id', 'index')

@section('content')

    <div class="button-holder">
        @if (Auth::user()->changelog_editable)
            <a id="new_chapter_button" class="styled-button" href="{{ route('changelog.create') }}">Nieuwe update </a>
            <a id="refresh_commits_button" class="styled-button" href="{{ route('commit.refresh') }}"> Refresh commits </a>
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

                @foreach($item['commit_info'] as $sub_index => $commit_info)
                    <div class="changelog-commit-holder" onclick="toggleCommits({{$index}}, {{$sub_index}}, {{$commit_info['changelog_id']}}, {{$commit_info['previous_id']}}, '{{ $commit_info['commit_repo'] }}' )">
                        <div class="changelog-commit-repo-name">
                            {{ $commit_info['commit_repo'] }}
                        </div>
                        <div class="changelog-commit-count">
                            <a href={{ $commit_info['url'] }}>
                                {{ $commit_info['commit_count']}} commits
                            </a>
                        </div>
                    </div>
                    <div id="commit-holder-{{$index}}-{{$sub_index}}" class="commits" style="display: none;"></div>
                @endforeach


                @if($item['changelog'] != null)
                    <div class="changelog-chapter">
                        {{-- <h2 class="edit-title">{{ $item['changelog']->title }} </h2> --}}
                        <h4 class="version-title"> versie: {{ $item['changelog']->version }} </h4>

                        <h3 class="date-title">{{ $item['changelog']->created_at->format('d-m-Y h:i') }} </h3>

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
