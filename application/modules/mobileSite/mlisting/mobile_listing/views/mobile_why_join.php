<?php 
$reasonJoin = unserialize(base64_decode($whyJoin));
if(!empty($reasonJoin['0']['0']['details'])){?>
<div class="round-box">
	<div class="course-details">
        <div class="inst-desc">
            <h2><?php echo "Why join ". $institute->getName()." ?";?></h2> 
        </div>
        <p><?php echo ($reasonJoin['0']['0']['details']);?></p>
    </div>
</div>

<?php }?>
