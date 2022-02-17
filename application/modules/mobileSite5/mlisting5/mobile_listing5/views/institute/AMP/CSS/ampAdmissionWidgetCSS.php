<style>
:root, html {
    font-size: 16px;
    line-height: 1.6;
}
* {
    margin: 0;
    padding: 0;
    outline: 0;
    box-sizing: border-box;
    font-size: 13px;
    font-family: 'Open Sans',Sans-serif;
    line-height: 18px;
}

a {
    text-decoration: none;
    color: #008489
}

body {
    font-family: "Open Sans";
    margin: 0px;
}

.rich-txt-container ul {
    list-style: inherit;
    margin-left: 20px
}

.rich-txt-container ul>li {
    font-size: 12px;
    margin-bottom: 5px!important
}

.rich-txt-container ul>li:last-child {
    margin-bottom: 0
}

.rich-txt-container ol>li,
.rich-txt-container ul>li {
    color: #666;
    font-size: 14px
}

.rich-txt-container ol>li>strong,
.rich-txt-container ul>li>strong {
    font-weight: 600
}

.rich-txt-container ol {
    margin-left: 20px
}

.quote-box {
    border: 1px solid #eee;
    padding: 10px;
    background: #f8f8f8;
    margin: 5px 0!important;
    width: 100%;
    -webkit-box-sizing: border-box;
    -moz-box-sizing: border-box;
    box-sizing: border-box;
    float: none!important
}

.quote-box{ quotes: "“" "”" "‘" "’";}

.quote-box:before {
    content: open-quote;
    font: 700 22px Georgia;
    color: #000
}

.quote-box:after {
    content: close-quote;
    font: 700 22px Georgia;
    color: #000
}

.detail-text {
    font-size: 14px;
    color: #666
}

.photo-widget .figure img {
    width: 100%!important
}

.photo-widget {
    width: 100%!important
}

.photo-widget .figure {
    width: 100%!important;
    float: left
}

.rich-txt-container table {
    width: 100%!important;
    border-collapse: collapse;
    border-spacing: 0;
    font-family: inherit
}

.rich-txt-container table {
    background-color: #fff!important
}

.rich-txt-container caption {
    padding-top: 8px!important;
    padding-bottom: 8px!important;
    color: #000!important;
    text-align: left!important
}

.rich-txt-container th {
    text-align: left!important
}

.rich-txt-container table {
    width: 100%!important;
    max-width: 100%!important;
    margin-bottom: 17px!important;
    border: 1px solid #e6e5e5!important;
    border-collapse: collapse!important;
    background-color: transparent!important
}

.rich-txt-container table>tbody>tr>td,
.rich-txt-container table>tbody>tr>th,
.rich-txt-container table>tfoot>tr>td,
.rich-txt-container table>tfoot>tr>th,
.rich-txt-container table>thead>tr>td,
.rich-txt-container table>thead>tr>th {
    padding: 4px!important;
    line-height: 1.42857!important;
    vertical-align: middle!important;
    border-top: 1px solid #e6e5e5!important
}

.rich-txt-container table .thead-default th {
    background: #f1f1f1!important
}

.rich-txt-container table>thead>tr>th {
    vertical-align: bottom!important;
    border-bottom: 2px solid #e6e5e5!important
}

.rich-txt-container table>caption+thead>tr:first-child>td,
.rich-txt-container table>caption+thead>tr:first-child>th,
.rich-txt-container table>colgroup+thead>tr:first-child>td,
.rich-txt-container table>colgroup+thead>tr:first-child>th,
.rich-txt-container table>thead:first-child>tr:first-child>td,
.rich-txt-container table>thead:first-child>tr:first-child>th {
    border-top: 0!important
}

.rich-txt-container table>tbody+tbody {
    border-top: 2px solid #e6e5e5!important
}

.rich-txt-container table {
    background-color: #fff!important
}

.rich-txt-container table {
    border: 1px solid #e6e5e5!important
}

.rich-txt-container table>tbody>tr>td,
.rich-txt-container table>tbody>tr>th,
.rich-txt-container table>tfoot>tr>td,
.rich-txt-container table>tfoot>tr>th,
.rich-txt-container table>thead>tr>td,
.rich-txt-container table>thead>tr>th {
    border: 1px solid #e6e5e5!important;
    background: 0 0
}

