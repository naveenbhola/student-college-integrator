<div class="filter__page">
	<div class="col-startd">
	   	   <div class="wrap-c">
                       <h1 class="title__m"><?php echo $seoDetails['h1TagString'];?></h1>
	   	   </div>
	  </div> 	   
    <div class="dtls-wrap">
        <div class="dtls-content">
	   	  
            <?php $this->load->view('scholarshipCategoryPage/sections/scholarshipCategoryPageFilters');?>

            <div class="dtls-lftbar">
                <div class="col-bar">
                    <?php $this->load->view('scholarshipCategoryPage/sections/scholarshipCategoryPageCountWithSort');?>
                    <?php $this->load->view('scholarshipCategoryPage/sections/scholarshipCategoryPageTuplesHead');?>
                    <?php $this->load->view('scholarshipCategoryPage/sections/scholarshipCategoryPageTuples');?>
                    <?php $this->load->view('scholarshipCategoryPage/sections/scholarshipCategoryPagePagination'); ?>
                </div>
            </div>

        </div>
   </div>
</div>