<section class="clearfix content-section">
    <p class="mentor-title">Chat with Mentor</p>
    <div class="mentor-widget-box clearfix">
        <p class="next-chat-content" style="margin-top: 10px;">Your chat has been started.</p>
    </div>
</section>
<?php if($showChatWidget=='yes' && $chatId != ''){ ?>
<!--Start of Tawk.to Script-->
<script type="text/javascript">
var $_Tawk_API={},$_Tawk_LoadStart=new Date();
(function(){
var s1=document.createElement("script"),s0=document.getElementsByTagName("script")[0];
s1.async=true;
s1.src='https://embed.tawk.to/<?=$chatId?>/default';
s1.charset='UTF-8';
s1.setAttribute('crossorigin','*');
s0.parentNode.insertBefore(s1,s0);
})();
</script>
<!--End of Tawk.to Script-->
<?php } ?>