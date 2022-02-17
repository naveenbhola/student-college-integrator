<?php

class CacheAnalyzer extends CI_Controller
{

function atest()
{

$this->load->library(array('LDB_Client','MiscClient'));
$ldbObj = new LDB_Client();
$userIds = "1634687,1959233,1969234";
_p(json_decode($ldbObj->sgetUserDetails($appID, trim($userIds,',')), true));

}

function clearCache($productKey)
{
        // $productKey would be one of these: 'LDBCourseCache', 'messageBoard', 'CategoryCache', 'Events', 'listing', 'groups', 'homePage', 'misc' etc.

        echo "<b>All keys to be deleted for product : '".$productKey."'</b>";

	$this->load->library('CacheLib');
        $keys = $this->cachelib->get($productKey);

        if($keys == "ERROR_READING_CACHE") {

         echo "<br><br> This product does not exist in Cache.";

        } else {

            echo "<br><br>Cache Keys to be deleted are: "; _p($keys); // die;

            foreach($keys as $key)
            {
                $this->cachelib->clearCacheForKey($key);
            }

            $this->cachelib->clearCacheForKey($productKey);
        }
}


function index()
{
//$this->load->builder('LocationBuilder','common');
//$locationBuilder = new LocationBuilder();
//$rep = $locationBuilder->getLocationRepository();

//$c = $rep->findCountry(2,'test');
//_p($c);
//exit();

        $this->load->library('CacheLib');
       // $this->cachelib->store("cacheConnCounter",1);

		
$time1 = strtotime("2012-03-20 10:20:00");
$time2 = time();
$timeElapsed = $time2-$time1;
echo "Time Elapsed: ".($timeElapsed)." seconds<br /><br />";

$gets = $this->cachelib->get('cacheConnCounter');
$getsRate = number_format($gets/$timeElapsed,2);
//$sets = $this->cachelib->get('cacheCounterStore');
//$setsRate = number_format($sets/$timeElapsed,2);


	echo "Get: ".$gets." (".$getsRate."/sec)";      
	echo "<br /><br />";		
  //      echo "Set: ".$sets." (".$setsRate."/sec)";

  
//        $keys = array_unique($this->cachelib->get(CACHEPRODUCT_CATEGORYPAGE_INSTITUTES));
       // $keys = array_unique($this->cachelib->get('misc'));
//print_r($keys);
//_p($keys);
//exit();
  //      echo count($keys)."<br />";
//        echo memory_get_usage();
    //    $data = $this->cachelib->getMulti($keys);
 //       echo strlen(serialize($data));
//        echo "<br />".memory_get_usage();
}

  function cleanCityCache()
  {
    $this->load->library('CacheLib');
    $this->cachelib->clearCacheForKey(md5("getCitiesInTier12"));
    $this->cachelib->clearCacheForKey(md5("getCitiesInTier22"));
    $this->cachelib->clearCacheForKey(md5("getCitiesInTier32"));
    $this->cachelib->clearCacheForKey(md5("sgetCityStateList12"));
  }

}
