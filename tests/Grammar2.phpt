--TEST--
Create a Structures_Grammar for a LALR-parsable, non LR(0)-parsable grammar
--FILE--
<?php
require_once(__DIR__ . '/../vendor/autoload.php');
$grammar = new \sergiosgc\Structures_Grammar(false, false);
$grammar->addTerminal(\sergiosgc\Structures_Grammar_Symbol::create('x'));
$grammar->addNonTerminal(\sergiosgc\Structures_Grammar_Symbol::create('S'));
$grammar->addNonTerminal(\sergiosgc\Structures_Grammar_Symbol::create('A'));
$grammar->addNonTerminal(\sergiosgc\Structures_Grammar_Symbol::create('B'));
$grammar->addNonTerminal(\sergiosgc\Structures_Grammar_Symbol::create('X'));

$rule = new \sergiosgc\Structures_Grammar_Rule();
$rule->addSymbolToLeft(\sergiosgc\Structures_Grammar_Symbol::create('S'));
$rule->addSymbolToRight(\sergiosgc\Structures_Grammar_Symbol::create('X'));
$rule->addSymbolToRight(\sergiosgc\Structures_Grammar_Symbol::create('X'));
$grammar->addRule($rule);

$rule = new \sergiosgc\Structures_Grammar_Rule();
$rule->addSymbolToLeft(\sergiosgc\Structures_Grammar_Symbol::create('X'));
$rule->addSymbolToRight(\sergiosgc\Structures_Grammar_Symbol::create('a'));
$rule->addSymbolToRight(\sergiosgc\Structures_Grammar_Symbol::create('X'));
$grammar->addRule($rule);

$rule = new \sergiosgc\Structures_Grammar_Rule();
$rule->addSymbolToLeft(\sergiosgc\Structures_Grammar_Symbol::create('X'));
$rule->addSymbolToRight(\sergiosgc\Structures_Grammar_Symbol::create('b'));
$grammar->addRule($rule);

print($grammar);
?>
--EXPECT--
[0] S->XX
[1] X->aX
[2] X->b
