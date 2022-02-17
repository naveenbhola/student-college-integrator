<html xmlns:fb="https://www.facebook.com/2008/fbml" xmlns="http://www.w3.org/1999/xhtml"><head>
<title>Indexing Log - Shiksha.com</title>
<style type="text/css">
    body {background:#eee; margin:0; padding:0; font:normal 14px arial;}
    table {border-left:1px solid #ccc; border-top:1px solid #ccc;}
    td {border-right:1px solid #ccc; border-bottom:1px solid #ccc; padding:8px 5px; font-size:13px;}
    th {border-right:1px solid #ccc; border-bottom:1px solid #ccc; padding:5px; text-align:left; background:#f6f6f6; font-size:13px;}
    h1 {font-size:30px; margin-top: 10px;}
    a {text-decoration:none; color:#444;}
    #overlay {
                position: fixed;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                background-color: #000;
                filter:alpha(opacity=50);
                -moz-opacity:0.5;
                -khtml-opacity: 0.5;
                opacity: 0.3;
                z-index: 10;
            }
</style>
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js" type="text/javascript"></script>
</head>
<body>
<div style="width:1000px; margin:0 auto; background:#fff; padding:20px; box-shadow: 0px 0px 10px #aaaaaa; min-height: 600px;">
    
    <h1><a href="#">Indexing Logs</a></h1>
    <div style="clear: both;"></div>
    
    <div style="width:960px; margin:30px 0; font-size:16px; background:#F3FAF2; color:#444; padding:20px;">
        <form id="qForm" action="indexLogs" method="get">
        <div style="float:left; padding-top:8px;"> Type : </div>
        <div style="float:left; margin-left:5px; padding-top:1px;">
            <select id = "type" name="type" style="font-size:14px; padding:3px; color:#444;" >
               	<option value="">Select</option>
                <?php foreach($types as $type ) {?>
                 <option value="<?=$type?>" <?= $type == $requestedLog['type'] ? "selected='selected'" : '' ?> ><?=$type ?></option>
                <?php }?>
			</select>
        </div>
        <div style="float:left; margin-left:30px; padding-top:8px;">Date From : </div>
        <div style="float:left; margin-left:5px; padding-top:1px;">
         <input type="date" name="fromDate" value="<?=$requestedLog['fromDate'] ?>">
     	</div>
     	<div style="float:left; margin-left:5px; padding-top:1px;">
            <select id ="fromHour" name="fromHour" style="font-size:14px; padding:3px; color:#444;" >
           
           <?php for($i=0 ;$i<=23;$i++) {?>
           <option value="<?=str_pad($i, 2, "0", STR_PAD_LEFT); ?>" <?= $i == $requestedLog['fromHour'] ? "selected='selected'" : '' ?> ><?=str_pad($i, 2, "0", STR_PAD_LEFT); ?></option>
           <?php }?> 
       	  </select> :
       	  <select id ="fromMinutes" name="fromMinutes" style="font-size:14px; padding:3px; color:#444;" >
          <?php for($i=0 ;$i<=1;$i++) {?>
           <option value="<?=str_pad($i*30, 2, "0", STR_PAD_LEFT); ?>" <?= $i*30 == $requestedLog['fromMinutes'] ? "selected='selected'" : '' ?> ><?=str_pad($i*30, 2, "0", STR_PAD_LEFT); ?></option>
           <?php }?> 
       	  </select>
     	</div>
     	
        <div style="float:left; margin-left:30px; padding-top:8px;" >Status : </div>
        <div style="float:left; margin-left:5px; padding-top:1px;">
            <select id ="status" name="status" style="font-size:14px; padding:3px; color:#444;" >
                <option value="">Select</option>
                <?php foreach($statusArray as $status ) {?>
              <option value="<?=$status?>" <?= $status == $requestedLog['status'] ? "selected='selected'" : '' ?> ><?=$status ?></option>
              <?php }?>          
                </select>
        </div>
        <div style="float: right;margin-right: 40px;margin-top: 10px;opacity: 0.4;">
		<a onclick="document.getElementById('qForm').submit();" href="#">
		 <b>Submit</b>
		</a>
        </div>
        <div style="margin-top: 10px;float: left;margin-left: 202;">
        <div style="float:left; margin-left:30px; padding-top:8px;">Date To: </div>
        <div style="float:left; margin-left:5px; padding-top:1px;">
           <input type="date" name="toDate" value="<?=$requestedLog['toDate'] ?>">
        </select>
        </div>
        <div style="float:left; margin-left:5px; padding-top:1px;">
             	
            <select id ="toHour" name="toHour" style="font-size:14px; padding:3px; color:#444;" >
           
           <?php for($i=0 ;$i<=23;$i++) {?>
           <option value="<?=str_pad($i, 2, "0", STR_PAD_LEFT); ?>" <?= $i == $requestedLog['toHour'] ? "selected='selected'" : '' ?> ><?=str_pad($i, 2, "0", STR_PAD_LEFT); ?></option>
           <?php }?> 
       	  </select> :
       	  <select id ="toMinutes" name="toMinutes" style="font-size:14px; padding:3px; color:#444;" >
          <?php for($i=0 ;$i<=1;$i++) {?>
           <option value="<?=str_pad($i*30, 2, "0", STR_PAD_LEFT); ?>" <?= $i*30 == $requestedLog['toMinutes'] ? "selected='selected'" : '' ?> ><?=str_pad($i*30, 2, "0", STR_PAD_LEFT); ?></option>
           <?php }?> 
       	  </select>
     	</div>
     	</div>

        </form>
        <div style="clear:both"></div>
    </div>

<table width="1000" cellspacing="0" cellpadding="0">
    <tbody><tr><th>#</th><th>Type</th><th>Type Id</th><th>Time</th><th>Operation Type</th><th>Status</th> <th>Error Msg</th><th>Re-Index Link</th></tr>
    <?= count($loggedData) == 0 ? "<tr valign='top'  > <td colspan = '8' style = 'text-align: center;'>No Data Found!!</td></tr>" : ""?>
    
    <?php 
    $urlPrefix = base_url();
    foreach($loggedData as $indexNo => $row) { ?>
    <tr>
    <td valign="top"><?=$indexNo ?></td>
    <td valign="top"><?=(isset($row['type']) && !empty($row['type']) ) ? $row['type'] : "-" ?></td>
	<td valign="top"><?=(isset($row['typeId']) && !empty($row['typeId']) ) ? $row['typeId'] : "-" ?></td>
	<td valign="top"><?=(isset($row['time']) && !empty($row['time']) ) ? $row['time'] : "-" ?></td>
	<td valign="top"><?=(isset($row['OperationType']) && !empty($row['OperationType']) ) ? $row['OperationType'] : "-" ?></td>
	<td valign="top"><?=(isset($row['status']) && !empty($row['status']) ) ? $row['status'] : "-" ?></td>
	<td valign="top"><?=isset($row['errorMsg']) && !empty($row['errorMsg']) ? $row['errorMsg'] : "-" ?></td>
	<td valign="top"><a target = "_blank" style ="color: blue" href = "<?=$urlPrefix."indexer/NationalIndexer/".$row['OperationType']."/".$row['type']."/".$row['typeId']."?debug=true" ?>"><?="link" ?></a></td>
	
	</tr>
    <?php }?>
    
    
    

</div>
<script>
function changeResultPerPage(id){
location.assign(document.getElementById(id).value);
}
</script> 

</body></html>