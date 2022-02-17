<?php

class RotationService
{
    private $cache;
    private $request;
    
    function __construct($cache = NULL,CategoryPageRequest $request = NULL)
    {
        $this->cache = $cache;
        $this->request = $request;
    }
    
    public function rotateBanners($banners,$isAbroadCategoryPageRequest = FALSE) /** Flag added to set rotation time to 15 min in case of abroad category pages**/
    {
        /*
        $rotationIndex = $this->cache->getBannerRotationIndex($this->request);
        if($rotationIndex === FALSE || $rotationIndex === '') {
            $bannerIds = array_keys($banners);
            $rotationIndex = array_rand(array_keys($bannerIds));
            $this->cache->storeBannerRotationIndex($this->request,$rotationIndex);
        }
        $rotatedBanners = $this->_applySequentialRotation($banners,$rotationIndex);
        return $rotatedBanners;
        */
        
        // Sequential rotation of banners (#1788)..        
        $rotationIndex = $this->cache->getBannerRotationIndex($this->request);
        $rotationIndexFlag = $this->cache->getLastBannerRotationFlag($this->request);
        
        // If any of the Rotation cache field (Flag or Index) is expired then reset them..
        if(($rotationIndexFlag === FALSE || $rotationIndexFlag === '') || ($rotationIndex === FALSE || $rotationIndex === '')) {
                $bannerIds = array_keys($banners);
                if($rotationIndex === FALSE || $rotationIndex === '') {
                    $rotationIndex = 0;
                } else {
                    if($rotationIndex < (int)(count($bannerIds) -1)) {
                        $rotationIndex = $rotationIndex + 1;
                    } else {
                        $rotationIndex = 0;
                    }
                }
                
                $this->cache->storeBannerRotationIndex($this->request,$rotationIndex);
                $this->cache->storeLastBannerRotationFlag($this->request,$isAbroadCategoryPageRequest); /** Flag added to set rotation time to 15 min in case of abroad category pages**/
        }
        
        $rotatedBanners = $this->_applySequentialRotation($banners,$rotationIndex);
        return $rotatedBanners;        
    }
    /*
     * this is the rotation function being called for multilocation case of category page
     * it rotates institutes within an inventory bucket(cs/mil/paid/free) , within a location  
     */
    public function rotateLocationWiseInventory( $locationWiseArray)
    {
        
        // get main rotation index from cache
        $clonedRequest = clone $this->request;
        $clonedRequest->setData(array('cityId'=>1,'stateId'=>1,'regionId'=>0));
        
        $mainRotationIndex = $this->cache->getInventoryBucketInstitutesRotationIndex("LocationWiseInventory",$clonedRequest);
        $rotationIndexFlag = $this->cache->getInventoryBucketInstitutesRotationFlag($clonedRequest);
        
        // If any of the Rotation cache field (Flag or Index) is expired then reset them..
        if(($rotationIndexFlag === FALSE || $rotationIndexFlag === '') || ($mainRotationIndex === FALSE || $mainRotationIndex === '')) {
                if($mainRotationIndex === FALSE || $mainRotationIndex === '') {
                    // for the very first time this rotation index would not be available in cache, so store in cache & return since no rotation would be applied for the first time
                    $mainRotationIndex = 0;
                } else {
                        $mainRotationIndex++;
                }
                
                //update reset/incremented rotation index in cache
                $this->cache->storeInventoryBucketInstitutesRotationIndex("LocationWiseInventory",$clonedRequest,$mainRotationIndex );
                $this->cache->storeInventoryBucketInstitutesRotationFlag($clonedRequest);
        }
        
        // apply rotation index 
        foreach ($locationWiseArray as $locId => &$locationWiseElement)
        {
            //echo "<br>locationWiseElement ::$locationWiseElement    ";  _p($locationWiseElement['data']);
            foreach($locationWiseElement['instituteData'] as $inventoryBucket => $bucketWiseData)
            {
                // skip the inventory bucket if empty
                if(count($locationWiseElement['instituteData'][$inventoryBucket])==0){ 
                    continue;
                }
                /*
                 * now the formula for bucketwise rotation index will be applied
                 * if main rotation index < size of bucket(size of keyMap will do)
                 *      bucket's rotation index  = main rotation index 
                 * else
                 *      remainder of main rotation index divided by size of bucket/keymap
                 */
                //$mainRotationIndex = 1;
                $bucketRotationIndex = $mainRotationIndex % count($locationWiseElement['instituteData'][$inventoryBucket]);
                //echo "<br>bucketRotationIndex ".$bucketRotationIndex ;
                $slice1 = array_slice($locationWiseElement['instituteData'][$inventoryBucket],$bucketRotationIndex,NULL,true);
                $slice2 = array_slice($locationWiseElement['instituteData'][$inventoryBucket],0,$bucketRotationIndex,true);
                $locationWiseElement['instituteData'][$inventoryBucket] = $slice1 + $slice2;
                
                //echo "<br> after rotation ";_p($locationWiseElement['instituteData'][$inventoryBucket]);
            }
        }
        // return rotated data
        return $locationWiseArray;
    }
    public function rotateStickyInstitutes($stickyInstitutes,$banner)
    {
        $stickyInstitutes = $this->_rotateInstitutes('sticky',$stickyInstitutes);
        if($banner && ($bannerInstituteId = $banner->getInstituteId())) {
            if(isset($stickyInstitutes[$bannerInstituteId])) {
                $bannerInstitute = $stickyInstitutes[$bannerInstituteId];
                unset($stickyInstitutes[$bannerInstituteId]);
                $stickyInstitutes = array_reverse($stickyInstitutes,TRUE);
                $stickyInstitutes[$bannerInstituteId] = $bannerInstitute;
                $stickyInstitutes = array_reverse($stickyInstitutes,TRUE);
            }
        }
        return $stickyInstitutes;
    }
    
