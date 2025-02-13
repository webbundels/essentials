<?php


namespace Webbundels\Essentials\Models;
use Illuminate\Database\Eloquent\Model;

class SubChapter extends Model
{
    protected $fillable = [
        'title',
        'body',
        'sequence',
        'documentation_chapter_id'
    ];


}
