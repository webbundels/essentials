<?php

namespace Webbundels\Essentials\Http\Controllers;

use Illuminate\Support\Arr;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\View;
use Webbundels\Essentials\Models\DocumentationChapter;
use Webbundels\Essentials\Http\Requests\Documentation\EditDocumentationRequest;
use Webbundels\Essentials\Http\Requests\Documentation\ViewDocumentationRequest;
use Webbundels\Essentials\Http\Requests\Documentation\StoreDocumentationRequest;
use Webbundels\Essentials\Http\Requests\Documentation\CreateDocumentationRequest;
use Webbundels\Essentials\Http\Requests\Documentation\DeleteDocumentationRequest;
use Webbundels\Essentials\Http\Requests\Documentation\UpdateDocumentationRequest;
use Webbundels\Essentials\Http\Requests\Documentation\ChangeOrderDocumentationRequest;
use Webbundels\Essentials\Models\SubChapter;

class DocumentationController extends Controller
{
    public function __construct()
    {
        View::share('section', 'documentation');
    }

    public function index(ViewDocumentationRequest $request)
    {
        $documentationChapters = DocumentationChapter::all();

        return view('EssentialsPackage::documentation.index', compact('documentationChapters'));
    }

    public function create(CreateDocumentationRequest $request)
    {
        $documentationChapter = new DocumentationChapter;

        return view('EssentialsPackage::documentation.edit', compact('documentationChapter'));
    }


    public function changeOrder(ChangeOrderDocumentationRequest $request) {

        $documentationChapters = DocumentationChapter::all();

        $new_sequence = (int) $request['current_sequence'] + (int) $request['new_move'];

        foreach($documentationChapters as $documentationChapter) {

                if ($documentationChapter->id == (int) $request['documentation_id']) {
                    $documentationChapter->sequence = $new_sequence; //1 -> 2
                    $documentationChapter->timestamps = false;
                    $documentationChapter->save();

                } else if ($documentationChapter->sequence == $new_sequence) { //2
                    $documentationChapter->sequence = (int) $request['current_sequence'];
                    $documentationChapter->timestamps = false;
                    $documentationChapter->save(); // 2 -> 1
                }
        }

        return redirect()
            ->route('documentation.index', ["#chapter-".$documentationChapter::find($request['documentation_id'])->title, "enable_moving" => true]);

    }

    //Converts a request form to an array with subchapter items
    //Note: these ARE NOT actuall subchapter model but they do share the same structure
    private function parseSubchapterInfo($data) {
        $subchapters = [];
        if (array_key_exists('sub_title', $data)) {
            $subchapter_titles = $data['sub_title'];
            for ($i=0; $i<count($subchapter_titles); $i++) {
                $subchapters[$i] = [
                                    'title' => $data['sub_title'][$i],
                                    'body' => $data['sub_body'][$i],
                                    'id' => (int) $data['sub_id'][$i],
                                    'sequence' => $i
                ];
                // The subchapter goes as follows:
                // Title
                // body
                // id
                // sequence
                // Note: the subchapter also needs a documentation_id but that is outside of this functions scope.
            }
        }

        return $subchapters;
    }

    //updates a subchapter model.
    // If the subchapter doenst exists yet it creates a new once.
    private function storeOrUpdateSubchapters($documentationChapterId, $subchapters) {


        foreach ($subchapters as $subchapter) {
            // If our subchapter already exists then we need a new one.
            // Otherwise just update the current one.
            if ($subchapter['id'] == -1) {
                $subchapter['documentation_chapter_id'] = $documentationChapterId;

                $new_subchapter = new SubChapter();
                $new_subchapter->fill($subchapter);

                $new_subchapter->save();
            } else if ($subchapter['id'] > -1) {
                // Do not update the Id since its a '-1'
                SubChapter::where('id', $subchapter['id'])->update(Arr::except($subchapter, ['id']));
            }

        }
    }

    public function store(StoreDocumentationRequest $request)
    {
        $data = $request->all();
        $data['sequence'] = DocumentationChapter::max('sequence')+1;
        $subchapters = $this->parseSubchapterInfo($data);

        $documentationChapter = new DocumentationChapter();
        $documentationChapter->fill($data);
        $documentationChapter->save();

        $this->storeOrUpdateSubchapters($documentationChapter->id, $subchapters);

        return redirect()
            ->route('documentation.index');
    }




    public function edit(EditDocumentationRequest $request, $id)
    {
        $documentationChapter = DocumentationChapter::find($id);
        $subchapters = $documentationChapter->subChapters;

        return view('EssentialsPackage::documentation.edit', compact('documentationChapter', 'subchapters'));
    }

    public function update(UpdateDocumentationRequest $request, $id)
    {

        DocumentationChapter::where('id', $id)->update(Arr::except($request->all(), ['_token', 'sub_body', 'sub_title', 'sub_id']));
        $subchapters = $this->parseSubchapterInfo($request->all());
        $this->storeOrUpdateSubchapters($id, $subchapters);


        return redirect()
            ->route('documentation.index');
    }

    public function delete(DeleteDocumentationRequest $request, $id)
    {
        DocumentationChapter::destroy($id);

        return redirect()
            ->route('documentation.index');
    }
}
