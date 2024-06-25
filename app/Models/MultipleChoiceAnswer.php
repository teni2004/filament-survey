<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Answer;
use App\Models\Option;

class MultipleChoiceAnswer extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function answer()
    {
        return $this->belongsTo(Answer::class);
    }

    public function option()
    {
        return $this->belongsTo(Option::class);
    }
}
