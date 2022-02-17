 <?php 
 if($isHomePage)
 {
 	$className = 'exm-dtl';
 }
 ?>
<div class="clg-blok dflt__card">
	<div>
		<h2><?php echo $sectionNameMapping[$section]?></h2>	
	<?php foreach ($sectionData as $key => $curObj) { ?>
		<?php 
		    $data = $curObj->getEntityValue();
		    $wikiLabel = new tidy ();
		    $wikiLabel->parseString (htmlspecialchars_decode($data) , array ('show-body-only' => true ), 'utf8' );
		    $wikiLabel->cleanRepair();
		?>
	<p class="f14__clr3"><?php echo $wikiLabel ?></p>
	<?php } ?>
</div>
</div>