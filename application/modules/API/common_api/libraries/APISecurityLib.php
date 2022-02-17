<?php
class APISecurityLib
{
    private $CI;
    
    function __construct() {
        $this->iv = "fedcba9876543210";//mcrypt_create_iv(mcrypt_enc_get_iv_size($td), MCRYPT_DEV_RANDOM);;
        $this->key = '0123456789abcdef';
    }

    function encrypt($data){

        $td = mcrypt_module_open('rijndael-128', '', 'cbc', $this->iv);

        if(MOBILE_APP_NEW){
            $size = mcrypt_get_block_size('rijndael-128', 'cbc'); 
            $data = $this->pkcs5_pad($data, $size);
        }


        mcrypt_generic_init($td, $this->key, $this->iv);
        $encrypted = mcrypt_generic($td, $data);

        mcrypt_generic_deinit($td);
        mcrypt_module_close($td);

        if(MOBILE_APP_NEW){
            return base64_encode($encrypted);
        }else{
           return bin2hex($encrypted);
        }
    }

    function pkcs5_pad ($text, $blocksize) 
    { 
        $pad = $blocksize - (strlen($text) % $blocksize); 
        return $text . str_repeat(chr($pad), $pad); 
    } 

    function pkcs5_unpad($text) 
    { 
        $pad = ord($text{strlen($text)-1}); 
        if ($pad > strlen($text)) return false; 
        if (strspn($text, chr($pad), strlen($text) - $pad) != $pad) return false; 
        return substr($text, 0, -1 * $pad); 
    }

    function decrypt($data){


        if(MOBILE_APP_NEW){
            $data = base64_decode($data);
        }else{
            $data = $this->hex2bin($data);
        }
        $td = mcrypt_module_open('rijndael-128', '', 'cbc', $this->iv);

        mcrypt_generic_init($td, $this->key, $this->iv);
        $decrypted = mdecrypt_generic($td, $data);
        
        if(MOBILE_APP_NEW){
            $decrypted = $this->pkcs5_unpad($decrypted);
        }

        mcrypt_generic_deinit($td);
        mcrypt_module_close($td);

        return utf8_encode(trim($decrypted));
    }

    protected function hex2bin($hexdata) {
        $bindata = '';

        for ($i = 0; $i < strlen($hexdata); $i += 2) {
            $bindata .= chr(hexdec(substr($hexdata, $i, 2)));
        }

        return $bindata;
    }
}
