<link href='https://fonts.googleapis.com/css?family=Open+Sans:300,300italic,400,400italic,600,600italic,700,700italic,800,800italic' rel='stylesheet' type='text/css'>

<div class="cms-wrapper">

    <form name="UserSetSearch" method="post" id="UserSetSearch">

        <div style="padding-top:10px;">         

            <table class="user-table"> 

                <?php $this->load->view('userSearchCriteria/searchWidgets/country'); ?>

                <?php $this->load->view('userSearchCriteria/searchWidgets/targetDB'); ?>

            </table>

        </div>

        <div id="usersetSearchBlock"></div>

        <div>         
            
            <div style="padding: 0 20px 10px;text-align: right;">
                <label id="addMoreLink" criteriaNo="<?php echo $criteriaNo;?>"><a href="javascript:void(0);" style="color:#0065de; font-size:14px; font-weight:600;">+ Add More</a></label>
            </div>

            <?php $this->load->view('userSearchCriteria/searchWidgets/dateFilter'); ?>
            
            <?php $this->load->view('userSearchCriteria/searchWidgets/output'); ?>

        </div>

        <input type="hidden" id="usersettype" name="usersettype" value="Profile" />
        <input type="hidden" id="ExtraFlag" name="ExtraFlag" value="national" />

    </form>

</div>

 <div id="searchCriteriaBlock_<?php echo $criteriaNo;?>" class="searchCriteriaBlock" style="display:none">    

    <div id="searchCriteriaTitleBlock_<?php echo $criteriaNo;?>" class="clone"></div>

    <div id="searchCriteriaSummaryBlock_<?php echo $criteriaNo;?>" class="clone" style="display:none">
        <div style="margin:10px 0 0 40px; font-size:14px;">
            <label style="width:250px; display:inline-block;" class="clone" id="streamNameInSummary_<?php echo $criteriaNo;?>">Stream:</label>
            <label style="display:none" id="streamTotalUsers_<?php echo $criteriaNo;?>" class="clone"></label>
            <label id="showUserSetCriteria_<?php echo $criteriaNo;?>" class="showUserSetCriteria clone" criteriaNo="<?php echo $criteriaNo;?>" style="float:right"><a href="javascript:void(0);">Show Details</a></label>
        </div>
    </div>

    <div id="searchCriteriaFieldsBlock_<?php echo $criteriaNo;?>" class="clone">

        <table class="user-table"> 

            <?php $this->load->view('userSearchCriteria/searchWidgets/stream'); ?>

            <?php $this->load->view('userSearchCriteria/searchWidgets/mode'); ?>

            <?php $this->load->view('userSearchCriteria/searchWidgets/zone'); ?>

            <?php $this->load->view('userSearchCriteria/searchWidgets/city'); ?>

            <?php $this->load->view('userSearchCriteria/searchWidgets/workExperience'); ?>

            <?php $this->load->view('userSearchCriteria/searchWidgets/responseCity'); ?>

        </table>    
    </div>  

</div>

<script>
    var searchCMSBinder = {};

    var searchCMSBinder =  new SearchCMSBinder();
    //searchCMSBinder.bindOnloadElements();
    searchCMSBinder.criteriaNo = '<?php echo $criteriaNo;?>';
    searchCMSBinder.virtualCitiesParentChildMapping = new  Array('<?php echo json_encode($virtualCitiesParentChildMapping);?>');
    searchCMSBinder.virtualCitiesChildParentMapping = new  Array('<?php echo json_encode($virtualCitiesChildParentMapping);?>');

    searchCMSBinder.loadDefaultUserSet();
</script>
