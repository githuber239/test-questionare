<?php namespace App\Http\Controllers;


use App\Models\Answer;
use App\Models\Question;
use Illuminate\Pagination\PaginationServiceProvider;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class WelcomeController extends Controller
{


    public function getIndex()
    {
        $questions = Question::all();
        $result = [];
        foreach ($questions as $item)
        {
            $answers = Answer::where('question_id', '=', $item->id)->get();
            foreach ($answers as $answer)
            {
                $result[$item->id][] = [
                    'id' => $answer->answer_in_question_id,
                    'answer' => $answer->answer,
                ];
            }
        }

        $this->set('questions', $questions)->set('answers', $result);
    }

    public function postIndex()
    {
        $email = Input::get('email', '');
        $questions = Question::all();
        $answers = Input::get('answers', '');

        $my_data = [
            'email' => $email,
        ];
        $validator = Validator::make($my_data, [
            'email' => 'required|email',
        ]);
        if ($validator->fails()) {
            return Redirect::to('/')->with('error', 'Неверно указан e-mail');
        } else {
            Mail::send('welcome/mail', ['items' => $questions, 'answers' => $answers], function ($message) use ($email) {
                $message->to($email)->subject('Новый ответ');
            });
            return Redirect::to('/')->with('notice', 'Анкета отправлена на указанный e-mail');
        }


    }

}