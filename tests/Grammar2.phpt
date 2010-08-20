--TEST--
Create a Structures_Grammar for a LALR-parsable, non LR(0)-parsable grammar
--FILE--
<?php
ini_set('include_path', realpath(dirname(__FILE__) . '/../') . ':' .
                        ini_get('include_path'));
require_once('Structures/Grammar/Symbol.php');
require_once('Structures/Grammar/Rule.php');
require_once('Structures/Grammar.php');
$grammar = new Structures_Grammar(false, false);
$grammar->addTerminal(Structures_Grammar_Symbol::create('x'));
$grammar->addNonTerminal(Structures_Grammar_Symbol::create('S'));
$grammar->addNonTerminal(Structures_Grammar_Symbol::create('A'));
$grammar->addNonTerminal(Structures_Grammar_Symbol::create('B'));
$grammar->addNonTerminal(Structures_Grammar_Symbol::create('X'));

$rule = new Structures_Grammar_Rule();
$rule->addSymbolToLeft(Structures_Grammar_Symbol::create('S'));
$rule->addSymbolToRight(Structures_Grammar_Symbol::create('X'));
$rule->addSymbolToRight(Structures_Grammar_Symbol::create('X'));
$grammar->addRule($rule);

$rule = new Structures_Grammar_Rule();
$rule->addSymbolToLeft(Structures_Grammar_Symbol::create('X'));
$rule->addSymbolToRight(Structures_Grammar_Symbol::create('a'));
$rule->addSymbolToRight(Structures_Grammar_Symbol::create('X'));
$grammar->addRule($rule);

$rule = new Structures_Grammar_Rule();
$rule->addSymbolToLeft(Structures_Grammar_Symbol::create('X'));
$rule->addSymbolToRight(Structures_Grammar_Symbol::create('b'));
$grammar->addRule($rule);

print($grammar);
?>
--EXPECT--
[0] S->XX
[1] X->aX
[2] X->b
