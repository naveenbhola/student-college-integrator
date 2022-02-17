<!--Exam Page Ends-->
<script type="text/javascript">
function LazyLoadJsCssExamPage(){
  $LAB
    .script('//<?php echo JSURL; ?>/public/js/<?php echo getJSWithVersion("quesDiscPosting");?>')
    .wait(function(){ LazyLoadCallBackExamPage(); });
   lazyLoadCss('//<?php echo CSSURL; ?>/public/css/<?php echo getCSSWithVersion("quesDiscPosting");?>');
}
</script>
<?php
	$this->load->view ( 'common/footerNew', array (
			'loadJQUERY' => 'YES' 
	) );
?>

<img id = 'beacon_img' src="/public/images/blankImg.gif" width=1 height=1 >
<script type="text/javascript" src="//<?php echo JSURL; ?>/public/js/<?php echo getJSWithVersion('examPages'); ?>"></script>

<?php if($mmp_details['display_on_page'] == 'newmmpexam') { ?>
	<script async src="//<?php echo JSURL; ?>/public/js/<?php echo getJSWithVersion("jquery.tinyscrollbar.min.v3"); ?>"></script>
	<link href="//<?php echo CSSURL; ?>/public/css/<?php echo getCSSWithVersion('nationalCourses'); ?>" type="text/css" rel="stylesheet" />
	<link href="//<?php echo CSSURL; ?>/public/css/<?php echo getCSSWithVersion('newmmp'); ?>" type="text/css" rel="stylesheet" />
<?php } ?>


<?php if($mmp_details['page_id'] != '') { ?>


<iframe name="iframe_div1" id="iframe_div1" style="width: 99%; position:absolute; display: none; top: 0; left: 0;  z-index: 1000; background-color: rgba(0, 0, 0, 0.3);" scrolling="no" allowtransparency="true"></iframe>

<div id="mmpOverlayForm" class="Overlay" style="display:none; position: fixed; top:20px;"></div>

<style>
    html.noscroll {
    position: fixed; 
    overflow-y: scroll;
    width: 100%;
}    
</style>

<script>
	var mmp_form_id_on_popup = '<?php echo $mmp_details['page_id']?>';
	var mmp_display_on_page = '<?php echo $mmp_details['display_on_page'];?>';
	var showpopup = '<?php echo $showpopup;?>';

	if(mmp_form_id_on_popup != '') {
	
		if(mmp_display_on_page == 'newmmpexam') {
	
			var mmp_form_heading = '<?php echo $mmp_details['form_heading']?>';
			var displayName = '';
			var user_id = '';
			
			<?php
			if(is_array($validateuser)) {?>
			   displayName = escape("<?php echo addslashes($validateuser[0]['displayname']); ?>");
			   user_id = '<?php echo $validateuser[0]['userid'];?>';
			<?php }  ?>
			
			$j(document).ready(function(){
				disable_scroll();
				setTimeout(loadmmpform,1000);
			});
		}
		
	}
	
	function loadmmpform() {
		var form_data = '';
		form_data += 'mmp_id='+mmp_form_id_on_popup;
		form_data += '&mmp_form_heading='+mmp_form_heading;
		form_data += '&isUserLoggedIn='+isUserLoggedIn;
		form_data += '&displayName='+displayName;
		form_data += '&user_id='+user_id;
		form_data += '&mmp_display_on_page='+mmp_display_on_page;
		form_data += '&exam_name='+'<?php echo $examName;?>';
		form_data += '&showpopup='+showpopup;

		$j.ajax({
			url: "/registration/Forms/loadmmponpopup",
			type: 'POST',
			async:false,
			data:form_data,
			success:function(result) {
				showMMPOverlay('530','860','',result);
				ajax_parseJs($('mmpOverlayForm'));
				setTimeout(enable_scroll,1000);
			}
		});
	}
 
    function showMMPOverlay(overlayWidth, overlayHeight, overlayTitle, overlayContent, modalLess, left, top) {
            
        if(trim(overlayContent) == '')
                return false;
        
        var body = document.getElementsByTagName('body')[0];
        
        $('iframe_div1').style.height = body.offsetHeight+'px';
        $('iframe_div1').style.width = body.offsetWidth+20+'px';
		$('iframe_div1').style.display = 'block';            
        
        $('mmpOverlayForm').innerHTML = overlayContent;
        $('mmpOverlayForm').style.width = overlayWidth + 'px';
        $('mmpOverlayForm').style.height = overlayHeight + 'px';

        var divX;                
        if(typeof left != 'undefined') {
           divX = left;
        } else {
           divX = (parseInt(body.offsetWidth)/2) - (overlayWidth/2);
        } 

        $('mmpOverlayForm').style.left = divX + 'px';
        $('mmpOverlayForm').style.top =  '20px';

        overlayHackLayerForIE('mmpOverlayForm', body);
        $('mmpOverlayForm').style.display = 'block';
    }

</script>

<?php } ?>

<script>
    $j(document).ready(function() {

        // put all your js code inside this function(examPages.js)
        setScrollbarForMsNotificationLayer();
       <?php if($validateuser == 'false') { ?>
            var formLength = regFormLoad.length;
            for(var i=0; i<formLength;i++) {
                window[regFormLoad[i]]();
            }
        <?php } ?>
	    if(typeof($j('._exam-hm-scroll')) !='undefined'){
	    	$j('._exam-hm-scroll').on('click',function(){
	    		scrollToSection('wiki-sec-0');
	    	});
	    }
	   if(typeof(cdFlag)!='undefined' && cdFlag=='true' && typeof($j(".cd_0"))!='undefined' && typeof($j(".cd_1"))!='undefined' ){
                        if($j(".cd_0").height() > $j(".cd_1").height()){ $j(".cd_1").height($j(".cd_0").height()); }else{ $j(".cd_0").height( $j(".cd_1").height());}
		}

    });
	
	/*
	 * Trackings on page load
	 */
	
	//to show tracking message (for visibility/debug purpose)
	$j(window).load(function() {
		//exam page view count tracking
    	/*var img = document.getElementById('beacon_img');
    	var randNum = Math.floor(Math.random()*Math.pow(10,16));
    	img.src = '<?php echo BEACON_URL; ?>/'+randNum+'/0010005/<?=$groupId?>+exam_group';*/
		
		<?php if(isset($_GET['sel']) && $_GET['sel']=='true'){ ?>
	              getSimilarExamLayer(examId,groupId);
        	<?php } ?>
		<?php if(isset($_GET['scrollTo']) && $_GET['scrollTo']=='article'){ ?>
                      $j('html,body').animate({
                        scrollTop: $j(".articleSlider").offset().top-$j('#fixed-card').height()-$j('#_globalNav').height()},
                      'slow');
                <?php } ?>

	});
</script>
<?php $this->load->view('common/newMMPForm'); ?>
