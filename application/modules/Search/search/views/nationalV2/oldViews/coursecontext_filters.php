<div class="widget margin-top-0">
	<?php if(isset($freshSearchUrl)) {?>
	<div class="slctd-catgrs">
	       <div class="frst-sidebr">
	           <p class="p6">Show Results for</p>
	           <p><a class="fltr-titl" href="<?=$freshSearchUrl?>"><strong class="search-sprite all"></strong>All Categories</a></p>
	           <?php
                if(!empty($selectedFilters['subCategory'])) { ?>
               <p class="p4"><?php echo $selectedFilters['subCategory'];?></p>
               <?php 
                }
               ?>
	       </div> 
	</div>
	<?php } ?>
    <aside class="rght-bar">  
    	<?php foreach ($filters as $filterType => $filter) {
    		switch ($filterType) {
    			case 'exams':
    				$this->load->view('nationalV2/filters/examsFilters',array('data'=>$filters['exams']));
    				break;
    			case 'locations':
    				$this->load->view('nationalV2/filters/locationsFilters',array('data'=>$filters['locations']));
    				break;
    			case 'specialization':
    				$this->load->view('nationalV2/filters/specializationsFilters',array('data'=>$filters['specialization']));
    				break;
    			case 'fees':
    				$this->load->view('nationalV2/filters/feesFilters',array('data'=>$filters['fees']));
    				break;
    			case 'courseLevel':
    				$this->load->view('nationalV2/filters/courseLevelFilters',array('data'=>$filters['courseLevel']));
    				break;
    			case 'mode':
    				$this->load->view('nationalV2/filters/modeFilters',array('data'=>$filters['mode']));
    				break;
    			default:
    				# code...
    				break;
    		}
    	}?>
        

		
                        
</aside>
</div>