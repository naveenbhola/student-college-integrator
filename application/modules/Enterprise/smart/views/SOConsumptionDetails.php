<?php
if(is_array($headerContentaarray) && count($headerContentaarray)>0) {
	foreach ($headerContentaarray as $content) {
		echo $content;
	}
}
?>

<div id="smart-content">

 <h2 align='middle'><u>Sales Order(SO) Consumption Details/Product Usage</b></u></h2> </u>

    <div class="client-login-list">

        <ul>
            <li>
                <div id='select_div'>

                    <form action = "/smart/SmartMis/getSOUser" method="post" id="SOCD">
                        <ul align='left'>
                            <table align='middle' >
                                <tr><h6><u>Please choose one option:</u></h6></tr>
                                <tr > 
                                    <td> Client Login E-Mail Address </td>
                                    <td><input type= 'option' align='middle' name = "loginEmail" id="loginEmail"/> </td>
                                </tr>
                                <tr></tr><tr><td> OR </td></tr><tr></tr>
                                <tr>
                                    <td> Sales Order Number </td>
                                    <td> <input type= 'option' align='middle' name = "salesOrder" id="salesOrder"/></td>   
                                </tr>
                                <tr></tr><tr></tr>
                                <tr><td><input type= 'button' value= "Download Data" onclick="submitSODetails()"/></td> </tr>
                            </form> 
                        </td>
                    </tr>
                    <tr> <td><p></td>   </tr>

                    </table></ul>
                    <label id="errorText" style="color: red"></label>
                    <br>
                    <h6>**Do not add more than 5 inputs in comma separated format</h6>
                    

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
