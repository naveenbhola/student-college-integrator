<?php

/**
 * Class numberToWords_library
 *
 * 
 * 
 */

class Numbertowords_library {
	
	function numberToWords($number)
			{
				
				
		$words = array ('Zero',
				'One',
				'Two',
				'Three',
				'Four',
				'Five',
				'Six',
				'Seven',
				'Eight',
				'Nine',
				'Ten',
				'Eleven',
				'Twelve',
				'Thirteen',
				'Fourteen',
				'Fifteen',
				'Sixteen',
				'Seventeen',
				'Eighteen',
				'Nineteen',
				'Twenty',
				30=> 'Thirty',
				40 => 'Fourty',
				50 => 'Fifty',
				60 => 'Sixty',
				70 => 'Seventy',
				80 => 'Eighty',
				90 => 'Ninety',
				100 => 'Hundred',
				1000=> 'Thousand',
				100000=> 'Lakhs'
				);
	 
		if (is_numeric ($number))
		{
			if ($number < 0)
			{
				$number = -$number;
				$number_in_words = 'minus ';
			}
			if ($number > 100000)
			{
				
				$number_in_words = $number_in_words . $this->numberToWords(floor($number/100000)) . " " . $words[100000];
				$thousands= $number  % 100000;
				$hundreds = $number % 1000;
				$tens = $hundreds % 100;
				if ($thousands > 1000)
				{
					$number_in_words = $number_in_words . ", " . $this->numberToWords ($thousands);
				}
				
				
				elseif ($hundreds > 100)
				{
					$number_in_words = $number_in_words . ", " . $this->numberToWords ($hundreds);
				}
				elseif ($tens)
				{
					$number_in_words = $number_in_words . " " . $this->numberToWords ($tens);
				}
			}
			
			
			
			elseif ($number > 1000)
			{
				
				$number_in_words = $number_in_words . $this->numberToWords(floor($number/1000)) . " " . $words[1000];
				$hundreds = $number % 1000;
				$tens = $hundreds % 100;
				if ($hundreds > 100)
				{
					$number_in_words = $number_in_words . ", " . $this->numberToWords ($hundreds);
				}
				elseif ($tens)
				{
					$number_in_words = $number_in_words . " " . $this->numberToWords ($tens);
				}
			}
			elseif ($number > 100)
			{
				$number_in_words = $number_in_words . $this->numberToWords(floor ($number / 100)) . " " . $words[100];
				$tens = $number % 100;
				if ($tens)
				{
					$number_in_words = $number_in_words . " " . $this->numberToWords ($tens);
				}
			}
			elseif ($number > 20)
			{
				$number_in_words = $number_in_words . " " . $words[10 * floor ($number/10)];
				$units = $number % 10;
				if ($units)
				{
					$number_in_words = $number_in_words . $this->numberToWords ($units);
				}
			}
			else
			{
				$number_in_words = $number_in_words . " " . $words[$number];
			}
			return $number_in_words;
		}
		return false;
	}
	
}