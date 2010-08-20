<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 foldmethod=marker: */
require_once('Structures/Grammar/Symbol.php');
require_once('Structures/Grammar/Exception.php');
/**
 * This is a simple class represeting a set of Structure_Grammar_Symbols. 
 *
 * This is a glorified array of symbol sets, capable of typical set manipulation
 */
class Structures_Grammar_Symbol_Set implements Iterator
{
    /* Constructor {{{ */
    public function __construct($symbols = null)
    {
        if (is_null($symbols)) $symbols = array();
        if ($symbols instanceof Structures_Grammar_Symbol_Set) $symbols = $symbols->getSymbols();
        if (!(is_array($symbols) || $symbols instanceof Structures_Grammar_Symbol_Set)) throw new Structures_Grammar_Exception('Expected array of symbols or Structures_Grammar_Symbol_Set as argument');
        foreach ($symbols as $symbol) if (!($symbol instanceof Structures_Grammar_Symbol)) throw new Structures_Grammar_Exception('Expected array of symbols as argument');
        $this->symbols = array();
        foreach ($symbols as $symbol) $this->symbols[] = $symbol;
    }
    /* }}} */
    /** The actual set, stored as an array */
    protected $symbols = array();
    /* getSymbol {{{ */
    /** 
     * Symbol getter
     *
     * @param int index
     * @returns Structures_Grammar_Symbol Symbol at the specified index 
     */
    public function getSymbol($i)
    {
        return $this->symbols[$i];
    }
    /* }}} */
    /* getSymbolCount {{{ */
    /** 
     * Symbol count getter
     *
     * @returns int Symbol set cardinality
     */
    public function getSymbolCount()
    {
        return count($this->symbols);
    }
    /* }}} */
    /* getSymbolIndex {{{ */
    /**
     * Search for a symbol and return its index
     *
     * @param Structures_Grammar_Symbol Symbol to search for
     * @returns int Index of sought symbol
     * @throws Structures_Grammar_UndefinedSymbol When symbol does not exist in the set
     */
    public function getSymbolIndex($symbol)
    {
        foreach ($this->symbols as $index => $candidate) if ($candidate == $symbol) return $index;
        throw new Structures_Grammar_UndefinedSymbol(sprintf('Symbol %s does not exist', (string) $symbol));
    }
    /* }}} */
    /* symbolExists {{{ */
    public function symbolExists($symbol)
    {
        try {
            $this->getSymbolIndex($symbol);
            return true;
        } catch (Structures_Grammar_UndefinedSymbol $e) {
            return false;
        }
    }
    /* }}} */
    /* addSymbol {{{ */
    public function addSymbol($symbol) 
    {
        if (!$this->symbolExists($symbol)) $this->symbols[] = $symbol;
    }
    /* }}} */
    /* removeSymbol {{{ */
    public function removeSymbol($symbol)
    {
        $new = array();
        for($i=0; $i<count($this->symbols); $i++) if (!$this->symbols[$i]->__equals($symbol)) $new[] =& $this->symbols[$i];
        $this->symbols = $new;
    }
    /* }}} */

    /* Iterator interface implementation {{{ */
    protected $symbolsIteratorCursor = 0;
    public function valid()
    {
        return $this->symbolsIteratorCursor >= 0 && $this->symbolsIteratorCursor < $this->getSymbolCount();
    }
    public function rewind()
    {
        $this->symbolsIteratorCursor = 0;
    }
    public function next()
    {
        $this->symbolsIteratorCursor++;
    }
    public function key()
    {
        if (!$this->valid()) throw new Structures_Grammar_Exception('Key() called on an invalid iterator state');
        return $this->symbolsIteratorCursor;
    }
    public function current()
    {
        if (!$this->valid()) throw new Structures_Grammar_Exception('Current() called on an invalid iterator state');
        return $this->symbols[$this->symbolsIteratorCursor];
    }
    /* }}} */
    /* Union  {{{ */
    /**
     * Transforms this set to be the union of itself with the argument set
     */
    public function union($symbolset)
    {
        foreach ($symbolset as $symbol) $this->addSymbol($symbol);
        return $this;
    }
    /* }}} */
    /* Complement  {{{ */
    /**
     * Transforms this set to be the complement of the argument set with itself (result <- this - argument)
     */
    public function complement($symbolset)
    {
        global $debug; 

        foreach ($symbolset as $symbol) $this->removeSymbol($symbol);
        return $this;
    }
    /* }}} */
    /* Intersection {{{ */
    /**
     * Transforms this set to be the intersection of itself with the argument set
     */
    public function intersection($symbolset)
    {
        $newset = array();
        foreach ($this->symbols as $symbol) if ($symbolset->symbolExists($symbol)) $newset[] = $symbol;
        $this->symbols = array_values($newset);

        return $this;
    }
    /* }}} */

    /* isDisjoint {{{ */
    /** 
     * Test whether this set is disjoint with the argument set. 
     * 
     * The two sets are disjoint if the intersection is an empty set
     *
     * @param Structures_Grammar_Symbol_Set Set to test against
     * @return boolean true iff sets are disjoint
     */
    public function isDisjoint($right)
    {
        $right = clone $right;
        $right->intersection($this);
        return $right->getSymbolCount() == 0;
    }
    /* }}} */
    /* __toString  {{{ */
    public function __toString()
    {
        $result = '';
        foreach ($this->symbols as $symbol) $result .= ($result == '' ? '{ ' : ', ') . $symbol;
        $result .= $result == '' ? '{ }' : ' }';
        return $result;
    }
    /* }}} */
}

?>
