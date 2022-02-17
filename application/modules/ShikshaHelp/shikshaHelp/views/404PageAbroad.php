<?php
$headerComponents = array(
        'css'=>array('studyAbroadCommon', 'studyAbroadHomePage'),
        'title'             => 'Study Abroad - Error Page - Shiksha.com',
        'metaDescription'   => 'Search for study abroad education at Shiksha.com. Explore various list of study overseas colleges & courses in countries like USA, UK, Canada, Australia, New Zealand, etc.',
        'metaKeywords'      => 'study abroad, study overseas, overseas education, higher education in abroad, study abroad programs, study abroad colleges, study abroad courses, International studies',
		'pgType'	        => '404Page',
		'pageIdentifier' 	=> '404Page'
);

$this->load->view('common/studyAbroadHeader', $headerComponents);
?>
	<div class="content-wrap clearfix">

	<style type="text/css">
	.wrapper-404{margin:20px 0 80px; background:#f2f2f2; -moz-border-radius:3px; -webkit-border-radius:3px; border-radius:3px; font:normal 15px Tahoma, Geneva, sans-serif; color:#525252}
	.sprite-404{background:url(/public/images/404-sprite.png); display:inline-block;font-style:none; vertical-align:middle;}
	.oops-icon{background-position:0 0; width:49px; height:59px; margin:0 8px 0 15px; float:left;}
	.wrapper-404 .title-box{background:#edebeb; padding:10px 14px; -moz-border-radius:3px 3px 0 0; -webkit-border-radius:3px 3px 0 0; border-radius:3px 3px 0 0;}
	.error-details{margin-left:78px}
	.notfound-msg{padding:25px 10px 10px; color:#333; font-size:18px;}
	.font-30{font-size:30px;}
	.error-callout{background:#fff; padding:15px; -moz-border-radius:3px; -webkit-border-radius:3px; border-radius:3px; margin-top:25px; font-size:14px; color:#666; position: relative;}
	.error-callout p{margin-left:65px;}
	.error-callout .pointer{background-position:0 -62px; width:23px; height:18px; position:absolute; top:-17px; left:50px;}
	.error-callout p span{font-size:20px; position:relative; top:2px; line-height:0}
	</style>

	<div class="wrapper-404">
			<div class="title-box">Page not found - 404</div>
		    <div class="notfound-msg">
			<i class="sprite-404 oops-icon"></i>
			<div class="error-details">
			    <p class="font-30" style="margin-bottom:5px">Oops!</p>
			    <p>The page you are looking for does not exist</p>
			</div>
		    <div class="error-callout">
		    <i class="sprite-404 pointer"></i>
			<p>You can start again from our Home page <span>&rsaquo;</span> <a href="<?=SHIKSHA_STUDYABROAD_HOME?>">studyabroad.shiksha.com</a></p>
		    </div>
		    </div>
	</div>

	</div>
<?php
	$footerComponents = array(
				'js'=>array('studyAbroadHomepage')
		);
	$this->load->view('common/studyAbroadFooter',$footerComponents);
?>
