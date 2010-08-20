<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 foldmethod=marker: */
/**
 * Structures_Grammar_Exception is the top level exception for the Structures_Grammar package
 */
class Structures_Grammar_Exception extends Exception
{
}
/**
 * Structures_Grammar_RestrictionException signals violation to grammar restrictions
 */
class Structures_Grammar_RestrictionException extends Structures_Grammar_Exception
{
}
/**
 * Structures_Grammar_UndefinedSymbol signals a reference to a non-existent symbol
 */
class Structures_Grammar_UndefinedSymbol extends Structures_Grammar_Exception
{
}

?>
