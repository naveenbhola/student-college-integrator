<?php ob_start('compress'); 
$this->load->view('/mcommon5/header');
?>

<script>
var shortListedCourses = '<?=implode('|',$courseArray)?>';
</script>

	<div id="wrapper" data-role="page" class="of-hide">
    
        <?php   $this->load->view('shortlistHeader'); ?>

	<?php   $mobile_details = json_decode($_COOKIE['ci_mobile_capbilities'],true);
    		$screenWidth = $mobile_details['resolution_width'];
    		$screenHeight = $mobile_details['resolution_height'];
    ?>        
        
    <input type="hidden" value="<?php echo $screenWidth;?>" id="screenwidth" />
    <input type="hidden" value="<?php echo $screenHeight;?>" id="screenheight" />
    
	     
        <div data-role="content">
        	<?php 
	        $this->load->view('mcommon5/dfpBannerView',array('bannerPosition' => 'leaderboard'));
    	?>
		
		<!----subheader--->
	       <?php $this->load->view('shortlistSubHeader');?>
		<!--end-subheader-->
		
            <div data-enhance="false">

		<?php
		if (getTempUserData('confirmation_message')){?>
		<section class="top-msg-row">
			<div class="thnx-msg">
			    <i class="icon-tick"></i>
				<p>
				<?php echo getTempUserData('confirmation_message'); ?>
				</p>
			</div>
			<div style="clear:both"></div>
		</section>
		<?php } ?>
		<?php
		   deleteTempUserData('confirmation_message');
		   deleteTempUserData('confirmation_message_ins_page');
		?>

		<?php
		//Check for No Results on Category page
		if(!$institutes || count($institutes)<=0){
		?>
			<nav class="clearfix fixed-wrap" id="no-result">
			    <p>Currently, no institutes have been shortlisted by you.</br>
			    <!--<a onclick="setCookie('scroll_page','yes') ;location.href='<?php echo SHIKSHA_HOME;?>'" href='javascript:void(0);' >Explore again</a>-->
			    </p>
			</nav>
		<?php
		}else{
		    $this->load->view('mobileShortlistInstituteList');
		}
		//End Check for No Results on Category page
            
            ?>
            <?php $this->load->view('/mcommon5/footerLinks'); ?>
            </div>
        </div>
</div>

<?php $this->load->view('/mcommon5/footer');?>
<?php ob_end_flush(); ?>


