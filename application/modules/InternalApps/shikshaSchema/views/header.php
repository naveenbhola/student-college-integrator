<html>
    <head>
		<link href='https://fonts.googleapis.com/css?family=Open+Sans' rel='stylesheet' type='text/css'>
        <style type='text/css'>
            body {font-size:12px; font-family: Tahoma, Geneva, sans-serif; margin:0; padding:0;}
            table {border:0px solid #BA3500;}
            th {border-right:0px solid #ccc; border-bottom:1px solid #ccc; padding:5px; background: #dde9f1; color:#50739f; font-size: 17px; font-family: "Trebuchet MS", Arial, Helvetica, sans-serif}
            td {border-right:1px solid #ccc; border-bottom:1px solid #ccc; padding:5px; font-size: 13px;}
            .tableBox {margin-bottom: 30px; box-shadow:0 0 5px #666; -moz-box-shadow: 0 0 5px #666; -webkit-box-shadow: 0 0 5px #666;}
            .clr {clear: both; margin-bottom: 20px;}
	    h3 {font-size:18px; border-bottom: 1px solid #eee; padding-bottom: 10px; color:#333; margin-top:20px; margin-bottom: 10px; padding-left: 5px;}
            h2 {font-size:20px; border-bottom: 1px solid #eee; padding-bottom: 10px; color:#222; margin-top:20px; margin-bottom: 10px; padding-left: 5px;}
            h1 {font-size:32px;}
            ul {margin-left:20px; padding-left:0; font-size:14px; font-family: Tahoma, Geneva, sans-serif;}
            li {margin-bottom: 15px; list-style-type: bullet; margin-left:20px;}
	    .desc {font-size:15px; color:#555; line-height: 130%;}
            .subth {font-size:11px; font-weight:normal; color:#276a94;}
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
            a.mainmenu:link,a.mainmenu:active,a.mainmenu:visited {
                float:left;
                display:block;
                padding:11px 20px 12px 20px;;
                font-size:13px;
                text-decoration:none;
                color:#555;
            }
            a.mainmenu:hover,a#active {
                background: #d3dde9;
                color:#111;
            }
	    a:link,a:active,a:visited {color:#245dc1; text-decoration: none; font-size: 15px;}
            a:hover {text-decoration: underline;}	
	    a.nlink:link,a.nlink:active,a.nlink:visited {color:#ccc; text-decoration: none; font-size: 18px;}
            a.nlink:hover {text-decoration: none; color:#888;}
			a.doclink {color:#999; text-decoration:none; font-size:14px; }
	    a.doclink:hover {text-decoration:underline;}
        </style>
        <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>
        <script>
            function showTable(table,divId) {
                $.post('/shikshaSchema/ShikshaSchema/table/'+table,{},function(data) {
                    var overlay = $('<div id="overlay"> </div>');
                    overlay.appendTo(document.body);
                    var html = "<div class='overlayTable' id='newTable_"+table+"_"+divId+"' style='position:relative;'><div style='position:absolute; background:#FFF; padding:10px; z-index:11; box-shadow:5px 5px 5px #999; -moz-box-shadow: 5px 5px 5px #999; -webkit-box-shadow: 5px 5px 5px #999;'>"+data+"</div></div>";
                    $('#'+divId).after(html);
                    $("#newTable_"+table+"_"+divId).click(function(event) {
                        event.stopPropagation();
                    });
                });
            }
            function hideTable(table,divId) {
                $("#newTable_"+table+"_"+divId).remove();
                $("#overlay").remove();
            }
            function hideAllTables() {
                $('.overlayTable').each(function() {
                   $(this).remove(); 
                });
                $("#overlay").remove();
            }
            $(document).ready(function() {
                $(this).click(function() {
                    hideAllTables();
                })
            });
        </script>
        <title>Shiksha DB Schema</title>
    </head>
    <body>
		<?php $this->load->view('InternalAppsCommon/headbar', array('app' => 'Documentation')); ?>	
<!--div style='width:900px; margin:0 auto; height:75px; margin-top:15px;'>
    <div style='float:left'>
        <img src='/public/logo.png' />
    </div>
    <div style='float:right; font-size:40px; font-weight:bold; color:#eee; margin-top:10px; font-family: "Trebuchet MS", Arial, Helvetica, sans-serif;'>
        
    </div>
    <div class='clr'></div>
</div>
    
<div style='background:#e7e7e7; border-bottom: 2px solid #777;'>
    <div style='width:900px; margin:0 auto; height:40px;'>
        <?php
        $mainmenuLinks = array(
            'user' => 'USER',
            'location' => 'LOCATION',
            'ldb' => 'LEAD DATABASE',
            'searchAgents' => 'SEARCH AGENTS',
            'ldbCourse' => 'LDB COURSE',
            'response' => 'RESPONSE',
            'porting' => 'PORTING'
        );
        foreach($mainmenuLinks as $menuKey => $menuLink) { ?>
            <a href='/shikshaSchema/ShikshaSchema/index/<?php echo $menuKey; ?>' class='mainmenu' <?php if($tableGroup == $menuKey) echo "id='active'"; ?>><?php echo $menuLink; ?></a>
        <?php } ?>
        <div class='clr'></div>
    </div>
</div-->

<div style='background:#f8f8f8; border-bottom:1px solid #eee; height:30px;'><div style='margin:0px auto; width:840px; text-align:right; padding-top:8px;'><a href='http://snapdragon.infoedge.com/public/documentation/' class='doclink' target='_blank'>View PHPDoc Documentation</a></div></div>
            </div>

<div style='background:#fff;'>
    <div style='background:#fff; width:840px; margin:0 auto; padding:30px;'>
