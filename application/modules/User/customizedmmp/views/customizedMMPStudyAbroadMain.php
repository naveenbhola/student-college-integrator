<!-- customizedMMPHeader starts -->
<?php $this->load->view('multipleMarketingPage/customizedMMPStudyAbroadHeader');?>
<!-- customizedMMPHeader ends -->

	<div>
	   	<!--Start_OuterBorder-->
		<div style="width:99%;">
            <div style="float:left;width:100%;">
                <!--Start_Form-->
                <div style="width:100%;">
   			        <?php $this->load->view('multipleMarketingPage/customizedMMPStudyAbroadForm');?>
                </div>
                <!--End_Form-->
            </div>
        </div>
    </div>
	<?php
		/* study country overlay starts */
		//$this->load->view('multipleMarketingPage/studyCountryOverlay');
		/* study country overlay ends */
		/* singlesinginoverlay overlay starts */
		$this->load->view('multipleMarketingPage/customizedMMPSignInOverlay');
		/* singlesinginoverlay overlay ends */
		/* register confirmation view starts */
		$this->load->view('user/registerConfirmation');
		/* register confirmation view ends */
	?>
	<div class="clear_L"></div>
	<div class="lineSpace_10">&nbsp;</div>
	<div id="emptyDiv" style="display:none;">&nbsp;</div>

<script type="text/javascript">
	var userCity = "";
	<?php
	if($logged == "Yes") { ?>
        userCity = "<?php echo $userData[0]['city']?>";
	<?php
	}?>
    categoryList = eval(<?php echo json_encode($allCategories);?>);
	fillProfaneWordsBag();
    addOnBlurValidate(document.getElementById("frm1"));
    //addOnFocusToopTip1(document.getElementById("frm1"));
    function OneCategoryForm(){
        var selectObj = $("fieldOfInterest");
        var num = selectObj.options.length;
        if(num == 2){
            selectObj.selectedIndex = 1;
            $("fieldOfInterest").style.display = 'none';
            var newdiv = document.createElement('div');
            newdiv.innerHTML = selectObj.options[1].text;
            $("fieldOfInterestFieldContainer").getElementsByTagName("div")[0].appendChild(newdiv);
        }
    }
	window.onload = function () {
        try {
            OneCategoryForm();
	        //publishBanners();
		} catch (e) {
            //alert(e);
        }
    }
	var FLAG_LOCAL_COURSE_FORM_SELECTION = 0;
	function loadScript(url, callback){
		var script = document.createElement("script")
		script.type = "text/javascript";
		if (script.readyState){  //IE
			script.onreadystatechange = function(){
				if (script.readyState == "loaded" ||
						script.readyState == "complete"){
					script.onreadystatechange = null;
					callback();
				}
			};
		} else {  //Others
			script.onload = function(){
				callback();
			};
		}
		script.src = url;
		document.getElementsByTagName("head")[0].appendChild(script);
	}
	loadScript('//<?php echo JSURL; ?>/public/js/<?php echo getJSWithVersion("ajax-api"); ?>', function() {
		//initialization code
		messageObj = new DHTML_modalMessage();
		messageObj.setShadowDivVisible(false);
		messageObj.setHardCodeHeight(0);
	});
</script>
<?php
	// load customizedMMPFooter: This file contains code related to tracking etc
	$this->load->view('multipleMarketingPage/customizedMMPFooter');
?>
