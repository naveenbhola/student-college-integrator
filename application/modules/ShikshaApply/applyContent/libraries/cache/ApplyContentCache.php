<?php
/**
 * Created by PhpStorm.
 * User: prateek
 * Date: 30/10/18
 * Time: 12:32 PM
 */

class ApplyContentCache extends Cache
{
    private $cacheExpirationTime = 86400;

    function __construct()
    {
        parent::__construct();
    }

    function getPopularApplyContentByApplyContentType($applyContentTypeId_noOfApplyContents)
    {
        $data = $this->get('SAPopularApplyContentByApplyContentTypeId-', $applyContentTypeId_noOfApplyContents);
        return unserialize($data);
    }

    function storePopularApplyContentByApplyContentType($applyContentTypeId_noOfApplyContents, $data)
    {
        $this->store('SAPopularApplyContentByApplyContentTypeId-', $applyContentTypeId_noOfApplyContents, serialize($data), $this->cacheExpirationTime);
    }
}
