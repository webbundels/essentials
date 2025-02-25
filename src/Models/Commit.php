<?php

namespace Webbundels\Essentials\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Commit extends Model
{

    protected $fillable = [
        'commiter',
        'message',
        'repository',
        'url'
    ];
}
