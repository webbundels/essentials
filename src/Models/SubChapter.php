<?php


namespace Webbundels\Essentials\Models;
use Illuminate\Database\Eloquent\Model;

class SubChapter extends Model
{
    protected $fillable = [
        'title',
        'body',
        'sequence',
        //Note: documentation_chapter_id is a forgein id for the DocumentationChapter model.
        'documentation_chapter_id'
    ];


}
