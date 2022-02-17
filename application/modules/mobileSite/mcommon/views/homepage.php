<?php $this->load->view('header'); ?>
<div class="search-btn-back">
	<a href="/search/showSearchHome" class="gray-button2">Search Institutes & Courses</a>
</div>
<?php $this->load->view('tabs');   ?>
<?php
if (getTempUserData('confirmation_message')){
?>
	<div style="border:1px solid #fff590; background:#feffcd; padding:10px; font:bold 13px Arial, Helvetica, sans-serif; margin:8px;">
	 <?php echo getTempUserData('confirmation_message'); ?>
	</div> 
<?php
} 
?>
<div id="content-wrap">
    <div id="contents">
        <ul>
<?php 
foreach($HomePageData as $key=>$tab_object) {
	$image_name = strtolower($tab_object['id'].'-icn.png');
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
             <li>
                          <a href="<?php echo SHIKSHA_HOME . "/mcommon/MobileSiteHome/showSubCategoriesHome/" . $key; ?>" />
                                       <div class="figure"><img class="lazy" src="/public/mobile/images/management-icn.png" data-original="<?php echo base64_encode_image('/public/mobile/images/'.$image_name);?>" /></div>
                <div class="details">
                <strong title="<?php echo $title_of_cat; ?>" ><?php echo $tab_object['name'];?></strong>
                <span>                        
                        <?php if($key == 3):?>
                        Full Time MBA | Part Time MBA | Distance MBA | BBA
                        <?php elseif($key == 2):?>
                        BE/BTech | MTech | Agriculture &amp; Forestry
                        <?php elseif($key == 14):?>
                        Govt. Sector Examinations | Medical Exams | Engineering Exams
                        <?php elseif($key == 12):?>
                        Animation | Graphic Designing | Web Designing
                        <?php elseif($key == 4):?>
                        Accounting | Banking | CA related | Commerce
                        <?php elseif($key == 10):?>
                        BCA | MCA | Part Time MCA | Distance MCA | Networking
                        <?php elseif($key == 6):?>
                        BHM | Air Hostess Training | Airlines &amp; Ticketing
                        <?php elseif($key == 7):?>
                        Acting, Modelling | Advertising | Films and Television
                        <?php elseif($key == 13):?>
                        Interior Design | Fashion &amp; Textile Design | Interaction Design | Industrial, Automotive, Product Design
                        <?php elseif($key == 9):?>
                        Arts &amp; Humanities | Creative Arts | Government Sector
                        <?php elseif($key == 11):?>
                        Front Office | Inventory | Shop Floor management
                        <?php elseif($key == 5):?>
                        Clinical Research | Dental Sciences | Health Care
                        <?php endif;?>
                </span>
                </div>
                </a>
            </li>
        <?php } ?>
        </ul>
    </div>
    <?php 
    deleteTempUserData('confirmation_message');
    ?>
    <script>
    $("img.lazy").show().lazyload({ 
        effect : "fadeIn",
        failure_limit : 5 
    });
    </script>
    <?php $this->load->view('footer'); ?>
