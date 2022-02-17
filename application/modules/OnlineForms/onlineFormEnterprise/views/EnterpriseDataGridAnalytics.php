<script type="text/javascript" src="https://www.google.com/jsapi"></script>
<script type="text/javascript">

   // Load the Visualization API and the piechart package.
   google.load('visualization', '1.0', {'packages':['corechart']});

   // Set a callback to run when the Google Visualization API is loaded.
   //google.setOnLoadCallback(drawChart);

   // Callback that creates and populates a data table,
   // instantiates the pie chart, passes in the data and
   // draws it.
   //function drawChart() {

	
   //}
</script>
<table cellpadding="0" cellspacing="0" border="0">
	<tr style="border:none">
<?php
	$data = $tab->getAnalyticsData($forms,$headings);
	for($i=1;$i<=10;$i++){
?>
<td style="border:none" valign="top">
<div style="width:470px;float:left;margin:5px;display:none" id="graph-parent-<?=$i?>" class="graphs">
	<div id="graph-div-<?=$i?>" style="display:none">
		<h4 id="graph-title-<?=$i?>" style="border-bottom:1px solid #EDEDED;font-size:16px;margin-bottom:8px;padding-bottom:8px;display:block">
			Select the graph criteria
		</h4>
		<div>
			<div style="float:right">
				<a href="#" onclick="removeGraph('<?=$i?>');return false;">[-] Remove</a>
			</div>
		</div>
		<div class="col-1" style="margin-bottom:5px;">
			<select class="universal-select" id="graph-type-<?=$i?>" onchange="selectGraphField(<?=$i?>)">
					<option value="">Choose a graph type</option>
					<option value="Pie">Pie</option>
					<option value="Bar">Bar</option>
					<option value="Line">Line</option>
					<option value="Column">Column</option>
					<option value="Area">Area</option>
			</select>
		</div>
		<div class="col-2">
			<select class="universal-select" id="graph-field-<?=$i?>" onchange="selectGraphField(<?=$i?>)">
				<option value="">Choose a Field</option>
				<?php
					foreach($fields as $key=>$heading){
				?>
					<option value="<?=$key?>"><?=html_escape($heading['name'])?></option>
				<?php
					}
				?>
			</select>
		</div>
		<div id="graph-<?=$i?>">
			
		</div>
	</div>
	<div style="text-align:center;">
	<button class="orange-button" id="graph-button-<?=$i?>" onclick="addGraph('<?=$i?>');" style="cursor:pointer;margin-top:40px">
		Add More Graph
	</button>
	</div>
</div>
</td>
<?php
	if($i%2 == 0){
?>
	</tr>
	<tr style="border:none">
<?php
	}
	}
?>
	</tr>
</table>
<script>
var analyticsData = <?=json_encode($data)?>;
</script>




