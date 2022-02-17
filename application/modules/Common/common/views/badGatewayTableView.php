<html>
<head>
<title>Bad Gateway Errors</title>
<style type='text/css'>
body {font-family:arial; font-size:13px; font-weight:normal;}
th {font-size:12px; font-weight:bold; text-align:left; padding:10px;}
td {word-break: break-all; font-size:12px; text-align:left; padding:10px;}
</style>
</head>
<body>
<div style='width:1200px; margin:10px auto'>
<h1>Bad Gateway Errors</h1>
<table border='1' width='1200'>
<tr><th width='50'>Server</th><th>URL</th><th width='150'>Time</th></tr>
<?php foreach($badGatewayErrors as $error) { ?>
<tr>
<td><?php echo $error['server']; ?></td>
<td><?php echo $error['url']; ?></td>
<td><?php echo $error['time']; ?></td>
</tr>
<?php } ?>
</table>
</div>
</body>
</html>
