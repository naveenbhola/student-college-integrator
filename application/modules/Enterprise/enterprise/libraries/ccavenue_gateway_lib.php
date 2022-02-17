<?php

 /**
  * Main payment gateway library file for CCAvenue.
  *
  *
  *
  */

class ccavenue_gateway_lib
{
	   
	/**
	 * @var object of The main CodeIgniter object.
	 */

	   	var $CI;	   
	   
		/**
		 * default Constructor
		 */
	   
	    public function ccavenue_gateway_lib()
	    {

           	$this->CI =& get_instance();
	       
	   		$this->CI->config->load('ccavenue_PaymentGateway_settings',true);
			
           	$this->Merchant_Id = $this->CI->config->item('Merchant_Id', 'ccavenue_PaymentGateway_settings');
	     
	   		$this->WorkingKey = $this->CI->config->item('WorkingKey', 'ccavenue_PaymentGateway_settings');

		   	$this->Access_Code = $this->CI->config->item('Access_Code', 'ccavenue_PaymentGateway_settings');

		   	$this->redirectURL = $this->CI->config->item('redirectURL', 'ccavenue_PaymentGateway_settings');

		   	// $this->TxnType = $this->CI->config->item('TxnType', 'ccavenue_PaymentGateway_settings');
	  
		   	// $this->actionID = $this->CI->config->item('actionID', 'ccavenue_PaymentGateway_settings');
		  
           	$this->Currency = $this->CI->config->item('Currency', 'ccavenue_PaymentGateway_settings');
           	
	    }	   
	   	   
       
	    function get_Merchant_Id()
	    {
	        return $this->Merchant_Id;
	    }

	    function get_WorkingKey()
	    {
	        return $this->WorkingKey;
	    }

	    function get_Access_Code()
	    {
	        return $this->Access_Code;
	    }

	    function get_redirectURL()
	    {	
	        return $this->redirectURL;
	    }
	   
	    function get_TxnType()
	    {
	        return($this->TxnType);
	    }
	   
	    function get_actionID()
	    {
	        return($this->actionID);
	    }
	   
	    /*
	     *function to get the value of Checksum
	     */
	   
	    function calculatechecksum($amt,$id)
	    {
            $checksum=$this->getchecksum($this->Merchant_Id,$id,$amt,$this->WorkingKey,$this->Currency,$this->redirectURL);
		    return($checksum);
	    }

	   	function calculateverifyCheckSum($id,$amt,$Status,$checksum)
	    {
            $check=$this->verifyCheckSumAll($this->Merchant_Id,$id,$amt,$this->WorkingKey,$this->Currency,$Status,$checksum);
	        return($check);
	    }


		/**
		 *Functions required to calculate the value of Checksum 
         */

	    function getchecksum($MerchantId,$OrderId,$Amount ,$WorkingKey,$currencyType,$redirectURL)
	    {		
			$str ="$MerchantId|$OrderId|$Amount|$WorkingKey|$currencyType|$redirectURL";
			$adler = 1;
			$adler = $this->adler32($adler,$str);
			return $adler;
	    }

	    function  verifyCheckSumAll($MerchantId, $OrderId,$Amount, $WorkingKey,$currencyType,$Auth_Status,$checksum) 
	    {
			$str = "$MerchantId|$OrderId|$Amount|$WorkingKey|$currencyType|$Auth_Status";
			$adler = 1;
			$adler = $this->adler32($adler,$str);
			if($adler == $checksum)
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

	    function encrypt($plainText,$key)
		{
			$secretKey = $this->hextobin(md5($key));
			$initVector = pack("C*", 0x00, 0x01, 0x02, 0x03, 0x04, 0x05, 0x06, 0x07, 0x08, 0x09, 0x0a, 0x0b, 0x0c, 0x0d, 0x0e, 0x0f);
		  	$openMode = mcrypt_module_open(MCRYPT_RIJNDAEL_128, '','cbc', '');
		  	$blockSize = mcrypt_get_block_size(MCRYPT_RIJNDAEL_128, 'cbc');
			$plainPad = $this->pkcs5_pad($plainText, $blockSize);
		  	
		  	if (mcrypt_generic_init($openMode, $secretKey, $initVector) != -1) 
			{
		        $encryptedText = mcrypt_generic($openMode, $plainPad);
	      	    mcrypt_generic_deinit($openMode);
			} 
			return bin2hex($encryptedText);
		}

		function decrypt($encryptedText,$key)
		{
			$secretKey = $this->hextobin(md5($key));
			$initVector = pack("C*", 0x00, 0x01, 0x02, 0x03, 0x04, 0x05, 0x06, 0x07, 0x08, 0x09, 0x0a, 0x0b, 0x0c, 0x0d, 0x0e, 0x0f);
			$encryptedText = $this->hextobin($encryptedText);
	  		$openMode = mcrypt_module_open(MCRYPT_RIJNDAEL_128, '','cbc', '');
			mcrypt_generic_init($openMode, $secretKey, $initVector);
			$decryptedText = mdecrypt_generic($openMode, $encryptedText);
			$decryptedText = rtrim($decryptedText, "\0");
		 	mcrypt_generic_deinit($openMode);
			return $decryptedText;
			
		}

		//*********** Padding Function *********************

		function pkcs5_pad ($plainText, $blockSize)
		{
		    $pad = $blockSize - (strlen($plainText) % $blockSize);
		    return $plainText . str_repeat(chr($pad), $pad);
		}
		

		function hextobin($hexString) 
   	 	{ 
        	$length = strlen($hexString); 
        	$binString="";   
        	$count=0; 
        	while($count<$length) 
        	{       
        	    $subString =substr($hexString,$count,2);           
        	    $packedString = pack("H*",$subString); 
        	    if ($count==0)
			    {
					$binString=$packedString;
			    } 
			    else 
			    {
					$binString.=$packedString;
			    } 
        	    
		    	$count+=2; 
        	} 
  	        return $binString; 
    	} 

}

/* End of payment gateway library file for CCAvenue. */

