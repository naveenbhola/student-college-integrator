</article>
<?php if(!empty($links)):?>
	    <h2 class="ques-title">
		<p>Previous year discussions</p>
	    </h2>
	    
	    <article class="req-bro-box clearfix">
            <ul class="bullet-item">
   
            	<?php foreach ($links as $id => $data):?>
		    <?php if($_GET['link_id'] == $data['id']):?>
			<li style="font-size: 1em;color:#666 !important"><?php echo $data["historyLink"];?></li>
		    <?php else:?>
			<li style="font-size: 1em;"><a href="<?php echo SHIKSHA_HOME.preg_replace('/\?.*$/', '',$_SERVER['REQUEST_URI']).'?link_id='.$data['id'].'#ca_aqf'?>" ><?php echo $data["historyLink"];?></a></li>
		    <?php endif;?>
                <?php endforeach;?>
		
            </ul>
	    </article>
<?php endif;?>
    
</section>