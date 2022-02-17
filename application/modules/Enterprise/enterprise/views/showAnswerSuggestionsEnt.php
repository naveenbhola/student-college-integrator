		<?php
		if(isset($answerSuggestions[$answerId]) && count($answerSuggestions[$answerId])>0 ){ ?>
			<div class="suggested-course-cont" style="font-size:10px; padding-left:20px;padding-top:10px;">
				<strong>The expert recommends following institutes:</strong>
				<ul>
				    <?php foreach ($answerSuggestions[$answerId] as $suggestion){ ?>
					    <li><div><a target="_blank" href='<?=$suggestion[2]?>'><?=$suggestion[1]?></a></div></li>
				    <?php } ?>
				</ul>
			</div>
		<?php } ?>
