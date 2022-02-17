<header id="page-header" class="clearfix" >
        <div data-role="header" data-position="fixed" class="head-group ui-header-fixed" data-tap-toggle="false">
	    <a id="examOverlayClose" href="javascript:void(0);" data-rel="back"><i class="head-icon-b"><span class="icon-arrow-left" aria-hidden="true"></span></i></a>   
            <h3>Select Exams given by you</h3>
        </div>
</header>

<section class="layer-wrap fixed-wrap">
	<p class="rnr-title-txt">This is an Optional field, select exam to get better results</p>
	<ul class="layer-list rnr-list">
	<?php for($i=0;$i<count($exams);$i++){ ?>
    	<li>
	        <label><input type="checkbox" value="<?php echo $exams[$i]['exam'];?>" name="exams[]" style="vertical-align:middle"/><span  style="vertical-align:middle"> <?php echo $exams[$i]['exam'];?></span></label>    
	<!--<select><option>Around 95</option></select>-->
        </li>
	<?php } ?>
	<li>
	        <label><input type="checkbox" value="No Exam Required" name="exams[]" style="vertical-align:middle" /><span  style="vertical-align:middle"> No Exam Required</span></label>
	</li>
	<li>
		&nbsp;
        </li>    	
    </ul>
</section>
<a id="examButton" class="refine-btn" href="javascript:void(0);" onclick="examSelectionDone();"><span class="icon-done"><i></i></span> Done</a>
<script>
jQuery(document).ready(function(){
window.onscroll = function() { 
        jQuery('#examButton').css({position: 'fixed', left: '0px', bottom: '0px', width:'100%'});
}
});

function examSelectionDone(){
	var elementsArr = document.getElementsByName("exams[]");
	var filterArray = "{";
	var examSelectedOnHomePage = 0;
        filterArray += '"exams" : ' ;
	subFilterArray = "[";
        for(var i=0;i<elementsArr.length;i++){
            if(elementsArr[i].checked){
                if(subFilterArray != "["){
                    subFilterArray += ",";
                }
                subFilterArray += '"' + elementsArr[i].value + '"' ;
		++examSelectedOnHomePage;
            }
        }
        filterArray += subFilterArray + "]}";
	if(examSelectedOnHomePage>0){
	$('input[name=examSelected]').val(base64_encode(filterArray));
	}else{
		$('input[name=examSelected]').val('');
		$('input[name=cityStateId]').val('');
	}
	
	$('#examOverlayClose').click();
	$('#examSelectionText').html('Exam Selected('+examSelectedOnHomePage+')');
	//createLocationHTMLForExams();
	
	 if(examSelectedOnHomePage > 0){
		if(getCookie('currentGeoLocation')!=''){
	        	selectLocationIfExistsInExamLocation();
		}
	 	createLocationHTMLForExams();
	 }else{
		createLocationHTML();
		$('input[name=cityStateId]').val();
	 }
	homepageScroll();
}

function selectLocationIfExistsInExamLocation(){
	var examSelected = $('input[name=examSelected]').val();
	jQuery.ajax({
		url: "/mcommon5/MobileSiteHome/checkLocationExists",
		type: "POST",
		data: {'examSelected':examSelected},	
		success: function(result)
		{
		    if(result!='NORESULT'){
		    var splitRes = result.split(':');
		    var cityName = splitRes[0];
		    var cityIdForExam = splitRes[1];
		    var stateIdForExam = splitRes[2];
		    $('#locationText').html(cityName);
		    $('input[name=locationIdSelected]').val(cityIdForExam);
		    $('input[name=cityStateId]').val(cityIdForExam+':'+stateIdForExam);
		    }else{
			    $('#locationText').html('Select Location');
		    }
		}
    	});
}

function createLocationHTMLForExams(){
     var examSelected = $('input[name=examSelected]').val();
     $('#locationDiv').html('<div style="margin-top:100px;text-align:center"><img class="lazy" border=0 src="/public/mobile5/images/ajax-loader.gif?"></div>');
    jQuery.ajax({
        url: "/mcommon5/MobileSiteHome/showLocationHTMLForExams",
        type: "POST",
		data: {'examSelected':examSelected},	
        success: function(result)
        {
            $('#locationDiv').html(result);
        }
    });
}
</script>
