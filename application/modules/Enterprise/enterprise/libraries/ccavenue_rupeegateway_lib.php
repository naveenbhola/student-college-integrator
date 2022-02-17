<?php

 /**
  * Main payment gateway library file for CCAvenue.
  *
  *
  *
  */

class ccavenue_rupeegateway_lib
{
	   /**
	   * @var object of The main CodeIgniter object.
	   */
	   private $CI;
	    	   
	   private $Merchant_Id;
	   private $WorkingKey;
	   private $redirectURL;
	   
	   /**
	    * default Constructor
	    */
	   
	   function __construct()
	   {

           $this->CI = & get_instance();
	       
		   $this->CI->config->load('ccavenue_PaymentGatewayINR_settings',TRUE);
			
           $this->Merchant_Id = $this->CI->config->item('Merchant_Id','ccavenue_PaymentGatewayINR_settings');
	     
	       $this->WorkingKey = $this->CI->config->item('WorkingKey','ccavenue_PaymentGatewayINR_settings');

	       $this->redirectURL = $this->CI->config->item('redirectURL','ccavenue_PaymentGatewayINR_settings');
	   }	   
	   	   
       function setMerchantId($merchant_id)
	   {
		  $this->Merchant_Id = $merchant_id;
	   }
	   
	   function setRedirectURL($redirect_url)
	   {
		  $this->redirectURL = $redirect_url;
	   }
	   
	   function get_Merchant_Id()
	   {
	      return $this->Merchant_Id;
	   }

	   function get_WorkingKey()
	   {
	      return $this->WorkingKey;
	   }

	   function get_redirectURL()
	   {	
	      return $this->redirectURL;
	   }
	   
	   /*
	    *function to get the value of Checksum
	    */
	   
	    function calculatechecksum($amt,$id)
	    {
			$checksum = $this->getchecksum($this->Merchant_Id,$amt,$id,$this->redirectURL,$this->WorkingKey);	      
	        return($checksum);
	    }

	    function calculateverifyCheckSum($id,$amt,$Status,$checksum)
	    {
            $check = $this->verifychecksum($this->Merchant_Id,$id,$amt,$Status,$checksum,$this->WorkingKey);
			return($check);
	    }



/**
*Functions required to calculate the value of Checksum 
*/


function getchecksum($MerchantId,$Amount,$OrderId ,$URL,$WorkingKey)
{
	$str ="$MerchantId|$OrderId|$Amount|$URL|$WorkingKey";
	$adler = 1;
	$adler = $this->adler32($adler,$str);
	return $adler;
}

function verifychecksum($MerchantId,$OrderId,$Amount,$AuthDesc,$CheckSum,$WorkingKey)
{
	$str = "$MerchantId|$OrderId|$Amount|$AuthDesc|$WorkingKey";
	$adler = 1;
	 $adler = $this->adler32($adler,$str);
	
	if($adler == $CheckSum)
		return "true" ;
	else
		return "false" ;
}

function adler32($adler , $str)
{
	$BASE =  65521 ;

	$s1 = $adler & 0xffff ;
	$s2 = ($adler >> 16) & 0xffff;
	for($i = 0 ; $i < strlen($str) ; $i++)
	{
		$s1 = ($s1 + Ord($str[$i])) % $BASE ;
		$s2 = ($s2 + $s1) % $BASE ;
			//echo "s1 : $s1 <BR> s2 : $s2 <BR>";

	}
	return $this->leftshift($s2 , 16) + $s1;
}

function leftshift($str , $num)
{

	$str = DecBin($str);

	for( $i = 0 ; $i < (64 - strlen($str)) ; $i++)
		$str = "0".$str ;

	for($i = 0 ; $i < $num ; $i++) 
	{
		$str = $str."0";
		$str = substr($str , 1 ) ;
		//echo "str : $str <BR>";
	}
	return $this->cdec($str) ;
}

function cdec($num)
{

	for ($n = 0 ; $n < strlen($num) ; $n++)
	{
	   $temp = $num[$n] ;
	   $dec =  $dec + $temp*pow(2 , strlen($num) - $n - 1);
	}

	return $dec;
}

}

/* End of payment gateway library file for CCAvenue. */

