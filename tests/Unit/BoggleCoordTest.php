<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\BoggleCoord;

class BoggleCoordTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testIsConnected()
    {
    	$coord00 = new BoggleCoord(0,0);
    	$coord11 = new BoggleCoord(1,1);
    	$coord21 = new BoggleCoord(2,1);
    	$coord12 = new BoggleCoord(1,2);
    	$coord33 = new BoggleCoord(3,3);

    	//diagonal, both directions
    	$this->assertTrue($coord00->isConnected($coord11));
		$this->assertTrue($coord11->isConnected($coord00));

        // left right
        $this->assertTrue($coord11->isConnected($coord21));
		$this->assertTrue($coord21->isConnected($coord11));

		// up down
		$this->assertTrue($coord11->isConnected($coord12));
		$this->assertTrue($coord12->isConnected($coord11));

		//opposite corners
        $this->assertFalse($coord00->isConnected($coord33));
        $this->assertFalse($coord33->isConnected($coord00));

        $this->assertFalse($coord00->isConnected($coord21));
        $this->assertFalse($coord21->isConnected($coord00));

        $this->assertFalse($coord00->isConnected($coord12));
        $this->assertFalse($coord12->isConnected($coord00));
    }

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
}
