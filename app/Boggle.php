<?php

namespace App;

use \Log;
use \Ds\Set;
use App\BoggleCoord;

class Boggle
{
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */

    protected $board;
    protected $validLetters;
    protected $letterString;
 
    private $width = 4;
    private $height = 4;
    private $maxLength;
    private $minLength;
    public $totalTested;
    
   public function __construct($board) {
        $this->board = $board;
        $this->maxLength = $this->width * $this->height;
        $this->minLength = $this->width - 1;

        $this->validLetters = new \Ds\Set();
        foreach ($this->board as $key => $value) {
            foreach($value as $key2 => $value2) {
                $this->validLetters->add($value2);
                $this->letterString .= strtoupper($value2);
            }
            
        }
        Log::debug("valid letters: " . $this->letterString);
    }

    public function solveIt() {
    /*    $candidates = new \Ds\Set();
        $candidates->add("ALE");
        $candidates->add("ART");
        $candidates->add("RAW");
        $candidates->add("BLACK");
        $candidates->add("ABLER");
*/
       $candidates = $this->getAllWords($this->width * $this->height);

        $finalList = new \Ds\Set();
        foreach($candidates as $c) {
            if ($this->isValidBoggleMove($c)) {
                $finalList->add($c);
            }
        }
        return $finalList;
    }

    /**
    Get relevant words out of the local dictionary. Return set containing only words with valid letters and length.
    */
    public function getAllWords($maxLength) {
        Log::debug("Starting to get all words...");
        $exists = file_exists("/home/vagrant/files/Unabr.dict");
        if (!$exists) {
            Log::error("Dictionary file not found!");
            return null;
        }
        
        $handle = @fopen("/home/vagrant/files/Unabr.dict", "r");
        $set = new \Ds\Set();
        if ($handle) {
            $i = 0;
            while (($buffer = fgets($handle, 4096)) !== false && $i < 500000) {
                $candidate = trim(strtoupper($buffer));
                if ($this->isValid($candidate)) {
                    $set->add($candidate);
                }
                $i++;
            }
            fclose($handle);
        }
        Log::debug("Size of set: " . $set->count());
        $this->totalTested = $set->count();
        return $set;
    }

    /*
    Check that the word only contains the available letters and is of the correct length
    */
    public function isValid($word) {
        $upper = strtoupper($word);
        $pattern = "/^[".$this->letterString."]{".$this->minLength.",".$this->maxLength."}$/";
        $test = preg_match($pattern,$upper, $matches);
        return $test > 0;
    }

    /**
    Check whether we can make a particular word on the boggle board, tracking moves taken already, the last coordinate
    and how many remanining letters
    */
    public function isValidBoggleMove($word, $taken = null, $lastCoord = null, $remaining = null) {
        Log::debug("isValidBoggleMove starting to check " . $word . " at " . $remaining);

        $word = strtoupper($word);
        if (!$taken) {
            $taken = new \Ds\Set();
        }
        
        if (!$remaining) {
            $remaining = strlen($word);
        }
        if ($remaining == 0) {
            $match = true; // we're done, no failures and no more letters
        } else {
            $currentWord = substr($word, (strlen($word) - $remaining)); // only iterate over remaining letters
            $l = str_split($currentWord, 1);
            $index = 0;
            foreach ($l as $letter) {
                $index++; 
                $coordSet = $this->findOnBoard($letter); // get the possible next moves
                if (!$coordSet || $coordSet->count() < 1) {
                    Log::debug("letter not on board");
                    $match = false;
                    break; // letter doesn't exist on board
                }
                $match = false;
                if ($lastCoord) {
                    foreach($coordSet as $coord) {
                        $isConn = $coord->isConnected($lastCoord);
                        $isTaken = $this->isUsed($coord, $taken);
            
                        if ($isConn && !$isTaken && !$match) {
                            // we have a valid move
                            $taken->add($coord);
                            $lastCoord = $coord;
                            $match = true;
                        }
                    }

                } else {
                    // first valid letter
                    foreach($coordSet as $coord) {
                        $remainingLetters = strlen($word) - $index;
                        $taken = new \Ds\Set();// since we know this is the first letter (no lastCoord)
                        $taken->add($coord);
                        if ($this->isValidBoggleMove($word, $taken, $coord, $remainingLetters)) {
                            return true;
                        }
                    }
                }
                
                if (!$match) {
                    return false;
                }
            }
        }
        
        return $match; // if we've made it this far it's valid
    }

    /**
    Return true if we have already made this move, otherwise false
    */
    public function isUsed($coord, $taken) {
        foreach($taken as $c) {
            if ($c->isEqual($coord)) {
                return true;
            }
        }
        return false;
    }

    /**
    Return a set of coordinates of each occurence of letter on board
    */
    public function findOnBoard($letter) {
        $result = new \Ds\Set();
        foreach ($this->board as $x => $row) {
            foreach ($row as $y => $column) {
                if ($column == $letter) {
                    $result->add(new BoggleCoord($x, $y));
                }
            }
        }
        return $result;
    }

}

