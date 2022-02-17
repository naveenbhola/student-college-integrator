var overlayParent;
function trim(_1){
if(_1){
return _1.replace(/^\s*|\s*$/g,"");
}else{
return "";
}
}
function sendReqInfo(_2){
var _3=validateFields(_2);
if(_3!=true){
return false;
}else{
var _4=document.getElementById("cAgree");
if(_4.checked!=true){
document.getElementById("cAgree_error").innerHTML="Please agree to Terms & Conditions.";
document.getElementById("cAgree_error").parentNode.style.display="inline";
return false;
}else{
document.getElementById("cAgree_error").innerHTML="Please agree to Terms & Conditions.";
document.getElementById("cAgree_error").parentNode.style.display="none";
return true;
}
}
}
function getCategoriesforRegistration(_5,_6){
var _7=getChilds(completeCategoryTree,1);
var op="";
for(var i=0;i<_7.length;i++){
op+="<option value =\""+_7[i][0]+"\">"+_7[i][1]+"</>";
}
if(typeof (_6)=="undefined"){
_6="categoryPlace";
}
document.getElementById(_6).innerHTML="<select class=\"normaltxt_11p_blk_arial fontSize_12p\" name=\"board_id\" id=\"board_id\" style=\"width:150px;\" tip=\"course_categories\">"+op+"</select>";
}
function insertWbr(_a,_b){
return _a.replace(eval("/[^ ]{"+_b+",}?/g"),"$&<wbr/>");
}
function randNum(){
return Math.floor(Math.random()*1000000);
}
function reloadCaptcha(_c,_d){
randomkey=Math.floor(Math.random()*1000000);
if(typeof (_d)=="undefined"){
document.getElementById(_c).src="/CaptchaControl/showCaptcha?width=100&height=40&characters=5&junk="+randomkey;
}else{
document.getElementById(_c).src="/CaptchaControl/showCaptcha?width=100&height=40&characters=5&junk="+randomkey+"&secvariable="+_d;
}
}
function emptyInnerHtml(_e){
document.getElementById(_e).innerHTML="";
}
function hideObject(_f){
document.getElementById(_f).style.display="none";
}
function camelCase(_10){
var _11=new Array();
_11=_10.split(" ");
var _12="";
for(var i=0;i<_11.length;i++){
if(i<(_11.length-1)){
_12+=_11[i].substring(0,1).toUpperCase()+_11[i].substring(1,_11[i].length).toLowerCase()+" ";
}else{
_12+=_11[i].substring(0,1).toUpperCase()+_11[i].substring(1,_11[i].length).toLowerCase();
}
}
return _12;
}
function showDiv(_14,_15){
for(i=0;i<_14.length;i++){
if(i==_15){
document.getElementById(_14[i]).style.display="block";
}else{
document.getElementById(_14[i]).style.display="none";
}
}
}
function validateFields(_16){
var _17=true;
for(var _18=0;_18<_16.elements.length;_18++){
var _19=_16.elements[_18];
if(_19.getAttribute("validate")){
var _1a=_19.getAttribute("validate");
var _1b=trim(_19.value);
_1b=stripHtmlTags(_1b);
_19.value=_1b;
_1b=_1b.replace(/[(\n)\r\t\"\'\\]/g," ");
var _1c=_19.getAttribute("maxlength");
var _1d=_19.getAttribute("minlength");
var _1e;
try{
_1e=_19.getAttribute("caption");
}
catch(e){
_1e="field";
}
if(!checkRequired(_19)){
continue;
}
var _1f=_1a+"(\""+_1b+"\", \""+_1e+"\", "+_1c+", "+_1d+")";
var _20=eval(_1f);
if(_20!==true){
document.getElementById(_19.id+"_error").parentNode.style.display="inline";
document.getElementById(_19.id+"_error").innerHTML=_20;
_17=false;
}else{
document.getElementById(_19.id+"_error").parentNode.style.display="none";
document.getElementById(_19.id+"_error").innerHTML="";
}
}else{
try{
var _1e=_19.getAttribute("caption");
var _1c=_19.getAttribute("maxlength");
var _1d=_19.getAttribute("minlength");
_1d=_1d==null?0:_1d;
_1c=_1c==null?0:_1c;
if(((_19.value.length>_1c||_19.value.length<_1d)&&(_19.value.length!=0)&&(_1c!=0))&&(_19.type=="text"||_19.type=="textarea")){
document.getElementById(_19.id+"_error").parentNode.style.display="inline";
document.getElementById(_19.id+"_error").innerHTML="Please fill the "+_1e+" within the range of "+_1d+" to "+_1c+" characters.";
_17=false;
continue;
}else{
document.getElementById(_19.id+"_error").parentNode.style.display="none";
}
if(checkProfanity(_19,_1e)){
continue;
}
}
catch(e){
}
}
}
return _17;
}
function checkProfanity(_21,_22){
var _23;
try{
_23=_21.getAttribute("profanity");
}
catch(e){
_23=false;
}
if(_21.getAttribute("readonly")!=null){
return false;
}
_23=true;
if(_21.type=="text"){
if(_23){
var _24=trim(_21.value);
_21.value=_24;
_24=_24.replace(/[(\n)\r\t\"\']/g," ");
var _25=isProfane(_24);
if(_25!==false){
try{
document.getElementById(_21.id+"_error").parentNode.style.display="inline";
document.getElementById(_21.id+"_error").innerHTML="Please don't use objected words ("+_25+") for the "+_22;
return false;
}
catch(e){
}
}else{
try{
if(document.getElementById(_21.id+"_error").innerHTML.indexOf("objected words")>-1){
document.getElementById(_21.id+"_error").parentNode.style.display="none";
document.getElementById(_21.id+"_error").innerHTML="";
}
return true;
}
catch(e){
}
}
}else{
return true;
}
}else{
return true;
}
}
function validateStr(str,_27,_28,_29,_2a){
if(checkHtmlTags(str)){
return "HTML tags will be removed.";
}
if(str.length==""){
return "Please enter the "+_27+".";
}else{
if(str.length>_28){
return _27+" cannot exceed "+_28+" characters.";
}else{
if(str.length<_29){
return _27+" should be atleast "+_29+" characters.";
}else{
str=removeNewLineCharacters(str);
str=str.toLowerCase();
str=str.replace(/[:;?.\-_!,\/]/g," ");
var _2b=str.split(" ");
for(var _2c=0;_2c<_2b.length;_2c++){
if(_2b[_2c].length>32){
return _27+" cannot contain any word exceeding 32 characters.";
}else{
if(isRestrictedWord(_2b[_2c])){
return false;
}
}
}
return true;
}
}
}
}
function validateSecurityCode(str,_2e,_2f,_30,_31){
if(str.length>_2f){
return "Please enter the "+_2e+" as shown in the image.";
}else{
if(str.length<_30){
return "Please enter the "+_2e+" as shown in the image.";
}else{
return true;
}
}
}
function validateSelect(str,_33,_34,_35){
if(str.length==""){
return "Please select "+_33+".";
}
return true;
}
function removeNewLineCharacters(str){
if(str.indexOf("\r\n")!=-1){
str=str.replace(/\r\n/g," ");
}else{
if(str.indexOf("\r")!=-1){
str=str.replace(/\r/g," ");
}else{
if(str.indexOf("\n")!=-1){
str=str.replace(/\n/g," ");
}
}
}
return str;
}
function isRestrictedWord(_37){
return false;
}
function validateEmail(_38,_39,_3a,_3b){
if(_38.length==0&&(typeof (_3b))&&_3b==0){
return true;
}
var _3c=/^((([a-z]|[A-Z]|[0-9]|\-|_)+(\.([a-z]|[A-Z]|[0-9]|\-|_)+)*)@((((([a-z]|[A-Z]|[0-9])([a-z]|[A-Z]|[0-9]|\-){0,61}([a-z]|[A-Z]|[0-9])\.))*([a-z]|[A-Z]|[0-9])([a-z]|[A-Z]|[0-9]|\-){0,61}([a-z]|[A-Z]|[0-9])\.)[\w]{2,4}|(((([0-9]){1,3}\.){3}([0-9]){1,3}))|(\[((([0-9]){1,3}\.){3}([0-9]){1,3})\])))$/;
if(_38==""){
return "Please enter the "+_39+".";
}else{
if(!_3c.test(_38)){
return "Please enter the "+_39+" in correct format.";
}
}
return true;
}
function validateDisplayName(str,_3e,_3f,_40,_41){
var _42=trim(str);
var _41=/^([A-Za-z0-9\s](,|\.|_|-){0,2})*$/;
if(_42==""){
return "Please enter the "+_3e;
}
if(_42.length<_40){
return _3e+" should be atleast "+_40+" characters.";
}
if(_42.length>_3f){
return _3e+" cannot exceed "+_3f+" characters.";
}
var _43=_41.test(_42);
if(_43==false){
return _3e+" can not contain special characters.";
}
return true;
}
function validateAlphabetic(str,_45,_46,_47,_48){
var _49=trim(str);
var _48=/^[a-zA-Z]+$/;
if(_49==""){
return "Please enter the "+_45;
}
if(_49.length<_47){
return _45+" should be atleast "+_47+" characters.";
}
if(_49.length>_46){
return _45+" cannot exceed "+_46+" characters.";
}
var _4a=_48.test(_49);
if(_4a==false){
return _45+" should be alphabetic.";
}
return true;
}
function validateUrl(url,_4c){
var _4d=/^(([\w]+:)?\/\/)?(([\d\w]|%[a-fA-f\d]{2,2})+(:([\d\w]|%[a-fA-f\d]{2,2})+)?@)?([\d\w][-\d\w]{0,253}[\d\w]\.)+[\w]{2,4}(:[\d]+)?(\/([-+_~.\d\w]|%[a-fA-f\d]{2,2})*)*(\?(&?([-+_~.\d\w]|%[a-fA-f\d]{2,2})=?)*)?(#([-+_~.\d\w]|%[a-fA-f\d]{2,2})*)?$/;
if(url==""){
return "Please enter the "+_4c+".";
}else{
if(!_4d.test(url)){
return "Please enter "+_4c+" in correct format";
}
}
return true;
}
function validateInteger(_4e,_4f,_50,_51){
if(_4e.toString().length==0&&_51==0){
return true;
}
var _52=/^(\d)+$/;
if(!_52.test(_4e)){
return "Please fill the "+_4f+" with correct integer value";
}
_51=typeof (_51)!=undefined?_51:0;
_50=typeof (_50)!=undefined?_50:_4e.toString().length;
if(_4e.toString().length>_50||_4e.toString().length<_51){
return "Please fill the "+_4f+" with "+_51+" to "+_50+" digits";
}
return true;
}
function validateMobileInteger(_53){
var _54=/^(\d)+$/;
if(_53!=""){
if(!_54.test(_53)){
return "Please fill the field with correct numeric value";
}
if(_53.length<4){
return "Phone No should be minimum of 4 characters";
}
}
if(_53==""){
return "Please enter your contact number";
}
return true;
}
function validateZip(_55,_56,_57,_58){
var _59=/^(\d)+$/;
if(!_59.test(_55)){
return "Please fill the field with correct integer value";
}else{
if(_55.length>_57||_55.length<_58){
return "Please fill the field with valid zip/pincode 5-6 numerals.";
}
}
return true;
}
function validateDate(_5a,_5b,_5c){
if(_5a==""){
return "Please enter the "+_5b;
}else{
if(!validateStr(_5a,10)){
return "Please enter date in correct format of yyyy-mm-dd";
}
}
var _5d=_5a.split("-");
if(_5d.length<3){
return "Please enter date in correct format of yyyy-mm-dd";
}
var _5e=_5d[0];
var _5f=_5d[1];
var _60=_5d[2];
if((validateInteger(_5e,4)!=true)||(validateInteger(_5f,2)!=true)||(validateInteger(_60,2)!=true)){
return "Please enter date in correct format of yyyy-mm-dd with all the values as numbers";
}
var _61=new Date();
var _62=new Date();
_62.setDate(_60);
_62.setMonth(_5f);
_62.setYear(_5e);
return true;
}
function validateEndDate(_63,_64,_65){
var _66=validateDate(_63,_64,_65);
if(_66===true){
var _67=document.getElementById("start_date").value;
if(_67==""||validateDate(_67)!=true){
return "";
}
var _68=_67.split("-");
var _69=_63.split("-");
_68[2]=(_68[2].indexOf("0")==0)?_68[2].replace("0",""):_68[2];
_69[2]=(_69[2].indexOf("0")==0)?_69[2].replace("0",""):_69[2];
var _67=new Date(parseInt(_68[0]),parseInt(_68[1]),parseInt(_68[2]));
var _6a=new Date(parseInt(_69[0]),parseInt(_69[1]),parseInt(_69[2]));
var _6b=1000*60*60*24;
var _6c=Math.ceil((_6a.getTime()-_67.getTime())/(_6b));
if(_6c>=0){
if(_6c>90){
return "The difference between End date and Start date should not be more than 90 days.";
}else{
return true;
}
}else{
return "End date should be greater than Start Date";
}
}else{
return _66;
}
}
function validateTime(_6d,_6e,_6f){
var _70=_6d.split(":");
if(_6d==""){
return "Please enter the "+_6e;
}else{
if(_70.length<2){
return "Please enter the "+_6e+" in correct format of hh:mm";
}
}
var _71=_70[0];
var _72=_70[1];
if((validateInteger(_71,2)!=true)||(validateInteger(_72,2)!=true)){
return "Please enter the "+_6e+" in correct format of hh:mm";
}
if(parseInt(_71)>12||(parseInt(_71)<0&&_71.indexOf(0)!=0)){
return "Please enter the "+_6e+" in correct format of hh:mm with all the values in numbers";
}
if(parseInt(_72)>59||(parseInt(_72)<0&&_72.indexOf(0)!=0)){
return "Please enter the "+_6e+" in correct format of hh:mm with all the values in numbers";
}
return true;
}
function validateEndTime(_73,_74,_75){
var _76=validateTime(_73,_74,_75);
if(document.getElementById("end_date_error").innerHTML!=""){
return "";
}
if(_76===true){
if(document.getElementById("start_date").value==document.getElementById("end_date").value||document.getElementById("end_date").value==""){
var _77=document.getElementById("start_time").value;
var _78=(document.getElementById("startTimeStampAM").checked)?document.getElementById("startTimeStampAM").value:document.getElementById("startTimeStampPM").value;
var _79=(document.getElementById("endTimeStampAM").checked)?document.getElementById("endTimeStampAM").value:document.getElementById("endTimeStampPM").value;
var _7a=document.getElementById("end_time").value;
var _7b=_77.split(":");
var _7c=_7a.split(":");
var _7d=(_7b[0].indexOf("0")>0)?_7b[0]:_7b[0].replace("0","");
var _7e=(_7c[0].indexOf("0")>0)?_7c[0]:_7c[0].replace("0","");
_7d=parseInt(_7d)+parseInt(_78);
_7e=parseInt(_7e)+parseInt(_79);
if(_7d>=_7e){
return "Please enter the End time greater than Start time";
}else{
document.getElementById("end_time_error").innerHTML="";
return true;
}
}else{
return true;
}
}else{
return _76;
}
}
var profaneWordsBag=null;
function isProfane(str){
var _80=str.split(" ");
for(var _81=0;_81<_80.length;_81++){
for(var _82=0;_82<profaneWordsBag.length;_82++){
if(_80[_81]==profaneWordsBag[_82]){
return profaneWordsBag[_82];
}
}
}
return false;
}
function fillProfaneWordsBag(){
profaneWordsBag=eval(base64_decode("WyJzdWNrIiwiZnVjayIsImRpY2siLCJwZW5pcyIsImN1bnQiLCJwdXNzeSIsImhvcm55Iiwib3JnYXNtIiwidmFnaW5hIiwiYmFiZSIsImJpdGNoIiwic2x1dCIsIndob3JlIiwid2hvcmlzaCIsInNsdXR0aXNoIiwibmFrZWQiLCJpbnRlcmNvdXJzZSIsInByb3N0aXR1dGUiLCJzZXgiLCJzZXh3b3JrZXIiLCJzZXgtd29ya2VyIiwiYnJlYXN0IiwiYnJlYXN0cyIsImJvb2IiLCJib29icyIsImJ1dHQiLCJoaXAiLCJoaXBzIiwibmlwcGxlIiwibmlwcGxlcyIsImVyb3RpYyIsImVyb3Rpc20iLCJlcm90aWNpc20iLCJsdW5kIiwiY2hvb3QiLCJjaHV0IiwibG9yYSIsImxvZGEiLCJyYW5kIiwicmFuZGkiLCJ0aGFyYWsiLCJ0aGFyYWtpIiwidGhhcmtpIiwiY2hvZCIsImNob2RuYSIsImNodXRpeWEiLCJjaG9vdGl5YSIsImdhYW5kIiwiZ2FuZCIsImdhbmR1IiwiZ2FhbmR1IiwiaGFyYWFtaSIsImhhcmFtaSIsImNodWRhaSIsImNodWRuYSIsImNodWR0aSIsImJhZGFuIiwiY2hvb2NoaSIsInN0YW4iLCJuYW5naSIsIm5hbmdhIiwibmFuZ2UiLCJwaHVkZGkiLCJmdWRkaSIsImxpZmVrbm90cyIsIjA5ODEwMTEyOTU0IiwiYWJpZGphbiIsInNpZXJyYS1MZW9uZSIsInNlbmVnYWwiLCJzaWVycmEgbGVvbmUiLCJsdWNreSBtYW4iLCJzaXJhIiwibWFkaGFyY2hvZCIsInRoYWJvIiwiZnVja2VkIiwiZnVja2luZyIsInB1YmxpYyBzaXRlIiwiRGFrdSIsInByaXZhdGUgbWFpbCIsInByaXZhdGUgbWFpbGJveCIsInNleHkiLCJqb2JzIHZhY2FuY2llcyIsIm9tbmkgY2l0eSIsImJhc3R1cmQiLCJqZWhhZCIsInRlbmRlcm5lc3MgY2FyZSJd"));
return false;
}
function validatepassandconfirmpass(_83,_84){
if($(_83).value!=$(_84).value){
if($(_84).value!=""){
$(_84+"_error").innerHTML="Password and confirm password should match";
$(_84+"_error").parentNode.style.display="inline";
return false;
}else{
$(_84+"_error").innerHTML="Please enter confirm password";
$(_84+"_error").parentNode.style.display="inline";
return false;
}
}else{
$(_84+"_error").innerHTML="";
$(_84+"_error").parentNode.style.display="none";
return true;
}
}
function selectComboBox(_85,_86){
try{
for(var i=0;i<_85.options.length;i++){
_85.options[i].setAttribute("selected",false);
if(_85.options[i].value==_86){
_85.options[i].setAttribute("selected",true);
_85.options[i].selected=true;
break;
}
}
}
catch(e){
}
return true;
}
function selectMultiComboBox(_88,_89){
for(var i=0;i<_88.options.length;i++){
_88.options[i].selected=false;
for(var j=0;j<_89.length;j++){
if(_88.options[i].value==_89[j]){
_88.options[i].selected=true;
break;
}
}
}
return true;
}
function getXMLHTTPObject(){
var _8c;
try{
_8c=new XMLHttpRequest();
}
catch(e){
try{
_8c=new ActiveXObject("Msxml2.XMLHTTP");
}
catch(e){
try{
_8c=new ActiveXObject("Microsoft.XMLHTTP");
}
catch(e){
alert("Your browser does not support AJAX!");
return false;
}
}
}
return _8c;
}
function setXHRHeaders(_8d){
_8d.setRequestHeader("Content-length",0);
_8d.setRequestHeader("Connection","close");
}
function truncateString(str,_8f){
str=trim(str);
if(str.length>_8f){
str=str.substr(0,_8f-3);
str+="...";
}
return str;
}
categoryComboCount=0;
var categoryCombos=new Array();
categoryComboCount=parseInt(categoryComboCount);
function getCategoryCombo(_90,_91){
var _92;
var _93=eval(_90[_91]);
var _94="";
var _95="";
_92++;
for(var cat in _93){
var _97=typeof (_93[cat]);
if(_97=="object"){
getCategoryCombo(_93,cat);
}else{
if(_97=="string"){
var _98=_93[cat];
var _99=_98.split("<=>");
var _9a=_99[0];
var _9b=trim(_99[1]);
if(_9b.substring(0,5)=="Other"){
_95+="<option value=\""+_9a+"\">"+_9b+"</option>";
}else{
_94+="<option value=\""+_9a+"\">"+_9b+"</option>";
}
}
}
}
if(_94!==""){
_94+=_95;
if(_91=="All"){
categoryCombos[categoryComboCount]="<span id=\""+_91+"\" style=\"display:inline\"><select style=\"font-size:11px;\" onChange=\"showSubCatgory(this);\"><option value=\"\">--Select--</option>"+_94+"</selct></span>";
}else{
categoryCombos[categoryComboCount]="<span id=\"catCom"+categoryComboCount+"\" style=\"display:none\"><select style=\"font-size:11px;\" onChange=\"updateCategoryForForm(this.value);createCategoryCrumb(this,0);\"><option value=\"\">-- Select Sub Category --</option>"+_94+"</selct></span>";
categoryComboCount++;
}
}
}
function showSubCatgory(_9c){
for(var i=0;i<_9c.options.length;i++){
if(document.getElementById("catCom"+i)){
if(i==(_9c.selectedIndex-1)){
document.getElementById("catCom"+i).style.display="inline";
}else{
document.getElementById("catCom"+i).style.display="none";
}
}
}
updateCategoryForForm(_9c.value);
createCategoryCrumb(_9c,1);
}
function updateCategoryForForm(_9e){
document.getElementById("board_id").value=_9e;
}
function createCategoryCrumb(_9f,_a0){
var _a1=_9f[_9f.selectedIndex].innerHTML;
if(document.getElementById("categoryCrumb")){
if(_a0==1){
document.getElementById("categoryCrumb").value=_a1;
}else{
var _a2=document.getElementById("categoryCrumb").value.split("-");
document.getElementById("categoryCrumb").value=_a2[0];
document.getElementById("categoryCrumb").value=document.getElementById("categoryCrumb").value+"-"+_a1;
}
}
}
function createCategoryCombo(_a3,_a4){
document.getElementById(_a4).innerHTML="";
if(!isNaN(_a3.selectedIndex)&&_a3.selectedIndex>-1){
var _a5=_a3.options[_a3.selectedIndex].innerHTML;
getCategoryCombo(categoryTreeMain.Root,"All");
for(var i=categoryCombos.length-1;i>=0;i--){
document.getElementById(_a4).innerHTML+=categoryCombos[i];
}
}
categoryCombos=new Array();
categoryComboCount=0;
}
function updateCluster(_a7){
for(var i=0;i<_a7.length;i++){
var _a9=_a7[i].categoryId;
var _aa=_a7[i].categoryCount;
if(document.getElementById("categoryLabel_"+_a9)){
document.getElementById("categoryLabel_"+_a9).innerHTML+="<count>("+_aa+")</count>";
}
}
return "";
}
function CountryChanged(){
var _ab=document.getElementsByTagName("count");
for(var i=0;i<_ab.length;i++){
var _ad=_ab[i].parentNode;
_ad.removeChild(_ab[i]);
}
getEventsCountForCountry();
CategoryChanged(document.getElementById("category_id").value);
}
function checkViewDDs(_ae){
if(document.getElementById("countOffset_DD1")){
if(parseInt(document.getElementById("countOffset_DD1").options[0].value)>=_ae){
if(document.getElementById("countOffset_DD1")){
document.getElementById("countOffset_DD1").parentNode.style.display="none";
}
if(document.getElementById("countOffset_DD2")){
document.getElementById("countOffset_DD2").parentNode.style.display="none";
}
}else{
if(document.getElementById("countOffset_DD1")){
document.getElementById("countOffset_DD1").parentNode.style.display="inline";
}
if(document.getElementById("countOffset_DD2")){
document.getElementById("countOffset_DD2").parentNode.style.display="inline";
}
}
}
}
function doPagination(_af,_b0,_b1,_b2,_b3,_b4,_b5){
checkViewDDs(_af);
if(!_b5){
_b5=10;
}
_b0=typeof (_b0)!="undefined"?_b0:"startOffSet";
_b1=typeof (_b1)!="undefined"?_b1:"countOffset";
count=_b1;
_b2=typeof (_b2)!="undefined"?_b2:"paginataionPlace1";
_b3=typeof (_b3)!="undefined"?_b3:"paginataionPlace2";
_b4=typeof (_b4)!="undefined"?_b4:"methodName";
_b1=parseInt(document.getElementById(_b1).value);
if(_b1<1){
_b1=15;
}
var _b6=parseInt(document.getElementById(_b0).value);
var _b7=getPaginationHtml(_af,_b0,_b1,_b6,_b4,_b5);
if(document.getElementById(_b2)){
document.getElementById(_b2).innerHTML=_b7;
}
if(document.getElementById(_b3)){
document.getElementById(_b3).innerHTML=_b7;
}
}
function getPageNumbers(_b8,_b9,_ba,_bb,_bc){
var _bd="";
var _be=_b8>=_ba/2?(_b8-Math.floor(_ba/2)):0;
if(_be+_ba>_b9){
_be=(_b9-_ba);
}
if(_be<0){
_be=0;
}
for(;0<_ba;_ba--,_be++){
if(_b9<_be+1){
break;
}
if(_b8==_be){
_bd+="<a href=\"#\" class=\"show\" onclick=\"return false;\">"+(_be+1)+"</a> ";
}else{
_bd+="<a href=\"#\" onClick=\"return updateStartOffset("+(_be)+",'"+_bb+"','"+count+"','"+_bc+"')\">"+(_be+1)+"</a> ";
}
}
return _bd;
}
function getPaginationHtml(_bf,_c0,_c1,_c2,_c3,_c4){
var _c5="";
var _c6=Math.ceil(_bf/_c1);
var _c7=Math.ceil(_c2/_c1);
var _c8="<span id=\"pageNumbers\">";
_c8+=getPageNumbers(_c7,_c6,_c4,_c0,_c3);
_c8+="</span>";
if(_c6<1){
}else{
if(_c6==1){
}else{
_c5+="<span class=\"normaltxt_11p_blk fontSize_12p\"> &nbsp;</span>";
if(_c7==0){
}else{
_c5+="<a href=\"#\" onClick=\"return updateStartOffset("+(parseInt(_c7)-1)+",'"+_c0+"','"+count+"','"+_c3+"')\">Prev</a>";
}
_c5+=_c8;
if(_c7==_c6-1){
}else{
_c5+="<a href=\"#\" onClick=\"return updateStartOffset("+(parseInt(_c7)+1)+",'"+_c0+"','"+count+"','"+_c3+"')\">Next</a>";
}
}
}
return _c5;
}
function updateStartOffset(_c9,_ca,_cb,_cc){
setStartOffset(_c9,_ca,_cb);
changePage(_cc);
return false;
}
function setStartOffset(_cd,_ce,_cf){
_ce=typeof (_ce)!="undefined"?_ce:"startOffSet";
_cf=typeof (_cf)!="undefined"?_cf:"countOffset";
document.getElementById(_ce).value=(parseInt(_cd)*parseInt(document.getElementById(_cf).value));
}
function updateCountOffset(_d0,_d1,_d2){
_d1=(typeof (_d1)=="undefined")?"startOffSet":_d1;
_d2=(typeof (_d2)=="undefined")?"countOffset":_d2;
var _d3=parseInt(document.getElementById(_d2).value);
var _d4=parseInt(document.getElementById(_d1).value);
document.getElementById(_d2).value=_d0.value;
selectComboBox(document.getElementById("countOffset_DD1"),_d0.value);
selectComboBox(document.getElementById("countOffset_DD2"),_d0.value);
if(_d4==0||_d4<_d0.value){
updateStartOffset(0,_d1,_d2);
}else{
updateStartOffset((_d4/_d3)/_d0.value,_d1,_d2);
}
}
function changePage(_d5){
var _d5=typeof (_d5)!="undefined"?_d5:"methodName";
var _d6=document.getElementById(_d5).value;
window[_d6]();
}
function updatePaginationMethodName(_d7,_d8){
_d8=typeof (_d8)!="undefined"?_d8:"methodName";
document.getElementById(_d8).value=_d7;
}
function getWindowHeight(){
var _d9=0;
if(typeof (window.innerHeight)=="number"){
_d9=window.innerHeight;
}else{
if(document.documentElement&&document.documentElement.clientHeight){
_d9=document.documentElement.clientHeight;
}else{
if(document.body&&document.body.clientHeight){
_d9=document.body.clientHeight;
}
}
}
return _d9;
}
function setContent(_da){
if(document.getElementById){
var _db=getWindowHeight();
if(_db>0){
var _dc=document.getElementById(_da);
var _dd=_dc.offsetHeight;
if(_db-_dd>0){
_dc.style.top=((_db/2)-(_dd/2))+"px";
}else{
}
}
}
}
function setScroll(x,y){
window.scrollTo(x,y);
window.onscroll=function(){
window.scrollTo(x,y);
};
}
function setNoScroll(){
h=document.documentElement.scrollTop;
window.scrollTo(0,h);
window.onscroll=function(){
};
}
function checkCity(_e0,_e1,_e2,_e3){
var _e4="country";
var _e5;
if((typeof (_e3)!="undefined")&&(trim(_e3)!="")){
_e4="country"+_e3;
}
var _e6=document.getElementById(_e0.id+"_other");
if(_e6){
_e6.value="";
if(_e0.value==-1){
showElement(_e6);
_e5=_e6.value;
}else{
hideElement(_e6);
_e5=_e0[_e0.selectedIndex].text;
}
}else{
_e5=_e0[_e0.selectedIndex].text;
}
if(typeof (_e2)!="undefined"){
createLocationCrumb(document.getElementById(_e4),_e5,_e2);
}
getInstitutesForCity(_e3);
if(typeof (_e1)!="undefined"){
eval(_e1+"("+_e0.value+")");
}
}
function createLocationCrumb(_e7,_e8,_e9){
if(document.getElementById("locationCrumb")){
var _ea=_e7[_e7.selectedIndex].innerHTML;
document.getElementById("locationCrumb").value=_ea+"-"+_e8;
selectComboBox(document.getElementById("cities"),_e9);
}
}
function checkInstitute(_eb,_ec,_ed){
var _ee="courses";
if((typeof (_ed)!="undefined")&&(trim(_ed)!="")){
_ee="courses"+_ed;
}
if(!_eb){
return false;
}
var _ef=document.getElementById(_eb.id+"_other");
if(_eb.value=="-1"){
showElement(_ef);
}else{
hideElement(_ef);
}
getCoursesForInstitute(_ed);
if(typeof (_ec)!="undefined"){
if(_eb){
eval(_ec+"(\""+_eb.value+"\",\""+_ee+"\")");
}else{
eval(_ec+"("+_eb.value+")");
}
}
}
function checkCourse(_f0,_f1){
var _f2=document.getElementById(_f0.id+"_other");
_f2.value="";
if(_f0.value=="-1"){
showElement(_f2);
}else{
hideElement(_f2);
}
if(typeof (_f1)!="undefined"){
if(_f0){
eval(_f1+"("+_f0.value+",\""+_f0.id+"\")");
}else{
eval(_f1+"("+_f0.value+")");
}
}
}
function showElement(_f3){
if(_f3){
_f3.style.display="";
_f3.style.display="inline";
}
}
function hideElement(_f4){
if(_f4){
_f4.style.display="";
_f4.style.display="none";
}
}
function getCitiesForCountry(_f5,_f6,_f7){
if((typeof (_f7)!="undefined")&&(trim(_f7)!="")){
var _f8=document.getElementById("cities"+_f7);
var _f9=document.getElementById("country"+_f7).value;
}else{
_f7="";
var _f8=document.getElementById("cities");
var _f9=document.getElementById("country").value;
}
if(typeof (_f5)=="undefined"){
_f5=_f8.value;
}
_f6=!_f6?false:_f6;
if(createCityDropDown(cityList[_f9],_f8,_f6)){
if(_f5==""){
_f5=_f8.value;
}
selectComboBox(_f8,_f5);
checkCity(_f8,"checkInstitute",_f5,_f7);
}
}
function getInstitutesForCity(_fa){
var _fb="colleges";
var _fc="cities";
if((typeof (_fa)!="undefined")&&(trim(_fa)!="")){
_fb="colleges"+_fa;
_fc="cities"+_fa;
}
if(document.getElementById(_fb)){
var _fd=document.getElementById(_fb);
var _fe=document.getElementById(_fc).value;
var _ff=getXMLHTTPObject();
_ff.onreadystatechange=function(){
if(_ff.readyState==4){
var _100=eval("eval("+_ff.responseText+")");
if(createInstituteDropDown(_100,_fd)){
checkInstitute(_fd,"updateInstitutes",_fa);
}else{
checkInstitute(_fd,"updateInstitutes",_fa);
}
}
};
if(_fe==""){
return false;
}
var url="/rating/Rating/getInstitutesForCity/1/"+_fe+"/"+randNum();
_ff.open("POST",url,true);
setXHRHeaders(_ff);
_ff.send(null);
}
}
function getCoursesForInstitute(_102){
var _103="colleges";
var _104="courses";
if((typeof (_102)!="undefined")&&(trim(_102)!="")){
_103="colleges"+_102;
_104="courses"+_102;
}
if(document.getElementById(_104)){
var _105=document.getElementById(_104);
var _106=document.getElementById(_103).value;
var _107=getXMLHTTPObject();
_107.onreadystatechange=function(){
if(_107.readyState==4){
var _108=eval("eval("+_107.responseText+")");
createCourseDropDown(_108,_105);
}
};
if(!_106){
return false;
}
var url="/rating/Rating/getCoursesForInstitute/1/"+_106+"/"+randNum();
_107.open("POST",url,true);
setXHRHeaders(_107);
_107.send(null);
}
}
function createCityDropDown(_10a,_10b,_10c){
var _10d=0;
if(_10a){
_10d=_10a.length;
}
_10b.innerHTML="";
var _10e=document.createElement("option");
_10e.value="";
if((_10c==true)||(_10c==1)){
_10e.innerHTML="All Cities";
_10e.title="All Cities";
}else{
_10e.innerHTML="Select City";
_10e.title="Select City";
}
_10b.appendChild(_10e);
for(var city in _10a){
_10e=document.createElement("option");
_10e.value=city;
_10e.innerHTML=getSmString(_10a[city],30);
_10e.title=_10a[city];
_10b.appendChild(_10e);
}
if(!_10c){
var _10e=document.createElement("option");
_10e.value=-1;
_10e.innerHTML="Other";
_10e.title="Other";
_10b.appendChild(_10e);
}
var _110=document.getElementById(_10b.id+"_other");
if(_10d===0){
_10b.style.display="none";
if(_110){
_110.style.display="inline";
}
updateInstitutes(-1,_10b.id);
return false;
}else{
_10b.style.display="inline";
if(_110){
_110.style.display="none";
}
return true;
}
}
function createInstituteDropDown(_111,_112){
_112.innerHTML="";
var _113=0;
if(_111){
for(;_113<_111.length;_113++){
var _114=document.createElement("option");
_114.value=_111[_113].instituteID;
_114.innerHTML=getSmString(_111[_113].instituteName,50);
_114.title=_111[_113].instituteName;
_112.appendChild(_114);
}
}
var _114=document.createElement("option");
_114.value=-1;
_114.innerHTML="Other";
_112.appendChild(_114);
if(_113===0){
updateCourses(-1,_112.id);
return false;
}else{
_112.style.display="inline";
return true;
}
}
function createCourseDropDown(_115,_116){
if(!_116){
return false;
}
_116.style.display="none";
_116.innerHTML="";
var _117=0;
for(;_117<_115.length;_117++){
var _118=document.createElement("option");
_118.value=_115[_117].courseID;
_118.innerHTML=getSmString(_115[_117].courseName,50);
_118.title=_115[_117].courseName;
_116.appendChild(_118);
}
var _118=document.createElement("option");
_118.value=-1;
_118.innerHTML="Other";
_116.appendChild(_118);
if(_117===0){
updateCourses(-1,_116.id);
}else{
_116.style.display="inline";
}
}
function updateInstitutes(_119,_11a){
var _11b=document.getElementById(_11a);
var _11c=document.getElementById(_11a+"_other");
if((!_11c)||(!_11b)){
return false;
}
_11c.value="";
if(_119<0){
hideElement(_11b);
showElement(_11c);
}else{
showElement(_11b);
hideElement(_11c);
}
updateCourses(_119,_11a);
}
function updateCourses(_11d,_11e){
var _11f=document.getElementById(_11e);
var _120=document.getElementById(_11e+"_other");
if((!_120)||(!_11f)){
return false;
}
_120.value="";
if(_11d<0){
hideElement(_11f);
showElement(_120);
}else{
showElement(_11f);
hideElement(_120);
}
}
function showOverlay(_121,_122,_123,_124,_125,left,top){
if(trim(_124)==""){
return false;
}
var body=document.getElementsByTagName("body")[0];
document.getElementById("overlayTitle").innerHTML=_123;
document.getElementById("genOverlay").style.width=_121+"px";
document.getElementById("genOverlay").style.height=_122+"px";
document.getElementById("genOverlay").style.display="inline";
document.getElementById("genOverlayContents").innerHTML=_124;
var divY=parseInt(screen.height)/2-(document.getElementById("genOverlay").offsetHeight/2);
if(typeof left!="undefined"){
var divX=left;
}else{
var divX=parseInt(body.offsetWidth)/2-(document.getElementById("genOverlay").offsetWidth/2);
}
if(typeof top!="undefined"){
var divX=top;
}else{
var divX=parseInt(body.offsetWidth)/2-(document.getElementById("genOverlay").offsetWidth/2);
}
h=document.documentElement.scrollTop;
divY=divY+h;
if(typeof _125=="undefined"||_125===false){
document.getElementById("dim_bg").style.height=body.scrollHeight+"px";
document.getElementById("dim_bg").style.display="inline";
}
if(document.getElementById("genOverlay").scrollHeight<body.offsetHeight){
document.getElementById("genOverlay").style.left=divX+"px";
document.getElementById("genOverlay").style.top=divY+"px";
}else{
document.getElementById("genOverlay").style.left=divX+"px";
document.getElementById("genOverlay").style.top="100px";
document.getElementById("dim_bg").style.height=(document.getElementById("genOverlay").scrollHeight+100)+"px";
window.scrollTo(divX,"100");
}
overlayHackLayerForIE("genOverlay",body);
}
function hideOverlay(){
dissolveOverlayHackForIE();
document.getElementById("genOverlay").style.display="none";
document.getElementById("dim_bg").style.display="none";
if((document.getElementById("genOverlayContents").innerHTML!="")&&(overlayParent)){
overlayParent.innerHTML=document.getElementById("genOverlayContents").innerHTML;
}
document.getElementById("genOverlayContents").innerHTML="";
setNoScroll();
}
function hideaddRequestOverlay(){
document.getElementById("dim_bg").style.display="none";
document.getElementById("addRequestOverlay").style.display="none";
dissolveOverlayHackForIE();
setNoScroll();
}
function loginScript(url,_12c,data,_12e,_12f){
var _130=document.createElement("script");
_130.src="/public/js/md5.js";
_130.type="text/javascript";
_130.defer=true;
document.getElementsByTagName("head").item(0).appendChild(_130);
var _12e=document.getElementById(_12e).value;
var _131=document.getElementById(_12f).value;
if((_12e=="")||(_131=="")){
_12c("Error");
return;
}
var _132=hex_md5(_131);
var _133=getXMLHTTPObject();
_133.onreadystatechange=function(){
if(_133.readyState==4){
_12c(_133.responseText,data);
}
};
var _134="email="+_12e+"&password="+_132;
_133.open("POST",url,true);
_133.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
_133.setRequestHeader("Content-length",_134.length);
_133.send(_134);
}
function showAddCollegeOverlay(_135){
var _136=550;
var _137=400;
var _138="Add College";
var _139=document.getElementById(_135).innerHTML;
document.getElementById(_135).innerHTML="";
overlayContent=_139;
overlayParent=document.getElementById(_135);
showOverlay(_136,_137,_138,overlayContent);
if(document.getElementById("status")){
document.getElementById("status").focus();
}
return false;
}
function showCountryOverlay(obj){
if(document.getElementById("countryCommonOverlay").style.display=="none"){
document.getElementById("categorySearchOverlay").style.display="none";
document.getElementById("categoryCommonOverlay").style.display="none";
document.getElementById("countryCommonOverlay").style.display="inline";
document.getElementById("countryCommonOverlay").style.left=obtainPostitionX(obj)-80+"px";
document.getElementById("countryCommonOverlay").style.top=obtainPostitionY(obj)+30+"px";
}else{
document.getElementById("countryCommonOverlay").style.display="none";
}
}
function showCategoryOverlay(obj){
if(document.getElementById("categoryCommonOverlay").style.display=="none"){
document.getElementById("categorySearchOverlay").style.display="none";
document.getElementById("countryCommonOverlay").style.display="none";
document.getElementById("categoryCommonOverlay").style.display="inline";
document.getElementById("categoryCommonOverlay").style.left=obtainPostitionX(obj)-165+"px";
document.getElementById("categoryCommonOverlay").style.top=obtainPostitionY(obj)+30+"px";
}else{
document.getElementById("categoryCommonOverlay").style.display="none";
}
}
function showCategorySearchOverlay(obj){
if(document.getElementById("categorySearchOverlay").style.display=="none"){
document.getElementById("categorySearchOverlay").style.display="inline";
document.getElementById("categorySearchOverlay").style.left=obtainPostitionX(obj)-0+"px";
document.getElementById("categorySearchOverlay").style.top=obtainPostitionY(obj)+25+"px";
overlayHackLayerForIE("categorySearchOverlay",document.getElementById("categorySearchOverlay"));
document.getElementById("categorySearchOverlay").style.zIndex=parseInt(document.getElementById("categorySearchOverlay").style.zIndex)+10;
}else{
document.getElementById("categorySearchOverlay").style.display="none";
dissolveOverlayHackForIE();
}
}
function getElementsByClassName(_13d,tag,_13f){
var _140=(tag=="*"&&_13d.all)?_13d.all:_13d.getElementsByTagName(tag);
var _141=new Array();
var _13f=_13f.replace(/\-/g,"\\-");
var _142=new RegExp("(^|\\s)"+_13f+"(\\s|$)");
for(var i=0;i<_140.length;i++){
var _144=_140[i];
if(_142.test(_144.className)){
_141.push(_144);
}
}
return _141;
}
function getCategories(_145){
var _146=getChilds(completeCategoryTree,1);
var op="";
for(var i=0;i<_146.length;i++){
op+="<option label=\""+_146[i][1]+"\" title = \""+_146[i][1]+"\" value=\""+_146[i][0]+"\">"+_146[i][1]+"</option>";
op+=getChildString(_146[i][0]);
}
if(!_145){
document.getElementById("c_categories_combo").innerHTML="<select name=\"c_categories[]\" id=\"c_categories\" size=\"10\" multiple ifvalidate=\"validateCatCombo\" minlength=\"1\" maxlength=\"10\" tip=\"course_categories\">"+op+"</select>";
}else{
document.getElementById("categoryPlace").innerHTML="<select class=\"normaltxt_11p_blk_verdana\" name=\"board_id\" id=\"board_id\" style=\"width:296px;\" tip=\"course_categories\" >"+op+"</select>";
}
}
function getChildString(_149){
var _14a="";
var _14b=getChilds(completeCategoryTree,_149);
var _14c="";
for(var i=0;i<_14b.length;i++){
var str=_14b[i][1];
if(str.substring(0,5)!="Other"){
_14a+="<option value=\""+_14b[i][0]+"\" title = \""+_14b[i][1]+"\">&nbsp;&nbsp;&nbsp;&nbsp;"+_14b[i][1]+"</option>";
}else{
_14c="<option value=\""+_14b[i][0]+"\" title = \""+_14b[i][1]+"\">&nbsp;&nbsp;&nbsp;&nbsp;"+_14b[i][1]+"</option>";
}
}
_14a+=_14c;
return _14a;
}
function getChilds(_14f,_150){
var arr=new Array();
for(catId in _14f){
if(_14f[catId][1]==_150){
arr.push(new Array(catId,_14f[catId][0]));
}
}
return arr;
}
function textKey(_152){
supportsKeys=true;
calcCharLeft(_152);
}
function calcCharLeft(_153){
clipped=false;
lenUSig=0;
maxLength=1000;
var _154;
var _155=trim(_153.value);
if(_155.length>maxLength){
_153.value=_155.substring(0,maxLength);
_154=maxLength;
clipped=true;
}else{
_154=_155.length;
}
document.getElementById(_153.id+"_counter").innerHTML=_154;
return clipped;
}
function addOnFocusToopTip(_156){
for(var _157=0;_157<_156.elements.length;_157++){
var _158=_156.elements[_157];
if(_158.getAttribute("tip")){
_158.onfocus=showTip;
}
}
}
function getEducationLevels(_159){
var _15a="Education";
var _15b=getXMLHTTPObject();
_15b.onreadystatechange=function(){
if(_15b.readyState==4){
if(trim(_15b.responseText)!=""){
var _15c=eval("eval("+_15b.responseText+")");
var Edu=_15c.results;
document.getElementById(_159).innerHTML="";
var _15e=document.createElement("option");
_15e.value="";
_15e.innerHTML="Select";
document.getElementById(_159).appendChild(_15e);
var _15e=document.createElement("option");
_15e.value="School";
_15e.innerHTML="School Student";
document.getElementById(_159).appendChild(_15e);
for(i=0;i<Edu.length;i++){
var _15e=document.createElement("option");
_15e.value=Edu[i].EducationId;
_15e.innerHTML=Edu[i].options;
document.getElementById(_159).appendChild(_15e);
}
}
}
};
url="/user/Userregistration/EducationLevel"+"/"+_15a;
_15b.open("POST",url,true);
_15b.send(null);
}
function addOnBlurValidate(_15f){
for(var _160=0;_160<_15f.elements.length;_160++){
var _161=_15f.elements[_160];
if(_161.getAttribute("validate")||_161.getAttribute("blurMethod")){
_161.onblur=function(){
hidetip();
if(this.getAttribute("blurMethod")){
eval(this.getAttribute("blurMethod"));
}
if(this.getAttribute("validate")){
var _162=this.getAttribute("validate");
var _163=trim(this.value);
this.value=_163;
_163=_163.replace(/[(\n)\r\t\"\'\\]/g," ");
var _164=this.getAttribute("maxlength");
var _165=this.getAttribute("minlength");
var _166=this.getAttribute("caption");
document.getElementById(this.id+"_error").parentNode.style.display="none";
document.getElementById(this.id+"_error").innerHTML="";
if(!checkRequired(this)){
return true;
}
var _167=_162+"(\""+_163+"\", \""+_166+"\", "+_164+", "+_165+")";
var _168=eval(_167);
if(_168!==true){
document.getElementById(this.id+"_error").parentNode.style.display="inline";
document.getElementById(this.id+"_error").innerHTML=_168;
returnFlag=false;
}else{
document.getElementById(this.id+"_error").parentNode.style.display="none";
document.getElementById(this.id+"_error").innerHTML="";
}
if(checkProfanity(this,_166)){
return true;
}
}
};
}else{
_161.onblur=function(){
hidetip();
var _169=this.getAttribute("caption");
try{
document.getElementById(this.id+"_error").parentNode.style.display="none";
var _16a=this.getAttribute("maxlength");
var _16b=this.getAttribute("minlength");
_16b=_16b==null?0:_16b;
_16a=_16a==null?0:_16a;
if(((this.value.length>_16a||this.value.length<_16b)&&(this.value.length!=0)&&(_16a!=0))&&(this.type=="text"||this.type=="textarea")){
document.getElementById(this.id+"_error").parentNode.style.display="inline";
document.getElementById(this.id+"_error").innerHTML="Please fill the "+_169+" within the range of "+_16b+" to "+_16a+" characters.";
return true;
}else{
document.getElementById(this.id+"_error").parentNode.style.display="none";
}
}
catch(e){
}
if(checkProfanity(this,_169)){
return true;
}
};
}
}
}
function checkRequired(_16c){
var _16d;
if(_16c.getAttribute("required")){
_16d=true;
}else{
if(_16c.value!=""){
_16d=true;
}else{
_16d=false;
}
}
return _16d;
}
var floatingTooltip={"userLevel":"Click on the image to know about Shiksha member level system.","saveSearchStatus":"If a Search Alert is ON then user will receive a daily email containing search query results"};
function createTooltipDiv(str){
if(!document.getElementById("flotingTooltip")){
var _16f=document.createElement("div");
_16f.setAttribute("id","flotingTooltip");
_16f.className="user-tooltip";
_16f.style.display="none";
_16f.innerHTML=floatingTooltip[str];
document.getElementsByTagName("body")[0].appendChild(_16f);
}else{
document.getElementById("flotingTooltip").innerHTML=floatingTooltip[str];
}
return document.getElementById("flotingTooltip");
}
function showHelp(e,_171){
var _172=_171.getAttribute("ftip");
createTooltipDiv(_172);
var help=document.getElementById("flotingTooltip");
var _174=e.pageX?pageXOffset+e.clientX-5:document.body.scrollLeft+e.clientX-5;
help.style.left=_174+"px";
var _175=e.pageY?pageYOffset+e.clientY+15:document.body.scrollTop+e.clientY+15;
help.style.top=_175+"px";
help.style.display="block";
}
function hideHelp(){
document.getElementById("flotingTooltip").style.display="none";
}
var arrTabsText={"course":["Course like B-Tech, MBA","Location like Delhi","Courses","course"],"college":["Colleges like IIT, IIM","Location like Delhi","Colleges","institute"]};
function selectSearchTab(tab,_177){
document.getElementById("searchType").value=arrTabsText[_177][3];
document.getElementById("searchButton").innerHTML="Search "+arrTabsText[_177][2];
document.getElementById("searchButtonMain").style.width="130px";
var tabs=document.getElementById("navigationUL").getElementsByTagName("li");
for(var i=0;i<tabs.length;i++){
tabs[i].id="";
}
tab.id="selected";
document.getElementById("keyword").className="inpKeyword";
document.getElementById("location").className="inpLocation";
document.getElementById("searchInputs").className="bgInputBox float_L courseInputWidth";
document.getElementById("searchhelptext").className="searchHelpText darkgray courseInputWidth";
document.getElementById("textforkeyword").innerHTML=arrTabsText[_177][0];
document.getElementById("textforlocation").className="float_L";
document.getElementById("textforlocation").innerHTML=arrTabsText[_177][1];
document.getElementById("searchButtonForTitle").title="";
}
function showInviteFriends(){
window.location="/inviteFriends/upload";
}
function stripHtmlTags(str){
var tags=/(<([^>]+)>)/ig;
plainstr=str.replace(tags,"");
return plainstr;
}
function checkHtmlTags(str){
var tags=/(<([^>]+)>)/ig;
return tags.test(str);
}
function getSmallImage(str){
if(trim(str)==""){
return (str);
}
if(str.indexOf("_s")!=-1){
return (str);
}
if(str.indexOf("_m")!=-1){
str=str.replace("_m","_s");
return (str);
}
if((str.length-str.lastIndexOf("."))>5){
str=str+"_s";
return (str);
}
var _17f=str.substring(0,(str.lastIndexOf(".")));
var _180=str.substring((str.lastIndexOf(".")+1),str.length);
return (_17f+"_s."+_180);
}
function getMediumImage(str){
if(trim(str)==""){
return (str);
}
if(str.indexOf("_m")!=-1){
return (str);
}
if(str.indexOf("_m")!=-1){
str=str.replace("_s","_m");
return (str);
}
if((str.length-str.lastIndexOf("."))>5){
str=str+"_m";
return (str);
}
var _182=str.substring(0,(str.lastIndexOf(".")));
var _183=str.substring((str.lastIndexOf(".")+1),str.length);
return (_182+"_m."+_183);
}
function setSearchText(obj){
var loc=obj.getAttribute("url");
writeSearchCookie();
location.replace(loc);
}
function validateSearch(_186,_187){
var msg="keyword";
var _189=document.getElementById("keyword").value;
_189=trim(_189);
var _18a=document.getElementById("location").value;
_18a=trim(_18a);
if(trim(_189)==""){
alert("Enter "+msg+" to search");
return false;
}
if(!(_187)){
document.getElementById("subLocation").value="-1";
}
document.getElementById("subCategory").value="-1";
document.getElementById("subType").value="0";
document.getElementById("startOffSetSearch").value="0";
document.getElementById("location").value="";
document.getElementById("cityId").value="-1";
if(!(_186)){
document.getElementById("searchType").value="";
}
writeSearchCookie();
return true;
}
function showContactus(){
var _18b=450;
var _18c=250;
var _18d="&nbsp; Contact Us";
var _18e="contactUs";
var _18f=document.getElementById(_18e).innerHTML;
document.getElementById(_18e).innerHTML="";
overlayContent=_18f;
overlayParent=document.getElementById(_18e);
showOverlay(_18b,_18c,_18d,overlayContent);
return false;
}
function showFeedBack(){
var _190=450;
var _191=250;
var _192="<span class=\"OrgangeFont\">&nbsp; Submit Feedback</span>";
var _193="feedback";
if(typeof (Ajax)=="undefined"){
var js=document.createElement("script");
js.setAttribute("type","text/javascript");
js.setAttribute("src","/public/js/prototype.js");
js.setAttribute("defer","defer");
document.getElementsByTagName("body")[0].appendChild(js);
}
document.getElementById("userFeedbackEmail_error").innerHTML="";
document.getElementById("feedBackContent_error").innerHTML="";
document.getElementById("userFeedbackEmail").value="";
document.getElementById("feedBackContent").value="";
var _195=document.getElementById(_193).innerHTML;
document.getElementById(_193).innerHTML="";
overlayContent=_195;
overlayParent=document.getElementById(_193);
showOverlay(_190,_191,_192,overlayContent);
reloadCaptcha("feedbackCaptcha","feedbackSecurityCode");
if(document.getElementById("userFeedbackEmail")){
document.getElementById("userFeedbackEmail").focus();
}
return false;
}
function hideFeedBackOverlay(_196){
var _197=eval("eval("+_196+")");
if(_197.captchaResult==0){
reloadCaptcha("feedbackCaptcha","feedbackSecurityCode");
document.getElementById("feedbackSecCode_error").parentNode.style.display="inline";
document.getElementById("feedbackSecCode_error").innerHTML="Please type in the characters as you see in the picture.";
}else{
if(_197.feedBackResult=="true"){
hideOverlay();
}
}
}
function showDataLoader(_198){
var _199=obtainPostitionX(_198)+(_198.offsetWidth/2)-20;
var _19a=obtainPostitionY(_198)+(_198.offsetHeight/2)-20;
var _19b=document.getElementById("dataLoaderPanel");
_19b.style.display="inline";
_19b.style.left=_199+"px";
_19b.style.top=_19a+"px";
_198.innerHTML="";
}
function hideDataLoader(_19c){
var _19d=document.getElementById("dataLoaderPanel");
_19d.style.display="none";
}
function getSmString(str,len){
if(str.length>len){
return str.substring(0,len-3)+"...";
}else{
return str;
}
}
function getCatCrumbsById(_1a0,_1a1,_1a2){
var _1a3="";
var _1a4=new Array();
var i=0;
while((completeCategoryTree[_1a1])&&(completeCategoryTree[_1a1][1])){
_1a4[i]=new Array();
_1a4[i][0]=completeCategoryTree[_1a1][0];
_1a4[i][1]=_1a1;
_1a1=completeCategoryTree[_1a1][1];
i++;
}
_1a4.reverse();
for(var i=1;i<(_1a4.length);i++){
if(i!=_1a4.length-1){
_1a3+=_1a4[i][0];
}else{
_1a3+="-"+_1a4[i][0];
}
}
document.getElementById(_1a2).value=_1a3;
return _1a3;
}
function base64_decode(data){
var b64="ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/=";
var o1,o2,o3,h1,h2,h3,h4,bits,i=0,enc="";
do{
h1=b64.indexOf(data.charAt(i++));
h2=b64.indexOf(data.charAt(i++));
h3=b64.indexOf(data.charAt(i++));
h4=b64.indexOf(data.charAt(i++));
bits=h1<<18|h2<<12|h3<<6|h4;
o1=bits>>16&255;
o2=bits>>8&255;
o3=bits&255;
if(h3==64){
enc+=String.fromCharCode(o1);
}else{
if(h4==64){
enc+=String.fromCharCode(o1,o2);
}else{
enc+=String.fromCharCode(o1,o2,o3);
}
}
}while(i<data.length);
return enc;
}
function ReportAbuseMessage(_1b2,_1b3){
document.getElementById("MsgId").value=_1b3;
paraString="categoryId="+_1b2+"&msgId="+_1b3;
var url="/messageBoard/MsgBoard/reportAbuse";
new Ajax.Request(url,{method:"post",parameters:(paraString),onSuccess:reportMessageAbuseResult});
var _1b5=getXMLHTTPObject();
return false;
}
function reportMessageAbuseResult(res){
res=eval("eval("+res.responseText+")");
var _1b7=document.getElementById("MsgId").value;
if(res.result==1){
document.getElementById("Message_"+_1b7).innerHTML="Reported Abuse";
document.getElementById("Message_"+_1b7).style.display="";
}
return false;
}
function reportAbuseListing(type,id){
var url="/listing/Listing/reportAbuse";
new Ajax.Request(url,{method:"post",parameters:("listing_type="+type+"&type_id="+id),onSuccess:showResponseForReportAbuseListing});
}
function showResponseForReportAbuseListing(_1bb){
if(_1bb.responseText==1){
document.getElementById("reportAbuseListing").innerHTML="Reported Abuse";
}
}
function reportAbuseEvent(_1bc,_1bd){
if(!_1bc||_1bc==""){
return false;
}
var _1be=getXMLHTTPObject();
_1be.onreadystatechange=function(){
if(_1be.readyState==4){
var _1bf=_1be.responseText;
if(_1bf==1){
if(!_1bd){
_1bd="eventOperationResponse";
}
document.getElementById(_1bd).innerHTML="<b>Event is reported for abuse.</b>";
}
}
};
var url="/events/Events/reportAbuseEvent/"+_1bc;
_1be.open("POST",url,true);
setXHRHeaders(_1be);
_1be.send(null);
}
function htmlspecialchars(str){
return str.replace(/&/g,"&amp;").replace(/</g,"&lt;").replace(/>/g,"&gt;").replace(/'/g,"&#039;").replace(/"/g,"&quot;");
}
function writeSearchCookie(){
var _1c2=htmlspecialchars(document.getElementById("keyword").value);
var _1c3=htmlspecialchars(document.getElementById("location").value);
setCookie("searchKeyword",_1c2);
setCookie("searchLocation",_1c3);
}
function createCategoryDropDownForParents(_1c4,_1c5,_1c6,_1c7){
if(typeof (_1c7)=="undefined"){
_1c7=false;
}
var op="<option value=\"1\" selected>All Categories</option>";
for(catId in completeCategoryTree){
if(completeCategoryTree[catId][1]==1){
op+="<option value=\""+catId+"\">"+completeCategoryTree[catId][0]+"</option>";
}
}
if(_1c7){
document.getElementById(_1c4).innerHTML="<select class=\"normaltxt_11p_blk_verdana\" name=\""+_1c6+"\" id=\""+_1c5+"\" onchange=\"parentCategoryChanged(this.value)\" style=\"width:296px;\" tip=\"course_categories\" >"+op+"</select>";
}else{
document.getElementById(_1c4).innerHTML="<select class=\"normaltxt_11p_blk_verdana\" name=\""+_1c6+"\" id=\""+_1c5+"\" style=\"width:296px;\" tip=\"course_categories\" >"+op+"</select>";
}
}
function popitup(url){
newwindow=window.open(url,"name","height=600,width=600, scrollbars=yes, top=50, left=200");
if(window.focus){
newwindow.focus();
}
return false;
}
function disableDatesTill(_1ca,_1cb){
var _1cc=_1cb.split("-");
var _1cd=_1cc[0];
var _1ce=_1cc[1]%13;
var _1cf=_1cc[2]%32;
var _1d0=Date.UTC(_1cd,_1ce-1,_1cf);
var _1d1=24*60*60*1000;
yesterdayUTC=_1d0-_1d1;
var _1d2=new Date(yesterdayUTC);
var _1d3=_1d2.getDate();
var _1d4=_1d2.getMonth()+1;
var _1d5=_1d2.getFullYear();
var _1d6=_1d4+"/"+_1d3+"/"+_1d5;
_1ca.addDisabledDates(null,_1d6);
}
function searchInSubProperty(_1d7,_1d8,_1d9){
if(trim(_1d7)==""){
return false;
}
document.getElementById("keyword").value=_1d7;
document.getElementById("searchType").value=_1d8;
if(_1d8=="foreign"){
if(!(_1d9==1)){
document.getElementById("subLocation").value=_1d9;
validateSearch(1,1);
}else{
validateSearch(1,0);
}
}else{
if(_1d8=="Country"){
document.getElementById("subLocation").value=_1d9;
document.getElementById("searchType").value="";
validateSearch(0,1);
}else{
if(_1d8=="Category"){
document.getElementById("cat_id").value=_1d9;
}
validateSearch(1,0);
}
}
document.getElementById("searchForm").submit();
}
function showSearchMailOverlay(type,Id,url){
if(!isUserLoggedIn){
showuserLoginOverLay("search");
return false;
}
document.getElementById("listingTypeForMail").value=type;
document.getElementById("listingIdForMail").value=Id;
document.getElementById("listingUrlForMail").value=url;
var _1dd=350;
var _1de=window.screen.height/2;
var _1df="Send Mail";
var _1e0=$("sendSearchMail").innerHTML;
$("sendSearchMail").innerHTML="";
overlayContent=_1e0;
overlayParent=$("sendSearchMail");
showOverlay(_1dd,_1de,_1df,overlayContent);
$("searchEmailAddr").focus();
}
function showSearchSmsOverlay(type,Id,url){
if(!isUserLoggedIn){
showuserLoginOverLay("search");
return false;
}
document.getElementById("listingTypeForSms").value=type;
document.getElementById("listingIdForSms").value=Id;
document.getElementById("listingUrlForSms").value=Id;
var _1e4=350;
var _1e5=window.screen.height/2;
var _1e6="Send SMS";
var _1e7=$("sendSearchSms").innerHTML;
$("sendSearchSms").innerHTML="";
overlayContent=_1e7;
overlayParent=$("sendSearchSms");
showOverlay(_1e4,_1e5,_1e6,overlayContent);
$("searchMobileNumber").focus();
}
function commonShowConfirmMessage(str){
document.getElementById("dim_bg").style.height=document.body.offsetHeight+"px";
document.getElementById("dim_bg").style.display="inline";
setNoScroll();
var divX=screen.width/2-document.getElementById("addRequestOverlay").offsetWidth/2-100;
var divY=screen.height/2-document.getElementById("addRequestOverlay").offsetHeight/2-170+document.documentElement.scrollTop;
document.getElementById("addRequestOverlay").style.left=divX+"px";
document.getElementById("addRequestOverlay").style.top=divY+"px";
document.getElementById("responseforadd").innerHTML=str;
document.getElementById("responseforadd").parentNode.style.display="";
document.getElementById("addRequestOverlay").style.display="";
}
function showReportChangeOverlay(){
var _1eb=550;
var _1ec=window.screen.height/2;
var _1ed="Report Changes";
if(document.getElementById("repChangeEmail_error")){
document.getElementById("repChangeEmail_error").innerHTML="";
}
document.getElementById("comment_error").innerHTML="";
var _1ee=document.getElementById("repChange").innerHTML;
overlayParent=document.getElementById("repChange");
showOverlay(_1eb,_1ec,_1ed,_1ee);
}
function validateReportChange(_1ef){
var _1f0=false;
if(_1ef.repChangeEmail){
_1f0=validateStr(_1ef.repChangeEmail.value,"email",100,3);
if(_1f0==true){
var _1f0=validateEmail(trim(_1ef.repChangeEmail.value),"email",100,3);
}
if(_1f0!=true){
document.getElementById("repChangeEmail_error").parentNode.style.display="inline";
document.getElementById("repChangeEmail_error").innerHTML=_1f0;
return false;
}
}
_1f0=validateStr(_1ef.comment.value,"comments",1000,3);
if(_1f0!=true){
document.getElementById("comment_error").parentNode.style.display="inline";
document.getElementById("comment_error").innerHTML=_1f0;
return false;
}
return true;
}

