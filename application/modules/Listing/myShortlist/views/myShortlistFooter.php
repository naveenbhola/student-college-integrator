</div>
<!--MyShortlist Ends-->


<?php
	$this->load->view ( 'common/footerNew', array (
			'loadJQUERY' => 'YES',
            'loadUpgradedJQUERY' => 'YES'
         
	) );
?>
<script type="text/javascript" src="https://www.google.com/jsapi"></script>

<script>
var isMyShortlistPage = true;
    $j(document).ready(function() {
        // put all your document ready callback js code inside documentReadyCallBack in myShortlist.js
    	documentReadyCallBack();
    	setScrollbarForMsNotificationLayer();
        $j(document).click(function (e) {
            if ($j(e.target).closest('#eligibilitySortLayerDiv').length <= 0 && $j(e.target).attr('id') != 'eligibilitySortLayerDiv')
                $j('#sort_exam_layer').hide('slow');
        });


        // opening tuple for which last ebrochure is downloaded.
        var lastEbrochureCourseId = getCookie('lastEBrochureCourseId');
        setCookie('lastEBrochureCourseId',null, {path:'/'});
        if(lastEbrochureCourseId != ''){
            if($j('#row_'+lastEbrochureCourseId).length == 1){
                scrollAndNavigateToRequiredSection(location.hash.replace("#",""));
                $j('#row_'+lastEbrochureCourseId).click();
            }else{
                if(!scrollAndNavigateToRequiredSection(location.hash.replace("#",""))) { 
                    $j('.shortlist-tuple:last').click(); 
                }
            }   
        }else{
            if(!scrollAndNavigateToRequiredSection(location.hash.replace("#",""))) { 
                $j('.shortlist-tuple:last').click(); 
            }
        }

	    var hashTag = window.location.hash;
        if(typeof(hashTag) != 'undefined' && hashTag =='#myshortlist'){
            $j('html, body').animate({scrollTop: $j('#shrtlstdInsttsCont').offset().top - ($j('#_globalNav').outerHeight() + 55)}, 500);
            window.location.hash.replace('#myshortlist','');
            location.hash = '';
        }
	
        $j("body").on("click", ".shortlist-info-detail a", function(e){
        	 notificationId =  $j(this).closest(".shortlist-info-detail").attr("notificationid");
			 if($j(this).closest(".shortlist-info-detail").hasClass('unseen')) 
			 {
        	 	$j(this).closest(".shortlist-info-detail").removeClass('unseen').addClass('seen');
        	 	setNotificationAsSeen(notificationId);
        	 	$j('.red-bell-icon').removeClass('red-bell-icon').addClass('bell-icon');
			 }	
        	 gaTrackEventCustom('MY_SHORTLIST_PAGE', 'notificationLayer', 'link');
        	 scrollAndNavigateToRequiredSection($j(this).attr('linkid'));
        	 e.preventDefault();
        	 e.stopPropagation();
                });
        
        $j("body").on("click", ".shortlist-info-detail:not(.shortlist-info-detail a)", function(e){
          	 	gaTrackEventCustom('MY_SHORTLIST_PAGE', 'notificationLayer', 'tuple');
      
           });

            $j("body").on("click", ".reminderEdit", function(e){
            if(!$j(e.target).hasClass('remind-cross-btn')) {
                    instance['noteId_'+$j(this).attr('noteId')].show();
					gaTrackEventCustom('MY_SHORTLIST_PAGE', 'Shortlist_notes', 'reminder');                    
					e.stopPropagation();
                }      
           });

           $j("body").on('click', function (e) {
               if($j(e.target).closest('.note-settings').length == 0) {
                    $j(".settings-layer").hide();
                    $j(".settings-link-active").removeClass('settings-link-active');
                }  
            
            }); 
        
    });
	
</script>
<script src="//<?php echo JSURL; ?>/public/js/BeatPicker.min.js"></script>