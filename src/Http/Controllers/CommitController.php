<?php

namespace Webbundels\Essentials\Http\Controllers;

use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\View;
use Webbundels\Essentials\Models\Commit;
use Webbundels\Essentials\Models\ChangelogChapter;
use Webbundels\Essentials\EssentialsServiceProvider;
use Webbundels\Essentials\Http\Requests\Commit\GetCommitRequest;

// CommitController only really has the refresh and get methods.
class CommitController extends Controller
{
    public function __construct()
    {
        View::share('section', 'commit');
    }

    // Gets all the commits and returns an array of them
        // The request should hold a:
        // - index (unused)
        // - changelog id
        // - previous id (can be -1 / null )
    public function get(GetCommitRequest $request) {

        $commits = collect();
        $data = $request->all();
        
        $chapter = ChangelogChapter::find($data["changelog_id"]);

        

        // If we dont have a previous id so we get all the commits that are younger than the changelog.
        if ($data['previous_id'] == -1) {
            $commits = Commit::where('created_at', '>', $chapter->created_at)->where('repository', '=', $data['repo'])->get();
        } else if ($data['previous_id'] == -2) {
            $commits = Commit::where('created_at', '<', $chapter->created_at)->where('repository', '=', $data['repo'])->get();
        }else {
            // Otherwise we get all the commits between the changelog and previous changelog
            $previous_chapter = ChangelogChapter::find($data["previous_id"]);
            $commits =  Commit::whereBetween('created_at', [$chapter->created_at, $previous_chapter->created_at])->where('repository', '=', $data['repo'])->get();
        }

        return  [
            'commits' => $commits
        ];
    }

    // Refresh the commits
    // Then redirects the user to the changelog's index
    public function refresh() {
        EssentialsServiceProvider::refreshCommits();

        return redirect()->to('changelog');
    }


    public function index( $request)
    {
            //unused
    }

    public function create( $request)
    {
        //unused
    }

    public function store( $request)
    {
        //unused
    }

    public function edit( $request, $id)
    {
    }

    public function update( $request, $id)
    {

    }

    public function delete( $request, $id)
    {
    }
}
