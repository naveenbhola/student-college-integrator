<?php 
global $ENT_EE_MODULES;
global $ENT_EE_SERVERS;
global $ENT_EE_MODULES_COLORS;
global $ENT_EE_MODULES;
global $clientSideModuleNames;
global $BOT_STATUSES;
?>
<html>
<head>
<title>Shiksha Health Monitoring Dashboard</title>
<link href="//<?php echo CSSURL; ?>/public/css/<?php echo getCSSWithVersion("abroad_cms"); ?>" type="text/css" rel="stylesheet" />
<link href="//<?php echo CSSURL; ?>/public/css/<?php echo getCSSWithVersion("common_new"); ?>" type="text/css" rel="stylesheet" />
<link href="//<?php echo CSSURL; ?>/public/css/<?php echo getCSSWithVersion("appmonitorbuttons"); ?>" type="text/css" rel="stylesheet" />
<link href="//<?php echo CSSURL; ?>/public/css/<?php echo getCSSWithVersion("internalapps"); ?>" type="text/css" rel="stylesheet" />
<link href='https://fonts.googleapis.com/css?family=Open+Sans' rel='stylesheet' type='text/css'>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
<link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.3/themes/smoothness/jquery-ui.css" />
<script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.3/jquery-ui.min.js"></script>
<script>
	$(document).ready(function() {
		$(document).click(function() {
			$('#mboverlay').hide();
			$('#moverlay').hide('slide');
		});
		
		$('#hburger').click(function(event) {
			 $('#mboverlay').show();
			 $('#moverlay').show('slide');
			 event.stopPropagation();
		});
		
		$('#moverlay').click(function(event) {
			event.stopPropagation();
		});
	})
</script>
</head>
<body style='background-color:#FFF;'>

<div class="voverlay" id="voverlay"></div>	
<div id="vdialog" title="Detailed URLs" style='margin:0 auto; width:900px; display:none;'>
	<div style='position:relative;'>
		<div style='position:absolute; width:900px; background:#fff; box-shadow: 0 0 15px #999; height:550px; z-index:189; overflow: auto; padding:10px 20px' id='vdialog_inner'>
			<div style='float:right;'><a href='#' onclick="$('#vdialog').hide(); $('#voverlay').hide(); $('body').removeClass('noscroll'); return false"><img src='/public/images/appmonitor/close.png' width='24' /></a></div>
			<div class="clearFix"></div>
			<div id='vdialog_content'></div>
		</div>
	</div>
</div>	


<div class="moverlay" id="moverlay">
	<?php $this->load->view("AppMonitor/common/parentTabs")?>
