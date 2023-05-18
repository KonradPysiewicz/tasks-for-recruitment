<?php

$destinationFilePath = 'txtFiles/replacedFile.txt';

$sourceText = file_get_contents('txtFiles/sourceFile.txt');

$lines = explode("\n", $sourceText);
$replacedLines = array();

foreach ($lines as $line){
    $replacedWords = [];
    $words = explode(" ", $line);

    foreach ($words as $word){
        $firstLetter = mb_substr($word, 0, 1);

        if(str_ends_with($word, ',') || str_ends_with($word, '.')){
            $lastLetter = mb_substr($word, -2, 2);
            $middle = mb_substr($word, 1, -2);
        }
        else{
            $lastLetter = mb_substr($word, -1, 1);
            $middle = mb_substr($word, 1, -1);
        }

        $characters = preg_split('//u', $middle);
        shuffle($characters);
        $middle = implode('', $characters);

        if (strlen($word) === 1){
            $replacedWord = $firstLetter;
        }
        else{
            $replacedWord = $firstLetter . $middle . $lastLetter;
        }
        $replacedWords[] = $replacedWord;
    }
    $replacedLines[] = implode(' ', $replacedWords);
}

$endResult = implode("\n", $replacedLines);

file_put_contents($destinationFilePath, $endResult);