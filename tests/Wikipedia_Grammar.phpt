--TEST--
Create a Structures_Grammar for the wikipedia example in http://en.wikipedia.org/wiki/Lr_parser
--FILE--
<?php
ini_set('include_path', realpath(dirname(__FILE__) . '/../') . ':' .
                        ini_get('include_path'));
require_once('Structures/Grammar/Symbol.php');
require_once('Structures/Grammar/Rule.php');
require_once('Structures/Grammar.php');
$grammar = new Structures_Grammar(false, false);
$grammar->addTerminal(Structures_Grammar_Symbol::create('0'));
$grammar->addTerminal(Structures_Grammar_Symbol::create('1'));
$grammar->addTerminal(Structures_Grammar_Symbol::create('+'));
$grammar->addTerminal(Structures_Grammar_Symbol::create('*'));
$grammar->addNonTerminal(Structures_Grammar_Symbol::create('E'));
$grammar->addNonTerminal(Structures_Grammar_Symbol::create('B'));

$rule = new Structures_Grammar_Rule();
$rule->addSymbolToLeft(Structures_Grammar_Symbol::create('E'));
$rule->addSymbolToRight(Structures_Grammar_Symbol::create('E'));
$rule->addSymbolToRight(Structures_Grammar_Symbol::create('*'));
$rule->addSymbolToRight(Structures_Grammar_Symbol::create('B'));
$grammar->addRule($rule);

$rule = new Structures_Grammar_Rule();
$rule->addSymbolToLeft(Structures_Grammar_Symbol::create('E'));
$rule->addSymbolToRight(Structures_Grammar_Symbol::create('E'));
$rule->addSymbolToRight(Structures_Grammar_Symbol::create('+'));
$rule->addSymbolToRight(Structures_Grammar_Symbol::create('B'));
$grammar->addRule($rule);

$rule = new Structures_Grammar_Rule();
$rule->addSymbolToLeft(Structures_Grammar_Symbol::create('E'));
$rule->addSymbolToRight(Structures_Grammar_Symbol::create('B'));
$grammar->addRule($rule);

$rule = new Structures_Grammar_Rule();
$rule->addSymbolToLeft(Structures_Grammar_Symbol::create('E'));
$rule->addSymbolToRight(Structures_Grammar_Symbol::create('0'));
$grammar->addRule($rule);

$rule = new Structures_Grammar_Rule();
$rule->addSymbolToLeft(Structures_Grammar_Symbol::create('E'));
$rule->addSymbolToRight(Structures_Grammar_Symbol::create('1'));
$grammar->addRule($rule);

print($grammar);
?>
--EXPECT--
[0] E->E*B
[1] E->E+B
[2] E->B
[3] E->0
[4] E->1
