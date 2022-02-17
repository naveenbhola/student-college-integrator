<!DOCTYPE html>
<html>
    <head>
	    <meta charset="UTF-8">
	    <title>Document</title>
	    <link href='https://fonts.googleapis.com/css?family=Open+Sans:300,300italic,400,400italic,600,600italic,700,700italic,800,800italic' rel='stylesheet' type='text/css'>
        <link href="//<?php echo CSSURL; ?>/public/css/<?php echo getCSSWithVersion("rootRevampNew"); ?>" type="text/css" rel="stylesheet" />
        <link href="//<?php echo CSSURL; ?>/public/css/<?php echo getCSSWithVersion("userProfileHome"); ?>" type="text/css" rel="stylesheet" />
        <link href="//<?php echo CSSURL; ?>/public/css/<?php echo getCSSWithVersion("userProfile"); ?>" type="text/css" rel="stylesheet" />
		<!-- <link href="//<?php echo CSSURL; ?>/public/css/<?php echo getCSSWithVersion("chosenSumo"); ?>" type="text/css" rel="stylesheet" />         -->
		<?php echo includeCSSFiles('shiksha-com'); ?>
		<script language="javascript" src="//<?php echo JSURL; ?>/public/js/<?php echo getJSWithVersion('userProfile'); ?>"></script>
    </head>

    <body>

    <div class="main_content reset" >
	    <div class="container">

        <!-- add profile header-->
		<?php $this->load->view('addProfileHeader'); ?>

		<!--profile tabs -->

        <!--change password starts-->
        <div class="n-row">

            <section class="prf-tabs">
            <?php $this->load->view('profilePageTabs');?>

                <div class="prf-tab-cont">
                    <div class="col-lg-9 pLR0">
                    <!--profile page starts-->
                        <?php $this->load->view('addProfilePersonalProfile');?>
                    <!--profile page ends-->

                    <!--Account Settings starts-->
                        <?php $this->load->view('profilePageAccountsAndSettings');?>
                    <!--Account Settings ends-->

                    <!--mentor ship-->
                        <?php $this->load->view('profilePageMentorPage');?>
                    <!--mentor ship ends-->
                
                    </div>
                </div>

                <!--right side ad-->
                    <?php $this->load->view('profilePageRightSideAd');?>
                <!--right side ads end-->
         
            </section>
         </div>

    </div>
</div>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
<script src="//<?php echo JSURL; ?>/public/js/<?php echo getJSWithVersion("jquery.sumoselect.min"); ?>"></script>
<script type="text/javascript">
$(document).ready(function(){
	crteSlider();

	$(".sumo-select").SumoSelect();
});

$(window).resize(function(){
	crteSlider();
});

var sldrPos=0;
function crteSlider(){
	var w_width=$(window).width();
	$('.n-featBanrs li').each(function(){
		$(this).width((w_width-50)/5);
	});

	$('.featMoveArw').click(function(){
		var t = $('.n-featBanrs li').width();
		var mrgnLft = $('.n-featBanrs').css('margin-left');
		if(sldrPos == 0){
			$('.n-featBanrs').css('margin-left','-100%');
			sldrPos=1;
		}
		else{
			$('.n-featBanrs').css('margin-left','0%');
			sldrPos=0;
		}
	});
}


</script>
<script type="text/javascript">
$(document).ready(function() {
	$(".prf-tabpane").hide(); //Hide all content
	$("ul.prft-tabs li:first").addClass("current").show();
	$(".prf-tabpane:first").show();
	$("ul.prft-tabs li").click(function() {
		$("ul.prft-tabs li").removeClass("current");
		$(this).addClass("current");
		$(".prf-tabpane").hide();
		var activeTab = $(this).find("a").attr("href");
		$(activeTab).fadeIn();
		return false;
	});
});

//custom drop down
$('.custom-drp').click(function(){
	$(this).toggleClass('drpdwn_active');
});
</script>
</body>
</html>
