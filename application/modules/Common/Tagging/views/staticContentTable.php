<!DOCTYPE HTML>
<html>
   <head>
   	<title>Static Thread Editing Interface</title>
   	<script type="text/javascript" src="//code.jquery.com/jquery-1.12.0.min.js"></script>
   </head>
   <?php //_p($threadData);
   ?>
   <body>
   		<h2>Static Thread</h2>
   		<form action="/Tagging/TaggingCMS/saveStaticThreads" method="POST">
   			<textarea id="threadIds" name="threadIds" style="height:100px;width:1000px"><?php echo implode(',', array_keys($threadData));?></textarea>
   			<br/>
   			<input value="save" type="button" title="Save" onclick="saveStaticThread(this);">
   		</form>
   		<br/>
	      <table id="threadTable" border>
	      	<thead>
	      		Static Thread
	      	</thead>
	      	<tbody>
	      		<tr>
	      			<th>S.No.</th>
	      			<th>Thread Id</th>
	      			<th>Thread Type</th>
	      			<th>Thread</th>
	      		</tr>
	      		<?php 
	      		$i = 0;
	      		foreach ($threadData as $value){?>
	      		
	      			<tr>
	      				<div>
		      				<td><?php echo ++$i;?></td>
		      				<td><?php echo $value['threadId'];?></td>
		      				<td><?php echo $value['threadType'];?></td>
	      					<td><?php echo $value['msgTxt'];?></td>
			      			</div>
			      		</tr>
		      		
	      	<?php }?>
	      	</tbody>
	      </table>
   </body>
   <script>
   			
			$(document).ready(function(){
				
			});
			
				function saveStaticThread(obj){
					var threadIds = $('#threadIds').val();
					threadIds = threadIds.split(',');
					//console.log(JSON.stringify(threadIds));
					for(var i=0; i<threadIds.length; i++){
					  if(!(parseInt(threadIds[i]) == threadIds[i])){
					    alert("List contains invalid threadIds");
					    return false;
					  }
					}
					//$(obj).value('working');
					obj.value = "Working... Please wait";
					obj.disabled = true; 
					$.ajax({
							url: '/Tagging/TaggingCMS/saveStaticThreads',
							type: 'POST',
							cache: false,
							data: {threadIds : JSON.stringify(threadIds)},
							success: function(data){
											console.log(data);
											if(data == 1){
												alert("Threads Saved Successfully");
												location.reload(true);
											}else{
												alert("Some problem occured");
												obj.value = "Save";
												obj.disabled = false;
											}
										},
							fail: function(n){ 
									alert ("Fail : Some problem occured");
									obj.value = "Save";
									obj.disabled = false;
								}
						}); 
					//obj.value = "Save";
				}
   </script>
</html>
<?php
?>