</div>
<div class="mboverlay" id="mboverlay"></div>		
<?php $this->load->view('InternalAppsCommon/headbar'); ?>	
<!--div style='box-shadow: 0px 0px 4px 1px #888; padding-top:0px; margin-bottom:0px; z-index:109; position:relative; height:45px; background:#fff'-->
<div style='box-shadow: 0px 5px 4px -4px #888; padding-top:0px; margin-bottom:0px; z-index:109; position:relative; height:40px; background:#fff'>
<!--div style='padding-top:0px; margin-bottom:0px; z-index:109; position:relative; height:45px; background:#000; color:#444;'-->
    <div style='margin:0 auto; width:1200px; border:0px solid red;'>
		
		<div style='float:left; margin:3px 5px 0 8px;'>
			<a href='/AppMonitor/Dashboard' id='hlogo'>
				<img src='/public/images/appmonitor/Logo.png' height='32' />
			</a>
		</div>
		
		<div style='float:left; margin:15px 10px 0 0px;'>
			<a href='#' id='hburger'>
				<img src='/public/images/appmonitor/hp3.png' style='opacity:0.7' />
			</a>
		</div>
		
		<div style='float:left; margin-top:15px; margin-left:5px;'>
			<h1 style='font-size:18px; color:#555;'>
				<?php
				switch($dashboardType) {
					case ENT_DASHBOARD_TYPE_DB_ERROR:
						echo "DB Errors";
						break;
					case ENT_DASHBOARD_TYPE_CRON_ERROR:
						echo "Cron Errors";
						break;
					case ENT_DASHBOARD_TYPE_SLOWQUERY:
						echo "Slow Queries";
						break;
					case ENT_DASHBOARD_TYPE_CACHE:
						echo "Cache Heavy Pages";
						break;
					case ENT_DASHBOARD_TYPE_EXCEPTION:
						echo "Exceptions";
						break;
					case ENT_DASHBOARD_TYPE_MEMORY:
						echo "High Memory Pages";
						break;
					case ENT_DASHBOARD_TYPE_SLOWPAGES:
						echo "Slow Pages";
						break;
					case ENT_DASHBOARD_TYPE_CLIENT_SIDE:
						echo "Client Side Performace Params";
						break;
					case ENT_DASHBOARD_TYPE_GOOGLEWEBLIGHT:
						echo "Google WebLight Sessions";
						break;
					case ENT_DASHBOARD_TYPE_SPEARALERTS:
						echo "SPEAR Alerts";
						break;
					case ENT_DASHBOARD_TYPE_JS_ERROR:
						echo "Javascript Errors";
						break;
					case ENT_DASHBOARD_TYPE_PAGESCORE:
						echo "Shiksha Page's Score";
						break;
					case ENT_DASHBOARD_TYPE_COMPSCORE:
						echo 'Competition Analysis';
						break;
					case ENT_DASHBOARD_TYPE_COMPSCORELH:
						echo 'Competition Analysis (LH)';
						break;
                    case ENT_DASHBOARD_TYPE_HTTPSTATUSCODES:
                        echo "HTTP Status Codes";
                        break;
                    case ENT_DASHBOARD_TYPE_TRAFFICREPORT:
                        echo "Traffic Report";
                        break;
					case ENT_DASHBOARD_TYPE_JSB9_REPORT:
                        echo "JSB9 Tracking";
                        break;    
                    case ENT_DASHBOARD_TYPE_BOTREPORT:
                        echo "Bot Report";
                        break;        
                    case ENT_DASHBOARD_TYPE_SEARCH_TRACKING:
                    	echo "Search Trends";
                    	break;
                    case ENT_DASHBOARD_CICD:
                    	echo "CI / CD";
                    	break;
					default:
						echo "Dashboard";
				}
				//echo $heading;
				?>
			</h1>
		</div>
		<?php
				if($dashboardType == ENT_DASHBOARD_MAIN) {
					echo "<div style='padding:10px 0;'>";
				?>
						<a href="javascript:void(0);" id='btn_yesterday' class='ybutton ysmall ywhite' onclick="switchChart('yesterday');" style="float:right; margin-right:25px;">Yesterday</a>
						<a href="javascript:void(0);" id='btn_today' class='ybutton ysmall ygreen' onclick="switchChart('today');" style="float:right; border-right:none;">Today</a>
				<?php
					echo "</div>";
				}
			?>
		
		<?php if(!in_array($dashboardType,array(ENT_DASHBOARD_TYPE_GOOGLEWEBLIGHT, ENT_DASHBOARD_TYPE_API_REPORT, ENT_DASHBOARD_MAIN, ENT_DASHBOARD_TYPE_HTTPSTATUSCODES, ENT_DASHBOARD_TYPE_TRAFFICREPORT,ENT_DASHBOARD_TYPE_SEARCH_TRACKING,ENT_DASHBOARD_TYPE_COMPSCORE,ENT_DASHBOARD_TYPE_COMPSCORELH)))  { ?>
			
			<div class='mbutton2' style="float:right; padding:6px 15px; font-size:14px; margin-top:7px; margin-right:10px;" onmouseover="$('#mbox').show()" onmouseout="$('#mbox').hide()">
				
				<div class='mselected'>
					<?php 
					if($dashboardType == ENT_DASHBOARD_TYPE_SLOWQUERY){

						?>
						<a href='javascript:void(0);'><?php echo $ENT_EE_SERVERS[$serverName]? $ENT_EE_SERVERS[$serverName] : 'Shiksha' ?></a>
						<?php
					} 
					else if($dashboardType == ENT_DASHBOARD_TYPE_CLIENT_SIDE) {
						?>
						<a href='javascript:void(0);'><?php echo $clientSideModuleNames[$selectedPage]? $clientSideModuleNames[$selectedPage] : 'Home Page' ?></a>
						<?php
					}
                    else if($dashboardType == ENT_DASHBOARD_TYPE_BOTREPORT) {
						?>
						<a href='javascript:void(0);'><?php echo $BOT_STATUSES[$botStatus]? $BOT_STATUSES[$botStatus] : 'All' ?></a>
						<?php
					}
					else {
						?>
						<a href='javascript:void(0);'><?php echo $ENT_EE_MODULES[$selectedModule]? $ENT_EE_MODULES[$selectedModule] : 'Shiksha' ?></a>
						<?php
					}
					?>
				</div>
				
				<div class='mbox_outer' id='mbox' style='display:none;'>
					<div class='mbox'>
						<ul class='moptions'>
						<?php
							foreach($modulesLinks as $module=>$link){
						?>
							<li><a href='<?php echo $link;?>'><?php echo $module;?></a></li>
						<?php
							}
						?>
						</ul>
					</div>
				</div>
				
			</div>
			<div class="clearFix"></div>
		<?php
		}
		?>
		</div>
		</div>

		<?php if(!in_array($dashboardType,array(ENT_DASHBOARD_TYPE_GOOGLEWEBLIGHT, ENT_DASHBOARD_TYPE_API_REPORT, ENT_DASHBOARD_TYPE_PAGESCORE, ENT_DASHBOARD_TYPE_JSB9_REPORT, ENT_DASHBOARD_MAIN, ENT_DASHBOARD_TYPE_COMPSCORE, ENT_DASHBOARD_TYPE_COMPSCORELH)))  { ?>
		<div style="width:100%; padding:5px 0px 0px 0px; margin-top:0px; background:#ebf2f7; position:relative; z-index:15;">
			<div class="clear-width" style="border-bottom:1px solid #bbccd8;">
			<div style='width:1180px; margin:0 auto;'>	
			<?php
			if($dashboardType == ENT_DASHBOARD_MAIN) {
				echo "<div style='padding:10px 0;'>";
				foreach($ENT_EE_MODULES_COLORS as $module=>$color) {
					if($module == 'all' || !$ENT_EE_MODULES[$module]) { continue; }
			?>
					<div style="height:11px;width:11px;margin-top:5px;background:<?php echo $color;?>;display:inline-block;"></div> <span style='color:#555;'><?php echo $ENT_EE_MODULES[$module];?></span> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
			<?php
				}
			?>
					<a href="javascript:void(0);" id='btn_yesterday' class='ybutton ysmall ywhite' onclick="switchChart('yesterday');" style="float:right; margin-right:25px;">Yesterday</a>
					<a href="javascript:void(0);" id='btn_today' class='ybutton ysmall ygreen' onclick="switchChart('today');" style="float:right; border-right:none;">Today</a>
			<?php
				echo "</div>";
			}
			else {
				foreach($reportLinks as $tab=>$data){
			?>
				<a href="<?php echo $data[1];?>"><div class="flLT tab2 <?php if($reportType == $tab) echo 'activetab2';?>"><?php echo $data[0];?></div></a>
			<?php
				}
			}
			?>
			</div>
			</div>
			<div class="clearFix"></div>
		</div>	
		</div>
		<?php } ?>
		

<?php if($dashboardType != ENT_DASHBOARD_MAIN){ ?>


<?php } ?>
