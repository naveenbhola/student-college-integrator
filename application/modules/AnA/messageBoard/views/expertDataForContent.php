<form action="/messageBoard/MsgBoard/expertDataForContent" method="post">
	From Date: <input type="text" name="fromDate" value="<?=$fromDate?>"/><br>
	To Date:   <input type="text" name="toDate" value="<?=$toDate?>" /><br>
	Email Ids:<br><textarea name="emailBox" cols="100" rows="10"></textarea><br>
	<input type="submit" value="Get Data" name="getData"/>
</form>
<div>
<table border = '1'>
<tr>
<th> EmailId </th>
<th> Answer Count </th>
</tr>	
	<?php
	if(!empty($answerCount)){
		foreach($answerCount as $value){
			echo '<tr>';
			echo '<td>'.$emailId[$value['userId']].'</td>';
			echo '<td>'.$value['answerCount'].'</td>';
			echo '</tr>';
		}
	}
	?>
	</table>
</div>