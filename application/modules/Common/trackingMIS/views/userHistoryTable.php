<link href="//<?php echo CSSURL; ?>/public/css/trackingMIS/<?php echo getCSSWithVersion("bootstrap",'nationalMIS'); ?>" rel="stylesheet">
<link href="//<?php echo CSSURL; ?>/public/css/trackingMIS/<?php echo getCSSWithVersion("customMIS",'nationalMIS'); ?>" rel="stylesheet">
<link href="//<?php echo CSSURL; ?>/public/css/trackingMIS/datatables/tools/css/dataTables.tableTools.css" rel="stylesheet">
<script src="//<?php echo JSURL; ?>/public/js/trackingMIS/<?php echo getJSWithVersion("jquery.min","nationalMIS"); ?>"></script>
<script type="text/javascript" src="//<?php echo JSURL; ?>/public/js/trackingMIS/<?php echo getJSWithVersion("jquery.dataTables","nationalMIS"); ?>"></script>
<script type="text/javascript" src="//<?php echo JSURL; ?>/public/js/trackingMIS/<?php echo getJSWithVersion("dataTables.tableTools","nationalMIS"); ?>"></script>
<body id="userHistory">
<span style='font-size:20px'><b>Data shown from 1st Jan, 2018. Only First 100 Records.</b><span><br>

<div class="item form-group bad">
	<label class="control-label col-md-3 col-sm-3 col-xs-12" for="email">Email <span class="required">*</span></label>
	<div class="col-md-6 col-sm-6 col-xs-12">
		<input type="email" id="email" name="email" required="required" class="form-control col-md-7 col-xs-12">
	</div>
</div>


<div class="">
	<div class="col-md-6 col-md-offset-3">
		<button id="send" onclick="showUserHistory()" class="btn btn-success">Submit</button>
	</div>
</div>

