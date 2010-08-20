<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 foldmethod=marker: */
require_once('Structures/Grammar/Exception.php');
/**
 * Structures_Grammar_Symbol is a representation of a symbol to be used in from grammar rules
 */
class Structures_Grammar_Symbol
{
    static protected $symbols = array();
    protected $id = null;
    protected $terminal = null;
    /* getId {{{ */
    /**
     * Id getter
     *
     * @return string Id
     */
    public function getId()
    {
        return $this->id;
    }
    /* }}} */
    /* isTerminal {{{ */
    /**
     * terminal getter
     *
     * @return boolean true if symbol is terminal
     */
    public function isTerminal()
    {
        return $this->terminal;
    }
    /* }}} */
    /* isNonTerminal {{{ */
    /**
     * non-terminal getter
     *
     * @return boolean true if symbol is non-terminal
     */
    public function isNonTerminal()
    {
        return !$this->terminal;
    }
    /* }}} */
    /* setTerminal {{{ */
    /** 
     * Terminal setter
     *
     * @param boolean true if the symbol is to be a terminal symbol
     */
    public function setTerminal($value = true)
    {
        $this->terminal = (boolean) $value;
    }
    /* }}} */
    /* setNonTerminal {{{ */
    /** 
     * Non-terminal setter
     *
     * @param boolean true if the symbol is to be a non-terminal symbol
     */
    public function setNonTerminal($value = true)
    {
        $this->terminal = !((boolean) $value);
    }
    /* }}} */
    /* Constructor {{{ */
    public function __construct($id)
    {
        $this->id = $id;
        $this->terminal = false;
    }
    /* }}} */
    /* __toString {{{ */
    public function __toString()
    {
        return $this->id;
    }
    /* }}} */
    /* __equals {{{ */
    public function __equals($right)
    {
        return $this->id == $right->getId();
    }
    /* }}} */
    /* create (factory method) {{{ */
    static public function &create($id)
    {
        if (!array_key_exists($id, self::$symbols)) self::$symbols[$id] = new Structures_Grammar_Symbol($id);
        return self::$symbols[$id];
    }
    /* }}} */
}
?>
