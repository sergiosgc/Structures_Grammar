--TEST--
Create a Structures_Grammar for the wikipedia example in http://en.wikipedia.org/wiki/Lr_parser
--FILE--
<?php
require_once(__DIR__ . '/../vendor/autoload.php');
$grammar = new \sergiosgc\Structures_Grammar(false, false);
$grammar->addTerminal(\sergiosgc\Structures_Grammar_Symbol::create('0'));
$grammar->addTerminal(\sergiosgc\Structures_Grammar_Symbol::create('1'));
$grammar->addTerminal(\sergiosgc\Structures_Grammar_Symbol::create('+'));
$grammar->addTerminal(\sergiosgc\Structures_Grammar_Symbol::create('*'));
$grammar->addNonTerminal(\sergiosgc\Structures_Grammar_Symbol::create('E'));
$grammar->addNonTerminal(\sergiosgc\Structures_Grammar_Symbol::create('B'));

$rule = new \sergiosgc\Structures_Grammar_Rule();
$rule->addSymbolToLeft(\sergiosgc\Structures_Grammar_Symbol::create('E'));
$rule->addSymbolToRight(\sergiosgc\Structures_Grammar_Symbol::create('E'));
$rule->addSymbolToRight(\sergiosgc\Structures_Grammar_Symbol::create('*'));
$rule->addSymbolToRight(\sergiosgc\Structures_Grammar_Symbol::create('B'));
$grammar->addRule($rule);

$rule = new \sergiosgc\Structures_Grammar_Rule();
$rule->addSymbolToLeft(\sergiosgc\Structures_Grammar_Symbol::create('E'));
$rule->addSymbolToRight(\sergiosgc\Structures_Grammar_Symbol::create('E'));
$rule->addSymbolToRight(\sergiosgc\Structures_Grammar_Symbol::create('+'));
$rule->addSymbolToRight(\sergiosgc\Structures_Grammar_Symbol::create('B'));
$grammar->addRule($rule);

$rule = new \sergiosgc\Structures_Grammar_Rule();
$rule->addSymbolToLeft(\sergiosgc\Structures_Grammar_Symbol::create('E'));
$rule->addSymbolToRight(\sergiosgc\Structures_Grammar_Symbol::create('B'));
$grammar->addRule($rule);

$rule = new \sergiosgc\Structures_Grammar_Rule();
$rule->addSymbolToLeft(\sergiosgc\Structures_Grammar_Symbol::create('E'));
$rule->addSymbolToRight(\sergiosgc\Structures_Grammar_Symbol::create('0'));
$grammar->addRule($rule);

$rule = new \sergiosgc\Structures_Grammar_Rule();
$rule->addSymbolToLeft(\sergiosgc\Structures_Grammar_Symbol::create('E'));
$rule->addSymbolToRight(\sergiosgc\Structures_Grammar_Symbol::create('1'));
$grammar->addRule($rule);

print($grammar);
?>
--EXPECT--
[0] E->E*B
[1] E->E+B
[2] E->B
[3] E->0
[4] E->1
