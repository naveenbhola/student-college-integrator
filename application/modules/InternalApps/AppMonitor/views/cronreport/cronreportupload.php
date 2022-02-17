<?php
$this->load->view('AppMonitor/common/header');
?>
<div class="blockbg">
    <form autocomplete="off" method="post" onsubmit="return false;" action="">
        <div style="float:left; margin-left:80px;width:200px; ">
            <input type="file" id="cronFile" accept="application/msword, text/plain">
        </div>
        <div style='float:left; margin-left:30px; padding-top:0px;font-size: 18px'>Server: </div>
        <div style='float:left; margin-left:10px;width:150px; padding-top:0px;'>
            <select style='font-size:14px; padding:1px; color:#444;' name='server' id="_server">
                <?php
                foreach($servers as $sk => $sv) {
                    echo "<option value='$sv'>$sv</option>";
                }
                ?>
            </select>
        </div>
        <button class="zbutton zsmall zgreen" id="btnGo" type="button">Go</button>
        <div>
            <span id="fileMsg" style="font-size: 16px;left:80px;position: absolute;top: 138px;color:red;display:none"></span>
        </div>
        <div>
            <span id="btnMsg" style="font-size: 16px;left:650px;position: absolute;top: 115px;display:none"></span>
        </div>
    </form>
</div>



<script type="text/javascript">
$(function(){

    if (!(window.File && window.FileReader && window.FileList && window.Blob)) {
        alert("The File APIs are not fully supported in this browser.");
    } 

    bindclick();

    var ctext = $('#btnGo').text();
    $('#cronFile').change(function(){

        $('#fileMsg').html('').slideUp();
        $('#btmMsg').html('').slideUp();

        try{
            if(this.value!="" && $('#cronFile').length==1){
                if(this.files[0].size!=0){

                    $('#btnGo').text('Uploading..').attr("disabled",true).css("color",'grey');
                    var reader = new FileReader();
                    reader.onload = function(event){
                        bindclick(event.target.result);
                        $('#btnGo').text(ctext).attr("disabled",false).css("color",'white');
                    };
                    reader.readAsText(this.files[0]);
                }
                else{
                    $('#fileMsg').html('Empty file!').slideDown();
                }
            }
            else{
                $('#fileMsg').html('No file selected!').slideDown();
            }
        }
        catch(e){
         $('#btnGo').text(ctext).attr("disabled",false).css("color",'white');
         $('#fileMsg').html(e).slideDown();
     }
    });


    function bindclick(fileContent){
        $('#btnGo').unbind();
        $('#btnGo').click(function(){

            $('#btnMsg').html('').slideUp();
            if($('#cronFile')[0].value!=""){
                if($('#cronFile')[0].files[0].size!=0){
                    $.ajax({
                        url : "<?php echo $ajaxURL; ?>",
                        data : {file:fileContent,selectedServer:$('#_server').val()},
                        type : "POST",
                        success : function(res){
                            $('#btnMsg').html(res).css('color', 'black').slideDown();
                        },
                        error : function(xhr, status, error){
                            $('#btnMsg').html("File parsing error, "+status+": "+error).css('color', 'red').slideDown();
                        }
                    });

                }
                else{
                    $('#btnMsg').html('File is empty!').css('color', 'red').slideDown();
                }
            }
            else{
                $('#btnMsg').html('No file selected!').css('color', 'red').slideDown();
            }
        })
    }
})
</script>
</body>
</html>