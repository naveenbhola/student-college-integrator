<?php

class CurrencyRepository extends EntityRepository
{
    function __construct($dao,$cache)
    {
        parent::__construct($dao,$cache);
        
        /*
         * Load entities required
         */ 
        $this->CI->load->entities(array('Currency'),'listing');
    }
    
    public function findCurrency($currencyId)
    {
        Contract::mustBeNumericValueGreaterThanZero($currencyId,'Currency ID');
        
        if($this->caching) {
            $data = $this->cache->getCurrency($currencyId);
            // _p($data);die('aaa');
        }

        if(empty($data)){
            $data = $this->dao->getCurrency($currencyId);
            $this->cache->storeCurrency($currencyId, $data);
        }
        
        $currency = new Currency;
        $this->fillObjectWithData($currency,$data);

        return $currency;
    }
    
}    