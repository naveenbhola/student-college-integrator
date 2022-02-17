<?php 
$this->load->library('category_list_client');
$categoryClient = new Category_list_client();
global $tabsContentByCategory;
if(!isset($tabsContentByCategory)){
	$tabsContentByCategory = $this->category_list_client->getTabsContentByCategory();
} else {
	$tabsContentByCategory = $tabsContentByCategory;
}
?>

<ul>
            	<?php $index_of_cat=0;foreach($tabsContentByCategory as $key=>$tab_object):?>
                <?php
                      if($key == 3) {
                      	$title_of_cat = 'MBA Colleges and Courses';
                      } else if($key == 2) {
		      	$title_of_cat = 'Engineering Colleges and Courses';	
                      } else if($key == 4) {
		      	$title_of_cat = 'Banking Institutes and Courses';	
                      } else if($key == 10) {
		      	$title_of_cat = 'IT Colleges and Courses';	
                      } else if($key == 14) {
		      	$title_of_cat = 'Coaching Classes';	
                      } else if($key == 12) {
		      	$title_of_cat = 'Animation Courses and Institutes';	
                      } else if($key == 6) {
		      	$title_of_cat = 'Hotel Management Courses and Colleges';	
                      } else if($key == 5) {
		      	$title_of_cat = 'Medical Colleges and Courses';	
                      } else if($key == 13) {
		      	$title_of_cat = 'Fashion and Design Courses';	
                      } else if($key == 7) {
		      	$title_of_cat = 'Media and Journalism Courses';	
                      } else if($key == 9) {
		      	$title_of_cat = 'Law Colleges and Courses';	
                      } else if($key == 11) {
		      	$title_of_cat = 'Retail Management Courses';	
                      }
                 ?>
                <li id="india_catrgory_li<?php echo $index_of_cat;?>" class = "india_categories" onclick="handleClickStck(this,$('hpgrdgn_left_category<?php echo $index_of_cat;?>'));" onmouseout="hideSpecialSubCatDiv($('hpgrdgn_left_category<?php echo $index_of_cat;?>'));" onmouseover="showSpecialSubCatDiv($('hpgrdgn_left_category<?php echo $index_of_cat;?>'));" alreadyclicked='NO'>
                	<div id="hpgrdgn_left_category<?php echo $index_of_cat;?>" catid="<?php echo $key;?>">
                    	
                        <?php if($key!=2 && $key!=3):?> <strong title="<?php echo $title_of_cat; ?>"><?php endif;?><?php if($key == 12) {echo str_replace(array('Visual Effects','(AVGC)'),array('VFX',''),$tab_object['name']);} else { if($key == 3) {echo '<h5 title="'.$title_of_cat.'">'.$tab_object['name'].'</h5>';} else if($key == 2) {echo '<h6 title="'.$title_of_cat.'">'.$tab_object['name'].'</h6>';} else {echo $tab_object['name'];}}?><?php if($key!=2 && $key!=3):?></strong><?php endif;?>
                        
                        
                        
                        <?php if($key == 3):?>
                        <p>Full Time MBA <span>|</span> Part Time MBA <span>|</span> Distance MBA <span>|</span> BBA</p>
                        <?php elseif($key == 2):?>
                        <p>BE/BTech <span>|</span> MTech <span>|</span> Agriculture &amp; Forestry </p>
                        <?php elseif($key == 14):?>
                        <p>Govt. Sector Examinations <span>|</span> Medical Exams <span>|</span> Engineering Exams</p>
                        <?php elseif($key == 12):?>
                        <p>Animation <span>|</span> Graphic Designing <span>|</span> Web Designing </p>
                        <?php elseif($key == 4):?>
                        <p>Accounting <span>|</span> Banking <span>|</span> CA related <span>|</span> Commerce</p>
                        <?php elseif($key == 10):?>
                        <p>BCA <span>|</span> MCA <span>|</span> Part Time MCA <span>|</span> Distance MCA <span>|</span> Networking</p>
                        <?php elseif($key == 6):?>
                        <p>BHM <span>|</span> Air Hostess Training <span>|</span> Airlines &amp; Ticketing</p>
                        <?php elseif($key == 7):?>
                        <p>Acting, Modelling <span>|</span> Advertising <span>|</span> Films and Television</p>
                        <?php elseif($key == 13):?>
                        <p>Interior Design <span>|</span> Fashion &amp; Textile Design <span>|</span> Interaction Design <span>|</span> Industrial, Automotive, Product Design</p>
                        <?php elseif($key == 9):?>
                        <p>Arts &amp; Humanities <span>|</span> Creative Arts <span>|</span> Government Sector</p>
                        <?php elseif($key == 11):?>
                        <p>Front Office <span>|</span> Inventory <span>|</span> Shop Floor management</p>
                        <?php elseif($key == 5):?>
                        <p>Clinical Research <span>|</span> Dental Sciences <span>|</span> Health Care</p>
                        <?php endif;?>
                    </div>
                    
                    <div class="sub-cates" style="display:none" id="hpgrdgn_left_category<?php echo $index_of_cat;?>_subcat">
                    	
                    </div>
                 </li>
                 <?php $index_of_cat++;endforeach;?>
              </ul>
