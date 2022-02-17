<?php ob_start('compress'); ?>
<?php
	$keyword = (!empty($solr_institute_data['raw_keyword']))  ? htmlspecialchars($solr_institute_data['raw_keyword']) : '';
	$title = 'Education India - Search Colleges, Courses, Institutes, University, Schools, Degrees - Forums - Results - Admissions - Shiksha.com';
	$metDescription = 'Search Colleges, Courses, Institutes, University, Schools,Degrees, Education options in India. Find colleges and universities in India & abroad. Search Shiksha.com Now! Find info on Foreign University, question and answer in education and career forum.Ask the education and career counselors.';
	$metaKeywords = 'Shiksha, education, colleges,universities, institutes,career, career options, career prospects, engineering, mba, medical, mbbs,study abroad, foreign education, college, university, institute, courses, coaching, technical education, higher education,forum, community, education career experts, ask experts, admissions,results, events,scholarships';
	$headerComponents = array(
	'm_meta_title'=>$title,
	'm_meta_description'=>$metDescription,
	'm_meta_keywords'=>$metaKeywords
	);
	$this->load->view('mcommon5/header',$headerComponents);
?>

<div id="wrapper" data-role="page"  >
	<?php echo Modules::run('mcommon5/MobileSiteHamburgerV2/getWrapperHtmlForHamburger','mypanel','true');
	      echo Modules::run('mcommon5/MobileSiteHamburger/getRightPanel','myrightpanel');
	 ?>
    
	<!-- Show the Search page Header -->    
	<header id="page-header" class="header" data-role="header" data-tap-toggle="false"  data-position="fixed">
	 <?php echo Modules::run('mcommon5/MobileSiteHamburger/getMainHeader',$displayHamburger=true);?>
	</header>   
	<!-- End the Search for Category page -->

		
        <div data-role="content">
        	<?php 
        $this->load->view('mcommon5/dfpBannerView',array('bannerPosition' => 'leaderboard'));
    ?>
		
		<?php $this->load->view('topsearchesmainpageSubHeader')?>
		
		<div data-enhance="false">
		
			<ul>
				<?php foreach($top_searches as $key=>$value): ?>				
				<section class="content-wrap2  clearfix">
					<article class="req-bro-box clearfix"  >
						<div class="details" style="cursor: pointer;" onclick="window.location='<?php echo SHIKSHA_HOME.$key;?>';">
							<h4 title="<?php echo $value; ?>"><?php echo $value; ?></h4>
						</div>
					</article>
				</section>
				<?php endforeach;?>
			</ul>
			
		</div>
		<?php $this->load->view('mcommon5/footerLinks');?>
	</div>

</div>

<?php $this->load->view('mcommon5/footer');?>
<?php ob_end_flush(); ?>
