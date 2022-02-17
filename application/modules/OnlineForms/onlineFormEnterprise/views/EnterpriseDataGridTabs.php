<ul class="dashboard-tab">
	<?php
		if($tabId == 0){
			$class = "active";
		}else{
			$class = "";
		}
	?>
	<li class="master-tab <?=$class?>" onclick="openTab(0);">Enterprise User Dashboard</li>
	<?php
		foreach($tabs as $t){
			if($tabId == $t['tabId']){
				$class = "active";
			}else{
				$class = "";
			}
			if($t['tabName'] == "Analytics"){
				$class = "master-tab ".$class;
			}
	?>
		<li class="<?=$class?>"><span onclick="openTab(<?=$t['tabId']?>);" id="tab-<?=$t['tabId']?>" title="<?=html_escape($t['tabName'])?>"><?=html_escape($t['tabName'])?></span>
		<?php if($t['tabName'] != "Analytics"){ ?>
		<span class="close-tab" title="Delete" onclick="deleteTab(<?=$t['tabId']?>); return false;" style="cursor:pointer"></span>
		<?php } ?>
		</li>
	<?php
		}
	?>
	<li class="add-tab" onclick="addTab();"><div>+</div></li>
</ul>