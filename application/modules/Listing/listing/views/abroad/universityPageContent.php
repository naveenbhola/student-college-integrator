<div id="left-col">
<?php
        $this->load->view('listing/abroad/widget/universityTitle');
        
        $this->load->view('listing/abroad/widget/universityPopularCourse');
        if($universityObj->getWhyJoin()!="")
        {
          $this->load->view('listing/abroad/widget/universityHighlights');  
        }
        ?>
        <div id="mediaForStudyAbroadPage"></div>
        <div id="userAdmittedCards"></div>
        <?php
        $this->load->view('listing/abroad/widget/deptCampusDetails');
        if(!empty($scholarshipCardData) && isset($scholarshipCardData['totalCount']) && $scholarshipCardData['totalCount']>0){
            $this->load->view('listing/abroad/widget/scholarshipInterlinkingULP');
        }
		if(!empty($universityAccomodationDetails) || isset($universityAccomodationURL))
        {
            $this->load->view('listing/abroad/widget/universityLocationAccomodationDetails');    
        }
        if(!empty($livingExpense) || ($livingExpenseURL!= "http://" && $livingExpenseURL!= ""))
        {
            $this->load->view('listing/abroad/widget/universityCostOfLiving');    
        }
        $this->load->view('listing/abroad/widget/downloadBrochureSingleSignup.php');
?>
</div>
