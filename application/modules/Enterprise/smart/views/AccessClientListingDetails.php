<?php
if(is_array($headerContentaarray) && count($headerContentaarray)>0) {
    foreach ($headerContentaarray as $content) {
        echo $content;
    }
}
?>

<div id="smart-content">

 <h2 align='middle'><u>Download Client's Listing Details</b></u></h2> </u>

    <div class="client-login-list">

        <ul>
            <li>
                <div id='select_div'>

                    <form action = "/smart/SmartMis/getClientListingData" method="post" id="ACLD">
                        <ul align='left'>
                            <table align='middle' >
                                <tr><h6><u>Please choose one option:</u></h6></tr>
                                <tr > 
                                    <td> Client Login E-Mail Address </td>
                                    <td><input type= 'option' align='middle' name = "loginEmail" id="loginEmail"/> </td>
                                </tr>
                                <tr></tr><tr><td> OR </td></tr><tr></tr>
                                <tr>
                                    <td> Client Id </td>
                                    <td> <input type= 'option' align='middle' name = "clientId" id="clientId"/></td>   
                                </tr>
                                <tr></tr><tr></tr>
                                <tr><td><input type= 'button' value= "Download Data" onclick="submitClientDetails()"/></td> </tr>
                            </form> 
                        </td>
                    </tr>
                    <tr> <td><p></td>   </tr>

                    </table></ul>
                    <label id="errorText" style="color: red"></label>
                    <br>
                    <h5>*Following fields will be downloaded in above data file : </h5>
                    <h6>Client Email ,Client Id ,Course Id ,Course Name ,Primary UILP Id ,Primary UILP Name ,  Primary UILP Type ,Parent UILP Id ,Parent UILP Name ,Parent UILP Type ,Current Subscription Status (Free/Paid)</h6>

                </div>
            </li>
        </ul>
</div>

<?php
if(is_array($footerContentaarray) && count($footerContentaarray)>0) {
    foreach ($footerContentaarray as $content) {
        echo $content;
    }
}
?>