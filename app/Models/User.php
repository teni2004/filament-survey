<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Models\Survey;
use App\Models\SurveyResponse;
use App\Models\Team;
use Illuminate\Support\Facades\Auth;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    public function surveys()
    {
        return $this->hasMany(Question::class);
    }

    public function responses()
    {
        return $this->hasMany(SurveyResponse::class);
    }

    public function teams()
    {
        return $this->belongsToMany(Team::class);
    }

    public function assignedSurveyCount()
    {
        $surveys = null;

        $teams = Auth::user()->teams()->get(); 
        $surveyIds = [];

        foreach ($teams as $team) {
            foreach ($team->surveys as $survey) {
                if ($survey->published && !in_array($survey->id, $surveyIds)) {
                    $surveys[] = $survey;
                    $surveyIds[] = $survey->id;
                }
            }
        }

        if ($surveys !== null && !empty($surveys))
        {
            $responses = Auth::user()->responses()->get();
            foreach($responses as $response) {
                foreach($surveys as $survey) 
                {
                    if ($survey->id === $response->survey->id)
                    {
                        $key = array_search($survey, $surveys);
                        if ($key !== false) {
                            // Remove the element
                            unset($surveys[$key]);
                        }
                    }
                }
            }
        }
        $returnVal = ($surveys !== null ? count($surveys) : null);
        return $returnVal;
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
}
