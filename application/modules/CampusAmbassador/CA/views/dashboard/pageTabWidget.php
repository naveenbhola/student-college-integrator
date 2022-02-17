<div class="campus-tab clear-width">
<ul>    
        <?php if($pageName == 'myTask'){?>
                <li><a href="/CA/CRDashboard/getCRUnansweredTab">Answer</a><i class="campus-sprite pointer"></i></li>
                <li class="active"><a>My Task</a><i class="campus-sprite pointer"></i></li>
		<?php }else{?>       
        		<li class="active"><a>Answer</a><i class="campus-sprite pointer"></i></li>
        		<li class=""><a href="/CA/CRDashboard/myTaskTab">My Task</a><i class="campus-sprite pointer"></i></li>
		<?php }?>
</ul>
</div>
