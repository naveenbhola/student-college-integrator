<div class="newSearch">
  <?php //$this->load->view('SASearch/breadcrumbs');?>
  <?php $this->load->view('SASearch/srpHeadingText');?>
  <?php $this->load->view('SASearch/appliedFilterInfo');?>
  <div class="abroadSrp clearwidth">
    <?php $this->load->view('SASearch/rightContentSection');?>
    <?php $this->load->view('SASearch/searchFilters');?>
  </div>
</div>
<?php $this->load->view('SASearch/tupleLoader');?>