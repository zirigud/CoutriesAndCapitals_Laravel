<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\Crypt;

class MainController extends Controller
{
    private $app_data;

    public function __construct()
    {
        //load app data from config file

        $this->app_data = require(app_path('app_data.php'));
    }

    public function startGame(): View{
        return view('home');
    }

    public function prepareGame(Request $request){
        //validate request

        $request->validate([
            'total_questions' =>'required|integer|min:3|max:30',
        ],
        [
            'total_questions.required' =>'O número de perguntas é obrigatório.',
            'total_questions.integer' =>'O número tem que ser um valor inteiro',
            'total_questions.min' =>'O número minimo de perguntas é 3.',
            'total_questions.max' =>'O número máximo de perguntas é 30.',
        ]

        );
        //get total questions

        $total_questions = intval($request->input('total_questions'));

        //prepare all the quiz structure

        $quiz = $this->prepareQuiz($total_questions);

        //store the quiz in session
        session()->put([
        'quiz' => $quiz,
        'total_questions' => $total_questions,
        'current_question' => 1,
        'correct_answers' => 0,
        'wrong_answers' => 0,
        ]);
        //redirect to the first question

        return redirect()->route('game');

    }

    private function prepareQuiz($total_questions){
        $questions = [];
        $total_countries = count($this->app_data);


    //create countries index for unique questions
    $indexes = range(0,$total_countries -1);
    shuffle($indexes);
    $indexes = array_slice($indexes,0,$total_questions);

    // create array of questions

    $question_number = 1;
    foreach($indexes as $index){
        $question['question_number'] = $question_number++;
        $question['country'] = $this->app_data[$index]['country'];
        $question['correct_answer'] = $this->app_data[$index]['capital'];

        //wrong answers

        $other_capitals = array_column($this->app_data,'capital');

        //remove correct answer from wrong answers array
        $other_capitals = array_diff($other_capitals,[$question['correct_answer']]);
        //shuffle the wrong answers
        shuffle($other_capitals);
        $question['wrong_answers'] = array_slice($other_capitals,0,3);
        //store answer result
        $question['correct']=null;

        $questions[] = $question;

    }
    //return quiz structure
    return $questions;

    }
    public function game():View{

        $quiz = session('quiz');
        $total_questions = session('total_questions');
        $current_question = session('current_question') -1;

        //prepare answers
        $answers = $quiz[$current_question]['wrong_answers'];
        $answers[] = $quiz[$current_question]['correct_answer'];
        shuffle($answers);
        //render game view
        return view('game')->with(['country' => $quiz[$current_question]['country'],
                           'total_questions' => $total_questions,
                           'current_question' => $current_question,
                           'answers' => $answers]);
    }

    public function answer($enc_answer){

        try{
            $answer = Crypt::decryptString($enc_answer);
        }catch(\Exception $e)
        {
            return redirect()->route('game');
        }
        //game logic

        $quiz = session('quiz');
        $current_question = session('current_question')-1;
        $correct_answer = $quiz[$current_question]['correct_answer'];
        $correct_answers = session('correct_answers');
        $wrong_answers = session('wrong_answers');
        //check the answer
        if($answer == $correct_answer){
            //correct answer
            $quiz[$current_question]['correct'] = true;
            $correct_answers++;
        }else{
            //wrong answer
            $quiz[$current_question]['correct'] = false;
            $wrong_answers++;
        }
        //update session data
        session()->put([
            'quiz' => $quiz,
            'correct_answers' => $correct_answers,
            'wrong_answers' => $wrong_answers,
        ]);
        //prepare data to show correct answer
        $data = [
            'country' => $quiz[$current_question]['country'],
            'correct_answer' => $correct_answer,
            'choice_answer' => $answer,
            'current_question' => $current_question,
            'total_questions' => session('total_questions'),


        ];
        return view('answer_result')->with($data);
        //check if it was the last question
    }

    public function nextQuestion(){
        $current_question = session('current_question');
        $total_questions = session('total_questions');
        if($current_question < $total_questions){
            $current_question++;
            session()->put('current_question', $current_question);
            return redirect()->route('game');
        }else{
            return redirect()->route('show_results');
        }
    }

    public function showResults(){
        $total_questions = session('total_questions');

        return view('final_results')->with([
            'correct_answers' => session('correct_answers'),
            'wrong_answers' => session('wrong_answers'),
            'total_questions' => session('total_questions'),
            'percentage' => round(session('correct_answers') / session('total_questions') * 100, 2),
        ]);
    }
}
