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

        // $subchapters = $documentationChapters->first()->subChapters;
        // dd($subchapters);


        return view('EssentialsPackage::documentation.index', compact('documentationChapters'));
    }

    public function create(CreateDocumentationRequest $request)
    {
        $documentationChapter = new DocumentationChapter;

        return view('EssentialsPackage::documentation.edit', compact('documentationChapter'));
    }

    private function validateSubchapterInfo($data) {
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
            }
        }

        return $subchapters;
    }

    private function storeOrUpdateSubchapters($documentationChapterId, $subchapters) {


        foreach ($subchapters as $subchapter) {

            if ($subchapter['id'] == -1) {
                $subchapter['documentation_chapter_id'] = $documentationChapterId;

                $new_subchapter = new SubChapter();
                $new_subchapter->fill($subchapter);

                $new_subchapter->save();
            } else {
                SubChapter::where('id', $subchapter['id'])->update(Arr::except($subchapter, ['id']));
            }

        }
    }

    public function store(StoreDocumentationRequest $request)
    {
        $data = $request->all();
        $data['sequence'] = DocumentationChapter::max('sequence')+1;
        $subchapters = $this->validateSubchapterInfo($data);

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
        $subchapters = $this->validateSubchapterInfo($request->all());
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
