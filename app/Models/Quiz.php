<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\Question;
use App\Models\User;
use App\Models\Result;

class Quiz extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'description', 'minutes'];

    public function questions()
    {
        return $this->hasMany(Question::class);
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'quiz_user');
    }

    public function storeQuiz($data)
    {
        return Quiz::create($data);
    }

    public function allQuiz()
    {
        return Quiz::all();
    }

    public function editQuiz($data)
    {
        return Quiz::find($data);
    }

    public function updateQuiz($data)
    {
        return Quiz::find($data)->update();
    }

    public function deleteQuiz($data)
    {
        return Quiz::find($data)->delete();
    }

    public function assignExam($data)
    {
        $quizId = $data['quiz_id'];
        $quiz = Quiz::find($quizId);
        $userId = $data['user_id'];
        return $quiz->users()->syncWithoutDetaching($userId);
    }

    public function hasQuizAttempted()
    {
        $attemptQuiz = [];
        $authUser = auth()->user()->id;
        $user = Result::where('user_id', $authUser)->get();
        foreach ($user as $u) {
            array_push($attemptQuiz, $u->quiz_id);
        }
        return $attemptQuiz;
    }
}
