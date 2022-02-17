<?php

?>
<ul id="course-tab" style="width:110px;">
    <li>
        <a href="javascript:void(0);" id="leftnav-overview" class="active" elementtofocus="overview-tab">
            <i class="consultant-sprite cons-overview-icon"></i>
            <span class="consultant-menu-title">Overview</span>
            <i class="listing-sprite tab-pointer"></i>
        </a>
    </li>
    <?php if($collegesRepresentedTabFlag){?>
    <li>
        <a href="javascript:void(0)" id="leftnav-collegeRepresented" elementtofocus="collegeRepresented-tab">
            <i class="consultant-sprite cons-college-rep-icon"></i>
            <span class="consultant-menu-title">Colleges represented</span>
            <i class="listing-sprite tab-pointer"></i>
        </a>
    </li>
    
    <li>
        <a href="javascript:void(0);" id="leftnav-countriesRepresented" elementtofocus="countriesRepresented-tab">
            <i class="consultant-sprite cons-countries-rep-icon"></i>
            <span class="consultant-menu-title">Countries represented</span>
            <i class="listing-sprite tab-pointer"></i>
        </a>
    </li>
   <?php } ?>
    <?php if($servicesOfferedTabFlag){?>
    <li>
        <a href="javascript:void(0);" id="leftnav-servicesOffered" elementtofocus="servicesOffered-tab">
            <i class="consultant-sprite cons-services-offered-icon"></i>
            <span class="consultant-menu-title">Services offered</span>
            <i class="listing-sprite tab-pointer"></i>
        </a>
    </li>
   <?php } ?>
    <?php if($studentAdmittedTabFlag){?>
    <li><a href="javascript:void(0);" id="leftnav-studentAdmitted" elementtofocus="studentAdmitted-tab"><i class="consultant-sprite cons-student-icon"></i><span class="consultant-menu-title">Students admitted</span><i class="listing-sprite tab-pointer"></i></a></li>
    <?php }?>
</ul>