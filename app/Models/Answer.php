<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Question;
use App\Models\MultipleChoiceAnswer;
use App\Models\RatingAnswer;
use App\Models\YesNoAnswer;
use App\Models\SelectOneAnswer;
use App\Models\FreeFormAnswer;

class Answer extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function question()
    {
        return $this->belongsTo(Question::class);
    }

    public function rating_answer()
    {
        return $this->hasOne(RatingAnswer::class);
    }

    public function yes_no_answer()
    {
        return $this->hasOne(YesNoAnswer::class);
    }

    public function multiple_choice_answers()
    {
        return $this->hasMany(MultipleChoiceAnswer::class);
    }

    public function select_one_answer()
    {
        return $this->hasOne(SelectOneAnswer::class);
    }

    public function free_form_answer()
    {
        return $this->hasOne(FreeFormAnswer::class);
    }
}
