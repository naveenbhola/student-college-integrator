<header id="page-header" class="clearfix">

    <div data-role="header" data-position="fixed" class="head-group ui-header-fixed">

	<a id="locationOverlayClose" href="javascript:void(0);" onclick="clearAutoSuggestor('layer-list-ul-exams');" data-rel="back"><i class="head-icon-b"><span class="icon-arrow-left" aria-hidden="true"></span></i></a>   	

        <h3>Choose Location</h3>

    </div>

</header>

<?php

    $cityListArray = array();

    $metroCitiesArray = array();

    $i=0;

?>

<section class="layer-wrap fixed-wrap" style="height: 100%">



    	<div class="search-option2">

            <div id="searchbox2">

            	<span class="icon-search"></span>

                <input id="search" type="text" placeholder="Enter city name" onkeyup="locationAutoSuggest(this.value,'layer-list-ul-exams');" autocomplete="off">

                <i class="icon-cl" onclick="clearAutoSuggestor('layer-list-ul-exams');">&times;</i>

            </div>

        </div>



    

    <ul class="layer-list" id="layer-list-ul-exams">

	<li onClick="locationSelectedForExams('1','1');"><a href="javascript:void(0);" id="locationName1">All Cities</a></li>

	<?php

	    $cityListArray['cityNames'][$i]['Name'] = 'All Cities';
	    $cityListArray['cityNames'][$i]['Id'] = '1';
		$cityListArray['cityNames'][$i]['stateId'] = '1';

	    $i++;

	?>



	<?php foreach($cityList[1] as $city){ ?>

	    <li onClick="locationSelectedForExams('<?=$city->getId();?>','<?=$city->getStateId();?>');"><a href="javascript:void(0);" id="locationName<?=$city->getId();?>"><?php echo $city->getName();?></a></li>

	<?php
	    if($city->getId()!='1'){
	    $cityListArray['cityNames'][$i]['Name'] = $city->getName();
	    $cityListArray['cityNames'][$i]['Id'] = $city->getId();
	    $cityListArray['cityNames'][$i]['stateId'] = $city->getStateId();
	    $metroCitiesArray[] = $city->getId();
	    $i++;
	    }
	}

	

	foreach($cityList[2] as $city){
		if($city->getId()!='1'){
	    $cityListArray['cityNames'][$i]['Name'] = $city->getName();
	    $cityListArray['cityNames'][$i]['Id'] = $city->getId();
	    $cityListArray['cityNames'][$i]['stateId'] = $city->getStateId();
	    $i++;
		}
	}



	foreach($cityList[3] as $city){
		if($city->getId()!='1'){
	    $cityListArray['cityNames'][$i]['Name'] = $city->getName();
	    $cityListArray['cityNames'][$i]['Id'] = $city->getId();
	    $cityListArray['cityNames'][$i]['stateId'] = $city->getStateId();
	    $i++;
		}
	}

	?>

    </ul>

      

</section>



<?php

$post_data = json_encode($cityListArray);

echo "<script>cityJSON = $post_data;</script>";

echo "<script> metroCities = new Array(1,".implode(',',$metroCitiesArray).");</script>";

?>
