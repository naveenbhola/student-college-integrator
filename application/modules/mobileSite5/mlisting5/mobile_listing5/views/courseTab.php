<?php ob_start('compress'); ?>
<?php $this->load->view('/mcommon5/header');
echo jsb9recordServerTime('SHIKSHA_MOB_NATIONAL_COURSE_LISTINGS_TAB',1);
global $shiksha_site_current_url;
global $shiksha_site_current_referral ; 
?>
<script>
var accordianObj;
</script>


<div id="popupBasic" style="display:none">
        <header class="recommen-head" style="border-radius:0.6em 0.6em 0 0;">
        <p style="width:210px;" class="flLt">Students who showed interest in this institute also looked at</p>
        <a href="#" class="flRt popup-close" onclick = "$('#popupBasic').hide();$('#popupBasicBack').hide();">&times;</a>
        <div class="clearfix"></div>
        </header>
        <div id="recomendation_layer_listing" style="margin-bottom:20px;"></div>
</div>
<div id="popupBasicBack" data-enhance='false'>
</div>


<div id="wrapper" style="min-height: 413px;padding-top: 40px;">
       
       <?php $this->load->view('listingHeader'); ?>
       <div data-role="content">
       	       <!----subheader--->
	       <?php $this->load->view('listingSubHeader');?>
	       <!--end-subheader-->
       <div>
	
       <?php $this->load->view('listingTabs');  ?>
        
        
    <section class="content-wrap2 tb-space"  id="courseTabPage">
    
       <?php $this->load->view('refineCourses');?>
       
       <?php $this->load->view('courseTabDetails');?>
       
     
              <article class="inst-details" id="noRes" style="display:none">
                     <div class="notify-details" data-enhance="false">
                         <div class="thnx-msg" style="margin-bottom:20px">
                              <i class="icon-404"></i>
                              <p>No result found. Please change your refinement</p>
                          </div>
                          <input id="reset" type="button" value="Reset" class="reset-btn">
                     </div>
              </article>
       
    </section>
    </div>
<?php $this->load->view('/mcommon5/footerLinks');?>
</div>
</div>
<div data-role="page" id="subcategoryDiv" data-enhance="false"><!-- dialog--> 
</div>
<?php $this->load->view('/mcommon5/footer');?>

<script>

$('#reset').click(function() {
    location.reload(); 
    $('select').prop('selectedIndex', 0);
    $('category').prop('selectedIndex', 0);
});

$('#wrapper').attr('data-role','page');

	(function($) {
              
	if(listingCourseTabPageFlagAccordion){
		listingCourseTabPageFlagAccordion = false;
                $(document).ready(function(){
                      $(window).trigger('resize');
              });
               
	}
       })($);
        
    
</script>
<?php ob_end_flush(); ?>

<script>
  <?php if($catIdFromSearchPage) {?>
    $(document).ready(function($) {
          setTimeout(function(){
          $('#category').val(<?php echo $catIdFromSearchPage; ?>);
          $('#category').selectmenu('refresh');
          $('#category').change();
        },200);   
    });
   
    
<?php } ?>
    
</script>
