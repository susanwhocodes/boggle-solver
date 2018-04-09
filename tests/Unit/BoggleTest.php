<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Boggle;
use App\BoggleCoord;
use \Ds\Set;

class BoggleTest extends TestCase {

    public function setUp() {
    	parent::setUp();
		$this->board = [
	        ['R','A','W','W'],
	        ['L','D','T','B'],
	        ['T','E','L','A'],
	        ['I','R','C','K']];

        $this->board2 = [
	        ['A','N','T','S'],
	        ['X','X','X','A'],
	        ['X','X','X','X'],
	        ['X','X','X','X']];
    }
    
    /**
     * Test that we're tracking used spaces correctly
     *
     * @return void
     */
    public function testIsUsed()
    {
    	$boggle = new Boggle($this->board);

    	$coord00 = new BoggleCoord(0,0);
    	$coord11 = new BoggleCoord(1,1);
    	$coord21 = new BoggleCoord(2,1);
    	$coord12 = new BoggleCoord(1,2);
    	$coord33 = new BoggleCoord(3,3);
    	$coord00dupe = new BoggleCoord(0,0);

    	$used = new \Ds\Set();
    	
    	$this->assertFalse($boggle->isUsed($coord00, $used));
    	$this->assertFalse($boggle->isUsed($coord11, $used));
		$this->assertFalse($boggle->isUsed($coord00dupe, $used));
		$this->assertFalse($boggle->isUsed($coord21, $used));
		$this->assertFalse($boggle->isUsed($coord12, $used));
		$this->assertFalse($boggle->isUsed($coord33, $used));

		$used->add($coord00);
    	$used->add($coord11);

		$this->assertTrue($boggle->isUsed($coord00, $used));
    	$this->assertTrue($boggle->isUsed($coord11, $used));
		$this->assertTrue($boggle->isUsed($coord00dupe, $used));
		$this->assertFalse($boggle->isUsed($coord21, $used));
		$this->assertFalse($boggle->isUsed($coord12, $used));
		$this->assertFalse($boggle->isUsed($coord33, $used));
		
    }

    /**
	Test that we are comparing coordinates accurately
    */
    public function testIsEqual() {
    	$coord00 = new BoggleCoord(0,0);
    	$coord11 = new BoggleCoord(1,1);
    	$coord11Dupe = new BoggleCoord(1,1);
    	$coord12 = new BoggleCoord(1,2);

    	$this->assertFalse($coord00->isEqual($coord11));
    	$this->assertFalse($coord11->isEqual($coord00));

    	$this->assertTrue($coord00->isEqual($coord00));
    	$this->assertTrue($coord11->isEqual($coord11Dupe));

    	$this->assertFalse($coord11->isEqual($coord12));
    }

    /**
	Test that the word only contains valid letters and is of valid length
    */
     public function testIsValid() {
    	$boggle = new Boggle($this->board);
    	$this->assertFalse($boggle->isValid("zebra"));//not all letters available
    	$this->assertTrue($boggle->isValid("ladle"));
    	$this->assertTrue($boggle->isValid("raw"));
    	$this->assertTrue($boggle->isValid("abler"));
    	$this->assertFalse($boggle->isValid("camera"));// not all letters available
    	$this->assertFalse($boggle->isValid("aa"));//too short
    	$this->assertFalse($boggle->isValid("rawrawrawrawrawrawraw"));//too long
    }

    /**
	Test that we can find words on the board correctly
    */
    public function testisValidBoggleMove() {
    	$boggle = new Boggle($this->board);
    	$this->assertTrue($boggle->isValidBoggleMove("WATER"));
    	$this->assertTrue($boggle->isValidBoggleMove("WAT"));
    	$this->assertTrue($boggle->isValidBoggleMove("BLACK"));
    	$this->assertTrue($boggle->isValidBoggleMove("ABLER"));
    	
    	$this->assertFalse($boggle->isValidBoggleMove("WATERED"));
    	$this->assertFalse($boggle->isValidBoggleMove("DELTAS"));

    	$boggle2 = new Boggle($this->board2);
    	$this->assertTrue($boggle2->isValidBoggleMove("ant"));
    	$this->assertTrue($boggle2->isValidBoggleMove("antS"));
    	$this->assertTrue($boggle2->isValidBoggleMove("antA"));
    	$this->assertFalse($boggle2->isValidBoggleMove("anb"));
    	
    	$this->assertFalse($boggle2->isValidBoggleMove("ANAX"));
    	$this->assertFalse($boggle2->isValidBoggleMove("ATA"));
    	$this->assertFalse($boggle2->isValidBoggleMove("NANT"));
    	   	
    }

}
