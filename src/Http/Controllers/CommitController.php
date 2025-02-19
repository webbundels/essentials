<?php

namespace Webbundels\Essentials\Http\Controllers;

use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\View;
use Webbundels\Essentials\Models\Commit;
use Webbundels\Essentials\Models\ChangelogChapter;
use Webbundels\Essentials\EssentialsServiceProvider;
use Webbundels\Essentials\Http\Requests\Commit\GetCommitRequest;


class CommitController extends Controller
{
    public function __construct()
    {
        View::share('section', 'commit');
    }

    public function get(GetCommitRequest $request) {

        $commits = collect();
        $data = $request->all();


        $chapter = ChangelogChapter::find($data["changelog_id"]);

        if ($data['previous_id'] == -1) {
            $commits = Commit::where('created_at', '>', $chapter->created_at)->get();
        } else {
            $previous_chapter = ChangelogChapter::find($data["previous_id"]);
            $commits =  Commit::whereBetween('created_at', [$chapter->created_at, $previous_chapter->created_at])->get();
        }

        return  [
            'commits' => $commits
        ];
    }

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
