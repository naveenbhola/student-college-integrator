<?php
$this->load->view('AppMonitor/common/header');
?>
<div class="blockbg">
    <form autocomplete="off" method="post" onsubmit="return false;" action="">
        <div style="width:1200px; margin:0 auto;">
            <div style="float:left; margin-top:0px;">   

                <div style="float:left; margin-left:15px; padding-top:3px;">From Date: </div>
                <div style="float:left; margin-left:10px; padding-top:1px;">
                    <input type="text" style="width:100px; cursor: text" value="" readonly="readonly" id="_fromDatePicker">
                </div>
            </div>

            <div style="float:left; margin-left:15px; padding-top:3px;">Start Time: </div>
            <div style="float:left; margin-left:10px; padding-top:1px;">
                <select id="_sh">
                    <option selected="selected" value="00">--Hours--</option>
                    <?php for($i=0;$i<=24;$i++) { $key = str_pad($i, 2, "0", STR_PAD_LEFT);?>
                    <option value="<?php echo $key;?>"><?php echo $key;?></option>
                    <?php }?>
                </select>
            </div>
            <div style="float:left;margin-left:10px; padding-top:2px;font-size:16px;"><b>:</b></div>
            <div style="float:left; margin-left:10px; padding-top:1px;">
                <select id="_sm">
                    <option selected="selected" value="00">--Minute--</option>
                    <?php for($i=0;$i<=60;$i++) { $key = str_pad($i, 2, "0", STR_PAD_LEFT);?>
                    <option value="<?php echo $key;?>"><?php echo $key;?></option>
                    <?php }?>
                </select>
            </div>
        </div>
        <div style="clear:both"></div>

        <div style="width:1200px; margin:0 auto;padding:20px;">
            <div style="float:left; margin-top:0px;">   

                <div style="float:left; margin-left:32px; padding-top:3px;">To Date: </div>
                <div style="float:left; margin-left:10px; padding-top:1px;">
                    <input type="text" style="width:100px; cursor: text" readonly="readonly" id="_toDatePicker">
                </div>
            </div>

            <div style="float:left; margin-left:15px; padding-top:3px;">Start Time: </div>
            <div style="float:left; margin-left:10px; padding-top:1px;">
                <select id="_eh">
                    <option selected="selected" value="00">--Hours--</option>
                    <?php for($i=0;$i<=24;$i++) { $key = str_pad($i, 2, "0", STR_PAD_LEFT);?>
                    <option value="<?php echo $key;?>"><?php echo $key;?></option>
                    <?php }?>
                </select>
            </div>
            <div style="float:left;margin-left:10px; padding-top:2px;font-size:16px;"><b>:</b></div>
            <div style="float:left; margin-left:10px; padding-top:1px;">
                <select id="_em">
                    <option selected="selected" value="00">--Minute--</option>
                    <?php for($i=0;$i<=60;$i++) { $key = str_pad($i, 2, "0", STR_PAD_LEFT);?>
                    <option value="<?php echo $key;?>"><?php echo $key;?></option>
                    <?php }?>
                </select>
            </div>

            <div style="float:left; margin-left:40px;">
                <button class="zbutton zsmall zgreen" id="_submitBtn" type="button">Search</button>
            </div>
            <div style="float:left; margin-left:40px;">
              <button class="zbutton zsmall zgreen" id="reset" type="button">Reset</button>
          </div>
          <div style="clear:both"></div>
      </div>
  </form>
</div>

<div style="background:#fff;display:none;z-index:9999;left:25%;top:50px;width:550px;position:fixed;overflow:auto;height:400px;" id="layer">
    <div style="background-color: lightgoldenrodyellow;width: 535px;position:fixed;">
        <h4 style=" font-size: 14px;
        margin-left: 160px;
        padding: 10px;
        top: 10px;">Cron Schedule (1000 max)</h4><span><a class="closebtn" href="javascript:void(0);" >X</a></span>
    </div>
    <div style="margin-top:35px;">
        <table id="_vSchedule" class="exceptionErrorTable" style="width:500px;margin-left:20px;text-align:center;" >
            <tr>
                <th style="text-align: center">S.No</th>
                <th style="text-align: center">Schedule Time</th>
            </tr>
        </table>   
    </div>
</div>



<div id="_searchResult">
    <span id="srch" style="font-size: 16px;position: absolute;margin-top: -30px;display:none;left:56px"></span> 
</div>

