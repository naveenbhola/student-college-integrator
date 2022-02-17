<?php

class LocationCacheInvalidator
{
    private $_locationCache;
    
    function __construct(LocationCache $locationCache)
    {
        $CI = & get_instance();
        
        $CI->load->library('location/cache/LocationCache');
        $this->_locationCache = new LocationCache;
    }
    
    public function update()
    {
        $this->invalidateLocationCache();
    }
    
    public function invalidateLocationCache()
    {
        $this->_locationCache->clearCache();
    }
}