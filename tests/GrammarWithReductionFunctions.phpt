--TEST--
Create a Structures_Grammar with some reduction functions
--FILE--
<?php
ini_set('include_path', realpath(dirname(__FILE__) . '/../') . ':' .
                        ini_get('include_path'));
require_once('Structures/Grammar/Symbol.php');
require_once('Structures/Grammar/Rule.php');
require_once('Structures/Grammar.php');
$grammar = new Structures_Grammar(false, false);
$grammar->addTerminal(Structures_Grammar_Symbol::create('b'));
$grammar->addNonTerminal(Structures_Grammar_Symbol::create('A'));
$grammar->addNonTerminal(Structures_Grammar_Symbol::create('B'));
$grammar->addNonTerminal(Structures_Grammar_Symbol::create('S'));

$rule = new Structures_Grammar_Rule();
$rule->addSymbolToLeft(Structures_Grammar_Symbol::create('A'));
$rule->addSymbolToRight(Structures_Grammar_Symbol::create('b'));
$rule->addSymbolToRight(Structures_Grammar_Symbol::create('B'));
$rule->addReductionFunctionSymbolmap(0, 'A');
$rule->addReductionFunctionSymbolmap(1, 'C');
$rule->setReductionFunction(<<<EOS
return \$A . \$C;
EOS
);
$grammar->addRule($rule);

$rule = new Structures_Grammar_Rule();
$rule->addSymbolToLeft(Structures_Grammar_Symbol::create('S'));
$rule->addSymbolToRight(Structures_Grammar_Symbol::create('b'));
$rule->addSymbolToRight(Structures_Grammar_Symbol::create('B'));
$rule->addReductionFunctionSymbolmap(0, 'xpto');
$rule->addReductionFunctionSymbolmap(1, 'foobar');
$rule->setReductionFunction(<<<EOS
return \$xpto || \$foobar;
EOS
);
$grammar->addRule($rule);

print($grammar);
?>
--EXPECT--
[0] A->bB
[1] S->bB
