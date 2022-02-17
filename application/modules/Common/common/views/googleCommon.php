<?php
  if(!$_REQUEST['loadFromStatic']) { ?>
<?php if(!empty($gtmParams)) { ?>
<script>
dataLayer = [<?php echo json_encode($gtmParams);?>];
</script>
<?php } ?>
<noscript>
<iframe src="//www.googletagmanager.com/ns.html?id=GTM-5FCGK6" height="0" width="0" style="display:none;visibility:hidden"></iframe>
</noscript>
<?php if((in_array($mobilePageName, array('CollegeReviewForm'))) || $pageSource != 'mobile'){  ?>
<script>
var isLoadGTM = true;
function enableGTM(){
	if(isLoadGTM){
		(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start': new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0], j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.defer=true;j.src=
'//www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);})
(window,document,'script','dataLayer','GTM-5FCGK6');
		isLoadGTM = false;
	}
}
window.addEventListener('scroll', function(e) {
  if(window.scrollY>0){
  		enableGTM();
  }
});
document.body.addEventListener('click', enableGTM, true); 
//enableGTM();
</script>
<?php } ?>
<?php } ?>