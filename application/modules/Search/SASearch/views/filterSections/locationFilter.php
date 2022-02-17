<?php  
//_p(array_keys($filters['location_parent']['country']));
if(count($filters['location_parent'])>0){
?>
<div class="newAcrdn locationFilter active">
    <h2>Location<i class="custm__ico"></i></h2>
    <div class="search__univ">
        <i class="srch__ico"></i>
        <input id="locSuggestBox" placeholder="Search location" type="text">
        <p id="locQryMsg">Sorry, no locations found for your query in <span id="locQryMsgTerm"></span></p>
    </div>
    <div class="contentBlock">
        <?php $this->load->view('SASearch/filterSections/locationFilterItems'); ?>
    </div>
</div>
<?php } ?>