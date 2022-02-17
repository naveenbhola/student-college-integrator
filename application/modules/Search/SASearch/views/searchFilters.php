<?php 
if($filterAjaxCall != 1){
    $this->load->view('SASearch/filterLoader');
}
//_p($filters);
$origState = ($filters['originalState']!=''?$filters['originalState']:$pageData['appliedFilter']['originalState']);
?>
<aside class="filterLeftside" id="filterContainer">
  <div class="filterBox">
    <div class="titleDiv">Filters <a class="clearAllFilters">Reset All</a></div>
    <input type="hidden" value="<?php echo $origState; ?>" id="origState">
    <div class="getClose">
    <?php 
    $this->load->view('SASearch/filterSections/locationFilter');
    $this->load->view('SASearch/filterSections/courseLevelFilter'); 
    $this->load->view('SASearch/filterSections/specializationFilter');
    $this->load->view('SASearch/filterSections/feesFilter');
    $this->load->view('SASearch/filterSections/examsFilter');
    $this->load->view('SASearch/filterSections/workXPFilter');
    $this->load->view('SASearch/filterSections/bachCutoffFilter');
    $this->load->view('SASearch/filterSections/12thCutoffFilter');
    //$this->load->view('SASearch/filterSections/deadlineFilter');
    //$this->load->view('SASearch/filterSections/intakeFilter');
    
    $this->load->view('SASearch/filterSections/scholarshipFilter');
    
    $this->load->view('SASearch/filterSections/durationFilter');
    $this->load->view('SASearch/filterSections/applicationProcessFilter'); 
    ?>
    </div>
  </div>
</aside>