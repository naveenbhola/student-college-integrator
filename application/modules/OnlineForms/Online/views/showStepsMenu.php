<?php
      $pageToBeFilled = 1;
      $userFilledPages = array();
      if(is_array($pageArray)){
	    for($i=0;$i<count($pageArray[0]['data']);$i++){
		array_push($userFilledPages, $pageArray[0]['data'][$i]['pageOrder']);
		$pageToBeFilled = intval($pageArray[0]['data'][$i]['pageOrder'])+1;
	    }
      }

?>

    <!-- Div Start to display the top 5 Steps -->
    <!-- Depending on which page to display, we will make certain Step number as active and others as inactive -->
    
    <div class="formSteps">
    	<ul>
	    <?php if(isset($pageOrder) && $pageOrder=='1'){ ?>
	    <li class="step1Active">
	    <?php } else { ?>
	    <li class="step1" onmouseover="this.className = 'step1 step1Hover';" onmouseout="this.className = 'step1';">
	    <?php } ?>

		<?php if($action == 'editProfile' || $action == 'updateScore') { ?>
		<a href="javascript:void(0);">Basic Information</a>
		<?php } else { ?>
	    <?php if($action != 'editProfile' && ($pageOrder == '2' || $pageOrder == '3' || $pageOrder == '4' || $pageOrder == '5')){ ?>
	    <a href="/Online/OnlineForms/showOnlineForms/<?php echo $courseId;?>/1/1">Basic Information</a><em><a href="/Online/OnlineForms/showOnlineForms/<?php echo $courseId;?>/1/1">Edit</a></em>
	    <?php } else { ?>
	    <a href="javascript:void(0);">Basic Information</a>
	    <?php } ?>
		<?php } ?>
  
	    <span></span>
            </li>

	    <?php if(isset($pageOrder) && $pageOrder=='2'){ ?>
            <li class="step2Active">
	    <?php } else { ?>
            <li class="step2" onmouseover="this.className = 'step2 step2Hover';" onmouseout="this.className = 'step2';">
	    <?php } ?>

		<?php if($action == 'editProfile' || $action == 'updateScore') { ?>
		<a href="javascript:void(0);">Personal Information</a>
		<?php } else { ?>	
	    <?php if($pageOrder == '3' || $pageOrder == '4' || $pageOrder == '5'){ ?>
	    <a href="/Online/OnlineForms/showOnlineForms/<?php echo $courseId;?>/1/2">Personal Information</a><em><a href="/Online/OnlineForms/showOnlineForms/<?php echo $courseId;?>/1/2">Edit</a></em>
	    <?php } else if($pageOrder == '1' && in_array('2',$userFilledPages)){ ?>
	    <a href="/Online/OnlineForms/showOnlineForms/<?php echo $courseId;?>/1/2">Personal Information</a><em><a href="/Online/OnlineForms/showOnlineForms/<?php echo $courseId;?>/1/2">Edit</a></em>
	    <?php } else if($pageOrder == '1' && $pageToBeFilled==2){ ?>
	    <a href="/Online/OnlineForms/showOnlineForms/<?php echo $courseId;?>">Personal Information</a>
	    <?php } else { ?>
	    <a href="javascript:void(0);">Personal Information</a>
	    <?php } ?>
		<?php } ?>
            <span></span>
            </li>

	    <?php if(isset($pageOrder) && $pageOrder=='3'){ ?>
	    <li class="step3Active">
	    <?php } else { ?>
	    <li class="step3" onmouseover="this.className = 'step3 step3Hover';" onmouseout="this.className = 'step3';">
	    <?php } ?>

		<?php if($action == 'editProfile' || $action == 'updateScore') { ?>
		<a href="javascript:void(0);">Educational Information</a>
		<?php } else { ?>		
	    <?php if($pageOrder == '4' || $pageOrder == '5'){ ?>
	    <a href="/Online/OnlineForms/showOnlineForms/<?php echo $courseId;?>/1/3">Educational Information</a><em><a href="/Online/OnlineForms/showOnlineForms/<?php echo $courseId;?>/1/3">Edit</a></em>
	    <?php } else if( ($pageOrder == '1' || $pageOrder == '2') && in_array('3',$userFilledPages)){ ?>
	    <a href="/Online/OnlineForms/showOnlineForms/<?php echo $courseId;?>/1/3">Educational Information</a><em><a href="/Online/OnlineForms/showOnlineForms/<?php echo $courseId;?>/1/3">Edit</a></em>
	    <?php } else if( ($pageOrder == '1' || $pageOrder == '2') && $pageToBeFilled==3){ ?>
	    <a href="/Online/OnlineForms/showOnlineForms/<?php echo $courseId;?>">Educational Information</a>
	    <?php } else { ?>
	    <a href="javascript:void(0);">Educational Information</a>
	    <?php } ?>
		<?php } ?>

      <span></span>
      </li>

	    <?php if(isset($instituteInfo[0]['instituteInfo'][0]['institute_name'])){ ?>

	    <?php if(isset($pageOrder) && $pageOrder=='4'){ ?>
	    <li class="step4Active">
	    <?php } else { ?>
	    <li class="step4" onmouseover="this.className = 'step4 step4Hover';" onmouseout="this.className = 'step4';">
	    <?php } ?>

	    <?php if($pageOrder == '5'){ ?>
	    <a href="/Online/OnlineForms/showOnlineForms/<?php echo $courseId;?>/1/4">Additional Information</a><em><a href="/Online/OnlineForms/showOnlineForms/<?php echo $courseId;?>/1/4">Edit</a></em>
	    <?php  } else if(($pageOrder == '1' || $pageOrder == '2' || $pageOrder == '3') && in_array('4',$userFilledPages)){ ?>
	    <a href="/Online/OnlineForms/showOnlineForms/<?php echo $courseId;?>/1/4">Additional Information</a><em><a href="/Online/OnlineForms/showOnlineForms/<?php echo $courseId;?>/1/4">Edit</a></em>
	    <?php  } else if(($pageOrder == '1' || $pageOrder == '2' || $pageOrder == '3') && $pageToBeFilled==4 ){ ?>
	    <a href="/Online/OnlineForms/showOnlineForms/<?php echo $courseId;?>">Additional Information</a>
	    <?php } else { ?>
	    <a href="javascript:void(0);">Additional Information</a>
	    <?php } ?>

            <span></span>
            </li>

	    <?php if(isset($pageOrder) && $pageOrder=='5'){ ?>
	    <li class="step5Active">
	    <?php } else { ?>
	    <li class="step5" onmouseover="this.className = 'step5 step5Hover';" onmouseout="this.className = 'step5';">
	    <?php } ?>

	    <?php  if(($pageOrder == '1' || $pageOrder == '2' || $pageOrder == '3' || $pageOrder == '4') && $pageToBeFilled==5 && $action != 'updateScore'){ ?>
		<a href="/Online/OnlineForms/showOnlineForms/<?php echo $courseId;?>">Payment</a>
	    <?php }else{ ?>
		<a href="javascript: void(0);">Payment</a>
	    <?php } ?>

            	<span></span>
            </li>

	    <?php } ?>

        </ul>
    </div>

    <div class="clearFix"></div>
    <!-- Div Ends to display the top 5 Steps -->