<div id= "dataTableDiv" class="col-md-12 col-sm-12 col-xs-12 <?php echo $class; ?>">
    <div class="x_panel">
        <div class="x_content">
            <div class="clear">
                <table id="example" class="table table-striped responsive-utilities jambo_table dataTable" style="font-size: 15px !important">
                    <thead>
                        <tr class="headings" role="row">
                            <?php
                                foreach ($fieldList as $key => $fieldDetails) { ?>
                                    <th class="sorting breather_left_important" role="columnheader" tabindex="0" aria-controls="example" rowspan="1" colspan="1" style="word-break:break-word;width: <?php echo $fieldDetails["width"];?> !important; "><?php echo $fieldDetails['field'];?>
                                    </th>
                            <?php }
                            ?>
                        </tr>

                    </thead>
                    <tbody id="data-table">
                    	<?php
                    	 	if(isset($userHistory) && count($userHistory) >0){
                    		$i = 1;
                    		foreach ($userHistory as $visitorId => $sessions) { 
                    			foreach ($sessions as $sessionId => $pageviews) {
                    				foreach ($pageviews as $index => $pageDetails) { ?>
	                    				<tr class="pointer odd">
	                    					<?php foreach ($fieldList as $key => $fieldDetails) {
	                    						$prepareDataForCSV[$i][] = $pageDetails[$fieldDetails["field"]];
	                    					?>
	                    						<td class=" " style="word-break:break-word;width: <?php echo $fieldDetails["width"];?> !important;padding:5px !important; "><?php echo $pageDetails[$fieldDetails["field"]];?></td>	
	                    					<?php }  ?>
	                    				</tr>
	                    			<?php } $i++;  ?>
	                    	<?php	}
	                    	}
                    	?>
                    	<?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<div class="loader_small_overlay">
        <img src="<?php echo SHIKSHA_HOME; ?>/public/images/trackingMIS/mis-loading-small.gif">
    </div>
</body>

<script type="text/javascript">
	$(".loader_small_overlay").hide();
	$("#dataTableDiv").hide();
	var  dataTableObj;
	var dataForCSV ="";
	function showUserHistory(){
		var smallLoaderForTabbedBarGraph = $('.loader_small_overlay');
    	smallLoaderForTabbedBarGraph.css({"background": "rgba(0, 0, 0, 0.18)"}).removeClass('cropper-hidden').show();
		var emailId = $("#email").val();
		if(emailId == ""){
			alert("Please enter email id");
			return;	
		}

		var pivot = "email";
		var url = "/trackingMIS/Dashboard/getUserHistory/"+pivot+"/"+emailId;
		

		$.ajax({
	        type : "POST",
	        url :url,
	        success:function (result) {
	        	result = JSON.parse(result);
	        	//console.log(result["dataForCSV"]);
	        	if(result['error'] == 2){
	        		$("#dataTableDiv").show();
	        		$("#dataTableDiv").show();
	        		$("#data-table").html(result["html"]);

	        		populateDataTable();
	        		dataForCSV = JSON.parse(result["dataForCSV"]);
	        	}else{
	        		$("#dataTableDiv").hide();
	        		$("#data-table").html("");

	        		if(result['error'] == 1){
	        			alert(result['message']);
	        		}

	        	}
	        	$(".loader_small_overlay").hide();
	        }
	    });
	}

	//populateDataTable();
	function populateDataTable(){
		if(dataTableObj != undefined){
			dataTableObj.fnDestroy()
		}
		var settings = {
	        "oLanguage": {
	            "sSearch": "Search all columns:",
	            "infoThousands": ""
	        },
	        
	        'iDisplayLength': 10,
	        "sPaginationType": "full_numbers",
	        "dom": 'T<"clear">lfrtip',
	    };

	    if( typeof destroy != 'undefined' ){
	        settings['destroy'] = destroy;
	    }

	    dataTableObj = $('#example').dataTable(settings);
	    $('.paging_full_numbers').css({'cssText': 'width: auto !important'});
	}


    function createRows()
	{
		createCSVWithHeader();
	}

	function createCSVWithHeader(){
	    var newDataForCSV = [];
	    var x = dataForCSV[0].length;    
	    var newDataForCSV =new Array(dataForCSV.length-1);
	    for(var i=1;i<dataForCSV.length;i++){ 
	        newDataForCSV[i-1] = new Array(x);
	        for(var j=0;j<x;j++){
	            newDataForCSV[i-1][j] = dataForCSV[i][j];    
	        }
	    }


	    for(var i=0;i<newDataForCSV.length;i++){
	        newDataForCSV[i][x] = i;
	    }

	    var tempArray = new Array;
	    var tempArray1 = new Array;
	    var x = newDataForCSV[0].length;    
	    for(var i=0;i<newDataForCSV.length;i++){ 
	        tempArray[newDataForCSV[i][x-1]] = newDataForCSV[i];
	        tempArray1[i]={"key":i,"value":newDataForCSV[i][x-3]};
	    }
	    var sorted = tempArray1.sort(function(a, b) {
	      return b.value - a.value;
	    });

	    var tempArray2 = new Array;
	    for(var i=0;i<sorted.length;i++){ 
	      tempArray2[i]= tempArray[sorted[i]["key"]];
	    }

	    var rows =new Array(dataForCSV.length);
	    for(var i=0;i<1;i++)
	    { 
	        rows[i] = new Array(dataForCSV[0].length);
	        for(var j=0;j<dataForCSV[0].length;j++)
	        {
	            rows[i][j] = dataForCSV[i][j];    
	        }   
	    }

	    var len = tempArray2[0].length-1;
	    for(var i=0;i<tempArray2.length;i++){ 
	        rows[i+1] = new Array(len);
	        for(var j=0;j<len;j++){
	            rows[i+1][j] = tempArray2[i][j];    
	        }
	    }

	    var dtCSV = Date();
	    var mCSV = new Date().getMonth();
	    var yCSV =new Date().getFullYear();
	    var dCSV =new Date().getDate();
	    if(mCSV <=9)
	    {
	        mCSV = '0'+mCSV.toString();
	    }
	    if(dCSV <= 9)
	    {
	        dCSV = '0'+dCSV.toString();
	    }
	    var filename = 'USER_PAGEVIEWS_'+yCSV.toString()+'-'+mCSV.toString()+'-'+dCSV.toString()+'.csv';
	    for(var i=1;i<rows.length;i++)
	    { 
	        rows[i][x-2] =   number_format(rows[i][x-2]);
	    }
	    exportToCsv(filename, rows);
	}

	function exportToCsv(filename, rows) 
	{
		console.log(rows);
	    var processRow = function (row) {
	        var finalVal = '';
	        for (var j = 0; j < row.length; j++) {
	            var innerValue = row[j] === null ? '' : row[j].toString();
	            if (row[j] instanceof Date) {
	                innerValue = row[j].toLocaleString();
	            };
	            var result = innerValue.replace(/"/g, '""');
	            if (result.search(/("|,|\n)/g) >= 0)
	                result = '"' + result + '"';
	            if (j > 0)
	                finalVal += ',';
	            finalVal += result;
	        }
	        return finalVal + '\n';
		};

		    var csvFile = '';
		    for (var i = 0; i < rows.length; i++) {
		        csvFile += processRow(rows[i]);
		    }

		    var blob = new Blob([csvFile], { type: 'text/csv;charset=utf-8;' });
		    if (navigator.msSaveBlob) { // IE 10+
		        navigator.msSaveBlob(blob, filename);
		    } else {
		        var link = document.createElement("a");
		        if (link.download !== undefined) { // feature detection
		            // Browsers that support HTML5 download attribute
		            var url = URL.createObjectURL(blob);
		            link.setAttribute("href", url);
		            link.setAttribute("download", filename);
		            link.style.visibility = 'hidden';
		            document.body.appendChild(link);
		            link.click();
		            document.body.removeChild(link);
		        }
		    }
	}

	function number_format(x){
	    //return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
	    return x
	}
</script>
