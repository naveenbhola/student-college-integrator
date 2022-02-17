<div id='clientPage'>
        
    <div class="OrgangeFont fontSize_18p bld" style="margin-top:15px;"><strong>Search Client Profile</strong></div>   
    
    <div class="lineSpace_10">&nbsp;</div>           
    <div class="row">
        <table class="tblSearchClient">
            <tr>
                <td>
                    CLIENT ID:
                </td>
                <td>
                    <input class="drip--fileds" name="clientId" id="clientId" type="text" size="30" maxlength="25" minlength="3" caption="Client Id" />
                </td>
            </tr>
            <tr>
                <td>
                    LOGIN EMAIL ID:
                </td>
                <td>
                    <input class="drip--fileds" name="emailId" id="emailId" type="text" size="30" maxlength="50" minlength="3" caption="Login Email Id" />
                </td>
            </tr>

            <tr>
                <td>
                    
                </td>
                <td>
                    <span class="redText">Please enter only one of the above.</span>
                </td>
            </tr>
            <tr>
                <td>
                    
                </td>
                <td>
                    <button class="btn-submit19" onclick="getClientDetails();" type="button" value="">
                        <div class="btn-submit19"><p style="padding: 15px 20px 15px;color:#FFFFFF; font-size:12px" class="btn-submit20">Search</p></div>
                    </button>
                </td>
            </tr>

        </table>
    </div>
    <div class="clearFix"></div>
    <div id="userresults"></div>
</div>
