<?php

namespace App;

use \Log;
use \Ds\Set;

class BoggleCoord
{
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */

    public $x;
    public $y;
    
    public function __construct($x, $y) {
        $this->x = $x;
        $this->y = $y;
    }

    /**
    Returns true if the provided coordinate is next to the current coordinate
    */
    public function isConnected($newCoord) {
        Log::debug("Comparing new " . $newCoord->x . ", " . $newCoord->y . " to current " . $this->x . ", " . $this->y);
        $result = true;
        if ($newCoord->x < 0 || $newCoord->y < 0 
            || $newCoord->x > 3 || $newCoord->y > 3) {
            Log::debug("coordinate not on board");
            $result = false; //invalid move
        }

        $diffx = abs($newCoord->x - $this->x);
        $diffy = abs($newCoord->y - $this->y);

        if ($diffx > 1 || $diffy > 1 || ($diffx == 0 && $diffy == 0)) {
            $result = false;
        }
        return $result;
    }

    /**
    Check if two coordinates point to the same location
    */
    public function isEqual($newCoord) {
        if ($newCoord->x == $this->x && $newCoord->y == $this->y) {
            return true;
        }
        return false;
    }

}

