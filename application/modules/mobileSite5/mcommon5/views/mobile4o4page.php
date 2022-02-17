<?php
$headerComponent = array(
        'm_meta_title' => '404 Page',
	'doNotLoadMobileFiles' => 'true',
	);
$this->load->view('header',$headerComponent);
?>
<div id="wrapper" data-role="page">
	<header id="page-header" class="clearfix">
        <div id="logo-box">
        <a href="<?=SHIKSHA_HOME?>" class="logo"></a></div>
    </header>
	<section id="wrapper-404">
        <article>
            <h3>
                <i class="icon-404"></i>
                <p>Page Not Found</p>
            </h3>
            <p>Sorry, the page that you are trying to access is not available or has been moved.</p>
            <p>Go back to <a href="<?=SHIKSHA_HOME?>">www.shiksha.com</a></p>
            <div class="pointer-404"></div>
        </article>
        <figure><a href="<?=SHIKSHA_HOME?>"><img src="/public/mobile5/images/404.png" /></a></figure>
     </section>
    
    <header id="page-header" class="clearfix" style="display: none;">
        <div class="head-group">
            <aside>
                <form id="searchbox1" action="">
                    <span aria-hidden="true" class="icon-search"></span>
                    <input id="search" type="text" placeholder="Enter Institute or Course Name">
                </form>
            </aside>
        </div>
    </header>
    
    <?php //$this->load->view('footerLinks');?>
    
</div>
<?php
$footerComponent = array(
			 'doNotLoadImageLazyLoad'=>'true',
			 'doNotShowFacebookPixel'=>'true'
		);
$this->load->view('footer',$footerComponent);
?>
