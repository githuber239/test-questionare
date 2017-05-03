<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Answer extends Model
{
	protected $table = 'answers';
    protected $fillable = ['answer_in_question_id', 'question_id'];
}
