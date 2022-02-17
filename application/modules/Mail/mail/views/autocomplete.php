<ul>
	<?php foreach($matches as $match): ?>
	<li><?php echo str_ireplace($string,"<b>".$string."</b>",$match['displayname']); ?></li>
	<?php endforeach; ?>
</ul> 