<style type="text/css">
    #main-wrapper{width:100%;background:#f9f9f9;box-shadow:none}
    body{min-width:1200px;background:#f9f9f9}
    .srpMarginTop{margin:15px 0px 30px}
    .newSearch a:hover{text-decoration:none}
    .newSrpWrapper{margin:10px 0px 30px;width:100%;float:left}
    .ulpWrapper{width:880px;margin:0 auto}
    .newSearch{width:1180px;margin:0 auto;padding:25px 0px;font-family:'Open Sans',sans-serif;box-sizing:border-box;position:relative;}
    .searchTags{cursor:pointer;position:relative;font-size:14px;margin:5px 7px 5px 0px;color:#111;display:inline-block;padding:3px 24px 3px 8px;border-radius:2px;background-color:#ffffff;border:1px solid #ccc;}
    a.searchTags:hover{text-decoration:line-through;}
    .closeTag{width:20px;height:100%;display:inline-block;position:absolute;right:0;top:0;}
    .abroadSrp{display:block;margin:16px 0px;position:relative;}
    .srpHeading{font-size:24px;font-weight:400;}
    .srpHeading strong{font-weight:600;}
    #srpHeading > .srpHeading{font-size:22px;width:70%;font-weight:400;}
    .getSpace{margin:15px 0px 8px;position:relative;}
    .multiTags{font-size:0px;}
    .univRanking{margin-top:20px;font-size:14px;color:#666;height:19px}
    .resultsListing{width:880px;background:transparent;min-height:300px;float:right;}
    .holdList{display:block}
    .newTupleDiv{display:block;min-height:235px;padding:16px 16px 14px;margin-bottom:20px;border-radius:2px;box-shadow:0px 1px 4px rgba(37,37,37,0.2);background-color:#ffffff;}
    .picDiv{width:300px;height:200px;overflow:hidden;float:left;position:relative;border-radius:2px;}
    .clickIcons{position:absolute;bottom:5px;right:0px;padding:0 10px;z-index:11}
    .selfi_imgs{min-width:30px;height:23px;cursor:default;float:right;display:inline-block;margin-right:10px;font-size:12px;color:#fff;}
    .selectedDtls{float:right;width:530px;position:relative;}
    .nameDiv{margin-top:-4px}
    .titleOfClg{font-size:16px;font-weight:600;overflow:hidden;white-space:nowrap;text-overflow:ellipsis;color:#008489}
    .titleOfClg a{color:#008489;cursor:pointer;}
    .subPlace{font-size:12px;color:#666;line-height:20px;}
    .subPlace span{display:inline-block;color:#ccc;margin:0 5px;}
    .courseDiv{display:block;margin-top:16px;}
    .itCourse{font-size:14px;color:#111;font-weight:600;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;}
    .flexBox{margin:5px 0 0px;display:flex;flex-wrap:wrap;display:-webkit-box;display:-moz-box;display:-ms-flexbox;display:-webkit-flex;}
    .flexBox div{width:176px}
    .flexBox div:nth-child(4), .flexBox div:nth-child(5),.flexBox div:nth-child(6){margin-top:10px;}
    .flexDtls{font-size:12px;color:#666;line-height:19px;}
    .costDiv{font-size:14px;color:#111;}
    .costDiv a{font-size:12px;display:inline-block;cursor:pointer;}
    .flexBox span.mrExm{display:block;color:#008489;width:80px;cursor:pointer;}
    .moreSelections{display:block;margin-top:25px;}
    .moreSelections .setDiv{margin-top:9px;float:left;font-size:0px;}
    .moreSelections a.rateBtn, .moreSelections a.dwnLdBtn{padding:0px 12px;;height:34px;border-radius:2px;display:inline-block;color:#fff;line-height:34px;text-align:center;font-size:14px;font-weight:600;cursor:pointer;}
    .rateBtn{background-color:var(--Teal);margin-right:8px}
    .rateBtn:hover{text-decoration:none;background-color:var(--Hover-Teal);}
    .dwnLdBtn{background-color:var(--Primary-Orange);}
    .dwnLdBtn:hover{cursor:pointer;text-decoration:none;background:var(--Hover-Orange);}
    .cssChck input[type="checkbox"]{display:none}
    .cssChck label{cursor:pointer;font-size:14px;color:#111;position:relative;padding-left:23px}
    .cssChck label p{display:inline}
    .cssChck label:before{content:'';position:absolute;left:0;top:50%;width:16px;height:16px;border-radius:2px;background-color:#000000;border:1px solid #999999;background:#fff;margin-top:-8px;}
    .cssChck input[type="checkbox"]:not(:checked) + label:after{opacity:0;transform:scale(0);}
    .cssChck input[type="checkbox"]:checked + label:after{opacity:1;transform:scale(1);}
    .cssChck input[type="checkbox"]:not(checked) + label:after, .cssChck input[type="checkbox"]:checked + label:after{content:'';position:absolute;left:1px;top:1px;width:15px;height:16px;background:url(<?php echo IMGURL_SECURE?>/public/images/newsrp-sprite.png)no-repeat;background-position:-101px 1px}
    .cssChck input[type="checkbox"]:checked + label:before{border:1px solid #4573b1}
    .moreOptns{position:relative;margin:15px -16px 0px;padding:0px 16px 0px;width:-moz-available;width:-webkit-fill-available;}
    .maxData{width:100%;position:relative;padding:16px 0 0px;min-height:47px;}
    .carouselDiv{font-size:0;position:absolute;left:-16px;right:-16px;overflow:hidden;height:61px;top:1px;z-index:1;padding:6px 0px 0px;}
    .courseData{text-decoration:none;width:max-content;margin-top:7px;width:-moz-max-content}
    .courseData li{width:248px;white-space:nowrap;overflow:hidden;border:1px solid transparent;text-overflow:ellipsis;display:inline-block;border-radius:12px;margin:0px 10px 0px 0px;height:32px;line-height:30px;box-shadow:0 2px 2px 0 rgba(0,0,0,0.16), 0 0 0 1px rgba(0,0,0,0.08);background-color:#ffffff;}
    .courseData li:first-child{width:81px;margin-left:10px}
    .courseData li a{color:#111;font-size:12px;font-weight:600;cursor:pointer;padding:0 10px;display:block;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;}
    .courseData li.active{box-shadow:0 3px 8px 0 rgba(0,0,0,0.2), 0 0 0 1px rgba(0,0,0,0.08);border:1px solid var(--Teal);}
    .courseData li:hover{box-shadow:1.414px 1.414px 4px rgba(37,37,37,0.52);}
    .courseData li:hover a{text-decoration:none;}
    .courseData li.active:hover{box-shadow:0 3px 8px 0 rgba(0,0,0,0.2), 0 0 0 1px rgba(0,0,0,0.08);}
    .courseData li.active a{background:#f2f8ff;}
    .courseData li.dfltTxt{box-shadow:none;color:#666;font-size:12px;font-weight:600}
    .clickLeft, .clickRight{cursor:pointer;display:inline-block;position:absolute;top:0px;width:32px;height:60px;background-color:rgba(255,255,255,0.9490196078431372);}
    a.clickRight:after{content:'';background:url(<?php echo IMGURL_SECURE?>/public/images/newsrp-sprite.png)no-repeat;position:absolute;background-position:-139px 2px;left:-4px;height:100%;width:13%;z-index:1;}
    a.clickLeft:after{content:'';background:url(<?php echo IMGURL_SECURE?>/public/images/newsrp-sprite.png)no-repeat;position:absolute;background-position:-123px 2px;right:-4px;height:100%;width:13%;z-index:1;}
    .clickLeft{left:0px;}
    .clickRight{right:0px;}
    .cssChck{margin-right:3px;}
    .startSelect{position:relative;padding-left:27px;font-size:14px;color:#111;cursor:pointer;}
    .flt_right{float:right;}
    .lineNone{font-size:0px;}
    .dataDiv{position:relative;min-height:162px;overflow:hidden}
    .srpSprite{background:url(<?php echo IMGURL_SECURE?>/public/images/newsrp-sprite.png);display:inline-block;}
    .iStar{width:20px;height:20px;background-position:-31px -17px;position:absolute;left:4px;top:0px;}
    .startSelect:hover .iStar{background-position:-1px -38px}
    .startSelect.active .iStar, .startSelect.active:hover .iStar{background-position:-52px -17px}
    .clickRight .ia, .clickLeft .ia{width:16px;height:28px;position:absolute;background-position:-11px -11px;top:50%;left:0;right:0;margin:-16px auto 0px;}
    .clickLeft .ia{background-position:1px -11px;width:15px;}
    .srpSprite.ics{position:absolute;top:50%;width:14px;height:12px;background-position:0px 0px;display:block;margin:-7px auto 0px;left:0;right:0px;}
    .icamera, .ivideo{width:25px;height:20px;display:inline-block;background-position:-71px -18px;vertical-align:middle;margin-right:4px;}
    .ivideo{background-position:-94px -18px}
    .iminus, .iPlus{top:4px;background-position:-67px 0px;position:absolute;right:5px;width:15px;height:15px;cursor:pointer;}
    .iPlus{background-position:-52px 0}
    .iprev, .inext{width:12px;height:20px;left:0;position:absolute;background-position:-80px 3px;top:4px;}
    .inext{background-position:-89px 3px;left:auto;right:0;top:3px}
    .icArow{position:absolute;top:12px;right:10px;width:11px;height:10px;background-position:-40px 0;pointer-events:none;}
    .icLrw{position:absolute;right:7px;width:10px;top:21px;height:15px;background-position:-15px 1px;transition:all .2s;}
    .textAbs{position:absolute;left:0;top:-9px;background:#fff;color:#666;width:139px;padding-left:16px;font-size:12px;}
    .newTupleDiv.ulpDiv .clickLeft, .newTupleDiv.ulpDiv .clickRight{top:17px;height:59px;z-index:1}
    .newTupleDiv.ulpDiv .clickLeft{border-top-right-radius:2px;border-bottom-right-radius:2px;}
    .newTupleDiv.ulpDiv .clickRight{border-top-left-radius:2px;border-bottom-left-radius:2px;}
    .courseData.ulpData li{height:58px;box-shadow:0 2px 2px 0 rgba(0,0,0,0.16), 0 0 0 1px rgba(0,0,0,0.08);white-space:normal;text-overflow:initial;border-radius:2px;margin:5px 10px 5px 0;position:relative;width:258px;}
    .courseData.ulpData li div{display:table;width:100%;height:56px;text-align:left}
    .courseData.ulpData li a{border-radius:2px;white-space:normal;text-overflow:initial;line-height:20px;font-size:14px;padding:9px 20px 11px 10px;font-weight:400;vertical-align:middle;display:table-cell}
    .courseData.ulpData li:hover{box-shadow:0 3px 8px 0 rgba(0,0,0,0.2), 0 0 0 1px rgba(0,0,0,0.08);}
    .newTupleDiv.ulpDiv .moreOptns{margin:25px -16px 0px;}
    .newTupleDiv.ulpDiv .maxData{min-height:80px;}
    .newTupleDiv.ulpDiv .maxData .carouselDiv{height:auto;}
    .picDiv:after{content:'';position:absolute;bottom:0px;background:url(<?php echo IMGURL_SECURE?>/public/images/srpshadow.png)no-repeat;right:0;background-size:contain;height:83px;width:180px;right:0;}
    .titleOfClg a:hover, .univRanking a:hover{color:#008489;}
    .newSrpWrapper.srpZrp{height:460px;display:flex;justify-content:center;align-items:center;flex-direction:row;}
    .newSrpWrapper.srpZrp .srpHeading{text-align:center;}
    .courseData.ulpData li:first-child{margin-left:18px;}
    .picDiv.noShadow:after{background:transparent;height:0px;}
    .filterLeftside{display:none;width:280px;min-height:1000px;float:left;box-shadow:0px 1px 4px rgba(37,37,37,0.2);background-color:#ffffff;}
    .titleDiv{padding:15px;font-size:18px;color:#111;font-weight:600;position:relative;border-bottom:1px solid #e1e1e1;}
    .getClose{margin:0 10px;}
    .newAcrdn{padding:0;display:block;border-bottom:1px solid #e1e1e1;}
    .newAcrdn h2{padding:16px 5px;font-size:14px;font-weight:600;position:relative;cursor:pointer;}
    .custm__ico{position:absolute;transform:translate(-6px,0);margin-top:20px;right:4px;top:0px;}
    .custm__ico:after, .custm__ico:before{transition:all .25s ease-in-out;content:"";position:absolute;background-color:#999;width:2px;height:8px;}
    .newAcrdn.active .contentBlock{position:relative;top:-8px;padding:0 5px 0px;display:block;max-height:200px;min-height:55px;overflow-y:auto;}
    .newAcrdn.active .custm__ico:before{transform:translate(-2px,0) rotate(45deg);}
    .newAcrdn.active .custm__ico:after{transform:translate(2px,0) rotate(-45deg);}
    .animateloader, .animateloader1, .animateloader2{height:8px;margin-bottom:10px}
    .filterLeftside.animateFilter{display:block;}
    .filterLeftside.animateFilter .newAcrdn h2{margin-bottom:10px;}
    .animateloader1{width:80%;}
    .animateloader2{width:60%;margin-bottom:0px;}
    .animateloader, .animateloader1, .animateloader2{background:#e9e9e9;background-image:linear-gradient(to right, #e9e9e9 0%, #d8d8d8 20%, #e9e9e9 40%, #e9e9e9 100%);background-repeat:no-repeat;-webkit-animation-duration:1s;-webkit-animation-fill-mode:forwards;-webkit-animation-iteration-count:infinite;-webkit-animation-name:placeholderShimmer;-webkit-animation-timing-function:linear;background-size:801px 199px;}
    @-webkit-keyframes placeholderShimmer{0%{background-position:-468px 0;}
        100%{background-position:468px 0;}
    }
    @-moz-keyframes placeholderShimmer{0%{background-position:-468px 0;}
        100%{background-position:468px 0;}
    }
    .filtersDropdown{position:absolute;right:0;top:0;font-size:14px;font-weight:600;font-family:'Open Sans',sans-serif;}
    .filtersDropdown select{-webkit-appearance:none;-moz-appearance:none;border:1px solid #ccc;padding:6px 20px 6px 10px;width:195px;font-size:14px;outline:none;background:#fff;color:#111;font-weight:400;font-family:'Open Sans',sans-serif;margin-left:6px;cursor:pointer;}
    select:-moz-focusring{color:transparent;text-shadow:0 0 0 #000;}
    .filter_arw{pointer-events:none;position:absolute;right:17px;top:15px;z-index:11;}
    .filter_arw::after{content:'';position:absolute;width:0;height:0;border-style:solid;border-width:5px 4px 0 4px;border-color:#000000 transparent transparent;}
    div#heightCheck > span.viewMoreBtn{display:block;position:absolute;right:0px;bottom:4px;color:#008489;padding:6px 0px 6px 20px;background:linear-gradient(transparent 0% , rgb(249, 249, 249) 100%);font-size:14px;background:-moz-linear-gradient(left, rgba(249,249,249,0) 0%, rgba(249,249,249,0) 1%, rgba(249,249,249,0.75) 13%, rgba(249,249,249,1) 17%);background:-webkit-linear-gradient(left, rgba(249,249,249,0) 0%,rgba(249,249,249,0) 1%,rgba(249,249,249,0.75) 13%,rgba(249,249,249,1) 17%);background:linear-gradient(to right, rgba(249,249,249,0) 0%,rgba(249,249,249,0) 1%,rgba(249,249,249,0.75) 13%,rgba(249,249,249,1) 17%);cursor:pointer;z-index:1;}
    div#topFilters{position:relative;overflow:hidden;}
    div.filterTopMaxHeight{max-height:74px;}
    .hid{display:none!important;}

    .Annoncement-Box {margin-top: 14px;}
    .Annoncement-Box strong {color: #333;font-weight: 600;font-size: 12px;}
    .Announcement-section {background: #F7FBFF;padding: 12px;font-size: 12px;margin: 5px 0 0;border: 1px solid #DDECFF;color:#333;}
    .Announcement-section p:first-child{margin-bottom: 6px;}
</style>
