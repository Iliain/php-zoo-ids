<?php

namespace Iliain\ZooIDs;

/**
 * Class UsernameRandomizer
 * Generates a random username comprised of multiple adjectives and one noun. Can be customized with custom word lists.
 * @package Iliain\ZooIDs
 */
class UsernameRandomizer
{
    /**
     * @var array
     */
    static $adjectiveList;

    /**
     * @var array
     */
    static $nounList;

    /**
     * UsernameRandomizer constructor.
     * @param null|array $customAdjectives
     * @param null|array $customNouns
     */
    public function __construct($customAdjectives = null, $customNouns = null) 
    {
        include __DIR__ . '/RandomizerArrays.php';

        if ($customAdjectives === null) {
            self::$adjectiveList = $adjectiveArray;
        } else {
            self::$adjectiveList = $customAdjectives;
        }

        if ($customNouns === null) {
            self::$nounList = $nounArray;
        } else {
            self::$nounList = $customNouns;
        }
    }

    /**
     * @param array $customAdjectives
     */
    public function setAdjectives($customAdjectives): void 
    {
        self::$adjectiveList = $customAdjectives;
    }

    /**
     * @param array $customNouns
     */
    public function setNouns($customNouns): void 
    {
        self::$nounList = $customNouns;
    }

    /**
     * Generates a random username comprised of multiple adjectives and one noun
     * @param string|int|null $seed                 The seed to use for the randomization, defaults to current time
     * @param int $numAdjectives                    The number of adjectives to use in the username
     * @param string $delimiter                     The delimiter to use between the adjectives and the noun
     * @param string $caseStyle                     The case style to use for the username
     * @return string
     */
    public static function generateID($seed = null, $numAdjectives = 2, $delimiter = '', $caseStyle = 'titlecase'): string
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
        
        // Generate Adjectives
        for ($i = 0; $i < $numAdjectives; $i++) {
            $adjective = self::getRandomElement(self::$adjectiveList);
            $result .= self::getFormattedWord($adjective, $fullOptions);
            $result .= $delimiter;
        }
        
        // Generate Noun
        $animal = self::getRandomElement(self::$nounList);
        $result .= self::getFormattedWord($animal, $fullOptions);

        // If camelcase, lowercase the first letter
        if ($caseStyle === 'camelcase') {
            return strtolower($result[0]) . substr($result, 1);
        }

        return $result;
    }

    /**
     * Generates a random number between 0 and the length of the provided array, then selects the word at that index
     * @param string $word   The word to format
     * @param array $words   The array of words to select from
     */
    public static function getRandomElement($words = []): string 
    {
        $index = rand(0, count($words) - 1);
        return $words[$index];
    }
    
    /**
     * Formats the provided word according to the provided options
     * @param string $word 
     * @param array $options
     */
    public static function getFormattedWord($word, $options): string 
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
     * Toggles the case of each letter in the provided word. E.g. "hello" becomes "HeLlO"
     * @param $word string
     */
    public static function getToggleCaseWord($word): string 
    {
        $wordArray = str_split($word);
        $wordArray = array_map(function($letter, $index) {
            return $index % 2 === 0 ? strtoupper($letter) : strtolower($letter);
        }, $wordArray);

        return implode('', $wordArray);
    }
}
