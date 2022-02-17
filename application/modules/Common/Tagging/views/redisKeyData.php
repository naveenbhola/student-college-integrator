<div style='border:1px solid #ccc; '>
<?php
if(true){
	?>
		<div class='heading' style=' border-bottom: 1px solid;margin-bottom:10px;text-align:center;'>
			<h4 style='margin:10px 0px;padding: 0 10px;'>Data for <?=$key;?></h4>
		</div>

		<table style="padding:0px 10px;"> 
			<tr style='padding:5px;'><td style='width:25%'><b>Type</b></td><td><?=$type?>(<?=$keyMapping[$type]?>)</td></tr>
			<tr style='height:10px;'></tr>
			<tr style='padding:5px;'><td><b>Total Members</b></td><td><?=$cnt;?></td></tr>
			<tr style='height:10px;'></tr>
			<tr style='padding:5px;'><td><b>TTL</b></td><td>
				<div id='ttlBox'>
					<?php
					if($ttl == "-1"){
						echo "TTL Not Set. "; ?>&nbsp;&nbsp;&nbsp;<a href='javascript:void(0);' onclick="$j('#actions').val('expireKey').trigger('change');$j('#expireTimeInputBox').focus();">Set TTL</a><?php
					} else if($ttl == "-2"){
						echo "Key doesn't exists";
					} else {
						echo $ttl."<b> secs</b>.&nbsp;&nbsp;&nbsp;<a href='javascript:void(0);' onclick='removeTTL()'>Remove TTL</a>".'<div style="margin: 40px;margin-left:30;display:none;" id="removeTTLLoader"><img border="0" src="/public/mobile5/images/ajax-loader.gif"></div>';
					}
					?>
				</div>
			</td></tr>
			<tr style='height:10px;'></tr>
			<tr style='vertical-align:top;padding:5px;'><td style='width:100px'><b>Data</b></td><td>

			<?php if($type != "none"){
				?>
					<ul style='padding:0;'>
					<?php
					if($type == 'hash'){

						if(!empty($data)){

							echo "<table border=1 cellspacing=0 cellpadding=5>";
							echo "<tr><th>Hash Key</th><th>Hash value</th></tr>";
							foreach ($data as $hashKeyName => $hashkeyNameValue) {
								echo "<tr><td>".$hashKeyName."</td><td>".$hashkeyNameValue."</td></tr>";
							}
							echo "</table>";
						} else {
							echo "<b>More than $MAX_SIZE keys</b>";
						}
						
					} elseif ($type == 'set') {
						if(!empty($data)){
							foreach ($data as $setValue) {
								echo "<li>".$setValue."</li>";
							}	
						}else{
							echo "<b>More than $MAX_SIZE keys</b>";
						}						
						
					}
					 elseif ($type == 'zset') {

					 	if(!empty($data)){
				 			echo "<table border=1 cellspacing=0 cellpadding=5>";
							echo "<tr><th>Sorted Set Key</th><th>Sorted Set value</th></tr>";
							foreach ($data as $zsetKeyName => $zsetkeyNameValue) {
								echo "<tr><td>".$zsetKeyName."</td><td>".$zsetkeyNameValue."</td></tr>";
							}
							echo "</table>";
					 	} else{
							echo "<b>More than $MAX_SIZE keys</b>";
						}
					 	
					} elseif($type == 'list'){

						echo "<table border=1 cellspacing=0 cellpadding=5>";
						echo "<tr><th>List value</th></tr>";

						foreach ($data as $listKey=>$listValue) {	
							echo "<tr><td>".$listValue."</td></tr>";
						}	
						echo "</table>";

					}
					elseif ($type == 'string') {
						if(isset($isSerialized) && $isSerialized == true){
							echo "It contains serialized data. Unserialized data : ";
							_p($data);
						}
						else if(isset($isJson) && $isJson == true){
							echo "It contains JSON data.<br /><br /> <b>JSON String</b> ";
							_p($data);
							echo "<b>JSON DECODED DATA : </b>";
							_p(json_decode($data));
						} else {
							_p($data);
						}
					}
					
					?>			
					</ul>
				<?php
			} else {
				echo "Key not exists.";
			}
			?>
			
			</td></tr>
		</table>
		
	<?php

}
?>
</div>