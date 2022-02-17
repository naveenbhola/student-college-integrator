<div id="upldThkLyr" data-role= "page" style = "background:#fff !important;width:100% !important;" data-enhance="false">
	<div class="layer-header">
        <a href="javaScript:void(0);" class="back-box upldDne"><i class="sprite back-icn"></i></a>
        <p>Get a free profile evaluation card</p>
    </div>
    <section class="content-wrap">
        <article class="content-inner">
            <div>
                <i class="cnslr-sprite tck-icn"></i>
                <strong class="card-hd mrgnLft">Thank you for submitting your documents.</strong>
            </div>
            <p class="card-caption">Our counseling team will review your documents and get back to you maximum within 5 working days.</p>
            
            <?php if($counselorInfo['firstname'] !=''){?>
            <p class="card-caption">For faster response, please get in touch with <?php echo ucfirst($counselorInfo['firstname']); ?> directly.</p>
            <div class="cnt-div">
            <div class="img"><img src="<?php echo getImageUrlBySize($counselorInfo['counselorImageUrl'],'medium'); ?>" alt="<?php echo ucfirst($counselorInfo['firstname']); ?>" title="<?php echo ucfirst($counselorInfo['firstname']); ?>"></div>
            <div class="data">
                <p class="titl"><?php echo ucfirst($counselorInfo['firstname']); ?></p>
                <a class="get-in" href="tel:+<?php echo ($counselorInfo['isdCode'].$counselorInfo['mobile']); ?>"><i class="cnslr-sprite ic-call"></i>Call</a>
                <a id="whatsapp1" class="get-in" href="whatsapp://send?text=+<?php echo ($counselorInfo['isdCode'].$counselorInfo['mobile']); ?>"><i class="cnslr-sprite ic-whts"></i>Whatsapp</a>

                <a id="whatsapp2" class="get-in" href="intent://+<?php echo ($counselorInfo['isdCode'].$counselorInfo['mobile']); ?>#Intent;scheme=smsto;package=com.whatsapp;action=android.intent.action.SENDTO;end"><i class="cnslr-sprite ic-whts"></i>Whatsapp</a>

                <a class="get-in" href="mailto:<?php echo ($counselorInfo['email']); ?>"><i class="cnslr-sprite ic-mail"></i><?php echo ($counselorInfo['email']); ?></a>
            </div>
            </div>
            <?php } ?>

            <a href="javaScript:void(0);" class="cl-btn upldDne">Done</a>
        </article>
    </section>
</div>
<script> 
var ua = navigator.userAgent.toLowerCase();
var isAndroid = ua.indexOf("android") > -1; 
//&& ua.indexOf("mobile");
if(isAndroid) {
    if(document.getElementById("whatsapp1"))
        document.getElementById("whatsapp1").style.display = "none";
    if(document.getElementById("whatsapp2"))
        document.getElementById("whatsapp2").style.display = "block";
}else{
    if(document.getElementById("whatsapp1"))
        document.getElementById("whatsapp1").style.display = "block";
    if(document.getElementById("whatsapp2"))
        document.getElementById("whatsapp2").style.display = "none";
}
</script>