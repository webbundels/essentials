<?php

namespace Webbundels\Essentials\Http\Controllers;

use Illuminate\Support\Arr;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\View;

use Webbundels\Essentials\Models\SubChapter;

use Webbundels\Essentials\Http\Requests\Subchapter\EditSubchapterRequest;
use Webbundels\Essentials\Http\Requests\Subchapter\DeleteSubchapterRequest;
use Webbundels\Essentials\Http\Requests\Subchapter\UpdateSubchapterRequest;
use Webbundels\Essentials\Http\Requests\Subchapter\ChangeOrderSubchapterRequest;
use Webbundels\Essentials\Models\DocumentationChapter;

class SubchapterController extends Controller
{
    public function __construct()
    {
        View::share('section', 'subchapter');
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

    public function edit(EditSubchapterRequest $request, $id)
    {
        $subchapter = SubChapter::find($id);

        return view('EssentialsPackage::subchapters.edit', ['subchapter' => $subchapter]);
    }

    public function update(UpdateSubchapterRequest $request, $id)
    {
        SubChapter::where('id', $id)->update(Arr::except($request->all(), ['_token']));


        return redirect()
            ->route('documentation.index');
    }

    public function delete(DeleteSubchapterRequest $request, $id)
    {
        SubChapter::destroy($id);

        return redirect()
            ->route('documentation.index');
    }

    public function changeOrder(ChangeOrderSubchapterRequest $request, $id) {
        $subchapters = DocumentationChapter::find($request['documentation_id'])->subchapters->all();

        $new_sequence = (int) $request['current_sequence'] + (int) $request['new_move'];

        $is_outdated = false;
        foreach($subchapters as $subchapter) {
                if ($subchapter->id == (int) $request['subchapter_id']) {
                    $subchapter->sequence = $new_sequence;
                    $subchapter->save();
                    $is_outdated = true;

                } else if ($subchapter->sequence == $new_sequence) {
                    $subchapter->sequence = (int) $request['current_sequence'];
                    $subchapter->save();
                }
        }

        return redirect()
            ->route('documentation.index');
    }
}
