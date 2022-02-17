<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xmlns:fb="https://www.facebook.com/2008/fbml">
<head>
<title>Listings Debugger - Shiksha.com</title>
<style type='text/css'>
    body {margin:0; padding:0; font-family:arial;}
    h1 {font-size:18px; color:#333;}
    table {border-left:1px solid #ddd; border-top:1px solid #ddd;}
    table th {border-right:1px solid #ddd; border-bottom:1px solid #ddd; font-size:12px; font-family: arial; padding:5px; background: #f6f6f6; text-align:left;}
    table td {border-right:1px solid #ddd; border-bottom:1px solid #ddd; font-size:12px; font-family: arial; padding:5px; color:#222;}
</style>
</head>

<body>
    <form method='get' action='/listing/ListingDebugger' id='sform'>
    <div style='background:#f1f1f1; padding:20px 0 25px 50px;'>
        <div style='margin:0 auto; width:360px; border:0px solid #fff;'>
            <div style='float:left;'>
                <input type='text' name='listingId' style='width:200px; border:1px solid #ccc; padding:5px; font-size: 16px;' placeholder="Type listing id here" value='<?php echo $listingId; ?>' />
            </div>
            <div style='float:left; margin-left: 10px; padding-top:1px;'>
                <select name='listingType' style='font-size:15px; padding:4px;'>
                    <option value='institute' <?php if($listingType == 'institute') echo "selected='selected'"; ?>>Institute</option>
                    <option value='course' <?php if($listingType == 'course') echo "selected='selected'"; ?>>Course</option>
                </select>
            </div>
            <div style='float:left; margin-left: 10px; padding-top:2px;'>
                <img src='/public/images/isearch.png' style='cursor:pointer;' onclick="document.getElementById('sform').submit();" />
            </div>
            <div style='clear:both'></div>
        </div>
    </div>
    </form>
    
    <div style='margin:20px;'>
    <?php foreach($tables as $table => $data) { ?>
        <h1 <?php if(count($data) == 0) {echo "style='color:#999;'";} ?>><?php echo $table; ?></h1>
        <table cellpadding='0' cellspacing='0' style='margin-bottom: 30px;'>
            <tr>
            <?php if(count($data) == 0) { ?>
                <td width='800' style='color:#999;'>No data</td>
            <?php } else { 
            $firstRow = $data[0]; 
            $columns = array_keys($firstRow);
            foreach($columns as $column) {
               echo "<th>".$column."</th>"; 
            }
            ?>
            </tr>
            <?php foreach($data as $key => $value) { ?>
                <tr>
                    <?php foreach($value as $vk => $vv) { ?>
                        <td valign='top'><?php echo substr(strip_tags($vv),0,50); ?></td>
                    <?php } ?>
                </tr>
            <?php } } ?>
        </table>    
    <?php } ?>
    
    </div>
</body>
</html>