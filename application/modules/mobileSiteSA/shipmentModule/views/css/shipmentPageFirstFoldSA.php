<style>
    *{margin: 0 auto;padding:0;-webkit-box-sizing: border-box;-moz-box-sizing: border-box;box-sizing: border-box;font-family: 'Open Sans',sans-serif;}
    .dhl-wrap{width: 100%;background-color: #fff; padding-bottom: 20px;}
    .dhl-bg{position: relative;height: 199px;background:#ccc url('<?php echo IMGURL_SECURE?>/public/mobileSA/images/dhl-bg1.jpg')no-repeat;overflow:hidden;background-size: cover}
    .dhl-btns{margin: 15px 0px 0px}
    .dhl-steps{padding:30px 20px; background-color:#fff;}
    .dhl-txt{text-align: center;font-size: 20px;color: #333;padding-bottom: 12px;font-weight: 600;position: relative;}
    .dhl-txt:after{content: '';position: absolute;left: 0;right: 0;margin: 0 auto;width: 106px;bottom: 0;border:1px solid #f6c379;}
    .dhl-process:after, .dhl-process:before, .dhl-block:after, .dhl-block:before, .cntr-col:after, .cntr-col:before{content: '';display: table;}
    .dhl-process:before, .dhl-block:before, .cntr-col:before{clear: both}
    .dhl-process{position: relative;padding-bottom: 20px;}
    .dhl-timeline{margin: 30px 20px 0px;width: 90%;position: relative;}
    .dhl-timeline:before{content:'';left: 10px;position: absolute;height: 96%;width: 1px;background: #eee;top: 0px;}
    .dhl-box{margin-bottom: 40px;position: relative;}
    .dhl-timeline > .dhl-box:last-child{margin-bottom: 0px}
    .dhl-icon{left: 0px;/* background: #ccc; */width: 70px;height: 70px;position: absolute;top: 0px;overflow: hidden;margin-left: -23px;border-radius: 50%;}
    .dhl-content{margin-left: 63px;top:3px;position: relative;height: 63px; line-height: 16px;}
    .dhl-content > h3{font-size: 13px;color: #333;font-weight: 600; margin-bottom: 5px;}
    .dhl-content > p{font-size: 11px;color: #666;}
    .dhl-sprite{background: url('<?php echo IMGURL_SECURE?>/public/mobileSA/images/dhl-steps1.png')no-repeat;display: inline-block;}
    .dhl-fill, .dhl-call, .dhl-pck, .dhl-track{background-position: -8px -18px;width: 70px;height: 70px;position: relative;display: block;top: -2px;}
    .dhl-call{background-position: -87px -18px;}
    .dhl-pck{background-position: -164px -18px;}
    .dhl-track{background-position: -240px -18px;}
    .text-col{position: relative;top:22%;text-align: center;z-index: 2;}
    .text-col > h1{font-size: 25px;color: #fff;font-weight: 600;margin-bottom: 10px;line-height: 30px}
    .text-col p{color: #fff;font-size: 14px; font-weight: 600;}
    .dhl-bg .shipmnt-shadow {background: linear-gradient(to bottom, rgba(0, 0, 0, 0) 0, rgba(0, 0, 0, 0.02) 49%, rgba(0, 0, 0, 0.83) 100%); position: absolute;  bottom: 0;  left: 0; right: 0; width: 100%; height: 100%;}
    .btn{height: 37px;text-align: center;line-height: 21px;text-decoration: none;font-weight: 600}
    a.btn-prime{background-color:var(--Primary-Orange);border: 1px solid var(--Primary-Orange);color: #fff;display: block;width: 75%;}
    a.btn-opt, a.btn-main{background-color: var(--Teal);color: #fff;font-size: 13px;padding: 7px 10px;}
    a.btn-main{background-color: var(--Primary-Orange);margin-right: 5px}

    .price-col{background-color: #f8f8f8;padding: 30px 10px 30px;}
    table.price-tble{width: 100%; margin: 25px auto 20px; background: #fff;}
    table.price-tble tr{width: 100%}
    table.price-tble tr th, table.price-tble tr td{border: 1px solid #e1e1e1; border-bottom: none; border-right: none; padding:10px 5px; color: #333; font-size: 13px;text-align: center;}
    table.price-tble tr th{font-weight: 600;font-size: 12px; line-height: 18px}
    table.price-tble tr:last-child td{border-bottom: 1px solid #e1e1e1;}
    table.price-tble tr td:last-child, table.price-tble tr th:last-child{border-right: 1px solid #e1e1e1;}
    @media screen and (orientation:landscape) {
        .dhl-content{top: 7px;}
    }
</style>
