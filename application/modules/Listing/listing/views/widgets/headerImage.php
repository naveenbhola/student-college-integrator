<?php if(count($headerImageUrl)==3){?>

<div>
    <div  id ="large" class="float_L" style="width:288px;height:200px;border-right:1px solid #fff; background:url(/public/images/loader.gif) 80% center no-repeat">
    </div>
    <div class="float_L" style="width:124px">
        <div id ="smallUp" style="border-bottom:1px solid #fff"></div>
        <div id ="smallDown"></div>
    </div>
    <div class="clear_B">&nbsp;</div>
</div>

<div id="large0" style="display:none">
    <img src="<?php echo $headerImageUrl['0']['bigImage'];?>" width="288"/>
</div>
<div id="large1" style="display:none">
    <img src="<?php echo $headerImageUrl['1']['bigImage'];?>" width="288"/>
</div>
<div id="large2" style="display:none">
    <img src="<?php echo $headerImageUrl['2']['bigImage'];?>" width="288"/>
</div>
<div id="small0" style="display:none">
    <img src="<?php echo $headerImageUrl['0']['smallImage'];?>" width="119"/>
</div>
<div id="small1" style="display:none">
    <img src="<?php echo $headerImageUrl['1']['smallImage'];?>" width="119"/>
</div>
<div id="small2" style="display:none">
    <img src="<?php echo $headerImageUrl['2']['smallImage'];?>" width="119"/>
</div>


<script>
var loadingDone=0;
var img1= new Image();
img1.onload = function (){ 
loadingDone++;

  var img2= new Image();
  img2.onload = function (){ 
  loadingDone++;

    var img3= new Image();
    img3.onload = function (){
      loadingDone++;
      if(loadingDone == 3){
      //alert("aakash");
      rotateImage();
      }
    }
    img3.src= "<?php echo $headerImageUrl['2']['smallImage'];?>";
  }
  img2.src= "<?php echo $headerImageUrl['1']['smallImage'];?>";

}
img1.src= "<?php echo $headerImageUrl['0']['bigImage'];?>";




    function rotateImage(){
       var html1 = '';
       var html2 = '';
       var html3 = '';

       if(typeof rotateImage.counter == 'undefined'){
         rotateImage.counter=0;
       }
                //alert(rotateImage.counter%3);               
               html1 = $('large'+rotateImage.counter%3).innerHTML;
               html2 = $('small'+(rotateImage.counter+1)%3).innerHTML;
               html3 = $('small'+(rotateImage.counter+2)%3).innerHTML;
               document.getElementById('large').innerHTML = html1 ;
               document.getElementById('smallUp').innerHTML = html2;
               document.getElementById('smallDown').innerHTML = html3;

            if(rotateImage.counter==2){
                rotateImage.counter=0;
            }else{
                rotateImage.counter++;
            }
     setTimeout(function () { rotateImage() },7000);

    }



</script>

<?php
//$headerImageUrl = array(array('bigImage'=>'http://localshiksha.com:80/mediadata/images/image1_big.jpeg' ,'smallImage'=>'http://localshiksha.com:80/mediadata/images/image1_small.jpeg'),array('bigImage'=>'http://localshiksha.com:80/mediadata/images/image3_big.jpeg' ,'smallImage'=>'http://localshiksha.com:80/mediadata/images/image3_small.jpeg',),array('bigImage'=>'http://localshiksha.com:80/mediadata/images/image2_big.jpeg' ,'smallImage'=>'http://localshiksha.com:80/mediadata/images/image2_small.jpeg'));
/*
echo "<script>";
echo "var arr = '".json_encode($headerImageUrl)."';";
echo "rotateImage(arr);";
echo "</script>";
*/
?>

<?php }?>
