<section class="detail-header">
    <?php foreach($content['data']['sections'] as $key => $section) { ?>
        <a href="javascript:void(0);" onclick="scrollToSection('<?=$key?>',this);" class="<?=$key==0?'active nocursor':''?>" style="line-height:16px; padding-left: 5px; padding-right: 5px;">
            <p><?=$section['heading']?></p>
            <i class="sprite pointer"></i>
        </a>
    <?php } ?>
</section>
<script>
    function scrollToSection(keyId,elem) {
        if(!$j(elem).hasClass("active")){   // Clicking on the active element does nothing.
            $j(".less-more-sec").children("a").trigger('click');
            $j("html, body").animate({scrollTop: $j("#sectionData"+keyId).offset().top},1000);
        }
    }
</script>