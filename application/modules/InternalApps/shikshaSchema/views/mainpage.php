<html>
    <head>
        <title>Shiksha.com</title>
		<link href="//<?php echo CSSURL; ?>/public/css/<?php echo getCSSWithVersion("internalapps"); ?>" type="text/css" rel="stylesheet" />
		<link href='https://fonts.googleapis.com/css?family=Open+Sans' rel='stylesheet' type='text/css'>
        <style type='text/css'>
            h3 {font-size:18px; border-bottom: 1px solid #eee; padding-bottom: 10px; color:#333; margin-top:20px; margin-bottom: 10px; padding-left: 5px;}
            a.blink:link,a.blink:active,a.blink:visited {color:#245dc1; text-decoration: none; display:block; float:left; padding:5px 20px 5px 10px; line-height: 140%;}
            a.blink:hover {text-decoration: none; background: #f1f4f9;}
            ul.doclist {margin-left:0px; padding-left:0}
            ul.doclist li {margin-bottom: 5px; list-style-type: none; margin-left:0; padding-left:0}
            .smalldesc {font-size:12px; color:#999;}
            h1 {font-size: 32px;}
			a.doclink {color:#999; text-decoration:none; font-size:14px; }
			a.doclink:hover {text-decoration:underline;}
        </style>
    </head>
    <body style="background: #fff; font-family: arial; font-size: 16px; margin:0; padding:0">
	<?php $this->load->view('InternalAppsCommon/headbar', array('app' => 'Documentation')); ?>	
	<div style='background:#f8f8f8; border-bottom:1px solid #eee; height:30px;'><div style='margin:0px auto; width:840px; text-align:right; padding-top:8px;'><a href='http://snapdragon.infoedge.com/public/documentation/' class='doclink' target='_blank'>View PHPDoc Documentation</a></div></div>
            </div>

	<div style='margin:40px auto; width:840px;border:0px solid red;'>
	    <h1>Projects</h1>
	    <ul class='doclist'>
		<li><a href="/shikshaSchema/ShikshaSchema/module/projects/shiksha2" class='blink'>Shiksha 2.0</a></li>
	    </ul>
	    <div class="clear">&nbsp;</div>
	</div>

        <div style='margin:40px auto; width:840px;border:0px solid red;'>
            <h1>Modules</h1>
            <?php
            $dir    = '/var/www/html/shiksha/application/modules';
            $moduleContainers = scandir($dir);
            usort($moduleContainers,function($a,$b) { if(strtolower($a)>strtolower($b)) {return 1;} else {return -1;} });
            
            displayModules($moduleContainers);
            
            function displayModules($moduleContainers)
            {
                $k = 1;
                foreach($moduleContainers as $moduleContainer) {
                    if($moduleContainer[0] != '.') {
                        echo "<div style='float:".($k%2 == 0 ? 'right' : 'left')."; width:350px; border:0px solid red;'>";
                        echo "<h3>".$moduleContainer."</h3>";
                        echo "<ul class='doclist'>";
                        $moduledir    = '/var/www/html/shiksha/application/modules/'.$moduleContainer;
                        $modules = scandir($moduledir);
                        foreach($modules as $module) {
                            if($module[0] != '.') {
                                echo "<li><a href='/shikshaSchema/ShikshaSchema/module/".$moduleContainer."/".$module."' class='blink'>".$module."<br /><span class='smalldesc'>Small description for this module</span></a><div style='clear:both;'></div></li>";
                            }
                        }
                        echo "</ul>";
                        echo "</div>";
                        if($k%2 == 0) {
                            echo "<div style='clear:both;'></div>";    
                        }
                        $k++;
                    }
                }
            }
            ?>
        </div>
        
    </body>
</html>

