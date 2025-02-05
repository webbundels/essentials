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

    public function store(StoreDocumentationRequest $request)
    {
        $data = $request->all();
        $data['sequence'] = DocumentationChapter::max('sequence')+1;


        $documentationChapter = new DocumentationChapter();
        $documentationChapter->fill($data);

        // $documentationChapter->body = str_replace('S^', '<h2 class="documentation-chapter" id="sub-chapter-', $documentationChapter->body);
        // $documentationChapter->body = str_replace('^S', '">', $documentationChapter->body);

        $documentationChapter->save();

        return redirect()
            ->route('documentation.index');
    }

    /* public function changeOrder(ChangeOrderDocumentationRequest $request)
    {
        $chapters = $request->get('chapters');

        foreach ($chapters as $index => $chapter) {
            $chapter['sequence'] = ($index+1);
            if (array_key_exists('id', $chapter)) {
                DocumentationChapter::where('id', $chapter['id'])->update(Arr::only($chapter, ['sequence']));
            }
        }

        return redirect()
            ->route('documentation.index');
    } */

    public function edit(EditDocumentationRequest $request, $id)
    {
        $documentationChapter = DocumentationChapter::find($id);

        return view('EssentialsPackage::documentation.edit', compact('documentationChapter'));
    }

    public function update(UpdateDocumentationRequest $request, $id)
    {
        DocumentationChapter::where('id', $id)->update(Arr::except($request->all(), ['_token']));

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
