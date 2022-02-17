<div class="tabSection">
    <ul class="">
        <li <?php if($contentType=='question'){ ?> class="active"  <?php } ?> data-index="1"><h3><a class="head" href="<?=$base_url?>"  contentType='<?php echo $contentType; ?>' id="home" ga-attr="QnAHOME_TAB">Questions</a></h3></li>
        <li <?php if($contentType=='discussion'){ ?> class="active"  <?php } ?> data-index="2"><h3><a class="head" href="<?=$base_url.'?type=discussion'?>" contentType='<?php echo $contentType; ?>' id = "discussion" ga-attr="DISCUSSIONS_TAB">Discussions</a></h3></li>
        <li <?php if($contentType=='unanswered'){ ?> class="active"  <?php } ?> data-index="3"><h3><a class="head" href="<?=$base_url.'?type=unanswered'?>" id = "unanswered" contentType='<?php echo $contentType; ?>' ga-attr="UNANSWERED_TAB">Unanswered Questions</a></h3></li>
    </ul>
</div>

