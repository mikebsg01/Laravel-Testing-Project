<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Traits\Likeability;

class Post extends Model
{
   use Likeability;
}
