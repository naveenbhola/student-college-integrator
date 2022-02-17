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
function update_country(country){
    if (document.getElementById('select1')) {
        document.getElementById('select1').disabled = true;
    }
    var countryArray = country.split("|");
    var id = countryArray[1];
    var url = countryArray[0];
    var regionId = countryArray[2];
    setCookie('countryId',id);
    setCookie('regionId_countryIdCookie',regionId+'|'+id);
    window.location = url;

}
function update_category(category){
    if ( document.getElementById('select2')) { document.getElementById('select2').disabled = true;}
        var categoryArray = category.split("|");
    var id = categoryArray[1];
    var url = categoryArray[0];
    setCookie('catIdCookie',id);
    window.location = url;
}

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
        if(timeUnit == 'homepage'){ cookieExpireValue = getCookie('homepage_ticker_track');}
        document.cookie=c_name+ "=" +escape(value)+";path=/;domain="+COOKIEDOMAIN+""+((expireTime==null) ? "" : ";expires="+cookieExpireValue);
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
$countriesArray = $locationRepository->getCountriesByRegion($request->getRegionId());
$countryStr = "";
foreach($countriesArray as $country){
    $countryStr .= $country->getId() . ",";
}
if(count($countriesArray) == 0)
{
    $urlRequest = clone $request;
    $countryStr = $urlRequest->getCountryId()  . ",";
}
global $count_country;
$count_country = count($countriesArray);
$categories = $categoryRepository->getSubCategories(1,'');
?>
<div id="head-sep"></div>
<div id="head-title">
    <h4><?php echo $category_data->getName();$urlRequest = clone $request;if($urlRequest->getCountryId()==1){echo " in ".$locationRepository->findRegion($request->getRegionId())->getName();} else echo " in ".$locationRepository->findCountry($urlRequest->getCountryId())->getName();?></h4>

  <div id="change-loc">
        <div align="left" class="location">

            <select id ="select1" onchange="update_category(value)" style="width:140px" >
<?php
$urlRequest = clone $request;
if($urlRequest->getCountryId()==1){
    $place = $locationRepository->findRegion($request->getRegionId())->getName();

} else {
    $place = $locationRepository->findCountry($urlRequest->getCountryId())->getName();
}
setcookie('subcatcookie',$category_data->getName() . "###" . $place ,0,'/',COOKIEDOMAIN);
$urlRequest = clone $request;
$urlRequest->setData(array('categoryId'=> 1 ,'subCategoryId'=>1,'LDBCourseId'=>1)); 
$cname = "All";	$cId = 1;	
$selected = '';if((!isset($_COOKIE['catIdCookie']))||$_COOKIE['catIdCookie']=="1")$selected = 'selected';?>		
        <option  <?=$selected;?> value = "<?php echo ($urlRequest->getURL().'|'.$cId); ?>" >All</option>
<?php foreach($categories as $category){
    if(in_array($category->getId(),array(14,11))){
        continue;
    }
    $urlRequest = clone $request;
    $selected ='';
    if($_COOKIE['catIdCookie'] !== false && $category->getId() == $_COOKIE['catIdCookie']){
        $selected = 'selected';}

            $urlRequest->setData(array('categoryId'=>$category->getId(),'subCategoryId'=>1,'LDBCourseId'=>1)); ?>
            <option <?=$selected;?> value = "<?php echo $urlRequest->getURL().'|'.$category->getId(); ?>" ><?php echo $category->getName();?>
        </option> <?php }?>
        </select>


    <?php if($count_country>0){ ?>
    <select id ="select2" onchange="update_country(value)" >
<?php 
        $urlRequest = clone $request;
        $urlRequest->setData(array('countryId'=> 1 )); 
        $cname = "All Countries";$cId = 1;	
        $selected = '';if((!isset($_COOKIE['countryId']))||$_COOKIE['countryId']=="1")$selected = 'selected';?>		
        <option  <?=$selected;?> value = "<?php echo ($urlRequest->getURL().'|'.$cId.'|'.$urlRequest->getRegionId());?>" >All Countries</option>

<?php 
        $urlRequest = clone $request; 

        foreach($countriesArray as $country){
            $urlRequest = clone $request;
            $selected ='';
            if($_COOKIE['countryId'] !== false && $country->getId() == $_COOKIE['countryId']){
                $selected = 'selected';}

                    $urlRequest->setData(array('countryId'=>$country->getId()));?>
        <option <?=$selected;?>  value = "<?php echo $urlRequest->getURL().'|'.$country->getId().'|'.$urlRequest->getRegionId(); ?>" ><?php echo $country->getName();?>
        </option>   <?php }?>
        </select>
      <?php }?>

        </div>
    <!--<div align="right" class="refine">
        <a href="#">Refine</a>
        </div>-->
    </div>
    <span>&nbsp;</span>
</div>
<?php
if (getTempUserData('confirmation_message')){?>
        <div style="border:1px solid #fff590; background:#feffcd; padding:10px; font:bold 13px Arial, Helvetica, sans-serif; margin:8px;">
                 <?php echo getTempUserData('confirmation_message'); ?>
        </div> 
    <?php } ?>
    <?php 
    deleteTempUserData('confirmation_message');
    ?>