    public function rotateMainInstitutes($mainInstitutes)
    {
        return $this->_rotateInstitutes('main',$mainInstitutes);
    }
    
    public function rotatePaidInstitutes($paidInstitutes)
    {
        return $this->_rotateInstitutes('paid',$paidInstitutes);
    }
    
    private function _rotateInstitutes($type,$institutes)
    {
        $rotationIndex = $this->cache->getInstitutesRotationIndex($type,$this->request);

        if($rotationIndex === FALSE || $rotationIndex == '') {

            $instituteIds = array_keys($institutes);
            $rotationIndex = array_rand(array_keys($instituteIds));
            $this->cache->storeInstitutesRotationIndex($type,$this->request,$rotationIndex);
        }
              
        $rotatedInstitutes = $this->_applySequentialRotation($institutes,$rotationIndex);
        return $rotatedInstitutes;    
    }
    
    private function _applySequentialRotation($list,$startIndex)
    {
        $startIndex = (int) $startIndex;
        $rotatedList = array_slice($list,$startIndex,NULL,TRUE) + array_slice($list,0,$startIndex,TRUE);
        return $rotatedList;
    }
    
    public function rotateCountryList($countryIds) {        
        $rotationIndex = $this->cache->getCountryRotationIndex($this->request);
        $rotationIndexFlag = $this->cache->getLastCountryRotationFlag($this->request);        
        // If any of the Rotation cache field (Flag or Index) is expired then reset them..
        if(($rotationIndexFlag === FALSE || $rotationIndexFlag === '') || ($rotationIndex === FALSE || $rotationIndex === '')) {
                // $bannerIds = array_keys($banners);
                if($rotationIndex === FALSE || $rotationIndex === '') {
                    $rotationIndex = 0;
                } else {
                    if($rotationIndex < (int)(count($countryIds) -1)) {
                        $rotationIndex = $rotationIndex + 1;
                    } else {
                        $rotationIndex = 0;
                    }
                }
                
                $this->cache->storeCountryRotationIndex($this->request,$rotationIndex);
                $this->cache->storeLastCountryRotationFlag($this->request);
        }        
        $rotatedCountries = $this->_applySequentialRotation($countryIds,$rotationIndex);
        return $rotatedCountries;
    }
    
    
	/*  Reason :For rotating banner on all country page 
	 * Params : $countryIds
	 *  author : Abhay
	*/
    public function rotateCountryListForCountryPageBanner($countryIds) {
        
        $rotationIndex = $this->cache->getCountryRotationIndexForCountryPageBanner();
        $rotationIndexFlag = $this->cache->getLastCountryRotationFlagForCountryPageBanner();        
        // If any of the Rotation cache field (Flag or Index) is expired then reset them..
        if(($rotationIndexFlag === FALSE || $rotationIndexFlag === '') || ($rotationIndex === FALSE || $rotationIndex === '')) {
                // $bannerIds = array_keys($banners);
                if($rotationIndex === FALSE || $rotationIndex === '') {
                    $rotationIndex = 0;
                } else {
                    if($rotationIndex < (int)(count($countryIds) -1)) {
                        $rotationIndex = $rotationIndex + 1;
                    } else {
                        $rotationIndex = 0;
                    }
                }
                
                $this->cache->storeCountryRotationIndexForCountryPageBanner($rotationIndex);
                $this->cache->storeLastCountryRotationFlagForCountryPageBanner();
                              
        }        
        $rotatedCountries = $this->_applySequentialRotation($countryIds,$rotationIndex);
                   

        return $rotatedCountries;
    }
    /*
     * This function puts the university on top, whose shoshkele/catspon banner is being displayed
     * in case of "sort by :popularity"
     */
    public function rotateCategorySponsorsAmongPopularUnivs($universities,$banner)
    {
        // Simply get univ mapped to banner & place it atop the list
        if($banner && ($bannerUniversityId = $banner->getInstituteId()))
		{
            if(isset($universities[$bannerUniversityId])) {
                $bannerUniv = $universities[$bannerUniversityId]; // extract
                unset($universities[$bannerUniversityId]); // remove
				$bannerUnivArr = array($bannerUniversityId=>$bannerUniv);// place at front
				$universities = $bannerUnivArr + $universities; // add others
            }
        }
        return $universities;
    }
    /*
     * rotation of cat spons [abroad category page running on solr]
     */
    public function rotateCategorySponsors($stickyUniversities,$banner)
    {
        $stickyUniversities = $this->_rotateUnivs('sticky',$stickyUniversities);
        if($banner && ($bannerUniversityId = $banner->getInstituteId()))
		{
            if(isset($stickyUniversities[$bannerUniversityId])) {
                $bannerUniv = $stickyUniversities[$bannerUniversityId]; // extract
                unset($stickyUniversities[$bannerUniversityId]); // remove
				$bannerUnivArr = array($bannerUniversityId=>$bannerUniv);// place at front
				$stickyUniversities = $bannerUnivArr + $stickyUniversities; // add others
            }
        }
        return $stickyUniversities;
    }
    /*
     * rotation of main univs [abroad category page running on solr]
     */
    public function rotateMainUnivs($mainUniversities)
    {
        return $this->_rotateUnivs('main',$mainUniversities);
    }
    /*
     * rotation of paid univs [abroad category page running on solr]
     */
    public function rotatePaidUnivs($paidUniversities)
    {
        return $this->_rotateUnivs('paid',$paidUniversities);
    }
    /*
     * rotation of free univs [abroad category page running on solr]
     */
    public function rotateFreeUnivs($freeUniversities)
    {
        return $this->_rotateUnivs('free',$freeUniversities);
    }
    /*
     * rotation of universities
     */
    private function _rotateUnivs($type,$universities)
    {
        $rotationIndex = $this->cache->getInstitutesRotationIndex($type,$this->request);
        if($rotationIndex === FALSE || $rotationIndex == '') {
            $universityIds = array_keys($universities);
            $rotationIndex = array_rand(array_keys($universityIds));
			error_log("SRB rotatin:".$type.":".$rotationIndex);
            $this->cache->storeInstitutesRotationIndex($type,$this->request,$rotationIndex);
        }
		$rotatedUniversities = $this->_applySequentialRotation($universities,$rotationIndex);
        return $rotatedUniversities;    
    }
}
