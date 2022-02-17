<script src="/public/js/owl-carousel/owl.carousel.js"></script>
<link rel="stylesheet" type="text/css" href="/public/css/owl-carousel/owl.carousel.css"></link>
<link rel="stylesheet" type="text/css" href="/public/mobile5/css/<?php echo getCSSWithVersion("owl.carousel.custom",'nationalMobile'); ?>"></link>
    <div id="demo">
        <div class="container">
        <a class="cross" onclick="$('#walkthroughHTML').hide();$('#wrapper').removeClass('of-hide').addClass('ui-page-active');">x</a>
            <div class="row">
                <div class="span12">

                    <div id="owl-demo" class="owl-carousel">
                        <div>
                            <table class="tour-tabl">
                                <tr>
                                    <td class="dta1 sk_slide0" rowspan="2" >
                                        <div class="sk_slide_data" style="">
        
          <div style="position: relative;">
          <h1><i class="sk-icons sk-star-icn"></i>My Shortlist</h1>
          <p>Shortlist to make an informed college decision. My Shortlist allows you to analyse your options in a single place</p>
          <div class="uibtns"> <a><i class="sk-icons sk-graph"></i></a> <a><i class="sk-icons sk-notes"></i></a> <a><i class="sk-icons sk-people"></i></a> 	    </div>
          </div>
          
          </div>
                                    </td>
                                </tr>
                            </table>
                        </div>
                        <div>
                            <table class="tour-tabl sk_slide2">
                                <tr>
                                    <td class="dta1"><i class="tour-img1 fadeInUp"></i>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="dta2">
                                        <div class="sk-mindata"> <i class="sk-icons sk-graph"></i>
                                            <h1>Find placement data</h1>
                                            <p>See where students end up after graduating</p>
                                            <a class="sk-btn" data-rel="back">Start Shortlisting</a>
                                        </div>
                                    </td>
                                </tr>
                            </table>
                        </div>
                        <div>
                            <table class="tour-tabl sk_slide2">
                                <tr>
                                    <td class="dta1"><i class="tour-img2 fadeInUp"></i>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="dta2">
                                        <div class="sk-mindata"> <i class="sk-icons sk-notes"></i>
                                            <h1>Read Reviews</h1>
                                            <p>See how alumni and current students rate their college</p>
                                            <a class="sk-btn" data-rel="back">Start Shortlisting</a>
                                        </div>
                                    </td>
                                </tr>
                            </table>
                        </div>
                        <div>
                            <table class="tour-tabl sk_slide2">
                                <tr>
                                    <td class="dta1"><i class="tour-img3 fadeInUp"></i>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="dta2">
                                        <div class="sk-mindata"> <i class="sk-icons sk-people"></i>
                                            <h1>Ask a Question</h1>
                                            <p>Get answers from experts and current students</p>
                                            <a class="sk-btn" data-rel="back">Start Shortlisting</a>
                                        </div>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

<style>
    #owl-demo .owl-item img {
        display: block;
        width: 100%;
        height: auto;
        -webkit-border-radius: 3px;
        -moz-border-radius: 3px;
        border-radius: 3px;
    }
    body{padding:0px; margin: 0px;}
</style>
<script type="text/javascript">
    var windwHeight=$(window).height();
    var boxHeight=$('#demo').height();
    $('.tour-tabl .sk_slide0 .sk_slide_data').css('height',windwHeight-123);
    $('.sk-mindata').css('min-height',windwHeight-314);
</script>
