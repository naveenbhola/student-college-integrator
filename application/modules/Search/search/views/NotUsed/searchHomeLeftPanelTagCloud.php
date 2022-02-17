<!--Course_TYPE-->
                        <div class="raised_blue_L">
                                <b class="b2"></b>
                                <div class="boxcontent_blue" style="background:none">
									<div class="row_blue" align="left" style="padding:5px 0px;">
										<span style="padding:5px;">Related Searches</span>
									</div>
									<div class="lineSpace_11">&nbsp;</div>
									<div style="margin-left:10px;margin-right:10px;">
						<?php
						shuffle($tagCloud);
						foreach($tagCloud as $row)
						{
							#echo $row['keyword']." : ".$row['count']."<br/>";
							$tags[$row['keyword']]=$row['count'];
						}
						$max_size = 18;
						$min_size = 9; 

						$max_qty = max(array_values($tags));
						$min_qty = min(array_values($tags));

						$spread = log($max_qty) - log($min_qty);
						if (0 == $spread) { 
						    $spread = 1;
						}
						//$step = ($max_size - $min_size)/($spread);
						foreach ($tags as $key => $value) {
                            $step = (log($value) - log($min_qty))/($spread);
    						$size = $min_size + (($max_size - $min_size) * $step);
    						$size = ceil($size); ?>
    						<a href="#" style="font-size:<?php echo $size; ?>px;" onclick='javascript:searchTag(this.innerHTML);'><?php 
		$originalKey=$key;
		while(strlen($key)>12)
		{
			echo substr($key,0,12)."<wbr>"; 
			$key=substr($key,12,strlen($key));
		} 
		echo $key; 
		?></a>
						<?php }
						?>
						</div>
                                        <div class="lineSpace_11">&nbsp;</div>
					</div>
					<b class="b4b" style="background:#ffffff;"></b><b class="b3b" style="background:#ffffff;"></b><b class="b2b" style="background:#ffffff;"></b><b class="b1b"></b>
				</div>
			</div>
