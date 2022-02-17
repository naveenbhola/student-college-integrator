<div class="lineSpace_5">&nbsp;</div>
<div>
<?php 

//print_r($details);
$crumb = array();
$refrer = $_SERVER['HTTP_REFERER'];

$refrer = strtolower($refrer);
if(stripos($refrer,"search/index") !== FALSE){
    $crumb['name'] = "Search Results";
    $crumb['url'] = $_SERVER['HTTP_REFERER'];
}
else{
    if(!isCategoryPageRefrer($crumb)){
        if($details['categoryArr'][0]['parent_cat_id'] == 1){
            $catUrl = $details['categoryArr'][0]['cat_url'];
            $crumb['name'] = $details['categoryArr'][0]['cat_name'];
        }
        else{
            $catUrl = $details['categoryArr'][0]['parent_url'];
            $crumb['name'] = $details['categoryArr'][0]['parent_cat_name'];
        }
        $crumb['url'] = constant("SHIKSHA_".strtoupper($catUrl)."_HOME");
    }
}

?>
    <span class="blogheading"><a href="<?php echo SHIKSHA_HOME; ?>">Home</a> > <a href="<?php echo $crumb['url']; ?>"><?php echo $crumb['name']; ?></a> > <?php echo $details['title']; ?></span>
</div>
<div class="lineSpace_7">&nbsp;</div>

