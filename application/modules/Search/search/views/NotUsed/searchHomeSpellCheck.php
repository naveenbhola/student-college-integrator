<?php if(isset($spellSuggest['0']['suggestions']) && $spellSuggest['0']['suggestions']!=""): 
					?>
					<span class="blackFont">Try</span>
					<a href="#" onclick="javascipt:searchTag(this.innerHTML,1);" style="font-size: 14px;"><?php echo $spellSuggest['0']['suggestions']; ?></a>
					<?php if($searchType!=""): ?>
					<span class="blackFont">in All products.</span>
					<?php endif; ?>
					<?php endif; ?>