.rich-txt-container table>thead>tr>td,
.rich-txt-container table>thead>tr>th {
    border-bottom-width: 2px!important
}

.rich-txt-container table>tbody>tr:nth-of-type(even) {
    background-color: rgba(241, 241, 241, .5)!important
}

.rich-txt-container table>tbody>tr>td>p {
    font-size: 12px;
    text-align: left
}

.m-5btm{
    margin-bottom: 5px;
}
.pos-rl{
    position: relative;
}
.color-3{
    color:#000;
}
.f16{
    font-size: 1rem;
}
.heading-gap {
    margin: 15px 0px 5px 10px;
}
.font-w6{font-weight: 600;}
.card-cmn {
    border: 1px solid;
    border-color: #e5e6e9 #dfe0e4 #e6e5e5 #dfe0e4;
    padding: 10px 10px 20px 10px;
    border-radius: 2px;
}
.color-w{background: #fff;}

.m-btm{margin-bottom: 10px}
.color-b{color:#008489;}
.admission-div{height: 150px;overflow: hidden;}
.gradient-col{display: block;text-align: center;height: 85px;position: absolute;width: 100%;left: 0;bottom: 0;    background: linear-gradient(to bottom, rgba(255,255,255,0) -25%,rgba(255,255,255,0.09) 16%,rgba(255,255,255,1) 44%,rgba(255,255,255,1) 18%,rgba(255,255,255,1) -1%,rgba(255,255,255,1) 58%,rgba(255,255,255,1) 100%);
      background: -webkit-linear-gradient(to bottom, rgba(255,255,255,0) -25%,rgba(255,255,255,0.09) 16%,rgba(255,255,255,1) 44%,rgba(255,255,255,1) 18%,rgba(255,255,255,1) -1%,rgba(255,255,255,1) 58%,rgba(255,255,255,1) 100%);
     background: -moz-linear-gradient(to bottom, rgba(255,255,255,0) -25%,rgba(255,255,255,0.09) 16%,rgba(255,255,255,1) 44%,rgba(255,255,255,1) 18%,rgba(255,255,255,1) -1%,rgba(255,255,255,1) 58%,rgba(255,255,255,1) 100%);
}
.gradient-col > .btn-tertiary{ position: absolute;
    top: 57%;
    left: 50%;
    margin-left: -39px;}
    .forward-icon{
            background: url(../images/clg-sprite_v1.svg);
    background-position: -324px -6px;
    content: '';
    display: inline-block;
    height: 23px;
    margin-left: 7px;
    top: 50%;
    right: 0;
    width: 18px;
    margin-top: -9px;
    position: absolute;
    }
    .rich-txt-container {
    font-size: 14px;
    color: #666;
    line-height: 22px;
}
 .card-cmn.color-w{
    margin-bottom: 10px;
 }
 .card-cmn.color-w > h3{
    margin: 0px;
 }
  .rich-txt-container p{
    margin: 3px 0px;
  }
  .padb {
    padding: 0px 0px 10px;
}
.cls-ul {
    list-style: none;
}
ol, ul {
    padding: 0px;
    margin: 0px;
}
.color-6 {
    color: #666;
}
.f14 {
    font-size: 0.875rem;
}
.cls-ul{list-style: none;}
.cls-ul > li{border-bottom: 1px solid #f1f1f1;}
.cls-ul > li:last-child{border: none;padding-bottom: 0px}
.cls-ul > li > a > p, .cls-ul > li > p {margin:0px;padding: 10px 0px;width: 93%;display: inline-block;vertical-align: middle;}
.s-container {
    word-wrap: break-word;
}
.crs-inf::after, .lft-frwd {
    content: '';
    display: inline-block;
    position: relative;
    background: url(images/Latest_sprite_v2.svg);
    background-position: -458px -35px;
    height: 15px;
    width: 15px;
    top: 3px;
}
.btn-secondary {
    border: 1px solid #008489;
}
.btn {
    width: 100%;
    height: 2rem;
    line-height: 2rem;
    display: inline-block;
    text-align: center;
    text-decoration: none;
    border-radius: 2px;
}

</style>
