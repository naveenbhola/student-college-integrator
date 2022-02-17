<div class="mar_full_10p">
    <div style="margin-left:1px">
        
        <div id="dataLoaderPanel" style="position:absolute;display:none">
    <img src="/public/images/loader.gif"/>
    </div>
        
        <?php if($isMMM) { ?>
            <div class="OrgangeFont fontSize_18p bld" style="margin-top:15px;"><strong>Search Client Profile</strong></div>
        <?php } else { ?>    
            <div class="bld fontSize_14p OrgangeFont" style="padding-left:10px;">Search Client Profile</div>
            <div class="grayLine"></div>
        <?php } ?>    
        
        <div class="lineSpace_10">&nbsp;</div>

        <form id="formForQuoteUsers" action="/enterprise/LDBSearchTabs/showClientDetails" method="POST" onsubmit="validateForm(this)" >
            
           
            <div class="row">
                <div style="display: inline; float:left; width:100%">
                    <div class="r1_1 bld">CLIENT ID:&nbsp;&nbsp;</div>
                    <div class="r2_2">
                        <input name="clientId" id="clientId" type="text" size="30" maxlength="25" minlength="3" caption="Client Id" />
                    </div>
                    <div class="clear_L"></div>
                    <div class="row errorPlace" style="margin-top:2px;">
                        <div class="r1_1">&nbsp;</div>
                        <div class="r2_2 errorMsg" id= "clientId_error"></div>
                    </div>
                </div>
            </div>
            <div class="lineSpace_10">&nbsp;</div>
            <div class="lineSpace_10">&nbsp;</div>
            <div class="lineSpace_10">&nbsp;</div>
            <div class="lineSpace_10">&nbsp;</div>
            <div class="lineSpace_10">&nbsp;</div>
            <div class="r2_2">
                <button class="btn-submit19" onclick="generateReport()" type="button" value="" style="width:100px">
                    <div class="btn-submit19"><p style="padding: 15px 8px 15px 5px;color:#FFFFFF; font-size:12px" class="btn-submit20">Search</p></div>
                </button>
            </div>
            <div class="clear_L"></div>
            <div class="lineSpace_10">&nbsp;</div>
            <div class="clearFix"></div>
        </form>
       
         <div id="userresults"></div>
        
    </div>
    <div class="clearFix"></div>
    <div class="lineSpace_35">&nbsp;</div>
</div>
<div class="clearFix"></div>
    <br />
    <br />
</body>
</html>
<?php $this->load->view('common/footerNew'); ?>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
<script type="text/javascript">
   
    function generateReport()
    {
        $j('#userresults').html('<div style="width:100%" align="center"><img src="/public/images/ajax-loader.gif" /></div><div style="width:100%;font-size:14px;" align="center">loading...</div>');
    
        var formStringData = $j("#formForQuoteUsers").serialize();
                            
        $j.post("/enterprise/LDBSearchTabs/showClientDetails", formStringData, function(response) {
                $j('#userresults').html(response);
        });        
    }
    
    
</script>

