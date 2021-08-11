<?php

// First i would like to create a new class which has method to get the largest and smallest number
// from the array input

Class MyClassThree
{
    // Declare public $input array
    public $_input;
    
    // Create initial constructor function which has parameter array input
    public function __construct(array $input)
    {
        $this->_input = $input;
    }
    
    // Create method to get the largest value of array input
    private function getLargest()
    {
        $n = count($this->_input);
        $largest = $this->_input[0];
        
        for ($i = 1; $i < $n; $i++)
            if ($largest < $this->_input[$i])
                $largest = $this->_input[$i];
                
        return $largest;
    }
    
    // Create methos to get the smallest value of array input
    private function getSmallest()
    {
        $n = count($this->_input);
        $smallest = $this->_input[0];
        
        for ($i = 1; $i < $n; $i++)
            if ($smallest > $this->_input[$i])
                $smallest = $this->_input[$i];
                
        return $smallest;
    }
    
    public function getLargestAndSmallest()
    {
        return "The largest is " . $this->getLargest() . " and the smallest is " . $this->getSmallest();
    }
}

$array = [15, 19, 3, 9, 36, 32, 25, 10, 57, 45, 22];

echo (new MyClassThree($array))->getLargestAndSmallest();