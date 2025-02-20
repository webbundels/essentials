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
        View::share('section', 'changelog');
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
        $changelogChapters = ChangelogChapter::get()->sortByDesc('created_at');
        $sorted_items = collect();
        $previous_chapter = null;

        foreach ($changelogChapters as $index => $chapter) {
            $commits_count = null;

            $previous_id = -1;

            if (! $previous_chapter) {
                $commits_count = Commit::where('created_at', '>', $chapter->created_at)->count();
            } else {
                $previous_id = $previous_chapter->id;
                $commits_count =  Commit::whereBetween('created_at', [$chapter->created_at, $previous_chapter->created_at])->count();
            }

            $sorted_items->push([
                'commit_count' => $commits_count,
                'changelog' => $chapter,
                'changelog_id' => $chapter->id,
                'previous_id' => $previous_id
            ]);

            $previous_chapter = $chapter;
        }


        return view('EssentialsPackage::changelog.index', compact('sorted_items'));
    }


    public function create(CreateChangelogRequest $request)
    {
        $titles = ChangelogChapter::pluck('title');
        $changelogChapter = new ChangelogChapter;

        return view('EssentialsPackage::changelog.create_edit', compact('changelogChapter', 'titles'));
    }

    // Note the created_at is filled in manually.
    public function store(StoreChangelogRequest $request)
    {
        $changelogChapter = new ChangelogChapter();
        $changelogChapter->fill($request->all());
        $changelogChapter->save();

        $changelogChapter->created_at = $request->all()['created_at'];
        $changelogChapter->save();

        return redirect()
            ->route('changelog.index');
    }

    public function edit(EditChangelogRequest $request, $id)
    {
        $changelogChapter = ChangelogChapter::find($id);
        $titles = ChangelogChapter::pluck('title');

        return view('EssentialsPackage::changelog.create_edit', compact('changelogChapter', 'titles'));
    }

    public function update(UpdateChangelogRequest $request, $id)
    {
        $changelogChapter = ChangelogChapter::find($id);
        $changelogChapter->update(Arr::except($request->all(), ['_token']));

        $changelogChapter->created_at = $request->all()['created_at'];
        $changelogChapter->save();

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
