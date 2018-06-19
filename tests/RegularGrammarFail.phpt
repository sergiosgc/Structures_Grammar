--TEST--
Test exception on a context-free-restricted Structures_Grammar
--FILE--
<?php
require_once(__DIR__ . '/../vendor/autoload.php');
$grammar = new \sergiosgc\Structures_Grammar(true, true);
$grammar->addTerminal(\sergiosgc\Structures_Grammar_Symbol::create('b'));
$grammar->addNonTerminal(\sergiosgc\Structures_Grammar_Symbol::create('A'));
$grammar->addNonTerminal(\sergiosgc\Structures_Grammar_Symbol::create('B'));
$grammar->addNonTerminal(\sergiosgc\Structures_Grammar_Symbol::create('S'));

$rule = new \sergiosgc\Structures_Grammar_Rule();
$rule->addSymbolToLeft(\sergiosgc\Structures_Grammar_Symbol::create('A'));
$rule->addSymbolToRight(\sergiosgc\Structures_Grammar_Symbol::create('B'));
$rule->addSymbolToRight(\sergiosgc\Structures_Grammar_Symbol::create('b'));
try {
    $grammar->addRule($rule);

    print($grammar);
} catch (\sergiosgc\Structures_Grammar_RestrictionException $e)
{
    print($e->getMessage());
}
?>
--EXPECT--
Trying to add non regular rule to regular grammar (A->Bb)
