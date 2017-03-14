<?php
namespace TriviaGame;

class Question
{
    public $question;
    
    public $option_A;
    
    public $option_B;
    
    public $option_C;
    
    public $option_D;
    
    public $answer_letter;
    
    public function __construct($questionData) 
    {
        $this->question = $questionData['question'];
        $letters = ['A', 'B', 'C', 'D'];
        /** 
         * Keeps randomly selecting letters until there is one left.
         * Assigns the letters an answer from wrong_answers.
         * What ever letter remains becomes the "correct answer".
         */
        $wrongAnswerIndex = 0;
        while (count($letters) > 1) {
            $i = rand(0, count($letters) - 1);
            $this->{"option_".$letters[$i]} = $questionData['wrong_answers'][$wrongAnswerIndex];
            unset($letters[$i]);
            //reindex the numbers after.
            $letters = array_values($letters);
            $wrongAnswerIndex ++;
        }
        $this->{"option_".$letters[0]} = $questionData['answer'];
        $this->answer_letter = $letters[0];
    }
}