<?php
$requestUrl = clone $request;
$requestUrl->setData(array('naukrilearning'=>0));		
$canonicalurl = $requestUrl->getCanonicalURL($requestUrl->getPageNumberForPagination());
$headerComponent = array(
        'canonicalURL' => $canonicalurl
);
$this->load->view('/mcommon/header',$headerComponent);
?>
<div class="search-btn-back">
	<a href="/search/showSearchHome" class="gray-button2">Search Institutes & Courses</a>
</div>
<script language = javascript>
var COOKIEDOMAIN = "<?php echo COOKIEDOMAIN; ?>";

function setCookie(c_name,value,expireTime,timeUnit) {
    var today = new Date();
    today.setTime( today.getTime() );
    var cookieExpireValue = 0;
    expireTime = (typeof(expireTime) != 'undefined')?expireTime:0;
    timeUnit = (typeof(timeUnit) != 'undefined')?timeUnit:'days';
    if(expireTime != 0){
        if(timeUnit == 'seconds'){
            expireTime = expireTime * 1000;
        }else{
            expireTime = expireTime * 1000 * 60 * 60 * 24;
        }
        var exdate=new Date( today.getTime() + (expireTime) );
        var cookieExpireValue = exdate.toGMTString();
        document.cookie=c_name+ "="
        +escape(value)+";path=/;domain="+COOKIEDOMAIN+""+((expireTime==null) ? "" : ";expires="+cookieExpireValue);
    }else{
        document.cookie=c_name+ "=" +escape(value)+";path=/;domain="+COOKIEDOMAIN;
    }
    if(document.cookie== '') {
        return false;
    }
    return true;
}
</script>
<?php 
$locationBuilder = new LocationBuilder;
$locationRepository = $locationBuilder->getLocationRepository();
global $cityList;
$cityList = $locationRepository->getCitiesByMultipleTiers(array(1),2);
$_COOKIE['userCity']=="All Cities";

?>
<div id="head-sep"></div>
<div id="head-title">
    <h4><?=$subCategory->getName();?></h4>
    <div id="change-loc">
        <div align="left" class="location">
            <label id ='location'> <?php echo $categoryPage->getCity()->getName();?></label> 
            <select id ="select" onchange="update_location(this.value)" >
<?php 
setcookie('subcatcookie',$subCategory->getName() . "###" . $categoryPage->getCity()->getName() ,0,'/',COOKIEDOMAIN);

$urlRequest = clone $request;
$urlRequest->setData(array('cityId'=>1,'stateId'=>1,'countryId'=>2,'localityId'=>0,'zoneId'=>0,'regionId'=>0));
$cId = 1;	
$selected = '';

$cookieValues=explode(':',$_COOKIE['userCityPreference']);
if('1'== $cookieValues[0])				
    $selected = 'selected ';			
?>		
        <option  <?php echo $selected;?> value = "<?php echo ($urlRequest->getURL().'|'.$cId.'|1'); ?>" >All Cities </option>	
<?php foreach($cityList[1] as $city){
    $urlRequest = clone $request;
    $selected ='';
    if($_COOKIE['userCityPreference'] !== false){ 
        $cookieValues=explode(':',$_COOKIE['userCityPreference']);
        error_log("CITYID".print_r($city->getId(),true));
        if($city->getId() == $cookieValues['0'])	{		
            $selected = 'selected ';
        }
    }

    $urlRequest->setData(array('cityId'=>$city->getId(),'stateId'=>$city->getStateId(),'localityId'=>0,'zoneId'=>0));?>
    <option <?=$selected;?> value="<?php echo $urlRequest->getURL().'|'.$city->getId().'|'.$city->getStateId(); ?>"><?php echo $city->getName();}?></option> 
        </select>
        </div>
    </div>
    <span>&nbsp;</span>
</div>
<?php
    if (getTempUserData('confirmation_message')){?>
        <div style="border:1px solid #fff590; background:#feffcd; padding:10px; font:bold 13px Arial, Helvetica, sans-serif; margin:8px;">
        <?php echo getTempUserData('confirmation_message'); ?>
        </div> 
<?php } 
?>
<?php 
   deleteTempUserData('confirmation_message');
?>
<script>
var link = "<?php  echo  $categoryPage->getCity()->getName(); ?>";
var selectobject=document.getElementById("select");
for (var i=0; i<selectobject.length; i++){
    if((selectobject.options[i].text) == link)
    {
        selectobject.options[i].selected = true;
    }
}
</script>