<div style='background-color:white;margin:0 auto;width:1200px; padding-bottom: 40px; border:0px solid red;'>
    <div style="padding: 5px 10px 0 10px;border: 0px solid lightgrey;border-top: 0;">
        <div style="width:100%; padding:0px; margin-top:5px;">
            <div id="tableData" style="min-height:600px;">  
                <table id="resultbl" class="exceptionErrorTable" width='1170' style="word-break:break-all;border-collapse: collapse;margin-top: 12px; border-top:1px solid #ccc; border-left:1px solid #ccc;">
                   <tr>
                    <th width="5%">S.No</th>
                    <th width="9%">Expressions</th>
                    <th width="76%">Path</th>
                    <th width="10%">Schedule Time</th>
                </tr>
            </table>
        </div>
    </div>
</div>
</div>

<div class="layer-bg" id="opecityLyr"></div>

<script src="https://cdn.rawgit.com/bunkat/later/master/later.min.js" type="text/javascript"></script>
<style type="text/css">

    #resultbl tr:nth-child(odd) {
        background-color: #EEE;
    }
    #resultbl tr td:first-child,
    tr td:last-child{
        text-align:center;
    }

    .layer-bg {
        background: rgba(0, 0, 0, 0.4) none repeat scroll 0 0;
        bottom: 0;
        display: none;
        height: 100%;
        left: 0;
        position: fixed;
        right: 0;
        top: 0;
        width: 100%;
        z-index: 999;
    }
    
    .closebtn{
        font-size: 20;
        float: right;
        margin-top: -30px;
        position: relative;
        right: 30px;
    }
    .disabled{pointer-events:none;cursor:default}

</style>

