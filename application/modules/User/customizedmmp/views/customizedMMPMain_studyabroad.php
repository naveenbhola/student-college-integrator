<!-- customizedMMPHeader starts -->
<?php $this->load->view('customizedmmp/customizedMMPStudyAbroadHeader');?>
<!-- customizedMMPHeader ends -->


	<div style="width:99%;float:left;padding-left:10px;">
		<div>
			<!--Start_OuterBorder-->
			<div style="width:100%;">
				<div style="float:left;width:100%;">
					<!--Start_Form-->
					<div style="width:100%">
						<?php $this->load->view('customizedmmp/customizedMMPStudyAbroadForm');?>
					</div>
					<!--End_Form-->
				</div>
			</div>
		</div>
		<?php
			/* study country overlay starts */
			//$this->load->view('customizedmmp/studyCountryOverlay');
			/* study country overlay ends */
			/* singlesinginoverlay overlay starts */
			$this->load->view('customizedmmp/customizedMMPSignInOverlay');
			/* singlesinginoverlay overlay ends */
			/* register confirmation view starts */
			$this->load->view('user/registerConfirmation');
			/* register confirmation view ends */
		?>
	</div>
	<div class="clear_L" style="clear:both;"></div>
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
	
	function showDestinationCountryOverlay(obj, overlayAlign) {
		var heightTop = document.all ? document.documentElement.scrollTop : window.pageYOffset;
		var heightLeft = document.all ? document.documentElement.scrollLeft : window.pageXOffset;   
		var overlayCon = document.getElementById('userPreferenceCountry').innerHTML;
		if( overlayCon == '') {
			overlayCon = document.getElementById('genOverlayContents').innerHTML;
		}
		var overlayWidth = 778;
		var xPosition = obtainPostitionX(obj) + 200 - 778;
		var marginLeftForOverlayAlign = (FIXED_RESOLUTION_WIDTH - (obtainPostitionX(obj) )  > overlayWidth  ) ? 10 : 210;
		showOverlay(overlayWidth, 199,'',overlayCon,true, xPosition, obtainPostitionY(obj)-heightTop+10);
		document.getElementById('overlayCloseCross').style.display= "none";
		putOnTheOldData();
		document.getElementById('genOverlayContents').style.border='1px';
		document.getElementById('genOverlayContents').style.background='#FFF';
		document.getElementById('overlayShadow1').className='';
		document.getElementById('overlayShadow2').className='';
		document.getElementById('overlayShadow3').className='';
		document.getElementById('overlayContainer4').className='';
		document.getElementById('iframe_div').style.width='351px';
		document.getElementById('iframe_div').style.height='253px';
		document.getElementById('iframe_div').style.border='1px';
		document.getElementById('iframe_div').style.left=obtainPostitionX(obj)-heightLeft-24+'px';
		document.getElementById('iframe_div').style.top=obtainPostitionY(obj)+17+'px';
		document.getElementById('genOverlay').style.height= ($('genOverlayHolderDiv').offsetHeight > 253 ? $('genOverlayHolderDiv').offsetHeight : 253) + 'px' ;//'253px';
		overlayHackLayerForIE('genOverlay',document.getElementById('genOverlay'));
		openCityOverLay_1 = true;
		attachOutMouseClickEventForPage(document.getElementById('genOverlay'),'isCountryOverlayOpen()');
		if(document.getElementById('homesubCategories'))
		document.getElementById('homesubCategories').onclick = isCountryOverlayOpen;
	}
</script>
<?php
	// load customizedMMPFooter: This file contains code related to tracking etc
	$this->load->view('customizedmmp/customizedMMPFooter');
?>
