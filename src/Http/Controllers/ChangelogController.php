<?php

namespace Webbundels\Essentials\Http\Controllers;

use Illuminate\Support\Arr;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\View;
use Webbundels\Essentials\Models\ChangelogChapter;
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
    
    public function index(ViewChangelogRequest $request)
    {
        $changelogChapters = ChangelogChapter::orderByDesc('created_at')->get();

        return view('EssentialsPackage::changelog.index', compact('changelogChapters'));
    }

    public function create(CreateChangelogRequest $request)
    {
        $titles = ChangelogChapter::pluck('title');
        $changelogChapter = new ChangelogChapter;

        return view('EssentialsPackage::changelog.create_edit', compact('changelogChapter', 'titles'));
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
        $titles = ChangelogChapter::pluck('title');

        return view('EssentialsPackage::changelog.create_edit', compact('changelogChapter', 'titles'));
    }

    public function update(UpdateChangelogRequest $request, $id)
    {
        ChangelogChapter::where('id', $id)->update(Arr::except($request->all(), ['_token']));

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
