<?php
//Change the structure of SubCat Array
$allSubCategoryArray = array();
$i=0;
//_p($HomePageData);die;

foreach ($HomePageData as $catArray){
    $catId = $catArray['id'];
    $catName = $catArray['name'];
    foreach ($catArray['subcats'] as $subCategoryArray){
        if(in_array($subCategoryArray['id'],$articleExistsArray) ){
            $allSubCategoryArray[$i] = array();
            $allSubCategoryArray[$i]['subCatName'] = $subCategoryArray['name'];
            $allSubCategoryArray[$i]['subCatId'] = $subCategoryArray['id'];
            $allSubCategoryArray[$i]['URL'] = SHIKSHA_HOME.$url."?subcat=".$subCategoryArray['id'];
            $allSubCategoryArray[$i]['catId'] = $catId;
            $allSubCategoryArray[$i]['catName'] = $catName;
            $i++;
        }
    }
}

/*
$allSubCategoryArray[$i] = array();
$allSubCategoryArray[$i]['subCatName'] = 'Tally';
$allSubCategoryArray[$i]['subCatId'] = 120;
$allSubCategoryArray[$i]['catId'] = 4;
$allSubCategoryArray[$i]['catName'] = 'Banking & Finance';
$allSubCategoryArray[$i]['URL'] = SHIKSHA_HOME . '/tally-courses-in-india-categorypage-10-120-1-0-0-1-1-2-0-none-1-0';
*/

uasort($allSubCategoryArray, function ($a, $b) {
    if ($a['subCatName']==$b['subCatName']) return 0;
    return strtolower ($a['subCatName']) > strtolower ($b['subCatName']) ? 1 : -1;
});

?>

<header id="page-header" class="clearfix">
    <div data-role="header" data-position="fixed" class="head-group ui-header-fixed">
        <a id="subcategoryOverlayCloseArticle" href="javascript:void(0);" onclick="setTimeout(function() {clearAutoSuggestorForSubcatA();}, 1000);" data-rel="back"><i class="head-icon-b"><span class="icon-arrow-left" aria-hidden="true"></span></i></a>
        <h3>Choose Course</h3>
    </div>
</header>

<section class="layer-wrap fixed-wrap" style="height: 100%">

    <div class="search-option2" id="autoSuggestRefine">
            <div id="searchbox2">
                <span class="icon-search"></span>
                <input class="searchInstitute" id="subcat-list-textboxA" type="text" placeholder="Enter course name" onkeyup="subcatAutoSuggestA(this.value,'state');" autocomplete="off" style="width:80% !important; display: inline-block;">
                <i class="icon-cl" onClick="clearAutoSuggestorForSubcatA();">&times;</i>
            </div>
    </div>

    <div class="content-child2 clearfix" style="padding: 0em;">
        <section>

           <div>
               <ul class="location-list location-list2" style="margin-left: 0px !important;border-left: 0px">
               <?php foreach( $allSubCategoryArray as $subCategory ){ ?>
                       <li id="ASCsLI<?php echo $subCategory['subCatId'];?>" onClick="window.location = '<?=$subCategory['URL']?>'">
                            <label id ="ASCL_sLI<?php echo $subCategory['subCatId'];?>" style="cursor: pointer;">
                                <?php if($subCategory['subCatName']=='Event Management' || $subCategory['subCatName']=='Aircraft Maintenance Engineering' || $subCategory['subCatName']=='Game Design and Development'){ ?>
                                <span id="A<?php echo 'SCsN'.$subCategory['subCatId'];?>" style="line-height:16px;"><?php echo $subCategory['subCatName'];?> in <?php echo $subCategory['catName'];?></span>
                                <?php }else{ ?>
                                <span id="A<?php echo 'SCsN'.$subCategory['subCatId'];?>" style="line-height:16px;"><?php echo $subCategory['subCatName'];?></span>
                                <?php } ?>
                            </label>
                       </li>
               <?php } ?>
                        <li href="javascript:void(0);" id="not-found-subcat-listA" style="display:none;">
                            <label><span>No result found for this course.</span></label>
                        </li>

               </ul>
           </div>

       </section>
    </div>

</section>


<script>
function subcatAutoSuggestA(searchedString,type){
        var str='s';
        jQuery("#not-found-subcat-listA").hide();

        var showSuggestionsCount = 1000;
        var highPriorityArray = new Array();
        var lowPriorityArray = new Array();
        var a=0, b=0, c=0;
        if(searchedString!=''){
                $('span[id^="ASCsN"]').each( function(ind) {
                        var cityName = $(this).html();
                        var cityId = $(this).attr('id').split('N');
                        cityId = cityId[1];

                        if(cityName.toLowerCase().indexOf(searchedString.toLowerCase())===0){
                                highPriorityArray[a] = new Array();
                                highPriorityArray[a]['name'] = cityName;
                                highPriorityArray[a]['id'] = cityId;
                                a++;
                        }
                        if(cityName.toLowerCase().indexOf(searchedString.toLowerCase())>0){
                                lowPriorityArray[b] = new Array();
                                lowPriorityArray[b]['name'] = cityName;
                                lowPriorityArray[b]['id'] = cityId;
                                b++;
                        }
                });
                $("li[id*='ASCsLI']").hide();
        }
        else{
                $("li[id*='ASCsLI']").show();
        }
        for(var i=0,x=showSuggestionsCount; i<highPriorityArray.length && i<x; i++)     {
                $('#ASCsLI'+highPriorityArray[i]['id']).show();
        }

        if(i<10){
                var valuesLeft = showSuggestionsCount - i;
                for(var j=0,k=showSuggestionsCount; j<lowPriorityArray.length && j<valuesLeft; j++)     {
                        $('#ASCsLI'+lowPriorityArray[j]['id']).show();
                }
        }
        if(searchedString!=''){
                if(highPriorityArray.length==0 && lowPriorityArray.length==0 && str=='s'){
                        jQuery("#not-found-subcat-listA").show();
                }
        }
}


function clearAutoSuggestorForSubcatA(){
    jQuery('#subcat-list-textboxA').val('');
    subcatAutoSuggestA('','state');
}

</script>

