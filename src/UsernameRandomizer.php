<?php

namespace Iliain\ZooIDs;

/**
 * Class UsernameRandomizer
 * @package Iliain\ZooIDs
 */
class UsernameRandomizer
{
    /**
     * Generates a random username comprised of multiple adjectives and one noun
     * @param $seed             string|int|null     The seed to use for the randomization, defaults to current time
     * @param $numAdjectives    int                 The number of adjectives to use in the username
     * @param $delimiter        string              The delimiter to use between the adjectives and the noun
     * @param $caseStyle        string              The case style to use for the username
     * @return string
     */
    public static function generateID($seed = null, $numAdjectives = 2, $delimiter = '', $caseStyle = 'titlecase')
    {
        if (!$seed) {
            $seed = (string)strtotime('now');
        }

        // Seed the random number generator with the provided seed
        $seed = crc32((string)$seed);
        srand($seed);

        $result = '';

        $fullOptions = [
            'numAdjectives' => $numAdjectives,
            'delimiter'     => $delimiter,
            'caseStyle'     => $caseStyle,
        ];

        include __DIR__ . '/RandomizerArrays.php';
        
        // Generate Adjectives
        for ($i = 0; $i < $numAdjectives; $i++) {
            $adjective = self::getRandomElement($adjectives);
            $result .= self::getFormattedWord($adjective, $fullOptions);
            $result .= $delimiter;
        }
        
        // Generate Noun
        $animal = self::getRandomElement($nouns);
        $result .= self::getFormattedWord($animal, $fullOptions);

        // If camelcase, lowercase the first letter
        if ($caseStyle === 'camelcase') {
            return strtolower($result[0]) . substr($result, 1);
        }

        return $result;
    }

    /**
     * Generates a random number between 0 and the length of the provided array, then selects the word at that index
     * @param $word  string The word to format
     * @param $words array  The array of words to select from
     */
    public static function getRandomElement($words = []) 
    {
        $index = rand(0, count($words));
        return $words[$index];
    }
    
    /**
     * Formats the provided word according to the provided options
     * @param $word string
     * @param $options array
     */
    public static function getFormattedWord($word, $options) 
    {
        if (strpos($word, '-') !== false) {
            $wordArray = explode('-', $word);
            $wordArray = array_map(function($word) use ($options) {
                return self::getFormattedWord($word, $options);
            }, $wordArray);

            return implode('-', $wordArray);
        }
        switch ($options['caseStyle']) {
            case 'titlecase':
            case 'camelcase':
                return strtoupper($word[0]) . substr($word, 1);
            case 'uppercase':
                return strtoupper($word);
            case 'togglecase':
                return self::getToggleCaseWord($word);
            case 'lowercase':
            default:
                return $word;
        }
    }
    
    /**
     * @param $word string
     */
    public static function getToggleCaseWord($word) 
    {
        $wordArray = str_split($word);
        $wordArray = array_map(function($letter, $index) {
            return $index % 2 === 0 ? strtoupper($letter) : strtolower($letter);
        }, $wordArray);

        return implode('', $wordArray);
    }
}
