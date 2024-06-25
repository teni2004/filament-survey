<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Survey;
use App\Models\Option;
use App\Models\RatingOption;

class Question extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function survey()
    {
        return $this->belongsTo(Survey::class);
    }

    public function options()
    {
        return $this->hasMany(Option::class);
    }

    public function rating_options()
    {
        return $this->hasOne(RatingOption::class);
    }
}
