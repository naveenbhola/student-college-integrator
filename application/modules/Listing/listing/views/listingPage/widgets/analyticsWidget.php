<div class="section-cont">
<div class="section-cont-title">Shiksha Analytics</div>
<div class="pL-7">
	<ul class="bullet-items">
		<?php if($responseCount > 0){ ?>
		<li><p><?=$responseCount?> students have applied to the institute</p></li>
		<?php } ?>
		<li><p>The <?=$pageType?> has been viewed <?=$viewCount?> times</p></li>
        <?php if($searchCount > 0){ ?>
		<li><p>The institute has appeared in <?=$searchCount?> searches</p></li>
	    <?php } ?>
    </ul>
</div>
</div>
