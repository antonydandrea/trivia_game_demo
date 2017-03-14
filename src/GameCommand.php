<?php
namespace TriviaGame;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Question\ConfirmationQuestion;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\Console\Question\ChoiceQuestion;
use Symfony\Component\Console\Formatter\OutputFormatterStyle;
use Symfony\Component\Console\Style\SymfonyStyle;

class GameCommand extends Command
{
    private $questions;
    
    public function __construct($questions)
    {
        $this->questions = $questions;
        parent::__construct(null);
    }
    
    protected function configure()
    {
        $this->setName('game:play')
        ->setDescription('Starts a new game.')
        ->setHelp('Start a new game.');
    }
    
    protected function setStyles(OutputInterface $output)
    {
        $style = new OutputFormatterStyle('green', 'default', ['bold']);
        $output->getFormatter()->setStyle('success', $style);
        
        $style = new OutputFormatterStyle('red', 'black', ['blink', 'bold']);
        $output->getFormatter()->setStyle('wrong', $style);
        
        $style = new OutputFormatterStyle('yellow', 'black', ['blink', 'bold']);
        $output->getFormatter()->setStyle('correct', $style);
        
        $style = new OutputFormatterStyle('white', 'blue', []);
        $output->getFormatter()->setStyle('dialog', $style);
        
        $style = new OutputFormatterStyle('cyan', 'default', []);
        $output->getFormatter()->setStyle('question', $style);
        
        $style = new OutputFormatterStyle('magenta', 'default', []);
        $output->getFormatter()->setStyle('quiz_question', $style);
    }
    
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);
        $this->setStyles($output);
        $score = 0;
        $questionMaster = $this->getHelper('question');
        $question = new Question('<question>Please enter your name: </question>');
        $name = '';
        while (empty($name)) {
            $name = $questionMaster->ask($input, $output, $question);
        }
        $output->writeln('<dialog>Hello, '.$name.'!</dialog>');
        $io->newLine();
        $output->writeln('<dialog>I will ask you some questions. For each one, answer A, B, C or D.</dialog>');
        $question = new ConfirmationQuestion('<question>Are you ready to begin (type "yes" if you are)? </question>', false, '/^(yes)/i');
        $begin = false;
        while (!$begin) {
            $begin = $questionMaster->ask($input, $output, $question);
        }
        $io->newLine();
        shuffle($this->questions);
        foreach ($this->questions as $questionNumber => $question) {
            $isFinal = false;
            while (!$isFinal) {
                $answer = null;
                $getAnswer = new ChoiceQuestion(
                    ['<quiz_question>', 'Question '.($questionNumber + 1), $question->question, '</quiz_question>'], 
                    [
                        'A' => $question->option_A,
                        'B' => $question->option_B,
                        'C' => $question->option_C,
                        'D' => $question->option_D,
                    ], 
                null);
                $answer = $questionMaster->ask($input, $output, $getAnswer);
                $getIsFinal = new ConfirmationQuestion('<question>Is '.$answer.' your final answer (type "yes" if it is)? </question>', false, '/^(yes)/i');
                $isFinal = $questionMaster->ask($input, $output, $getIsFinal);
            }
            $io->newLine();
            if (strtolower($answer) == strtolower($question->answer_letter)) {
                $output->writeln('<correct>Correct!</correct>');
                $score ++;
            } else {
                $output->writeln('<wrong>Wrong! The correct answer was '.$question->answer_letter.'</wrong>');
            }
        }
        $io->newLine();
        $output->writeln('<dialog>Your final score is '.$score.' out of '.count($this->questions).'!</dialog>');
    }
}