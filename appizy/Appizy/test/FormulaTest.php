<?php

namespace Appizy;

class FormulaTest extends \PHPUnit_Framework_TestCase
{

    public function testCanCreate()
    {
        $f = new Formula([0, 0, 0],'of:=1+1');

        $this->assertEquals(['1','+','1'], $f->formula_elements);
    }
}