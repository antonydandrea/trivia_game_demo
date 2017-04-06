<?php
require_once 'bootstrap.php';
use Symfony\Component\Console\Application;
use TriviaGame\GameCommand;
use TriviaGame\QuestionFactory;

$questions = QuestionFactory::generateQuestion('questions.yml');

$trivia_app = new Application();
$trivia_app->add(new GameCommand($questions));
$trivia_app->run();