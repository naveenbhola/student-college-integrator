        <?php 
        if($paginator->isPaginationPossible())
        {
        ?>
        
          <div class="cms-pagination flRt">
        	<ul>
       	     <?php $paginator->generateURLsForBottomSection(); ?>
			</ul>
       	 </div>
        <?php }?>