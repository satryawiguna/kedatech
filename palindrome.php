<?php

// First i would like to create a class which has method to check the string input
// does it palindrome or not

Class MyClassTwo
{
    // Declare public property input
    public $_input;
    
    // Create initial contructor function which has parameter string input
    public function __construct(string $input)
    {
        $this->_input = $input;
    }
    
    // Create method to check if the input is palindrome or not
    public function checkIsPalindrome()
    {
        $n = strlen($this->_input);
        
        // An empty string will considered as palindrome
        if ($n == 0)
            return true;
            
        return $this->palindromeRecursive($this->_input, 0, $n - 1);
    }
    
    // Create a recursive method that check the $string[$start..$end]
    // is palindrome or not
    private function palindromeRecursive(string $string, int $start, int $end)
    {
        // If there only one character
        if ($start == $end)
            return true;
            
        // If $start and $end do not match
        if ($string[$start] != $string[$end])
            return false;
            
        // If there are more than two character, check if it in the middle
        // substring is also palindrome or not
        if ($start < ($end + 1))
            return self::palindromeRecursive($string, $start + 1, $end - 1);
            
        return true;
    }
}

// Declare new variable which has string value
// i.e i would like to use $string = weekkeew;
$string = "weekkeews";

if ((new MyClassTwo($string))->checkIsPalindrome())
    echo "Yes, the input string is Palindrome";
else
    echo "No, the input string is not Palindrome";