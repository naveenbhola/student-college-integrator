<!-- page content -->
<div class="right_col" role="main">          
    <h2 style=''><?php echo $reportHeading;?></h2>
    <?php
            $data['page'] = $page;
            if(isset($authorNames))
            {
                $data['authorNames'] =$authorNames;
            }
            $this->load->view('trackingMIS/CD/filters',$data);
    ?>
    <?php if($page == 'DomesticOverview' || $page == 'StudyAbroadOverview') {?>
    <div class ="row">
        <?php $this->load->view('trackingMIS/CD/actualDeliveryDashBoard');?>
    </div>
    <?php } else if($page == 'DomesticOverview' || $page == 'contentDomesticOverview' || $page == 'contentStudyAbroadOverview' || $page == 'StudyAbroadOverview' || $page == 'DiscussionOverview' || $page =='ActualDelivery') {?>
    <div class="row" id="cdDashBoardId">
    <div id="message" class="message" >
    <h2>No record found for selected criteria.</h2>

    </div>

    </div>
    <?php } if($page == 'byInstitute' || $page == 'bySubcatId' || $page == 'byUniversity' || $page == 'bySubcatId_SA') {?>
          <div id ="customerDashboard" style="display:none">
            <?php $this->load->view('trackingMIS/CD/customerDeliveryDashboard');?>
          </div>  
    <?}
    else{?>
        <div id="subcatIdBoard" style="display:none">
        <?php $this->load->view('trackingMIS/CD/subcatDashBoard');?>
        </div>
    <?php }?>
    