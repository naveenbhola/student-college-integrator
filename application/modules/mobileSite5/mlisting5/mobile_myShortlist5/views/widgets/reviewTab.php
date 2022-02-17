<?php 
    if($viewData['totalReview'] < 1) 
    { ?>
        <div class="shortlist-tab-details" style="border:none;"><article class="find-options"style="display:block"><div class="NoFound-Result-box"><i class="msprite no-result-icon flLt"></i><p style="margin-left:20px;">Sorry, reviews are currently not available. We will notify you as soon as the reviews are published.</p></div></article></div>
<?php }
    else 
    { ?>
    <article class="lising-nav-details padLR-zero">
        <div class="mys-standrRtng">
        	<label style="cursor: default; font-size: 13px; font-weight: bold; color:#999;"><a href="<?php echo $reviewURL;?>">Average Course Rating <b>: </b></a></label>
            <div class="ranking-bg"><strong><?php echo $viewData['averageRating'];?></strong><sub>/5</sub></div>
            <p class="clr"></p>
        </div>
        <?php //$this->load->view("widgets/reviewTabDetails");?>
    </article>
<?php }
?>

<div id="loading_img" style="text-align:center;margin-top:20px;display:none;"><img src="/public/mobile5/images/ajax-loader.gif" border=0 /></div>