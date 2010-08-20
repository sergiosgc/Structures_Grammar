--TEST--
Test exception on a context-free-restricted Structures_Grammar
--FILE--
<?php
ini_set('include_path', realpath(dirname(__FILE__) . '/../') . ':' .
                        ini_get('include_path'));
require_once('Structures/Grammar/Symbol.php');
require_once('Structures/Grammar/Rule.php');
require_once('Structures/Grammar.php');
$grammar = new Structures_Grammar(true, false);
$grammar->addTerminal(Structures_Grammar_Symbol::create('b'));
$grammar->addNonTerminal(Structures_Grammar_Symbol::create('A'));
$grammar->addNonTerminal(Structures_Grammar_Symbol::create('B'));
$grammar->addNonTerminal(Structures_Grammar_Symbol::create('S'));

$rule = new Structures_Grammar_Rule();
$rule->addSymbolToLeft(Structures_Grammar_Symbol::create('b'));
$rule->addSymbolToRight(Structures_Grammar_Symbol::create('b'));
$rule->addSymbolToRight(Structures_Grammar_Symbol::create('B'));
try {
    $grammar->addRule($rule);

    print($grammar);
} catch (Structures_Grammar_RestrictionException $e)
{
    print($e->getMessage());
}
?>
--EXPECT--
Trying to add non context-free rule to context-free grammar (b->bB)
