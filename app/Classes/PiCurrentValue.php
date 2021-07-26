<?php

namespace App\Classes;

//use PhpParser\Node\Expr\Cast\Double;
use App\Classes\Contracts\CurrentValueInterface;

class CurrentPiValue implements CurrentValueInterface
{
    public $CurrentPiValue = 0;

    public function __construct() {
        $this->CurrentPiValue = 1.0;
        // lad($this->CurrentPiValue);
    }
    public function setNewPiValue(float $newvalue){
        $this->CurrentPiValue = $newvalue;
    }
}