<script type="text/javascript">

    $(function(){
        $("#_fromDatePicker").datepicker();
        $("#_toDatePicker").datepicker();

        window.selectedModule = 'Shiksha';

        // disable button
        var ctext = $('#_submitBtn').text();
        $('#_submitBtn').text('Fetching Crons...').addClass('disabled');

        //request to read cron file from server
        $.ajax({
            url: "<?php echo $ajaxURL; ?>",
            type: 'POST',
            data: {file : "<?php echo $fileType; ?>"},
            success:function(result) {
                //store cron file globally
                window.cronfile = JSON.parse(result);
                //parse file into json array
                loadInitResults(window.cronfile,ctext);
            },
            error:function(xhr, status, error){
                alert("File "+status+": "+error);
            }
        });

    })

    $(function(){
        $('#_submitBtn').click(function(){
            if($('#_fromDatePicker').val().trim()==''){
                alert('Empty start date')
            }
            else{
                loadResults();
            }
        })
        $('.closebtn').click(function(){
            $("body").css({"overflow":"visible"});
            $('#layer').slideUp(function(){$('#opecityLyr').hide();});
        });

        $('#reset').click(function(){
            window.location.reload();
        })


        $('.moptions a').each(function(idx, a) {///
            //bind server-wise events here
            $(a).click(function(e){//optimise
                e.preventDefault(); // prevent hrefs
                window.selectedModule = $(a).text();
                $('.mselected a').text($(a).text());
                loadResults();
            })
        });
    });


    function loadResults(){
        //filter on modules(servers)
        var searchCron = [];
        var module = window.selectedModule;
        if(module==='Shiksha'){
            searchCron = window.cronfile;
        }
        else{
            var crons = window.cronfile;
            for (var key in crons) {
                if(crons[key]['cronServer']===module){
                    searchCron.push(crons[key]);
                }
            }
        }

        var ctext = $('#_submitBtn').text();
        //filter by time
        if($('#_fromDatePicker').val().trim()==''){//if no time selected
            $('#_submitBtn').text('Filtering...').addClass('disabled');
            loadInitResults(searchCron,ctext);
        }
        else{
            setTimeout(function(){
                $('#_submitBtn').text('Filtering...').addClass('disabled');
            },30);
            setTimeout(function(){

            //filter by time
            $('#srch').html('').slideUp();
            var startTimestamp = $('#_fromDatePicker').val().trim()+" "+$('#_sh').val().trim()+':'+$('#_sm').val().trim();
            var endTimestamp = $('#_toDatePicker').val().trim()+" "+$('#_eh').val().trim()+':'+$('#_em').val().trim();

            try{
                    //filter cronjobs
                    var cronArr = filterCron(searchCron,new Date(startTimestamp),new Date(endTimestamp));
                    
                    var htm = scheduleInHtml(cronArr['cron_results']);
                    $("#resultbl").find('tr').slice(1).remove();
                    $("#resultbl tr:first").after(htm);
                    $('#srch').html('Search <b>'+cronArr['cron_results'].length+' crons </b> found.').slideDown();
                    
                    //bind corresponding schedules
                    bindClick(cronArr['cron_details']);
                }
                catch(err){
                    alert(err.message);
                }
            },60);
            setTimeout(function(){
                $('#_submitBtn').text(ctext).removeClass('disabled');
            },90);
        }
    }

    function loadInitResults(cronArr,ctext){
        var cron_results = new Array();
        for (j=0;j<cronArr.length;j++) {
            cron_results.push([cronArr[j]['cronExp'] , cronArr[j]['cronStr']]);
        }

        //display all cron jobs initially
        $("#resultbl").find('tr').slice(1).remove();
        $("#resultbl tr:first").after(scheduleInHtml(cron_results));
        $('#_submitBtn').text(ctext).removeClass('disabled');
        
        //bind empty list
        //bindClick(null);
        $('._v').css('color','#888');
        $('._v').css('pointer-events','none');
    }


    /*
    * bind corresponding cronSched identified (& dynimically indexed) by data-str attribute to each job  
    * @args:n X y array for timestamps) where n is number of jobs found, y is number of timestamps per job
    */
    function bindClick(cronSched = null)
    {    
        $('._v').unbind();
        $('._v').click(function(){
            $("body").css({"overflow":"hidden"});
            $("#_vSchedule").find('tr').slice(1).remove();
            if ((typeof cronSched !== "undefined") && (cronSched !== null)) {
                $("#_vSchedule tr:first").after('<tr id="'+(i+1)+'"><td></td><td>'+'Loading..'+'</td></tr>');
                var str = $(this).attr("data-str");
                str = parseInt(str.split('datastr')[1]);
                str--;
                var cronSchedule = cronSched[str];
                var htm = "";
                for (i=0;i<cronSchedule.length;i++) {
                    htm+= '<tr id="'+(i+1)+'"><td><b>'+(i+1)+'</b></td><td>'+cronSchedule[i]+'</td></tr>';
                }
                $("#_vSchedule").find('tr').slice(1).remove();
                $("#_vSchedule tr:first").after(htm);
            }
            $('#opecityLyr').show();
            $('#layer').slideDown();
        });
    }


    /*
    * @args:n X 2 array containing cron expression and cron command
    * @returns:returns table in html 
    */
    function scheduleInHtml(cronArr) {
        var htm = '';
        for (i=0; i<cronArr.length; i++) {
            htm += '<tr id='+(i+1)+'><td><b>'+(i+1)+'</b></td><td>'+cronArr[i][0]+'</td><td id="crn_'+(i+1)+'"">'+cronArr[i][1]+'</td><td id="btn-'+(i+1)+'""><a href="javascript:void(0);" id="v-'+(i+1)+'" data-str="'+'datastr'+(i+1)+'" class="_v">View</a></td></tr>';
        }
        return htm;
    }

    /*
    * @args: from and to timestamp of Date type, max timestamps per job of Int type, global variable window.cronfile
    * @returns: json array containing cron_results(n X 2 array for cron expression & command) & cron_details(n X y 
    *           array for timestamps) where n is number of jobs found, y is number of timestamps per job
    */
    function filterCron(cronArr,start_date,end_date,max_schedule_perjob = 1000){
        //read from global file
        //var cronArr = window.cronfile;
        
        var cron_results = new Array();
        var cron_details = new Array();

        //use localtime for calculation
        later.date.localTime();

        for (j=0;j<cronArr.length;j++) {

            //convert cron expression to schedule
            var schedule = later.parse.cron(cronArr[j]['cronExp']);
            //get job-run timestamps between two timestamps based on schedule 
            var results = later.schedule(schedule).next(max_schedule_perjob, start_date, end_date );

            var tmpTime = new Array();
            for (i = 0; i < results.length; i++) { 
                tmpTime.push(results[i].toLocaleString());
            }

            if(results!=0){
                cron_details.push(tmpTime);
                cron_results.push([cronArr[j]['cronExp'] , cronArr[j]['cronStr']]);
            }
        }
        return {"cron_results":cron_results,"cron_details":cron_details};
    }
</script>
</body>
</html>