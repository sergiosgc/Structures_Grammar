--TEST--
Test acceptance on a context-free-restricted Structures_Grammar
--FILE--
<?php
require_once(__DIR__ . '/../vendor/autoload.php');
$grammar = new \sergiosgc\Structures_Grammar(true, false);
$grammar->addTerminal(\sergiosgc\Structures_Grammar_Symbol::create('b'));
$grammar->addNonTerminal(\sergiosgc\Structures_Grammar_Symbol::create('A'));
$grammar->addNonTerminal(\sergiosgc\Structures_Grammar_Symbol::create('B'));

$rule = new \sergiosgc\Structures_Grammar_Rule();
$rule->addSymbolToLeft(\sergiosgc\Structures_Grammar_Symbol::create('A'));
$rule->addSymbolToRight(\sergiosgc\Structures_Grammar_Symbol::create('b'));
$rule->addSymbolToRight(\sergiosgc\Structures_Grammar_Symbol::create('B'));
$grammar->addRule($rule);

print($grammar);
?>
--EXPECT--
[0] A->bB
