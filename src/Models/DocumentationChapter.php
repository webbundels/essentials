<?php

namespace Webbundels\Essentials\Models;

use Webbundels\Essentials\Models\SubChapter;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class DocumentationChapter extends Model
{
    public $timestamps = true;

    protected $fillable = ['title', 'body', 'sequence'];

    protected static function booted()
    {
        static::addGlobalScope('default_order', function (Builder $builder) {
            $builder->orderBy('sequence');
        });
    }

    public function subChapters() {
        return $this->hasMany(SubChapter::class);
    }
}
