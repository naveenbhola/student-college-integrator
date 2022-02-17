 <article class="inst-details mb0">
        <h2 class="ques-title" id="placementCompaniesHeader">
            <p>Placement Details</p>
        </h2>

        <?php if($course->getRecruitingCompanies()){ ?>
        <div class="comp-details"id="placementCompanies">
            <strong>Recruiting Companies</strong>  
		<figure>
	            <?php 
			global $companiesLogoArray;
			$companiesLogoArray = array();
			$hidePlacementComp = true;
			$i=0; 
			foreach ($course->getRecruitingCompanies() as $company){  $i++;
			   if($company->getLogoUrl()){
			   	   $companiesLogoArray[$i] = $company->getLogoUrl();
		       	           echo '<img id="logo'.$i.'" alt="'.$company->getName().'" />';
				   $hidePlacementComp = false;
			   }
                   }?>    
		</figure>
        </div>
        <?php 
		if($hidePlacementComp){
			echo "<script>$('#placementCompanies').hide(); $('#placementCompaniesHeader').hide();</script>";
		}
	 }
	 ?>
         <?php $this->load->view('mobileCollegeReview'); ?>
         <?php $this->load->view('mobile_contact_details'); ?>
      
           
       <?php if($course->isCourseMultilocation(0)=="true"){?>      
        <div class="other-opt">
              <a href="#branches" data-inline="true" data-rel="dialog" data-transition="slide">See All Branches
                     <i class="icon-arrow-r2 sprite"></i>
              </a>
       </div>
       <?php }?>
</article>
    
