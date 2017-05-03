<?php
namespace App\Http\Controllers;

use Illuminate\Support\Facades\Input;
use App;
use App\Models\Question;
use App\Models\Answer;
use Illuminate\Support\Facades\Redirect;

class ContentController extends Controller
{

    public function getIndex()
    {

        if (!\Auth::user())
        {
            App::abort(404);
        }

        if (!$this->user->is_admin)
        {
            return redirect('/');
        }
    
        $questions = Question::all();

        $this->set('questions', $questions);
    }

    public function getEdit($id = 0)
    {

        if (!\Auth::user())
        {
            App::abort(404);
        }

        if (!$this->user->is_admin)
        {
            return redirect('/');
        }

        if ($id)
        {
            $question = Question::find($id);
            $answers = Answer::where('question_id', '=', $id)->get();
            $this->set('question', $question)->set('answers', $answers);
        }

    }

    public function postEdit($id = 0)
    {

        if (!\Auth::user())
        {
            App::abort(404);
        }

        if (!$this->user->is_admin)
        {
            return redirect('/');
        }

        if ($id) $question = Question::find($id);
            else $question = new Question;

        $question->question = Input::get('question', '');
        $question->save();

        Answer::where('question_id', '=', $question->id)->delete();

        $answers = Input::get('answers', null);
        $index = 0;

        foreach ($answers as $item)
        {
            if ($item != null)
            {
                $index++;
                $answer = Answer::firstOrCreate(['answer_in_question_id' => $index, 'question_id' => $question->id]);
                $answer->answer = $item == null ? '' : $item;
                $answer->answer_in_question_id = $index;
                $answer->question_id = $question->id;
                $answer->save();
            }
        }

        return Redirect::to('content/edit/'.$question->id)->with('notice', 'Сохранено');
    }

    public function getDelete($id)
    {
        $question = Question::find((int)$id);

        if (!\Auth::user())
        {
            App::abort(404);
        }

        if (!$this->user->is_admin)
        {
            return redirect('/');
        }

        $this->set('question', $question);
    }

    public function postDelete($id)
    {
        $question = Question::find((int)$id);

        if (!\Auth::user())
        {
            App::abort(404);
        }

        if (!$this->user->is_admin)
        {
            return redirect('/');
        }

        $answers =  Answer::where('question_id', '=', $id)->get();
        foreach ($answers as $item)
        {
            $item->delete();
        }

        $question->delete();

        return redirect('content')->with('notice', 'Вопрос успешно удалён.');
    }

}