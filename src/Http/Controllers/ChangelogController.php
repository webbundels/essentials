<?php

namespace Webbundels\Essentials\Http\Controllers;

use Illuminate\Support\Arr;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\View;
use Webbundels\Essentials\Models\Commit;
use Webbundels\Essentials\Models\ChangelogChapter;
use Webbundels\Essentials\EssentialsServiceProvider;
use Webbundels\Essentials\Http\Requests\Changelog\EditChangelogRequest;
use Webbundels\Essentials\Http\Requests\Changelog\ViewChangelogRequest;
use Webbundels\Essentials\Http\Requests\Changelog\StoreChangelogRequest;
use Webbundels\Essentials\Http\Requests\Changelog\CreateChangelogRequest;
use Webbundels\Essentials\Http\Requests\Changelog\DeleteChangelogRequest;
use Webbundels\Essentials\Http\Requests\Changelog\UpdateChangelogRequest;

class ChangelogController extends Controller
{
    public function __construct()
    {
        View::share('section', 'Changelog');
    }

    // Gathers some commits info
    // Sends the following structure with the view:
    /* 'sorted_items' - array [
     *  'commit_count' - int,
     *  'changelog' - ChangelogChapter,
     *  'changelog_id' - int,
     *  'previous_id' - int (can be -1)
     * ]
     */
    public function index(ViewChangelogRequest $request)
    {

        //dd(explode(',', env('GITHUB_REPOS')));

        $changelogChapters = ChangelogChapter::get()->sortByDesc('release_date');
        $sorted_items = collect();
        $previous_chapter = null;

        foreach ($changelogChapters as $index => $chapter) {
            $commits_count = null;

            $previous_id = -1;

            $repos = explode(',', env('GITHUB_REPOSITORIES'));

            $commit_info = collect();

            foreach($repos as $repo) {

                $url = "https://github.com/".env("GITHUB_OWNER")."/";

                if (! $previous_chapter) {
                    $commits_count = Commit::where('created_at', '>', $chapter->release_date)->where('repository', '=', $repo)->count();
                    $url = "https://github.com/".env("GITHUB_OWNER")."/".$repo.'/commits/?since='.$chapter->created_at->format('Y-m-d');
                } else {
                    $previous_id = $previous_chapter->id;
                    $commits_count =  Commit::whereBetween('created_at', [$chapter->release_date, $previous_chapter->release_date])->where('repository', '=', $repo)->count();
                    $url = "https://github.com/".env("GITHUB_OWNER")."/".$repo.'/commits/?since='.$chapter->release_date->format('Y-m-d').'&until='.$previous_chapter->release_date->format('Y-m-d');
                }

                $commit_info->push([
                    'commit_repo' => $repo,
                    'commit_count' => $commits_count,
                    'changelog_id' => $chapter->id,
                    'previous_id' => $previous_id,
                    'url' => $url,
                ]);

            }

            $sorted_items->push([
                'commit_info' => $commit_info,
                'changelog' => $chapter,
                'changelog_id' => $chapter->id,
                'previous_id' => $previous_id
            ]);

            $previous_chapter = $chapter;

            if ($chapter == $changelogChapters->last()) {
                $repos = explode(',', env('GITHUB_REPOSITORIES'));

                $commit_info = collect();

                foreach($repos as $repo) {

                    $commits_count = Commit::where('created_at', '<', $chapter->release_date)->where('repository', '=', $repo)->count();
                    

                    $commit_info->push([
                        'commit_repo' => $repo,
                        'commit_count' => $commits_count,
                        'changelog_id' => $chapter->id,
                        'previous_id' => -2,
                        'url' => "https://github.com/".env("GITHUB_OWNER")."/".$repo.'/commits/?until='.$previous_chapter->release_date->format('Y-m-d')
                    ]);

                }
                $sorted_items->push([
                    'commit_info' => $commit_info,
                    'changelog' => null,
                    'changelog_id' => $chapter->id,
                    'previous_id' => -2
                ]);

            }
        }


        return view('EssentialsPackage::changelog.index', compact('sorted_items'));
    }


    public function create(CreateChangelogRequest $request)
    {
        $changelogChapter = new ChangelogChapter;

        return view('EssentialsPackage::changelog.create_edit', compact('changelogChapter'));
    }

    public function store(StoreChangelogRequest $request)
    {
        $changelogChapter = new ChangelogChapter();
        $changelogChapter->fill($request->all());
        $changelogChapter->save();

        return redirect()
            ->route('changelog.index');
    }

    public function edit(EditChangelogRequest $request, $id)
    {
        $changelogChapter = ChangelogChapter::find($id);

        return view('EssentialsPackage::changelog.create_edit', compact('changelogChapter'));
    }

    public function update(UpdateChangelogRequest $request, $id)
    {
        $changelogChapter = ChangelogChapter::find($id);
        $changelogChapter->update(Arr::except($request->all(), ['_token']));

        return redirect()
            ->route('changelog.index');
    }

    public function delete(DeleteChangelogRequest $request, $id)
    {
        ChangelogChapter::destroy($id);

        return redirect()
            ->route('changelog.index');
    }
}
