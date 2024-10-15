# php-zoo-ids

[![Latest Stable Version](https://poser.pugx.org/iliain/php-zoo-ids/v)](https://packagist.org/packages/iliain/php-zoo-ids) 
[![Total Downloads](https://poser.pugx.org/iliain/php-zoo-ids/downloads)](https://packagist.org/packages/iliain/php-zoo-ids) 
[![Latest Unstable Version](https://poser.pugx.org/iliain/php-zoo-ids/v/unstable)](https://packagist.org/packages/iliain/php-zoo-ids) 
[![License](https://poser.pugx.org/iliain/php-zoo-ids/license)](https://packagist.org/packages/iliain/php-zoo-ids) 
[![PHP Version Require](https://poser.pugx.org/iliain/php-zoo-ids/require/php)](https://packagist.org/packages/iliain/php-zoo-ids)

A PHP version of [bryanmylee's zoo-ids](https://github.com/bryanmylee/zoo-ids), originally written in JavaScript, which was in turn inspired by the URL ID's of [gfycat.com](https://gfycat.com). For a frontend or backend that uses JavaScript, I highly recommend you use their package!

Generate **predictable** and **unique** identifiers composed of adjectives and animal names, with the ability to seed the random identifiers.

## Range of IDs

Currently, there are 1347 adjectives and 221 animals. The more adjectives used, the more possible combinations of IDs available.

For a quick reference, with 2 adjectives used, there are 400,984,389 possible unique IDs.

With 3 adjectives, there are 540,125,971,983 possible unique IDs.

## Installation

```bash
$ composer require iliain/php-zoo-ids
```

## Usage

### Examples

```php
use Iliain\ZooIDs\UsernameRandomizer;

$randomizer = new UsernameRandomizer();
$randomizer->generateID('short seed'); // KnobbyNauticalKingfisher

// Defaults to the current time if seed is null.
$randomizer->generateID(null, 2, '🍓', 'lowercase'); // enchanted🍓narrow🍓wallaby
```

If you want to control the list of Nouns and Adjectives, you can provide your own: 

```php
$randomizer = new UsernameRandomizer(['Jumping','Flying','Running','Waving'], ['Rock', 'Paper', 'Scissors']);
$randomizer->generateID(); // JumpingRunningScissors

$randomizer->setAdjectives(['Diving','Walking','Spinning']);
$randomizer->generateID(); // WalkingDivingScissors

$randomizer->setNouns(['Koala', 'Dog']);
$randomizer->generateID(); // WalkingDivingKoala
```

### Documentation

#### `new UsernameRandomizer($adjectives, $nouns)`

##### `adjectives: array`

An array of adjectives to use in generation. Defaults to preprovided list if none are specified.

##### `nouns: array`

An array of nouns to use in generation. Defaults to preprovided list if none are specified.

#### `generateID($seed, $numAdjectives, $delimiter, $caseStyle)`

##### `seed: string|int`

The seed used to generate the id. This allows us to generate predictable, but random and unique identifiers.

Defaults to the current time.

##### `numAdjectives: int`

The number of adjectives to use in the identifier.

Defaults to `2`.

##### `delimiter: string`

The delimiter used between words. The delimiter will also be used between
multi-word adjectives.

Defaults to `''`.

##### `caseStyle: string`

The case style for the words. Possible options are `'titlecase'`, `'camelcase'`, `'uppercase'`, `'lowercase'`, and `'togglecase'`.

```php

generateID($seed, $numAdjectives, $delimiter, 'titlecase'); // FineAntiqueElk

generateID($seed, $numAdjectives, $delimiter, 'camelcase'); // pertinentPoshGoldfinch

generateID($seed, $numAdjectives, $delimiter, 'uppercase'); // PIERCINGRESPONSIBLECAMEL

generateID($seed, $numAdjectives, $delimiter, 'lowercase'); // imaginarywingedsalamander

generateID($seed, $numAdjectives, $delimiter, 'togglecase'); // sTuNnInGdEsCrIpTiVepEaFoWl
```

Defaults to `'titlecase'`.

## Credits

All credit goes to [Bryan Lee](https://github.com/bryanmylee) and his [zoo-ids](https://github.com/bryanmylee/zoo-ids) package. I wanted a version of this to use in PHP (username generation was handled by a PHP CMS), and converted the majority of his existing code to that format. Currently lacking some of his original features, such as the ability to use objects as seeds.
