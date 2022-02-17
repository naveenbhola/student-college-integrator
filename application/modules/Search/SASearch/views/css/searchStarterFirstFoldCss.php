<style type="text/css">
.abroadSearch{
  display: block;
  pointer-events: none;
  position: fixed;
  top: -100px;
  width: 100%;
  z-index: 3001;
  background-color: #fbfcfe;
  max-width: 100%;
  height: 100%;
  transition: transform .4s ease,opacity .4s ease;
  transform: scale(.5);
  opacity: 0;
  visibility: hidden;
  left:0;
}

.abroadSearch.remove{
  opacity: 0;
  transition: .3s all;
  transform: scale(1);
}
.abroadSearch.active{
  pointer-events: all;
    top: 0;
    transform: scale(1);
    opacity: 1;
    height: 100%;
    visibility: visible;
}
.searchContent{
   opacity: 0;
   transform: translateY(60px);
   height: 100%;
   transition: 1.1s all;
   margin-top: 180px;
}

.abroadSearch.active .searchContent{
  opacity: 1;
  transform: translateY(0);
}
.sugstrLocation{
  display: -webkit-box;
  display: -moz-box;
  display: -ms-flexbox;
  display: -webkit-flex;
  display: flex;
  flex-direction: row;
  -webkit-flex-flow: row wrap;
  width: 891px;
  height: 49px;
  border-radius: 4px;
  /* box-shadow: 0px 4px 6px rgba(0,0,0,0.67); */
  background-color: #ffffff;
  border: 1px solid #ccc;
  border-right: 1px solid transparent;
  margin: 0 auto;
}
.inputDiv{
  position: relative;
}
.inputDiv input[type="text"]{
  width: 100%;
  box-sizing: border-box;
  height: 46px;
  border-radius: 4px 0px 0px 4px;
  border: none;
  padding: 0 30px 0 15px;
  font-size: 14px;
  color: #111;
  font-family: 'Open Sans',sans-serif;
  outline: none;
  display: block;
}
.inputDiv input[type="text"]:disabled{
    background-color: #ffffff;
    color:#666;
    height: 47px;
}
.inputDiv #locInput:disabled{
    background-color: #f2f2f2;
    color:#666;
    height: 47px;
}
.inputDiv .locationAdd.disabled{
    background-color: #f2f2f2;
  }
.SearchBox-submitBtn {
    border-radius: 0;
    background: #f1a536;
    /* text-transform: uppercase; */
    color: #fff;
    border: none;
    font-size: 16px;
    font-weight: 700;
    outline: none;
    border-top-right-radius: 4px;
    border-bottom-right-radius: 4px;
    width: 117px;
    position: relative;
    top: -1px;
    height: 49px;
    cursor: pointer;
    right: 0px;
    border: 1px solid #f1a536;
    flex: 0 0 auto;
        text-shadow: 1px 1px 3px rgba(0,0,0,0.3)
}
.SearchBox-submitBtn:hover{
    background: #ea942d;
    text-decoration: none;
}

[type=submit], [type=reset], button, html [type=button] {
    -webkit-appearance: button;
    font-family: 'Open Sans',sans-serif;
}
[type=course] {
    -webkit-appearance: textfield;
    outline-offset: -2px;
}

.inputDiv input::-webkit-input-placeholder{
  color: #666;
}
.sugstrLocation .inputDiv:nth-child(1) {
  width: 455px;
}
.sugstrLocation .inputDiv:nth-child(2) {
    border-left: none;
    width: 317px;
}
.sugstrLocation .inputDiv:nth-child(2) input[type="text"]{
  border-radius: 0px;
}
input[type="text"]:focus{
  /* border: 1px solid #0000ff;
  border-width: thin; */
}
.locationAdd{
    /* border-bottom:1px solid #ccc; */
    border-left: 1px solid #ccc;
    /* border-right: 1px solid #ccc; */
}
.searchSprite, .locationAdd.disabled:before, .locationAdd:before, .closeBox{
  background: url('public/images/search-icons.png');
  display: inline-block;
}
.close-i{
  background-position: 0 0;
  width: 22px;
  height: 20px;
  cursor: pointer;
  position: absolute;
  right: 100px;
  top: 70px;
}
.rmvSml-icn{
    background-position: 56px 2px;
    width: 20px;
    height: 20px;
    cursor: pointer;
}
.locationAdd.disabled:before, .locationAdd:before{
   content: '';
   background-position: -37px 0px;
   width: 17px;
   height: 19px;
   position: absolute;
   left: 12px;
   top: 15px;
}
.locationAdd:before {
    background-position: -21px 0px;
}
.locationAdd{
  display: block;
  background: #fff;
  position: absolute;
  top: 0;
  height: auto;
  min-height: 47px;
  padding: 0 10px 0 35px;
  width: 100%;
}
.locationAdd.disabled{
  background-color: #efefef;
  border-bottom:none;
}

.loc_placeholder{
  font-size: 14px;
  line-height: 47px;
  font-weight: 400;
  color: #111;
  background-color: transparent;
  padding: 0;
}
.locationAdd.disabled .loc_placeholder{
  color: #b1b1b1;
}
.addPlaces li input[type=text] {
    padding: 0 7px 0 0;
}
</style>