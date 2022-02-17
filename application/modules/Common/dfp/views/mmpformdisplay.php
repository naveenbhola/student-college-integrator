<?php
$httpReferer = $_SERVER['HTTP_REFERER'];
$REQ_showpopup = $_REQUEST['showpopup'];
$REQ_resetpwd = $_REQUEST['resetpwd'];
$referer = 0;
if(((strpos($httpReferer, 'google') !== false) || ($REQ_showpopup != '')) && ($REQ_resetpwd != 1)) 
{
	$referer = 1;

}

$showMMPform = 0;
$showCustomFields = 0;
switch ($dfpData['parentPage']) 
{
    case "DFP_CategoryPage":
        $showMMPform = 1;
        $trackingKeyId = 1905;
        $showCustomFields = 1;
        break;
    case "DFP_InstituteDetailPage":
	    if($listing_type=='institute')
	    {
	    	$trackingKeyId = 1899;
	    }
	    else if($listing_type=='university')
	    {
	    	$trackingKeyId = 1901;
	    }
        $showMMPform = 1;
        break;
    case "DFP_ExamPage":
        $showMMPform = 1;
        $trackingKeyId = 1911;
        $showCustomFields = 1;
        break;
    case "DFP_AllArticle":
        $showMMPform = 1;
        $trackingKeyId = 1907;
        break;
    case "DFP_RankingPage":
        $showMMPform = 1;
        $trackingKeyId = 1909;
        $showCustomFields = 1;
        break;
    case "DFP_CourseDetailPage":
        $showMMPform = 1;
        $trackingKeyId = 1903;
        $showCustomFields = 1;
        break;
    default:
        $showMMPform = 0;
        $trackingKeyId = 0;
        $showCustomFields = 0;
}

$hideSubstream = 0;

if($dfpData['stream']!='' && $dfpData['baseCourse']!='')
{
	$hideSubstream = 1;
}

?>

if((document.referrer.indexOf('google') != -1) && !isUserLoggedIn && (<?php echo $showMMPform; ?> == 1))
{
	var customFields = "";

	<?php 

	if($showCustomFields==1 && $dfpData['stream']!='' && $dfpData['stream'] != GOVERNMENT_EXAMS_STREAM)
	{ ?>
		var customFields = {
	        'stream': {
	        	<?php 
	        	if($dfpData['stream']!='')
	        	{
	        		?>  'value' : <?php echo $dfpData['stream']; ?>,
	        			<?php ?>
	        			'hidden' : 1
	        	<?php }
	        	?>
	        },
	        'subStreamSpec':{
	            <?php
	            if($hideSubstream==0)
	            {
	            	?>  'hidden' : 0
	            <?php 
	        	} 
            	else if($dfpData['substream']!='')
            	{
            		?>  'value' : {
            			<?php echo $dfpData['substream']; ?> : <?php 
		        			if($dfpData['specialization']!='')
		        			{
		        				echo json_encode($dfpData['specialization']);
		        			}
		    				else
		    				{
		    					?>
		    					['']
		    					<?php
		    				}
            			?>
            			},
            			'hidden' : 1
            	<?php 
            	}
            	else if($dfpData['specialization']!='')
            	{
            		?> 'value' : {
            			'ungrouped' : <?php echo json_encode($dfpData['specialization']); ?>
	            		},
	            		'hidden' : 1
            	<?php 
            	}
            	else
            	{ ?>
            		'hidden' : <?php echo $hideSubstream ?>
            	<?php 
            	}
	            ?>
	        },
	        'baseCourses': {
	            <?php 
	        	if($dfpData['baseCourse']!='')
	        	{
	        		?>  'value' : <?php echo json_encode($dfpData['baseCourse']); ?>,
	        			'hidden' : 1
	        	<?php }
	        	?>
	        },
	        'educationType': {
	            <?php 
	        	if($dfpData['educationType']!='')
	        	{
	        		if($dfpData['educationType']==21)
	        		{
	        			$deliveryMethod = ['33','34','35','36','39'];
	        			if($dfpData['deliveryMethod']!='')
	        			{
	        				$deliveryMethod = $dfpData['deliveryMethod'];
	        			}
	        		}
	        		else
	        		{
	        			$deliveryMethod = $dfpData['educationType'];
	        		}
	        		?>
	        		'value' : <?php echo json_encode($deliveryMethod); ?>,
	        		'hidden' :1
	        	<?php 
	        	}

	        	if($dfpData['parentPage']=='DFP_ExamPage')
	        	{
	        		?>
	        		'value' : <?php echo json_encode($examOtherAttribute); ?>,
	        		'hidden' : 1
	        	<?php 
	        	}
	        	?>
	        }


    	}; <?php
	}

	?>


    var formData = 
    {
        'trackingKeyId' : '<?php echo $trackingKeyId; ?>',
        'customFields': customFields,
        'httpReferer' : document.referrer
    };

    MMPLayerCommon(formData);
}

// for on scroll popup

function MMPLayerCommon(formData)
{
	var isScrolling;
    var flag=0;
	window.addEventListener('scroll',function(event) 
	{
		if(flag==0)
		{
			window.clearTimeout(isScrolling);

			isScrolling = setTimeout(function() 
			{
				var scrollsNumber = 2;
				var default_height = $j(window).height();
				var height_after_scroll = $j(window).scrollTop();	
				if(default_height*scrollsNumber<=height_after_scroll)
				{
					flag=1;
					if(typeof(registrationForm) != 'undefined'){
						registrationForm.showRegistrationForm(formData);
					}
				}				

			},1000);
		}

	},false);
}

// for mouse click popup
/*function MMPLayerCommon(formData)
{
	var flag = 0;
	document.body.onmouseup = function(evt) 
	{
		if(flag==0)
		{
			var scrollsNumber = 2;
			var default_height = $j(window).height();
			var height_after_scroll = $j(window).scrollTop();	
			if(default_height*scrollsNumber<=height_after_scroll)
			{
				flag=1;
				registrationForm.showRegistrationForm(formData);
			}
		}
	}
}*/
