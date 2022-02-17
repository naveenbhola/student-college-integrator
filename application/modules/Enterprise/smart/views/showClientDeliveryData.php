<title> Client Delivery Report </title>
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>

<?php 
$headerComponents = array(
        'css'   =>  array('headerCms','raised_all','mainStyle','footer','cal_style','smart'),
        'js'    =>  array('common','enterprise','home','CalendarPopup','discussion','events','listing','blog'),
        'displayname'=> (isset($validateuser[0]['displayname'])?$validateuser[0]['displayname']:""),
        'tabName'   =>  '',
        'taburl' => site_url('smart/SmartMis'),
        'metaKeywords'  =>''
        );
$this->load->view('enterprise/headerCMS', $headerComponents);
$this->load->view('enterprise/cmsTabs');
?>

<style type="text/css">
	.date-range{font-size:12px; padding-left:2px; float:left}
	.date-range label{width:auto !important; text-align:left !important; font-size:12px !important; }
	.date-range input{width:80px; font:normal 11px Arial, Helvetica, sans-serif; color:#84888b}
	
	.icon-cal, .arrow-d, .drop-icon, .info-icon, .prev-item, .next-item, .prev-item-active, .next-item-active, .popup-layer2 .pointer, .popup-layer2 .pointer2{background:url(/public/images/smart-sprite.png) 0 0 no-repeat; position:relative; display:inline-block;}
	.info-icon{background-position:-50px 0; width:16px; height:16px; cursor:pointer}
	.icon-cal{background-position:0 0 ; width:15px; height:15px; vertical-align:middle; cursor:pointer}
</style>

<?php
	$this->load->view('common/calendardiv');
?>

<form action="/smart/SmartMis/getDataForReport/" id='sums_form' method='post'>
	<div style="margin-top:10px;">
		<h1 style="margin:18px; font-size:18px !important;"> Client Delivery Report </h1>
	</div>
	<hr style="margin-left:15px; margin-right:15;"/>
	
	<div id="clientReport" style="margin:18px; font-size:13px;">
		
		<div style="margin:4px 0 4px 45px;"">
			<span>Select Category: </span>
			<span style="margin-left:44px;">
				<select id="categoriesHolder" name="categoriesHolder" style="width:210px;">
					<?php
						echo "<option value=''>Choose a Category</option>";
						foreach($catSubcatList as $key=>$catData){
							echo "<option id=catId_".$key." value=".$key.">".$catData['name']."</option>";
						}
					?>
				</select>
			</span>
		</div>
		
		<div style="margin:4px 0 4px 45px;">
			<span >Select Sub Category: </span>
			<span style="margin-left:17px;">
				<select id="subCategoriesHolder" name="subCategoriesHolder" style="width:210px;">
					<option value=''>Choose a Sub Category</option>
				</select>
			</span>
		</div>
		
		<div style="margin:4px 0 4px 45px;">
			<label>Enter Institute Id(s) : </label>
			<input type="text" id="instituteId" name="instituteId" style="width:210px;margin-left:22px;" value="" placeholder="Enter Institute Id(s)"/>
		</div>
		
		<div style="margin:4px 0 4px 45px;">
			<label>From : </label>
			<input type="text" id="timerangeFrom" name="timerangeFrom" style="width:190px;margin-left:104px;" value="" placeholder="YYYY-MM-DD"/>
			<img id="timerangeFromImg" onclick="timerange('From');" src="/public/images/calendar.jpg" width="15" height="15" alt="calendar" class="calendar-image"/>
		</div>
		
		<div style="margin:4px 0 4px 45px;">
			<label style="width:40px;">To : </label>
			<input type="text" id="timerangeTill" name="timerangeTill" style="margin-left:120px;width: 190px;" value="" placeholder="YYYY-MM-DD"/>
			<img id="timerangeTillImg" onclick="timerange('Till');" src="/public/images/calendar.jpg" width="15" height="15" alt="calendar" class="calendar-image"/>
		</div>
		
		<div style="margin-left:45px; margin-top:15px;">
			<a href="#" onclick="saveClientCriteria();" class="orange-button" style=" color: #ffffff;">Download Report</a>
		</div>
	</div>
</form>
<?php $this->load->view('enterprise/footer'); ?>

<script>
	
	var catSubcatList = <?php echo json_encode($catSubcatList); ?>;
	
	document.getElementById('categoriesHolder').onchange = function(){
        $j("#subCategoriesHolder").empty();
        $j('#subCategoriesHolder').html("<option value=''>Choose a Sub Category</option>");
        
		var cat = document.getElementById('categoriesHolder').value;
		returnHTML = "<option value=''>Choose a Sub Category</option>";
		if (cat != 0) {
			for (var subcat in catSubcatList[cat]['subcategories']) {
				returnHTML += '<option id="subCatId_' + subcat + '" value="' + subcat + '"/>';
				returnHTML += catSubcatList[cat]['subcategories'][subcat]['catName']+'</option>';
			}
		}
		
		$("subCategoriesHolder").innerHTML = returnHTML;
        
    };
	
	function timerange(timeFlag) {
		var calMain = new CalendarPopup('calendardiv');
		calMain.select($('timerange'+timeFlag),'timerange'+timeFlag+'Img','yyyy-mm-dd');
		return false;
    }
	
	Date.prototype.yyyymmdd = function() {         
         
        var yyyy = this.getFullYear().toString();                                    
        var mm = (this.getMonth()+1).toString(); // getMonth() is zero-based         
        var dd  = this.getDate().toString();             
            
        return yyyy + '-' + (mm[1]?mm:"0"+mm[0]) + '-' + (dd[1]?dd:"0"+dd[0]);
    };
	
	function saveClientCriteria() {
		if (validateFields() && validatetimeRange()) {
			$("sums_form").submit();
			return;
		}
	}
	
	function validatetimeRange() {
        var startDate = $('timerangeFrom').value;
        var endDate = $('timerangeTill').value;
        var fromdate = startDate.replace( /(\d{4})-(\d{2})-(\d{2})/, "$2/$3/$1");
        var todate = endDate.replace( /(\d{4})-(\d{2})-(\d{2})/, "$2/$3/$1");
        
		if (fromdate !== '' && todate !== '') {
            if (Date.parse(todate) >= Date.parse(fromdate)) {
				timeInterval = getTimeInterval(fromdate,todate);
			}
			else {
				alert("Please select a 'TO' date greater than the 'FROM' date.");
				return false;
			}
        }
        else if (fromdate !== '' && todate == '') {
            alert("Please select a 'TO' date.");
			return false;
        }
        else if (fromdate == '' && todate !== '') {
            alert("Please select a 'FROM' date.");
			return false;
        }
		else {
            alert("Please select a 'TO' date and a 'FROM' date.");
			return false;
        }
		
		if (timeInterval > 366) {
            alert("Date range must not exceed 1 year.");
            return false;
        }
		
		return true;
    }
	
    function getTimeInterval(timerangeFrom,timerangeTill) {
		
        var oldDate = new Date(timerangeFrom);
        var newDate = new Date(timerangeTill);
		
		var timeInterval = ((newDate.getTime()-oldDate.getTime())/(1000*60*60*24)) + 1;
        
        return timeInterval;
    }
	
	Date.prototype.yyyymmdd = function() {         
         
        var yyyy = this.getFullYear().toString();                                    
        var mm = (this.getMonth()+1).toString(); // getMonth() is zero-based         
        var dd  = this.getDate().toString();             
            
        return yyyy + '-' + (mm[1]?mm:"0"+mm[0]) + '-' + (dd[1]?dd:"0"+dd[0]);
    };
	
	function validateFields() {
		
		var category = document.getElementById('categoriesHolder').value;
        
		if (category == '') {
			alert('Please select a Category');
			return false;
		}
		
		var instituteId = document.getElementById('instituteId').value;
        
		if (instituteId == '') {
			alert('Please enter at least one Institute ID');
			return false;
		}
		
		return true;
	}
	
</script>
