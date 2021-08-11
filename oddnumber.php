<?php

// First i would like to create a class which has method to check if the input number is odd or not

Class MyClassOne
{
    // Declare public property input
    public $_input;
    
    // Create initial contructor function which has parameter integer input
    public function __construct(int $input)
    {
        $this->_input = $input;
    }
    
    // Create method to check if the input is odd number or not
    public function checkIsOddNumber()
    {
        if ($this->_input % 2)
            return true;
        
        return false;
    }
}

// Declare new variable which has number value
// i.e i would like to use $number = 3
$number = 4;

// Next we try init the class then call the method to check the number input
var_dump((new MyClassOne($number))->checkIsOddNumber());