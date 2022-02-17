<div class="recomend-col">
	<div class="group-card bg-none gap rco-tblDiv">
		<h2 class="head-1">People who search for <?=$heading?> are also interested in: </h2>
		<div class="table-Flx flex-ul clg-intrst">
			<table>
				<tbody>
			<?php 
			$categoryPageInterlinkgUrls = array_values($categoryPageInterlinkgUrls);
			// _p($categoryPageInterlinkgUrls); die;
				for($i=0;$i <4;$i++) { ?>

					<tr>
						<?php for ($j=$i*4;$j<(($i*4)+4);$j++) { 
								if(!empty($categoryPageInterlinkgUrls[$j]['url'])) {
							?>
							<td><a target="_blank" ctpg-key="<?=$categoryPagekey?>" href="<?=$categoryPageInterlinkgUrls[$j]['url']?>"><?=$categoryPageInterlinkgUrls[$j]['heading']?></a></td>
						<?php } 
					}?>
					</tr>
			<?php }
				// foreach ($categoryPageInterlinkgUrls as $interlinkingUrl) { 
			?>
			<?php 
			?>
		</tbody>
		</table>
		</div>
	</div>
</div>