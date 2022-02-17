<?php
global $categoryForCollegeInstituteChange;
if(isset($widgets_data['registrationWidget']) && $widgets_data['registrationWidget'][0] == "YES") { ?>
    <div class="widget-wrap last-widget" id="RegistrationWidgetContainer">
        <h2><?=$widgetObj->getWidgetHeading();?></h2> 
        <input type="button" value="Search now" class="orange-button" onclick="showRegistrationBox();" />
    </div>
<?php
    }
    else {
        // $this->load->view('/coursepages/widgets/RecommendationsWidget');
    }
?>
