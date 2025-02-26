<?php

namespace Webbundels\Essentials\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class ChangelogChapter extends Model
{
    public $timestamps = true;

    protected $fillable = ['body', 'version', 'release_date'];

    protected $casts = ['release_date' => 'datetime'];
}
