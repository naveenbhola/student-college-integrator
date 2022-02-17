<link href='https://fonts.googleapis.com/css?family=Open+Sans:300,300italic,400,400italic,600,600italic,700,700italic,800,800italic' rel='stylesheet' type='text/css'>

<div class="cms-wrapper">

    <form name="UserSetSearch" method="post" id="UserSetSearch">

        <div id="usersetSearchBlock">

            <div id="searchCriteriaBlock_<?php echo $criteriaNo;?>">      

                <input id="targetDBCustom" class="targetDB" type="radio" name="targetDB" value="custom" checked="true" style="display:none" >   
                    
                <div id="searchCriteriaFieldsBlock_<?php echo $criteriaNo;?>" class="clone">

                    <table class="user-table"> 

                        <?php $this->load->view('userSearchCriteria/searchWidgets/listings'); ?>

                    </table>    

                </div>  

            </div>

        </div>

        <div>         

            <?php $this->load->view('userSearchCriteria/searchWidgets/zonecitysearchactivity'); ?>
            
            <?php $this->load->view('userSearchCriteria/searchWidgets/dateFilter'); ?>
            
            <?php $this->load->view('userSearchCriteria/searchWidgets/output'); ?>

        </div>

        <input type="hidden" id="usersettype" name="usersettype" value="Activity" />
        <input type="hidden" id="ExtraFlag" name="ExtraFlag" value="national" />

    </form>

</div>

<script>
    var searchCMSBinder = {};

    var searchCMSBinder =  new SearchCMSBinder();

    searchCMSBinder.virtualCitiesParentChildMapping = new  Array('<?php echo json_encode($virtualCitiesParentChildMapping);?>');
    searchCMSBinder.virtualCitiesChildParentMapping = new  Array('<?php echo json_encode($virtualCitiesChildParentMapping);?>');

    searchCMSBinder.showLoader();
    searchCMSBinder.unbindOnloadElements();
    searchCMSBinder.bindOnloadElements();
    searchCMSBinder.criteriaNo = '<?php echo $criteriaNo;?>';
    setTimeout(function(){
        searchCMSBinder.hideLoader();
    },100);

</script>
