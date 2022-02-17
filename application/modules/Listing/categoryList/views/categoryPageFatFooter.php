<?php if((count($finalFooter['city'])>0 || count($finalFooter['state'])>0) && !empty($finalFooter)){?>
<div class="FAT-footer-cont" uniqueattr="categoryPageFatFooter">
	<div style="padding-bottom:5px"><strong style="font-size:16px;display:block;font-family:arial;">You may also be interested in</strong></div>
	<table cellpadding="0" cellspacing="0" border="0" width="100%">
		<tr>
		<?php $no_footer = count($finalFooter);if(count($finalFooter['city'])>0){?>
		<td style="width:<?=(100/$no_footer)?>%" id="_ctyLst">
			<div style="width:<?=(850/$no_footer)?>px"><ul><?php
				foreach($finalFooter['city'] as $link){
					echo '<li><a href="'.$link['url'].'" title="'.html_escape($link['urlTitle']).'">'.html_escape($link['urlTitle']).'</a></li>';
				}
			?></ul></div>
		</td>
		<?php } if(count($finalFooter['state'])>0){

				if(count($finalFooter['state'])>8){
					$stateData[0] = array_slice($finalFooter['state'],0,8);
					$stateData[1] = array_slice($finalFooter['state'],8,8);
				}else{
					$stateData[0] = $finalFooter['state'];
				}
				$stateCount = count($stateData);
                foreach($stateData as $footer){
                        $i = 0;
                ?>
                <td style="width:<?=(100/$stateCount)?>%" id="_stLst">
                        <div style="width:425px"><ul><?php
                                foreach($footer as $link){
                                        $i++;
                                        if($i > 8){
                                                break;
                                        }
                                        echo '<li><a href="'.$link['url'].'" title="'.html_escape($link['urlTitle']).'">'.html_escape($link['urlTitle']).'</a></li>';
                                }
                        ?></ul></div>
                </td>
                <?php } 
            }?>
		</tr>
	</table>
</div>
<?php }?>