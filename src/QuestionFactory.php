<?php
namespace TriviaGame;
use Symfony\Component\Yaml\Yaml;
use Symfony\Component\Yaml\Exception\ParseException;
use TriviaGame\Question;

class QuestionFactory
{
    private function __construct(){}
    
    public static function generateQuestion(string $questionFilePath)
    {
        $questionObjs = [];
        try {
            $ymlData = Yaml::parse(file_get_contents($questionFilePath));
        } catch (ParseException $e) {
            printf("Unable to parse the YAML string: %s", $e->getMessage());
        }
        
        foreach ($ymlData['questions'] as $question) {
            $questionObjs[] = new Question($question);
        }
        return $questionObjs;
    }
}

