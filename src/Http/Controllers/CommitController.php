<?php

namespace Webbundels\Essentials\Http\Controllers;

use Illuminate\Support\Arr;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\View;
use Webbundels\Essentials\Http\Requests\Commit\GetCommitRequest;
use Webbundels\Essentials\Models\Commit;


class CommitController extends Controller
{
    public function __construct()
    {
        View::share('section', 'commit');
    }

    public function get(GetCommitRequest $request) {


        return "{ \"Hello world\" }";
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
