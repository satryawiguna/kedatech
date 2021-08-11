<?php

// First i would like to create a new class which has method to get missing number
Class MyClassFour
{
    // Declare public input array
    public $_input;
    
    public function __construct(array $input)
    {
        $this->_input = $input;
    }
    
    // Create method to get one missing number in array
    public function getMissingNumber()
    {
        $newArray = range(min($this->_input), max($this->_input));
        
        return implode(", ", array_diff($newArray, $this->_input));
    }
}

$array = [11,12,14,15,16, 19, 20];

echo "The missing number in array is " . (new MyClassFour($array))->getMissingNumber();