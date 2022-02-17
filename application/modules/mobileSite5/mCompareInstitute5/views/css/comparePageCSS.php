@charset "UTF-8";
abbr,
address,
article,
aside,
audio,
b,
blockquote,
body,
canvas,
caption,
cite,
code,
dd,
del,
details,
dfn,
div,
dl,
dt,
em,
fieldset,
figcaption,
figure,
footer,
form,
h1,
h2,
h3,
h4,
h5,
h6,
header,
hgroup,
html,
i,
iframe,
img,
ins,
kbd,
label,
legend,
li,
mark,
menu,
nav,
object,
ol,
p,
pre,
q,
samp,
section,
small,
span,
strong,
sub,
summary,
sup,
time,
ul,
var,
video {
    background: 0 0;
    border: 0;
    margin: 0;
    outline: 0;
    padding: 0;
    vertical-align: baseline
}

article,
aside,
details,
figcaption,
figure,
footer,
header,
hgroup,
menu,
nav,
section {
    display: block
}

body {
    font-family: "Open Sans", sans-serif;
    font-size: 100%;
    background: #efefee;
    color: #000
}

html {
    overflow-y: scroll;
    -webkit-text-size-adjust: 100%;
    -ms-text-size-adjust: 100%
}

footer ul,
nav ul {
    list-style: none
}

blockquote,
q {
    quotes: none
}

blockquote:after,
blockquote:before,
q:after,
q:before {
    content: '';
    content: none
}

a {
    background: 0 0;
    margin: 0;
    padding: 0;
    vertical-align: baseline
}

ins {
    background-color: #ff9;
    color: #000;
    text-decoration: none
}

mark {
    background-color: #ff9;
    color: #000;
    font-style: italic;
    font-weight: 700
}

del {
    text-decoration: line-through
}

abbr[title],
dfn[title] {
    border-bottom: 1px dotted;
    cursor: help
}

table {
    border-collapse: collapse;
    border-spacing: 0;
    border: 1px solid #666;
    font-family: inherit
}

hr {
    border: 0;
    border-top: 1px solid #ccc;
    display: block;
    height: 1px;
    margin: 1em 0;
    padding: 0
}

input,
select {
    vertical-align: middle
}

code,
kbd,
pre,
samp {
    font-family: "Open Sans"
}

h1,
h2,
h3,
h4,
h5,
h6 {
    font-weight: 700;
    font-size: inherit
}

a,
a:active,
a:hover,
a:visited {
    outline: 0;
    text-decoration: none;
    color: #008489
}

a:focus {
    outline: 0 none
}

input:focus,
textarea:focus {
    outline: 0 none
}

:focus {
    outline: 0 none
}

ol,
ul {
    list-style: none
}

ol {
    list-style-type: decimal
}

nav li,
nav ul {
    margin: 0
}

small {
    font-size: 85%
}

b,
strong,
th {
    font-weight: 700
}

td,
td img {
    vertical-align: top
}

sub {
    font-size: smaller;
    vertical-align: sub
}

sup {
    font-size: .9em;
    vertical-align: text-top
}

pre {
    padding: 15px;
    white-space: pre;
    white-space: pre-line;
    white-space: pre-wrap;
    word-wrap: break-word
}

textarea {
    overflow: auto;
    vertical-align: top;
    resize: none
}

button,
input[type=button],
input[type=reset],
input[type=submit] {
    cursor: pointer;
    -webkit-appearance: button
}

label {
    cursor: pointer
}

button,
input {
    line-height: normal
}

[hidden] {
    display: none
}

button[disabled],
input[disabled] {
    cursor: default
}

button,
input,
select,
textarea {
    margin: 0
}

body {
    -webkit-text-size-adjust: none
}

.clearfix:after {
    visibility: hidden;
    display: block;
    font-size: 0;
    content: " ";
    clear: both;
    height: 0
}

.clearfix {
    display: inline-block
}

* html .clearfix {
    height: 1%
}

.clearfix {
    display: block
}

textarea {
    border: 1px solid #ddd;
    padding: .5em;
    -moz-border-radius: 12px;
    -webkit-border-radius: 12px;
    border-radius: 12px;
    -moz-box-shadow: 0 3px 4px rgba(61, 61, 61, .2) inset;
    -webkit-box-shadow: 0 3px 4px rgba(61, 61, 61, .2) inset;
    box-shadow: 0 3px 4px rgba(61, 61, 61, .2) inset;
    display: block;
    width: 90%;
    font: 400 1em inherit;
    color: #656565;
    -moz-background-clip: padding;
    -webkit-background-clip: padding;
    background-clip: padding-box
}

.pad-10 {
    padding: .625em
}

.gray-font {
    color: #797979
}

.light-font {
    color: #acacac
}

.tac {
    text-align: center
}

.fs12 {
    font-size: .9em
}

.flLt {
    float: left
}

.flRt {
    float: right
}

.spacer10 {
    height: 10px;
    overflow: hidden
}

.spacer5 {
    height: 5px;
    overflow: hidden
}

.ui-panel-content-wrap {
    background: #fff url(//images.shiksha.ws/public/mobile5/images/body-bg.jpg) 0 0 repeat!important
}

.content-wrap,
.content-wrap2 {
    background-color: rgba(255, 255, 255, .4);
    margin: 0 .4em .6em .4em;
    -moz-box-shadow: 0 1px 3px rgba(0, 0, 0, .5);
    -webkit-box-shadow: 0 1px 3px rgba(0, 0, 0, .5);
    box-shadow: 0 1px 3px rgba(0, 0, 0, .5);
    border-radius: 0 0 10px 10px;
    -moz-border-radius: 0 0 10px 10px;
    -webkit-border-radius: 0 0 10px 10px;
    border-top: 0 none;
    background-clip: padding-box
}

.content-wrap2 {
    background: #fff;
    border-radius: 0;
    margin-bottom: .9em;
    position: relative
}

.home-content-wrap {
    border: 3px solid rgba(216, 216, 216, .7);
    background-color: rgba(255, 255, 255, .5);
    margin: 0 .4em .6em .4em;
    border-radius: 10px 10px 10px 10px;
    -moz-border-radius: 10px 10px 10px 10px;
    -webkit-border-radius: 10px 10px 10px 10px;
    background-clip: padding-box
}

.fixed-wrap {
    padding-top: .6em;
    margin-top: 50px
}

#wrapper-404 h3,
.content-wrap h3,
.content-wrap2 h3,
.home-content-wrap h1,
.home-content-wrap h2,
.home-content-wrap h3 {
    font-weight: 400;
    margin-bottom: .7em;
    float: left;
    width: 100%
}

#wrapper-404 h3 {
    margin-bottom: .9em
}

.layer-wrap {
    background: #fff
}

#wrapper-404 h3 p,
.content-wrap h3 p,
.content-wrap2 h3 p,
.home-content-wrap h1 p,
.home-content-wrap h2 p,
.home-content-wrap h3 p,
.related-header p {
    position: relative;
    font-size: 1.1em;
    font-weight: 700;
    line-height: 90%;
    margin: 6px 0 0 32px;
    margin: 6px 0 0 30px\0/
}

#wrapper-404 h3 p {
    font-size: 1.3em;
    margin: 4px 0 0 33px
}

.q-font {
    font-size: 1.3em!important
}

.content-child {
    padding: 1em .8em
}

.ask-panel {
    padding: .6em .8em .1em
}

.content-child2 {
    padding: .8em .7em
}

.btn-section {
    padding: 1em
}

.tb-space {
    margin-bottom: 0;
    padding-top: 1em
}

#logo-box {
    text-align: center;
    padding: .7em 0 .6em;
    display: block;
    width: 100%;
    position: relative
}

#logo-box .logo {
    background: url(//images.shiksha.ws/public/mobile5/images/mobile-sprite.png) no-repeat;
    width: 88px;
    height: 21px;
    display: block;
    margin: 0 auto;
    padding: 0
}

.head-group {
    background: #fcd146;
    position: static;
    z-index: 9;
    display: table;
    width: 100%;
    height: 50px
}

.head-group>strong {
    text-align: left;
    display: table-cell;
    padding-left: 12px;
    padding-right: 98px;
    text-shadow: 0 1px 0 rgba(255, 255, 255, 1);
    vertical-align: middle
}

.head-group aside {
    padding: 5px 5px 3px
}

.head-group h1,
.head-group h3,
.title-text {
    font-size: 1em;
    font-weight: 700;
    text-align: center;
    padding: .4em 0;
    line-height: 110%;
    vertical-align: middle;
    display: table-cell
}

.left-align {
    text-align: left!important;
    margin-left: 40px
}

.title-text {
    text-align: left!important;
    margin: 10px 0 4px 45px;
    display: block
}

.head-group h1 p,
.head-group h3 p {
    color: #a07f13;
    font-size: .9em;
    margin-top: 3px
}

#searchbox2,
#searchbox3 form {
    border: 1px solid #cca21c;
    position: relative;
    height: 30px
}

#searchbox2 {
    background: #fff;
    padding: 2px 0 1px 6px;
    border-color: #bdbdbd;
    -moz-box-shadow: 0 3px 4px rgba(0, 0, 0, .1) inset;
    -webkit-box-shadow: 0 3px 4px rgba(0, 0, 0, .1) inset;
    box-shadow: 0 3px 4px rgba(0, 0, 0, .1) inset;
    margin: 0
}

#search,
#search-loc,
#searchInstitute,
.searchInstitute {
    border: 0 none;
    font-size: .9em;
    font-family: inherit;
    width: 99%;
    background: 0 0;
    padding: 6px 0;
    line-height: normal;
    margin: 0;
    -moz-border-radius: 0;
    -webkit-border-radius: 0;
    border-radius: 0;
    vertical-align: middle
}

#searchInstitute,
.searchInstitute {
    height: 32px;
    display: table;
    position: relative;
    left: 5px;
    width: 81%!important;
    -moz-box-sizing: border-box;
    -webkit-box-sizing: border-box;
    box-sizing: border-box;
    font-size: 13px!important;
    vertical-align: middle!important;
    padding: 0!important
}

.home-search-btn {
    margin: 0;
    height: 32px;
    -webkit-border-radius: 0;
    -moz-border-radius: 0;
    border-radius: 0;
    background: #828282;
    text-align: center;
    border: 0;
    font-weight: 700;
    color: #fff;
    font-size: .9em;
    padding: 0 .5em
}

.loggedin-row {
    padding: .5em .312em!important
}

.loggedin-row {
    border-top: 1px solid #242a37;
    -moz-box-shadow: 0 -1px 0 #454c5c!important;
    -webkit-box-shadow: 0 -1px 0 #454c5c!important;
    box-shadow: 0 -1px 0 #454c5c!important
}

.loggedin-details {
    margin: .6em 0 .5em 68px;
    display: block!important;
    left: auto!important
}

.loggedin-details p {
    left: auto!important;
    top: auto!important
}

.progress-main {
    width: 90%;
    margin: 7px 0 4px 0
}

.progress-base {
    width: 100%;
    background: #d1d1d1;
    height: 12px;
    border-radius: 5px
}

.progress-graph {
    width: 70%;
    background: #6db6e3;
    height: 100%;
    border-radius: 5px 0 0 5px
}

.loggedin-details em {
    display: block;
    margin-bottom: 10px
}

.loggedin-details em strong {
    color: #fff
}

.login-user {
    width: 50px;
    height: 50px;
    float: left;
    border: 4px solid #e1d9d2;
    -moz-border-radius: 50% 50%;
    -web-kit-border-radius: 50% 50%;
    border-radius: 50% 50%;
    overflow: hidden
}

.login-user img {
    -moz-box-shadow: 0 0 1px rgba(0, 0, 0, .9);
    -webkit-box-shadow: 0 0 1px rgba(0, 0, 0, .9);
    box-shadow: 0 0 1px rgba(0, 0, 0, .9);
    border-radius: 50% 50%;
    height: 100%;
    width: 100%
}

.selectbox,
.selectbox:visited,
.textbox:visited,
a.selectbox,
a.selectbox:visited,
a.textbox:visited {
    background: #e5e5e4;
    border: 1px solid #c3c1c1;
    display: table;
    width: 99.9%;
    position: relative;
    height: 35px;
    font-size: .9em;
    text-shadow: 0 1px 0 #fff
}

.selectbox,
a.selectbox {
    height: auto;
    min-height: 35px
}

.selectbox:active,
.textbox:active,
a.selectbox:active,
a.textbox:active {
    background: #f1cd78;
    border: 1px solid #eebc44
}

.selectbox p,
a.selectbox p {
    padding: 11px 4px 8px 6px;
    display: block;
    text-shadow: 0 1px 0 #fff;
    text-decoration: none;
    color: #000!important;
    outline: 0 none;
    -moz-border-radius: 0 0;
    -webkit-border-radius: 0 0;
    border-radius: 0 0;
    width: 88%;
    font-weight: 400!important
}

.textbox2,
ol.form-item input[type=email],
ol.form-item input[type=number],
ol.form-item input[type=password],
ol.form-item input[type=tel],
ol.form-item input[type=text] {
    padding: 9px 4px 9px 6px;
    display: block;
    text-shadow: 0 1px 0 #fff;
    text-decoration: none;
    color: #000;
    outline: 0 none;
    -moz-border-radius: 0 0;
    -webkit-border-radius: 0 0;
    border-radius: 0 0;
    width: 100%;
    background: rgba(0, 0, 0, 0);
    border: 0 none;
    -webkit-box-shadow: none;
    -moz-box-shadow: none;
    box-shadow: none;
    -webkit-box-sizing: border-box;
    -moz-box-sizing: border-box;
    box-sizing: border-box;
    font-size: .9em
}

.textbox,
.textbox2,
a.textbox {
    background: #efefef;
    border: 1px solid #c3c1c1;
    display: block;
    width: 100%;
    -webkit-box-sizing: border-box;
    -moz-box-sizing: border-box;
    box-sizing: border-box
}

.textbox2 {
    display: inline;
    font-size: .8em;
    -moz-box-shadow: none;
    -webkit-box-shadow: none;
    box-shadow: none;
    margin-bottom: 5px;
    padding: 8px 2px 8px 3px
}

.error .selectbox,
.error .selectbox:hover,
.error .textbox,
.error .textbox:hover {
    border: 1px solid red
}

.errorMsg,
.regErrorMsg {
    font-size: .75em;
    color: red;
    margin-top: 2px
}

.refine-section .selectbox {
    border-color: #d5d5d5;
    background: #fff!important
}

.duration-cont table td:active,
.refine-section .selectbox:active {
    background: #f1cd78!important
}

.msg-box {
    border: 1px solid #fff590;
    background: #feffcd;
    padding: 10px;
    font: 700 13px inherit;
    margin-bottom: 10px
}

.blue {
    color: #fff;
    background: #008489;
    margin: 9px 0 3px;
    font-weight: 700;
    font-size: .75em!important
}

.disabled {
    color: #cfcfcf!important;
    background: #f2f1f0!important;
    cursor: default
}

.shortlist-enable-btn.dsbl-nt-sl {
    color: #c6c5c5;
    background: #e5e5e4
}

.blue:active,
.blue:hover {
    color: #fff
}

.big {
    padding: 9px 7px 0 8px;
    font-size: 1.2em
}

.yellow:active {
    color: #000
}

.blue .icon-pencil {
    display: none
}

.button:active {
    -webkit-box-shadow: inset 0 0 3px rgba(0, 0, 0, .8);
    -moz-box-shadow: inset 0 0 3px rgba(0, 0, 0, .8);
    box-shadow: inset 0 0 3px rgba(0, 0, 0, .8)
}

.yellow-disabled {
    background: #ccc;
    text-shadow: none!important;
    -webkit-box-shadow: none!important;
    -moz-box-shadow: none!important;
    box-shadow: none!important;
    color: #999!important
}

.disabled:active {
    -webkit-box-shadow: 0 -1px 1px rgba(0, 0, 0, .5);
    -moz-box-shadow: 0 -1px 1px rgba(0, 0, 0, .5);
    box-shadow: 0 -1px 1px rgba(0, 0, 0, .5)
}

.button:visited {
    color: #fff
}

.full-width {
    width: 100%;
    -moz-box-sizing: border-box;
    -webkit-box-sizing: border-box;
    box-sizing: border-box
}

.blue-title {
    background: #e4f1ff;
    padding: .7em .625em;
    font-size: 1.2em;
    font-weight: 700;
    box-shadow: 0 1px 3px rgba(0, 0, 0, .3);
    -moz-box-shadow: 0 1px 3px rgba(0, 0, 0, .3);
    -webkit-box-shadow: 0 1px 3px rgba(0, 0, 0, .3);
    margin-bottom: .312em;
    text-shadow: 0 1px 1px #fff
}

#registration-box,
ol.form-item,
ol.form-item2 {
    clear: both
}

#registration-box ul li,
ol.form-item li,
ol.form-item2 li {
    margin-bottom: .9em;
    list-style: none;
    float: left;
    width: 100%
}

#registration-box ul li {
    position: relative;
    margin-bottom: .6em
}

#registration-box a.add-txt {
    font-size: .9em
}

#registration-box ul li p,
ol.form-item li label,
ol.form-item2 li p {
    display: block;
    margin-bottom: 1px
}

ol.form-item li label {
    font-size: .9em
}

#registration-box ul li p {
    margin-bottom: 0;
    font-weight: 700
}

ol.form-item li:last-of-type,
ol.form-item2 li:last-of-type {
    margin-bottom: 0
}

#registration-box ul li label {
    margin: 0 5px 5px 0;
    font-size: .9em;
    display: inline-block
}

#registration-box ul li strong {
    font-size: 1em;
    font-weight: 700;
    font-family: inherit;
    display: block;
    margin-bottom: 5px
}

#registration-box ul li label input {
    position: relative;
    top: -2px
}

.fields-row label {
    float: left;
    margin: 10px 5px 0 0!important
}

.login-title {
    font-size: 1em;
    font-weight: 700;
    margin-bottom: .4em!important
}

.section-header {
    border-bottom: 3px solid #c8eaff;
    -moz-box-shadow: 1px 3px 3px rgba(0, 0, 0, .3);
    -webkit-box-shadow: 1px 3px 3px rgba(0, 0, 0, .3);
    box-shadow: 1px 3px 3px rgba(0, 0, 0, .3);
    background-image: -webkit-gradient(linear, left top, left bottom, from(#fff), to(#c8eaff));
    background-image: -webkit-linear-gradient(#fff, #c8eaff);
    background-image: -moz-linear-gradient(#fff, #c8eaff);
    background-image: -ms-linear-gradient(#fff, #c8eaff);
    background-image: -o-linear-gradient(#fff, #c8eaff);
    background-image: linear-gradient(#fff, #c8eaff)
}

.section-header h4 {
    padding: .62em .625em .38em .5em;
    font-weight: 700;
    font-size: 1.2em;
    text-shadow: 0 1px 0 #fff
}

.section-header p {
    background: #abdbf8;
    width: 45px;
    height: 36px;
    float: right;
    margin-left: .93em;
    border-left: 2px solid #fff;
    -moz-box-shadow: -1px 0 2px rgba(0, 0, 0, .1);
    -webkit-box-shadow: -1px 0 2px rgba(0, 0, 0, .1);
    box-shadow: -1px 0 2px rgba(0, 0, 0, .1);
    overflow: hidden;
    text-align: center
}

.section-footer,
.section-footer2 {
    border-bottom: 2px solid #fff;
    border-top: 1px solid #e8e6e3;
    -moz-box-shadow: 0 1px 0 #fff inset;
    -webkit-box-shadow: 0 1px 0 #fff inset;
    box-shadow: 0 1px 0 #fff inset;
    background-image: -webkit-gradient(linear, left top, left bottom, from(#f4f4f4), to(#f1eeea));
    background-image: -webkit-linear-gradient(#f4f4f4, #f1eeea);
    background-image: -moz-linear-gradient(#f4f4f4, #f1eeea);
    background-image: -ms-linear-gradient(#f4f4f4, #f1eeea);
    background-image: -o-linear-gradient(#f4f4f4, #f1eeea);
    background-image: linear-gradient(#f4f4f4, #f1eeea)
}

.section-footer2 {
    border-bottom: 1px solid #e8e6e3
}

.section-footer2 p {
    width: 100%;
    text-align: center;
    border-bottom: 2px solid #fff
}

.ask-q,
.ask-q2 {
    padding: .55em;
    text-shadow: 0 1px 0 #fff;
    margin-left: 55px
}

.ask-q2 {
    margin-left: 0
}

.ask-q textarea,
.ask-q2 textarea {
    height: 22px;
    color: #a3a2a2
}

.section-footer p {
    width: 51px;
    height: 51px;
    float: left;
    text-align: center
}

.section-footer p.footer-cont {
    width: 61%;
    height: auto;
    padding: 0 0 0 .4em
}

.rating-col {
    width: 100%;
    padding: 0 .125em .8em .5em;
    float: left;
    border-right: none;
    vertical-align: middle;
    font-size: .9em;
    color: #797979
}

.rating-col span {
    vertical-align: middle
}

.rating-col span.rate-num {
    position: relative;
    top: 2px
}

.rating-col img {
    vertical-align: middle;
    margin: 0 1px
}

.refine-section {
    border-bottom: 1px solid #d6d6d6;
    box-shadow: 0 1px 0 #fff;
    -moz-box-shadow: 0 1px 0 #fff;
    -webkit-box-shadow: 0 1px 0 #fff;
    padding: .75em
}

.refine-section h5 {
    font-size: 1.5em;
    color: grey;
    text-align: center;
    padding-top: .5em
}

.refine-section label {
    display: block;
    margin-bottom: 3px;
    font-weight: 700!important;
    color: #000;
    cursor: auto!important
}

#mode_block_vlsJyW label,
.course-level label {
    font-weight: 400!important;
    font-size: 1em!important
}

.refine-section ul li {
    margin-bottom: .94em
}

.duration-cont {
    width: 100%;
    box-shadow: 0 1px 0 #fff
}

.duration-cont table {
    border: 0 none
}

.duration-cont table td {
    padding: 8px 0;
    border: 1px solid #d6d6d6;
    margin: 0;
    text-align: center;
    vertical-align: middle;
    background: #fff;
    box-shadow: 0 1px 0 #fff inset;
    -moz-box-shadow: 0 1px 0 #fff inset;
    -webkit-box-shadow: 0 1px 0 #fff inset;
    cursor: pointer
}

.duration-cont table td.active {
    background: #e5e5e5
}

.quest-box aside {
    padding: .75em;
    border-bottom: 2px solid #ececec;
    margin-bottom: .3em;
    overflow: hidden
}

.quest-box aside figure {
    width: 90px;
    height: 50px;
    float: left;
    border: 1px solid #ddd
}

.quest-box aside figure img {
    width: 100%
}

.quest-box aside details {
    margin-left: 96px;
    font-size: 1em;
    font-weight: 400;
    line-height: 120%;
    padding-top: .625em
}

.quest-box aside details span {
    color: #797979
}

.quest-box h3 {
    font-size: 1.2em;
    margin-bottom: .3em
}

.quest-box p {
    clear: both
}

.ques-details {
    margin: .6em 0 .9em 0;
    font-size: 1.1em;
    line-height: 115%
}

#tab {
    background: #214369;
    position: relative;
    z-index: 1;
    font-size: .9em;
    font-weight: 700;
    padding: 0 .3em 0 .3em;
    height: 58px;
    overflow: hidden
}

#tab table {
    display: block;
    border: 2px solid rgba(0, 0, 0, .1);
    border-top: 0 none;
    border-bottom: 0 none
}

#tab table td {
    padding: 0!important;
    cursor: pointer;
    width: 25%;
    vertical-align: middle;
    height: 52px;
    overflow: hidden;
    background-color: #2b5079!important
}

#home-tab {
    clear: left;
    margin: .4em 0 1.8em;
    -moz-border-radius: 5px;
    -webkit-border-radius: 5px;
    border-radius: 5px;
    border: 1px solid #c1c0c0;
    height: auto;
    moz-box-shadow: 0 2px 3px rgba(0, 0, 0, .14);
    -webkit-box-shadow: 0 2px 3px rgba(0, 0, 0, .14);
    box-shadow: 0 2px 3px rgba(0, 0, 0, .14);
    -webkit-background-clip: padding;
    background-clip: padding-box;
    float: left;
    width: 100%;
    background: #d4d4d4
}

#home-tab ul {
    display: table;
    width: 100%
}

#home-tab ul li {
    display: table-cell;
    border-radius: 5px 0 0 5px
}

#home-tab ul li:nth-child(n+2) {
    border-radius: 0 5px 5px 0
}

#home-tab ul li a {
    padding: .5em 0;
    color: #707070;
    display: block;
    text-align: center;
    font-weight: 700;
    position: relative;
    font-size: 1.1em
}

#tab table td a {
    display: block;
    padding: .5em 0 .4em 0;
    color: #fff;
    text-align: center;
    text-shadow: 0 1px 1px rgba(0, 0, 0, .5)
}

#home-tab ul li.active {
    background: #f2f1f1;
    -moz-border-radius: 5px 0 0 5px;
    -webkit-border-radius: 5px 0 0 5px;
    border-radius: 5px 0 0 5px
}

#home-tab ul li.active:nth-child(n+2) {
    border-radius: 0 5px 5px 0
}

#home-tab ul li.active a span {
    background-position: -407px -70px;
    width: 20px;
    height: 15px;
    position: absolute!important;
    left: 42%;
    bottom: -15px;
    float: none!important;
    z-index: 9;
    overflow: hidden
}

#tab table td:nth-child(n+2) {
    background: #2b5079!important
}

#tab table td:nth-child(n+3) {
    background: #355a84!important
}

#tab table td:nth-child(n+4) {
    background: #4d6987!important
}

#tab table td:nth-child(n+5) {
    background: #6e859d!important
}

#tab table td.active {
    background: #fff!important
}

#home-tab ul li.active a,
#tab table td.active a {
    color: #000
}

#tab td.active .icon-info {
    background-position: -146px -70px
}

#tab td.active .icon-course {
    background-position: -175px -70px
}

#tab td.active .icon-image {
    background-position: -251px -70px
}

#tab td.active .icon-star {
    background-position: -277px -70px
}

ul.photo-bolck {
    padding: .75em .625em 0
}

ul.photo-bolck li {
    float: left;
    width: 33%;
    max-width: 170px;
    margin-bottom: 2%;
    position: relative
}

ul.photo-bolck li img {
    width: 90%;
    max-width: 170px
}

.filter-applied {
    background: #efefef;
    color: #929090;
    text-shadow: 0 1px 0 #fff;
    padding: 5px 10px 2px;
    position: relative;
    z-index: 1;
    -moz-box-shadow: 0 2px 2px rgba(0, 0, 0, .3);
    -webkit-box-shadow: 0 2px 2px rgba(0, 0, 0, .3);
    box-shadow: 0 2px 2px rgba(0, 0, 0, .3);
    font-size: .9em
}

.filter-child {
    padding: 3px 10px 3px 0
}

.filter-applied strong {
    font-weight: 700;
    display: block;
    color: grey;
    margin-bottom: 4px
}

.filter-applied p {
    vertical-align: top;
    line-height: 20px
}

.filter-applied p span {
    font-size: 2em;
    vertical-align: super;
    line-height: 0
}

.filter-cont {
    margin: 1.1em 5% 0 5%
}

ul.filter-options {
    padding: 0;
    margin-bottom: 1em;
    clear: left;
    float: left;
    width: 100%
}

ul.filter-options li {
    color: #fff;
    margin: 0;
    float: left;
    -webkit-background-clip: padding;
    background-clip: padding-box;
    background: #7793b2;
    position: relative;
    width: 49%;
    text-align: center
}

ul.filter-options li:first-of-type {
    -moz-border-radius: 8px 0 0 8px;
    -webkit-border-radius: 8px 0 0 8px;
    border-radius: 8px 0 0 8px;
    border-right: solid 1px #c3c3c3
}

ul.filter-options li:last-of-type {
    -moz-border-radius: 0 8px 8px 0;
    -webkit-border-radius: 0 8px 8px 0;
    border-radius: 0 8px 8px 0
}

ul.filter-options li a {
    color: #fff!important;
    text-decoration: none;
    padding: .5em 0;
    float: left;
    display: block;
    text-align: center;
    font-weight: 400!important;
    font-size: 1.2em;
    text-shadow: 0 -1px 1px rgba(0, 0, 0, .5);
    width: 100%
}

ul.filter-options li.active {
    background: #254971;
    -webkit-background-clip: padding;
    background-clip: padding-box;
    font-weight: 700
}

ul.filter-options li .icon-play {
    display: none
}

ul.filter-options li.active .icon-play {
    position: absolute;
    bottom: -9px;
    left: 46%;
    display: block
}

ul.notification-list {
    padding-top: 1em
}

.review-list li,
.stream-list li,
ul.layer-list li,
ul.layer-list2 li,
ul.notification-list li {
    padding: .625em;
    border-bottom: 1px solid #dedede;
    overflow: hidden
}

.review-list {
    font-size: 1.1em;
    line-height: 118%
}

.review-list li p {
    margin-bottom: .4em
}

.review-list li p:last-of-type {
    margin: 0
}

.review-list li p.rating-col {
    width: auto;
    float: none;
    padding: 0;
    margin-bottom: .4em;
    border: 0 none
}

ul.layer-list li {
    position: relative;
    font-size: .9em;
    padding: 0
}

ul.layer-list li.activeLink a {
    float: left;
    margin-right: 25px;
    font-weight: 700
}

ul.layer-list li a {
    color: #000;
    text-decoration: none;
    padding: .8em .63em .8em .63em;
    display: block
}

ul.layer-list li.non-click a {
    cursor: auto
}

ul.notification-list li:last-of-type {
    padding: 0
}

ul.notification-list li figure {
    width: 50px;
    height: 50px;
    float: left
}

.stream-list li figure {
    width: 30px;
    height: 30px;
    float: left
}

.stream-list li figure {
    text-align: center;
    height: auto
}

ul.notification-list li p,
ul.stream-list li div.details {
    margin: 1px 0 0 57px;
    font-size: 1.1em
}

ul.stream-list li div.details {
    margin: 2px 0 0 35px
}

ul.stream-list li div.details h2 {
    font-size: .9em;
    font-weight: 400;
    margin-bottom: 4px;
    display: block;
    line-height: 110%
}

ul.stream-list li div.details span {
    display: block;
    color: #797979;
    font-size: .7em;
    line-height: 108%
}

.cancel-btn,
.close-btn,
.refine-btn,
.req-btn {
    padding: .7em 0;
    color: #fff;
    background: #008489;
    display: block;
    text-align: center;
    margin: .312em 0 0 0;
    text-decoration: none!important;
    color: #fff!important;
    font-size: 12px;
    vertical-align: middle;
    text-transform: uppercase
}

.cancel-btn,
.refine-btn,
.req-btn {
    margin: 0;
    font-weight: 700;
    padding: .7em 0;
    vertical-align: middle;
    z-index: 999
}

.cancel-btn {
    background: #e5e5e4;
    color: #5a595c!important
}

.req-btn {
    background: #fcd146;
    color: #000!important;
    text-shadow: 0 1px 0 #fff;
    box-shadow: 0 -1px 2px rgba(0, 0, 0, .6);
    font-size: 1.4em
}

.close-btn span {
    top: 1px;
    position: relative;
    left: 1px;
    text-shadow: 0 1px 1px #7d7d7d
}

.college-pic {
    width: 100%;
    height: 100%;
    padding-top: 1em
}

.college-pic img {
    width: 100%;
    max-width: 650px;
    display: block
}

.notify-details {
    padding: .6em .9em .9em
}

.notify-details h3,
.notify-details p {
    margin-bottom: .4em;
    line-height: 130%;
    font-size: .9em
}

.notify-details em,
.tiny-contents ul {
    margin-top: 10px
}

.notify-details em {
    font-style: italic;
    color: #797979;
    margin-bottom: .6em;
    display: block;
    font-size: .9em
}

.tiny-contents ol li,
.tiny-contents ul li,
ul.bullet-item li {
    color: #797979;
    list-style: disc outside none;
    margin: 0 0 .5em 18px;
    line-height: 115%;
    font-size: .9em
}

.tiny-contents ol li,
.tiny-contents ul li {
    line-height: 130%
}

.tiny-contents ol,
.tiny-contents ul {
    margin-bottom: .8em
}

.tiny-contents ol li {
    list-style: decimal outside none
}

.tiny-contents p strong,
.tiny-contents span {
    font-family: inherit!important;
    font-size: inherit!important
}

.tiny-contents ol li,
.tiny-contents ul li,
ul.bullet-item li span {
    color: #000
}

ul.bullet-item li span i {
    color: #e2e2e2;
    margin: 0 .18em;
    font-style: normal
}

.ques-title {
    background: #f2f2f2;
    padding: .5em;
    text-shadow: 0 1px 0 #fff;
    -moz-box-shadow: 0 1px 2px rgba(0, 0, 0, .3);
    -webkit-box-shadow: 0 1px 2px rgba(0, 0, 0, .3);
    box-shadow: 0 1px 2px rgba(0, 0, 0, .3);
    font-weight: 400
}

.ans-header,
.ques-title p {
    font-weight: 700
}

.ques-title p {
    position: relative;
    margin-left: 3px;
    top: 1px
}

.ques-title p.ml32 {
    margin-left: 32px
}

.ques-title p.valign {
    margin-left: 3px
}

.ans-header {
    background: #4c6e94;
    color: #fff;
    text-shadow: 0 1px 1px rgba(0, 0, 0, .5);
    -moz-box-shadow: 0 2px 2px rgba(0, 0, 0, .4);
    -webkit-box-shadow: 0 2px 2px rgba(0, 0, 0, .4);
    box-shadow: 0 2px 2px rgba(0, 0, 0, .4);
    padding: .4em .5em
}

.ans-header span {
    color: #d3d3d3
}

.related-header,
.similar-header {
    background: #e0f0fa;
    padding: .5em .6em;
    overflow: hidden
}

.similar-header {
    padding: 0;
    background: #000;
    font-size: 1.4em;
    font-weight: 700
}

.comp-details,
.contact-details {
    color: #797979;
    padding: 0 .9em .9em .9em;
    font-size: .9em
}

.comp-details {
    padding: .9em .9em
}

.contact-details p {
    margin-bottom: .3em
}

.comp-details strong {
    display: block;
    color: #000;
    font-size: 1.1em;
    font-weight: 400;
    margin-bottom: 10px
}

.comp-details img {
    border: 0 none;
    margin: 0 .8em .6em 0
}

.contact-details span {
    color: #000
}

.ans-box {
    padding: .75em;
    font-size: 1.1em
}

.ans-box p {
    margin-bottom: .9em
}

.ans-box ol li {
    list-style: decimal;
    margin: 0 0 .6em 1.75em
}

.btn-row {
    margin: 0 3%;
    padding: 0 0 .8em 0
}

ul.related-items {
    float: left;
    width: 100%
}

ul.related-items li {
    background: #e5e5e5;
    overflow: hidden;
    border-bottom: 1px solid #dedede
}

ul.related-items li:last-of-type {
    border: 0 none
}

.sim-figure,
ul.related-items li figure {
    width: 55px;
    padding: .3em 0 0;
    float: left;
    text-align: center
}

.sim-details,
ul.related-items li aside {
    margin-left: 55px;
    background: #fff;
    overflow: hidden;
    padding: .75em
}

.sim-details {
    background: #e0f0fa;
    padding: .4em .2em 0 .4em
}

.sim-details h3 {
    line-height: 110%;
    font-size: .9em!important
}

ul.related-items li aside p {
    font-size: 1em
}

.sim-details h3,
ul.related-items li aside h3 {
    font-size: 1.2em;
    font-weight: 400
}

.view-institute {
    background: #4c6e94;
    padding: .5em .312em .75em .5em;
    color: #fff
}

.view-institute a {
    color: #fff!important;
    text-decoration: none;
    font-size: 1.2em;
    position: relative;
    top: -1px;
    left: 4px
}

.text-shadow {
    -moz-text-shadow: 0 1px 1px rgba(0, 0, 0, .5);
    -webkit-text-shadow: 0 1px 1px rgba(0, 0, 0, .5);
    text-shadow: 0 1px 1px rgba(0, 0, 0, .5)
}

.text-shadow-w {
    -moz-text-shadow: 0 1px 0 #fff;
    -webkit-text-shadow: 0 1px 0 #fff;
    text-shadow: 0 1px 0 #fff
}

.icon-close2,
.icon-done {
    width: 25px;
    height: 25px;
    background: #fff;
    color: #6db6e3;
    text-shadow: 0 1px 0 rgba(67, 102, 122, .9);
    -moz-box-shadow: 0 -1px 1px rgba(0, 0, 0, .5);
    -webkit-box-shadow: 0 -1px 1px rgba(0, 0, 0, .5);
    box-shadow: 0 -1px 1px rgba(0, 0, 0, .5);
    font: 400 1.4em inherit;
    -moz-border-radius: 50px 50px 50px 50px;
    -webkit-border-radius: 50px 50px 50px 50px;
    border-radius: 50px 50px 50px 50px;
    display: inline-block;
    text-align: center;
    outline: 0 none;
    line-height: 120%;
    font-style: normal
}

.icon-done {
    width: 27px;
    height: 26px;
    font-size: inherit;
    vertical-align: middle
}

.icon-done .icon-checkmark {
    position: relative;
    top: -5px;
    font-size: .8em;
    font-weight: 400;
    text-shadow: none
}

.icon-cl {
    background: #ccc;
    color: #fff;
    -moz-border-radius: 25px 25px;
    -webkit-border-radius: 25px 25px;
    border-radius: 25px 25px;
    font-size: 22px;
    height: 24px;
    text-align: center;
    width: 24px;
    line-height: 24px;
    position: absolute!important;
    right: 6px;
    top: 5px;
    cursor: pointer;
    font-style: normal
}

.help-box {
    padding: 20px
}

.help-box p {
    font: 700 1.4em/150% inherit;
    color: #787878;
    margin-bottom: .6em;
    padding-left: 15px
}

.help-pic {
    width: 100%;
    text-align: center;
    margin-bottom: 1px
}

.help-pic img {
    max-width: 140px;
    width: 100%;
    vertical-align: bottom
}

.help-box p span {
    color: #000;
    font-size: 1.1em
}

.icon-vid {
    text-align: center;
    position: absolute;
    width: 90%;
    top: 30%;
    cursor: pointer
}

.icon-vid img {
    width: 40%!important
}

.icon-title {
    position: absolute;
    right: 6px;
    top: 14px;
    overflow: hidden
}

.shortlist-heart {
    background-position: right -27px;
    background-repeat: no-repeat;
    width: 54px;
    height: 62px;
    position: fixed;
    right: 0;
    top: 90px;
    font-size: 1.5em;
    color: #fff;
    font-style: normal;
    z-index: 999
}

.shortlist-heart span {
    display: block;
    margin: 18px 0 0 17px;
    width: 38px;
    text-align: center
}

.head-filter,
.head-refine,
.mylist-head {
    position: relative;
    top: 6px;
    right: 5px;
    width: 48px;
    text-align: center;
    font: 700 12px inherit;
    z-index: 999
}

.head-filter {
    width: 40px;
    right: 7px
}

.head-refine {
    top: 15px
}

.head-filter p,
.mylist-head p {
    text-shadow: 0 1px 0 rgba(255, 255, 255, .7)
}

.head-filter p,
.head-refine p,
.mylist-head p {
    position: relative;
    top: -2px;
    line-height: normal!important;
    font-size: 9px;
    text-transform: uppercase;
    font-weight: 700;
    color: #505050
}

.head-filter p {
    top: 5px!important
}

.mylist-head p {
    float: left;
    width: 100%
}

.head-filter a,
.head-refine a,
.mylist-head a {
    color: #505050;
    display: block;
    height: 43px
}

.mylist-head a {
    height: auto
}

.fb-btn {
    cursor: pointer
}

.fb-btn span,
.login-field span,
.pass-field span {
    background-color: #395696;
    width: 48px;
    height: 48px;
    -webkit-border-radius: 2px 0 0 2px;
    -moz-border-radius: 2px 0 0 2px;
    border-radius: 2px 0 0 2px;
    float: left;
    text-align: center
}

.fb-btn p,
.login-field p,
.pass-field p {
    background: #fff;
    padding: .93em 0 .312em 0;
    -moz-border-radius: 2px 2px 2px 2px;
    -webkit-border-radius: 2px 2px 2px 2px;
    border-radius: 2px 2px 2px 2px;
    height: 27px;
    margin-left: 48px;
    text-align: center;
    font-size: 1.3em;
    color: #395696;
    text-transform: uppercase;
    -moz-box-shadow: -1px 0 0 #1e3568;
    -webkit-box-shadow: -1px 0 0 #1e3568;
    box-shadow: -1px 0 0 #1e3568;
    position: relative;
    z-index: 9
}

.login-field p,
.pass-field p {
    -moz-border-radius: 0 2px 2px 0;
    -webkit-border-radius: 0 2px 2px 0;
    border-radius: 0 2px 2px 0;
    box-shadow: none;
    padding: 5px;
    height: 38px;
    text-align: left
}

.login-form input[type=password],
.login-form input[type=text] {
    padding: .5em .312em 0 .312em!important;
    font-size: 1.1em;
    font-family: inherit;
    min-height: none;
    line-height: normal;
    color: #000;
    border: 0 none;
    background: 0 0;
    width: 92%
}

.login-field span,
.pass-field span {
    background: #d1d1d1
}

.l-btn,
.l-btn:visited,
.r-btn,
.r-btn:visited,
.reset-btn,
.search-btn,
a.call-btn,
a.call-btn:visited,
a.l-btn,
a.l-btn:visited,
a.reset-btn {
    background: #008489;
    padding: .7em 0;
    opacity: 1;
    color: #fff;
    font-size: 12px!important;
    border: 0 none;
    display: block;
    width: 100%;
    text-transform: uppercase;
    font-weight: 700;
    text-align: center
}

.l-btn {
    font-size: 1em
}

.call-btn {
    padding: .3em 0 .3em!important;
    vertical-align: middle
}

.call-btn .icon-mobile {
    position: relative;
    display: inline-block;
    left: auto;
    margin-top: 0;
    top: -1px;
    vertical-align: middle;
    float: none!important
}

.call-btn span {
    vertical-align: middle;
    margin: 0 0 0 5px
}

.r-btn {
    background: #008489;
    border-color: #008489;
    color: #fff;
    text-transform: uppercase;
    padding: .7em 0 .7em
}

.reset-btn {
    background: #b3b3b3;
    border-color: #898888
}

.l-btn:active,
.search-btn:active,
a.call-btn:active,
a.close-btn:active,
a.refine-btn:active {
    background: #0a828c
}

.r-btn:active {
    background: #0a828c;
    border-color: #0a828c
}

hr.h-rule {
    border-top: 1px solid #d7d6d6;
    padding-bottom: 0;
    -moz-box-shadow: 0 1px 0 #fff;
    -webkit-box-shadow: 0 1px 0 #fff;
    box-shadow: 0 1px 0 #fff;
    overflow: hidden;
    margin: 0!important
}

.login-box {
    border-radius: 0 0 10px 10px;
    -moz-border-radius: 0 0 10px 10px;
    -webkit-border-radius: 0 0 10px 10px;
    padding: 1em .8em .5em;
    background-clip: padding-box
}

.login-box .icon-user {
    float: left;
    top: 0
}

.login-box p a {
    color: #e6b002;
    text-decoration: none
}

i.info {
    font-size: .7em;
    color: #888;
    float: left;
    font-style: normal;
    margin-top: 4px;
    line-height: 100%;
    font-weight: 400
}

.inst-details {
    margin-bottom: 1em
}

.loc-map,
.other-opt {
    background: #fff;
    border-color: #dedede;
    border-style: solid;
    border-width: 1px 0;
    margin: .8em 0;
    clear: left
}

.other-opt a {
    padding: .5em .3em .5em .5em;
    display: block;
    color: #000
}

.loc-map img {
    width: 100%;
    height: 100%;
    max-width: 500px
}

.req-bro-box {
    padding: 1em .625em .625em;
    position: relative
}

.req-bro-box .details {
    float: left;
    width: 100%
}

.req-bro-box .details h3,
.req-bro-box .details h4 {
    margin-bottom: .3em;
    font-weight: 700;
    word-wrap: break-word;
    color: #006fa2;
    line-height: 130%;
    font-size: 1em
}

.req-bro-box .details h3 span,
.req-bro-box .details h4 span {
    font-size: .9em;
    color: #828282
}

.req-bro-box .details ul li {
    margin-bottom: 6px;
    font-size: .9em;
    float: left;
    width: 100%
}

.req-bro-box .details ul li p {
    margin: 0 0 0 26px
}

.req-bro-box .details ul li label {
    color: #828282
}

ul.setting-list {
    clear: both
}

ul.setting-list li {
    color: #c4ccda;
    border-bottom: 1px solid #242a37;
    -moz-box-shadow: 0 1px 0 #454c5c;
    -webkit-box-shadow: 0 1px 0 #454c5c;
    box-shadow: 0 1px 0 #454c5c;
    text-shadow: 0 -1px 0 #000;
    position: relative;
    font-weight: 700;
    overflow: hidden
}

ul.setting-list li:active {
    background: #232937
}

ul.setting-list li.title {
    background-image: -webkit-gradient(linear, left top, left bottom, from(#434a5e), to(#394052));
    background-image: -webkit-linear-gradient(#434a5e, #394052);
    background-image: -moz-linear-gradient(#434a5e, #394052);
    background-image: -ms-linear-gradient(#434a5e, #394052);
    background-image: -o-linear-gradient(#434a5e, #394052);
    background-image: linear-gradient(#434a5e, #394052);
    text-transform: uppercase;
    font-family: inherit;
    font-weight: 700;
    color: #9ca4b3;
    text-shadow: 0 -1px 0 #000;
    -moz-box-shadow: 0 1px 0 #454c5c;
    -webkit-box-shadow: 0 1px 0 #454c5c;
    box-shadow: 0 1px 0 #454c5c;
    padding: 10px 0 0 8px;
    overflow: hidden;
    height: 35px
}

ul.setting-list li p,
ul.setting-list li.title p {
    display: inline-block;
    vertical-align: middle;
    position: relative;
    left: 0
}

ul.setting-list li p {
    left: 6px;
    top: 3px
}

ul.setting-list li a {
    color: #c4ccda;
    text-decoration: none;
    display: block;
    height: 35px;
    padding: 10px 0 0 6px
}

.badge {
    background: #f38335;
    min-width: 19px;
    height: 21px;
    color: #fff;
    border-radius: 20px;
    display: inline-block;
    text-align: center;
    -moz-box-shadow: 0 3px 2px #141414;
    -webkit-box-shadow: 0 3px 2px #141414;
    box-shadow: 0 3px 2px #141414;
    padding: 3px 3px 0 2px;
    font-size: 1.2em;
    text-shadow: none;
    margin-left: .312em;
    line-height: normal;
    position: absolute;
    top: -6px;
    right: -30px
}

ul.login-form {
    padding: 0 .625em .625em
}

ul.login-form li {
    margin-bottom: 1px
}

.opt-txt {
    padding: 1em 0;
    color: #fff;
    text-align: center;
    font-size: 1.6em
}

.pass-txt {
    color: #fff;
    text-align: center;
    font-size: 1.5em;
    padding: .5em 0 .4em;
    line-height: 190%
}

.pass-txt span {
    font-size: 1.3em
}

#registration-box li input::-moz-placeholder,
ol.form-item li input::-moz-placeholder,
ol.form-item2 li input::-moz-placeholder {
    color: #000;
    opacity: 1
}

#side-nav {
    float: left;
    width: 50px;
    min-height: 300px
}

.loc-nav {
    width: 90px!important;
    border-right: solid 2px #254971
}

.loc-nav li span {
    padding: 0!important
}

.location-list {
    border-left: solid 2px #254971;
    margin-left: 50px
}

.location-list2 {
    margin-left: 90px!important
}

.location-list .ui-checkbox {
    float: none
}

#side-nav li,
.location-list li {
    padding: 15px 0 1px 8px;
    border-bottom: 1px solid #dedede;
    min-height: 30px;
    position: relative;
    font-size: .9em
}

.location-list li {
    padding: 0!important;
    min-height: inherit!important
}

.location-list li label {
    display: block;
    padding: 15px 0 1px 8px;
    min-height: 30px
}

.location-list li:last-of-type {
    border-bottom: 0 none
}

#side-nav li {
    padding: 8px 0 3px 0;
    text-align: center;
    min-height: 35px;
    display: block
}

#side-nav li span {
    padding-top: 7px;
    display: block
}

#side-nav li.active {
    background: #254971;
    color: #fff
}

#side-nav li.active:after,
#side-nav li.active:before {
    content: "";
    width: 4px;
    height: 4px;
    background: #254971;
    z-index: 99
}

#side-nav li.active:before {
    border-radius: 4px 0 0 0;
    position: absolute;
    left: 0;
    top: -3px
}

#side-nav li.active:after {
    border-radius: 0 0 0 4px;
    position: absolute;
    left: 0;
    bottom: -3px
}

.box-shadow {
    box-shadow: 0 -1px 2px rgba(0, 0, 0, .6)
}

.reg-opt {
    clear: both;
    float: left;
    width: 100%
}

.reg-opt .ui-btn-text {
    color: #7c7c7c!important
}

.reg-opt .ui-radio {
    clear: none;
    float: left;
    margin-right: 20px
}

.static-cont {
    margin-bottom: 0;
    font-size: .9em;
    line-height: 140%
}

.static-cont h6 {
    padding: 10px 10px 2px;
    border-bottom: 1px solid #e4e4e4;
    font-size: 1.1em;
    font-weight: 700
}

.static-cont p,
.sub-title {
    padding: 10px;
    line-height: 135%
}

.sub-title {
    padding: 10px 10px 0;
    line-height: 135%
}

.static-cont ol,
.static-cont ul {
    padding: inherit!important;
    margin: inherit!important;
    padding-top: 10px!important;
    padding-bottom: 10px!important
}

.static-cont ul li {
    list-style-type: disc;
    margin: 0 0 8px 25px
}

.static-cont ol li {
    list-style-type: lower-alpha;
    margin: 0 0 8px 30px
}

.static-cont ul ul li {
    list-style-type: circle;
    margin: 0 0 8px 30px
}

.pt20 {
    padding-top: 20px!important
}

#no-result,
#paging,
.top-msg-row {
    background: #f3f1ef;
    border-bottom: 2px solid #e7e7e7;
    border-top: 2px solid #e7e7e7;
    margin-bottom: 1.5em
}

.top-msg-row {
    margin: 1.2em 0 1em 0;
    padding: 0 10px;
    overflow: hidden
}

#no-result p,
#no-result-filter p,
#paging a {
    font-weight: 700;
    color: #565656;
    text-shadow: 0 1px 1px #fff;
    padding: .6em
}

#no-result p,
#no-result-filter p {
    text-align: center
}

#paging a.prev {
    float: left
}

#paging a.next {
    float: right
}

.head-icon,
.head-icon-b,
.head-icon-r {
    top: 15px;
    left: 6px;
    position: absolute;
    padding: 3px
}

.head-icon-b {
    top: 12px
}

.head-icon-r {
    left: auto;
    right: 5px;
    top: 10px!important
}

.mb0 {
    margin-bottom: 0
}

.mtb0 {
    margin-top: 0;
    margin-bottom: 0
}

#category-button:after,
#course_input-button:after,
#eng_branch_pref1-button:after,
#eng_branch_pref2-button:after,
#eng_branch_pref3-button:after,
#home-tab ul li.active a span,
#isdCode-button:after,
#level-button:after,
#location_input-button:after,
#menteeExamYr-button:after,
#menteeRequestSlotAMPM-button:after,
#menteeRequestSlotHour-button:after,
#menteeRequestSlotMin-button:after,
#motivation-button:after,
#motivationReviewRatingFactor-button:after,
#select-5-button:after,
#userCategory-button:after,
#userRound-button:after,
#yearOfGraduation-button:after,
.arrow-d,
.comp-circle,
.filter-arr,
.icn-10,
.icn-11,
.icn-12,
.icn-13,
.icn-14,
.icn-2,
.icn-3,
.icn-4,
.icn-5,
.icn-6,
.icn-7,
.icn-9,
.icon-404,
.icon-about,
.icon-arrow-dwn,
.icon-arrow-left,
.icon-arrow-r2,
.icon-arrow-up,
.icon-article,
.icon-ask,
.icon-ask2,
.icon-brightness,
.icon-bubbles,
.icon-busy,
.icon-check,
.icon-checkmark,
.icon-course,
.icon-done i,
.icon-eligible,
.icon-facebook,
.icon-file,
.icon-file2,
.icon-gplus,
.icon-heart,
.icon-heart2,
.icon-home,
.icon-home2,
.icon-image,
.icon-info,
.icon-inst,
.icon-latest,
.icon-library,
.icon-library2,
.icon-location,
.icon-logout,
.icon-medal,
.icon-menu,
.icon-mobile,
.icon-next,
.icon-pencil,
.icon-phone,
.icon-play,
.icon-play2,
.icon-policy,
.icon-popular,
.icon-predictor,
.icon-prev,
.icon-qna,
.icon-rating,
.icon-reg,
.icon-related,
.icon-right,
.icon-rupee,
.icon-search,
.icon-search2,
.icon-select,
.icon-select2,
.icon-star,
.icon-student,
.icon-terms,
.icon-tick,
.icon-tweet,
.icon-user,
.nextSlider-icon,
.plus-icon,
.pointer-404,
.prev-icon,
.prevSlider-icon,
.right-arrow,
.sprite,
.star,
.ui-select .ui-btn-icon-right .ui-icon {
    background-image: url(//images.shiksha.ws/public/mobile5/images/icons-sprite.png);
    display: inline-block;
    position: relative;
    float: left
}

.icn-10,
.icn-11,
.icn-12,
.icn-13,
.icn-14,
.icn-2,
.icn-3,
.icn-4,
.icn-5,
.icn-6,
.icn-7,
.icn-9 {
    background-position: 0 -100px;
    width: 27px;
    height: 27px
}

.icn-3 {
    background-position: 0 -100px
}

.icn-2 {
    background-position: -30px -100px
}

.icn-4 {
    background-position: -60px -100px
}

.icn-10 {
    background-position: -90px -100px
}

.icn-14 {
    background-position: -120px -100px
}

.icn-12 {
    background-position: -150px -100px
}

.icn-6 {
    background-position: -180px -100px
}

.icn-5 {
    background-position: -210px -100px
}

.icn-13 {
    background-position: -240px -100px
}

.icn-7 {
    background-position: -270px -100px
}

.icn-9 {
    background-position: -300px -100px
}

.icn-11 {
    background-position: -330px -100px
}

.icon-404,
.icon-about,
.icon-article,
.icon-ask,
.icon-ask2,
.icon-file,
.icon-file2,
.icon-home,
.icon-home2,
.icon-library,
.icon-library2,
.icon-logout,
.icon-phone,
.icon-policy,
.icon-qna,
.icon-reg,
.icon-related,
.icon-search2,
.icon-student,
.icon-terms,
.icon-user {
    background-position: -30px 0;
    width: 27px;
    height: 27px
}

.icon-404 {
    background-position: -388px -100px
}

.icon-home {
    background-position: 0 -70px
}

.icon-home2 {
    background-position: 0 -130px
}

.icon-related {
    background-position: -90px 0
}

.icon-file2 {
    background-position: -484px 0
}

.icon-ask {
    background-position: -509px 0
}

.icon-ask2 {
    background-position: 0 0
}

.icon-user {
    background-position: -120px 0
}

.icon-library {
    background-position: -538px 0
}

.icon-qna {
    background-position: -210px 0
}

.icon-about {
    background-position: -240px 0
}

.icon-phone {
    background-position: -330px 0
}

.icon-student {
    background-position: -421px 0
}

.icon-policy {
    background-position: -391px 0
}

.icon-terms {
    background-position: -330px 0
}

.icon-search2 {
    background-position: -418px -100px
}

.icon-reg {
    background-position: -30px -130px
}

.icon-logout {
    background-position: -514px 0
}

.icon-search {
    background-position: 0 -35px;
    width: 19px;
    height: 18px;
    float: none;
    vertical-align: middle
}

.icon-arrow-left,
.icon-menu {
    background-position: -79px -35px;
    width: 24px;
    height: 21px
}

.icon-menu {
    background-position: -139px -35px;
    width: 21px;
    height: 13px
}

.icon-mobile,
.icon-pencil {
    background-position: -284px -36px;
    width: 20px;
    height: 19px;
    overflow: hidden;
    right: 2px
}

.icon-pencil {
    top: 2px;
    left: -1px
}

.icon-mobile {
    background-position: -249px -70px;
    width: 17px;
    height: 25px;
    top: -5px
}

.icon-heart {
    background-position: -524px -35px;
    width: 25px;
    height: 21px;
    top: 1px
}

.icon-heart2 {
    background-position: 0 -70px;
    width: 26px;
    height: 22px
}

.icon-busy,
.myList-icon {
    background-position: -193px -35px;
    height: 16px;
    width: 22px;
    top: 8px;
    float: none
}

.right-arrow {
    background-position: -405px -35px;
    width: 12px;
    height: 22px
}

.arrow-d {
    background-position: -169px -35px;
    width: 24px;
    height: 13px;
    float: none;
    top: 13px
}

.icon-check,
.icon-done i {
    background-position: -289px -70px;
    width: 21px;
    height: 15px;
    left: 4px;
    top: 6px
}

.icon-check {
    left: auto;
    right: 10px;
    top: 0;
    position: absolute
}

.icon-brightness {
    background-position: -502px -35px;
    width: 19px;
    height: 30px;
    top: 6px;
    float: none
}

.icon-bubbles {
    background-position: -109px -35px;
    width: 26px;
    height: 26px;
    top: 6px;
    float: none
}

.icon-bubbles {
    background-position: -109px -35px;
    width: 26px;
    height: 26px;
    top: 6px;
    float: none
}

.icon-location,
.icon-select,
.icon-select2 {
    background-position: -22px -35px;
    width: 18px;
    height: 11px;
    position: absolute;
    right: 8px;
    top: 43%
}

.icon-select2 {
    background-position: -64px -35px;
    width: 9px;
    height: 14px;
    top: 30%
}

.icon-location {
    background-position: -42px -35px;
    width: 15px;
    height: 17px;
    top: 11px
}

.icon-play {
    background-position: -225px -35px;
    width: 26px;
    height: 11px
}

.icon-inst {
    background-position: -308px -35px;
    width: 28px;
    height: 24px;
    float: none
}

.icon-play2 {
    background-position: -339px -35px;
    width: 9px;
    height: 15px;
    float: right;
    top: 7px;
    right: 5px
}

.icon-info {
    background-position: -45px -70px;
    width: 26px
}

.icon-course {
    background-position: -73px -70px;
    width: 19px
}

.icon-image {
    background-position: -122px -70px;
    width: 23px
}

.icon-star {
    background-position: -148px -70px;
    width: 23px
}

.icon-course,
.icon-image,
.icon-info,
.icon-latest,
.icon-popular,
.icon-star {
    display: block;
    line-height: 125%;
    font-size: 1.6em;
    float: none;
    margin: 0 auto;
    height: 23px
}

.icon-next,
.icon-prev {
    background-position: -376px -70px;
    width: 8px;
    height: 16px;
    margin-right: 2px;
    top: -1px;
    float: none;
    vertical-align: middle
}

.icon-next {
    background-position: -387px -70px;
    margin-left: 2px;
    margin-right: 0
}

.icon-arrow-dwn,
.icon-arrow-up {
    background-position: -313px -70px;
    width: 16px;
    height: 9px;
    top: 48%;
    right: 5px;
    float: none;
    position: absolute
}

.icon-arrow-dwn {
    background-position: -313px -83px
}

.filter-arr {
    position: absolute;
    bottom: -15px;
    left: 45%;
    background-position: -332px -70px;
    width: 21px;
    height: 15px
}

.icon-tick {
    background-position: -359px -99px;
    width: 27px;
    height: 27px
}

.icon-arrow-r2 {
    background-position: -30px -70px;
    width: 13px;
    height: 19px;
    float: right;
    top: 0
}

.star {
    background-position: -358px -70px;
    width: 15px;
    height: 15px;
    top: 5px;
    float: none;
    text-align: left;
    margin-right: 1px
}

.pointer-404 {
    background-position: -361px -35px;
    width: 36px;
    height: 31px;
    position: absolute;
    left: 40%;
    bottom: -30px
}

.icon-eligible {
    background-position: -398px -35px;
    width: 19px;
    height: 10px;
    top: 2px
}

.icon-medal {
    background-position: -420px -35px;
    width: 13px;
    height: 17px;
    left: 3px;
    top: 1px
}

.icon-rupee {
    background-position: -435px -35px;
    width: 18px;
    height: 18px;
    left: 0;
    top: 1px
}

#side-nav li:active,
.head-icon-b:active,
.head-icon-r:active,
.head-icon:active,
.inst-detail-list dt a:active,
.location-list li:active,
.other-opt a:active,
.req-bro-box:active,
.review-list li:active,
.shortlist-box .comp-detail-item:active,
.similar-course:active,
.similar-section:active,
.sorting a:active,
.stream-list li:active,
ul.layer-list li:active,
ul.notification-list li:active {
    background-color: #f4f4f4
}

.shortlist-box:active {
    background: #fff
}

.home-search-btn:active {
    background-color: #9f9f9f
}

ul.layer-list li.search-option:active {
    background: #fff;
    position: relative
}

.search-filter p:active {
    color: #fff;
    text-shadow: 0 1px 0 rgba(0, 0, 0, .3)
}

.search-filter p .icon-check {
    top: 11px;
    right: 8px
}

ul.layer-list li.non-click:active {
    background: 0 0
}

.i.photo-widget .figurecon-plus,
.icon-minus {
    color: #fff;
    position: relative;
    top: -1px;
    background: #8f8f8f;
    width: 25px;
    height: 20px;
    display: inline-block;
    float: left;
    border: 1px solid #fafafa;
    text-align: center;
    font: 400 18px/19px inherit;
    font-style: normal;
    cursor: pointer
}

.icon-minus {
    font-family: "Courier New", Courier, monospace;
    line-height: 16px
}

.inst-detail-list {
    padding-bottom: 10px
}

.inst-detail-list dt {
    border-bottom: 1px solid #dfdfdf
}

#courseTabDesc dt {
    margin-bottom: 1em;
    clear: both
}

#courseTabDesc dt p {
    color: #000!important
}

.inst-detail-list dt a {
    display: block;
    padding: .8em .3em .5em;
    position: relative
}

.inst-detail-list dt h2,
.inst-detail-list dt h3,
.inst-detail-list dt p {
    font-weight: 700;
    color: #8d8d8d;
    margin: 0 15px 0 0
}

.inst-detail-list dt h2,
.inst-detail-list dt h3 {
    float: none
}

.inst-detail-list dd {
    margin: 10px 0 22px;
    line-height: 125%
}

.tiny-contents {
    font-family: inherit!important;
    font-size: .9em
}

.tiny-contents em {
    margin-bottom: 0!important
}

.tiny-contents p {
    line-height: 125%!important
}

.thnx-msg {
    clear: both;
    color: #666;
    overflow: hidden;
    margin: 15px 0 10px;
    float: left;
    width: 100%
}

.thnx-msg p {
    margin: 2px 0 0 35px;
    display: block;
    font-size: 14px!important;
    font-weight: 400;
    color: #666
}

.refine-box {
    display: block;
    margin-bottom: 1.2em;
    padding: 0 .8em .5em
}

.refine-box p {
    margin: 0 0 3px 0;
    font-weight: 700
}

.refine-box ul li {
    display: block;
    width: 100%;
    margin-bottom: .8em
}

.pr5 {
    padding-right: 5px
}

.sub-branch {
    margin-left: 10px
}

.blue-bar {
    background: #264971;
    padding: 10px 0 10px 6px;
    color: #fff;
    font-size: .9em
}

.blue-bar p {
    margin-bottom: 3px
}

.blue-bar h5 {
    font-weight: 700;
    color: #fcd144
}

#locationDiv,
#preferredCourse,
#subcategoryDiv {
    background: #fff
}

#wrapper-404 {
    margin: 0 .5em
}

#wrapper-404 p {
    font-size: 1.1em;
    margin-bottom: .5em;
    line-height: 130%
}

#wrapper-404 p a {
    font-size: 1.2em
}

#wrapper-404 article {
    border: solid 2px #d8d8d8;
    padding: 10px;
    background: #ececec;
    box-shadow: 0 1px 3px rgba(0, 0, 0, .1);
    position: relative
}

#wrapper-404 figure {
    text-align: center;
    margin-top: 40px;
    margin-left: 20px
}

.of-hide {
    overflow-x: hidden!important
}

.owl-carousel .owl-wrapper:after {
    content: ".";
    display: block;
    clear: both;
    visibility: hidden;
    line-height: 0;
    height: 0
}

.owl-carousel {
    display: none;
    position: relative;
    width: 100%;
    -ms-touch-action: pan-y;
    margin-bottom: .3em
}

.owl-carousel .owl-wrapper {
    display: none;
    position: relative;
    -webkit-transform: translate3d(0, 0, 0);
    -webkit-perspective: 1000
}

.owl-carousel .owl-wrapper-outer {
    overflow: hidden;
    position: relative;
    width: 100%
}

.owl-carousel .owl-item {
    float: left
}

.owl-controlls .owl-buttons div,
.owl-controlls .owl-page {
    cursor: pointer
}

.owl-controlls {
    -webkit-user-select: none;
    -khtml-user-select: none;
    -moz-user-select: none;
    -ms-user-select: none;
    user-select: none;
    -webkit-tap-highlight-color: transparent
}

.owl-carousel .owl-item,
.owl-carousel .owl-wrapper {
    -webkit-backface-visibility: hidden;
    -moz-backface-visibility: hidden;
    -ms-backface-visibility: hidden
}

.registrationLoader {
    background: url(https://www.shiksha.com/public/images/loader.gif) no-repeat center
}

.owl-theme .owl-controlls {
    text-align: center;
    position: absolute;
    left: 44%;
    bottom: 10px
}

.owl-theme .owl-controlls .owl-page {
    display: inline-block;
    zoom: 1/*IE7 life-saver */
}

.owl-theme .owl-controlls .owl-page span {
    display: block;
    width: 9px;
    height: 9px;
    margin: 5px 8px 0 0;
    opacity: .9;
    -webkit-border-radius: 20px;
    -moz-border-radius: 20px;
    border-radius: 20px;
    background: #bcbcbc
}

.owl-theme .owl-controlls .owl-page.active span {
    background-color: #fff;
    border: 1px solid #9d9d9d
}

.sorting {
    border-bottom: 1px solid #e5e4e4;
    padding: 17px 10px 12px;
    color: #b7b7b7
}

.sorting strong {
    font-weight: 700;
    color: grey
}

.sorting a {
    padding: 0 5px
}

.similar-section {
    padding: 4px 10px 7px;
    margin-bottom: 10px;
    border-bottom: 1px solid #eaeaea
}

.similar-section h5 {
    font-size: .95em;
    margin-bottom: 12px;
    font-weight: 700
}

.similar-section .notify-details {
    font-size: inherit!important;
    padding: 0
}

.similar-section .notify-details em {
    margin-top: 0!important;
    margin-bottom: .3em!important
}

.similar-course {
    border-style: dotted;
    border-color: #d3d3d3;
    border-width: 2px 0;
    padding: 10px 8px;
    color: #737373;
    position: relative
}

.similar-course p {
    font-size: 1.3em;
    font-weight: 700;
    margin-right: 20px
}

.similar-course .icon-arrow-r2 {
    position: absolute;
    top: 35%;
    float: none;
    right: 8px
}

.search-filter p {
    border-bottom: 1px solid #e2e2e2;
    padding: 10px 0 10px 8px;
    color: #515151;
    position: relative
}

.search-filter p a {
    color: #5b5b5b!important;
    text-decoration: none!important
}

.search-filter p.active {
    font-weight: 700;
    color: #000;
    background: #e3e3e3
}

.search-btn {
    border: 0 none!important;
    border-radius: 0;
    -moz-box-shadow: 0 2px 2px rgba(0, 0, 0, .5);
    -webkit-box-shadow: 0 2px 2px rgba(0, 0, 0, .5);
    box-shadow: 0 2px 2px rgba(0, 0, 0, .5);
    margin-top: 20px
}

.search-title {
    padding: 20px 0 6px 8px;
    font-size: 1.1em;
    font-weight: 700
}

#page-not-found {
    padding: 10px 0 15px
}

#page-not-found p.not-foundTxt {
    padding: 5px 0 20px 32px;
    clear: left;
    font-size: 1em
}

.home-search {
    clear: left;
    position: relative
}

.home-search aside {
    background: #fff;
    border: 1px solid #d0d0d0;
    display: table;
    width: 100%;
    -moz-box-sizing: border-box;
    -webkit-box-sizing: border-box;
    box-sizing: border-box
}

.button-base,
.search-base {
    display: table-cell;
    vertical-align: top;
    position: relative
}

.activeLink .icon-check {
    top: 34%
}

.search-option2 {
    padding: 2px 10px 12px;
    border-bottom: 1px solid #dedede
}

.or-sep {
    position: relative;
    display: table;
    width: 100%;
    margin-top: 5px
}

.or-sep hr.h-rule {
    display: table-cell;
    width: 45%
}

.or-sep span {
    position: absolute;
    top: -8px;
    color: #919090;
    font-size: 13px;
    display: table-cell;
    width: 10%;
    text-align: center
}

.exam-label {
    float: left;
    width: 45%;
    display: block;
    padding: .8em 0 .8em .8em
}

.exam-label input {
    position: relative;
    top: -2px
}

.login-btn div {
    font-size: .75em
}

html.gecko #home-tab ul li.active a span {
    bottom: -14px
}

html.gecko #search,
html.gecko #search-loc {
    padding: 5px 0 6px
}

html.gecko .icon-search {
    top: -2px
}

html.gecko .head-filter p,
html.gecko .head-refine p {
    top: -5px
}

.change-loc {
    background: rgba(156, 125, 23, 1);
    border-radius: 5px;
    color: #fff!important;
    padding: 0 4px 1px;
    text-shadow: 0 -1px 0 #907723;
    margin-left: 5px
}

.transparency {
    background: rgba(156, 125, 23, .4);
    padding: 2px 2px 3px 4px;
    margin: 0
}

.icon-right {
    background-position: -429px -70px;
    width: 5px;
    height: 10px;
    float: none;
    top: 1px;
    margin-left: 3px
}

.unsubs-text {
    margin: 0 0 25px 0;
    font-size: 1.1em;
    line-height: 22px
}

.unsubs-btn-box {
    margin-top: 20px;
    float: left;
    width: 100%;
    border-top: 1px solid #cacaca;
    padding: 20px 0 30px 0
}

#city-list-textbox,
#search,
#search-loc,
#searchInstitute1,
#state-list-textbox,
.searchInstitute {
    border: 0 none;
    font-size: .9em;
    font-family: inherit;
    width: 80%;
    background: 0 0;
    padding: 6px 0;
    line-height: normal;
    margin: 0;
    -moz-border-radius: 0;
    -webkit-border-radius: 0;
    border-radius: 0;
    vertical-align: middle
}

#searchInstitute1,
.searchInstitute {
    height: 32px;
    display: table;
    position: relative;
    left: 5px;
    width: 99%!important;
    -moz-box-sizing: border-box;
    -webkit-box-sizing: border-box;
    box-sizing: border-box;
    font-size: 13px!important;
    vertical-align: middle!important;
    padding: 0!important
}

.quick-link-head {
    font-size: 15px;
    color: #505251;
    font-weight: 700
}

.link-box {
    margin: 3px 0 15px 0
}

.link-box a {
    color: #4f5352;
    font-size: 13px;
    line-height: 26px
}

.link-sep {
    color: #b9b9b9;
    margin: 0 3px
}

.icon-library2 {
    background-position: -180px 0
}

.rank-number {
    color: #fff;
    font-size: 18px;
    background: #666;
    position: absolute;
    left: 0;
    top: 0;
    text-align: center;
    font-weight: 700;
    width: 40px
}

.rank-details {
    margin-left: 2.4em
}

.rank-details h4 {
    font-weight: 700!important
}

select.refine-search {
    border: 1px solid #ccc;
    outline: 0;
    padding: .3em;
    font-size: 1em;
    margin-right: .2em
}

.rank-details p {
    margin-bottom: .3em;
    color: #999;
    font-size: .9em
}

.rank-details p span {
    color: #000
}

ul.rnr-list li {
    padding: .8em .7em
}

ul.rnr-list li:active {
    background: 0 0!important
}

.rnr-list label {
    display: block
}

.rnr-list label input[type=checkbox],
.rnr-list label input[type=radio] {
    vertical-align: middle
}

.rnr-list select {
    margin-top: 8px
}

.rnr-title-txt {
    padding: 3px 10px 5px;
    font-size: 1.1em
}

.icon-latest {
    background-position: -121px -130px;
    width: 29px;
    height: 25px
}

.icon-popular {
    background-position: -187px -130px;
    width: 33px;
    height: 25px
}

#tab td.active .icon-latest {
    background-position: -90px -130px
}

#tab td.active .icon-popular {
    background-position: -154px -130px
}

.tupple-wrap {
    padding: 12px 11px 12px 10px;
    display: block
}

.tupple-wrap h2,
.tupple-wrap h3 {
    display: block;
    margin-bottom: 5px;
    color: #008489
}

#relatedArticles .tupple-wrap {
    border-bottom: 1px solid #e6e6e6
}

#relatedArticles .tupple-wrap::after {
    display: table;
    clear: both;
    content: ""
}

.tupple-content {
    margin-bottom: 10px;
    color: #797979;
    line-height: 130%;
    font-size: 14px;
    color: #666
}

.footer-links {
    color: #006fa2;
    font-size: 95%;
    line-height: 125%
}

.footer-links label {
    color: #aeadad;
    float: left;
    width: 60px
}

.footer-links p {
    margin-left: 60px
}

.article-content {
    padding: 10px;
    color: #000;
    line-height: 1.58;
    word-wrap: break-word
}

.article-content p {
    margin-bottom: 12px
}

.article-heading {
    font-weight: 700;
    margin-bottom: 3px;
    font-size: 1.2em
}

.article-content ul {
    list-style: disc;
    margin-bottom: 15px
}

.article-content ul li {
    margin: 0 0 5px 18px
}

.article-content ol li {
    margin: 0 0 5px 18px
}

.article-content ol li ol,
.article-content ul li ol,
.article-content ul li ul {
    margin-top: 12px
}

.article-keywords {
    color: #000
}

.article-keywords p {
    margin-bottom: 4px
}

.article-keywords span {
    color: #666
}

.related-articles-head {
    background: #ededed;
    padding: 12px 10px;
    box-shadow: 0 4px 3px #dfdfdf;
    font-weight: 700;
    margin-bottom: 10px;
    font-size: 1.2em;
    color: #515151
}

.article-img-box {
    width: 100%;
    padding-top: 10px;
    position: relative
}

.article-img-box img {
    width: 100%;
    max-width: 615px;
    display: block
}

.icon-article {
    background-position: -60px -130px
}

.slide-counter {
    background-color: rgba(0, 0, 0, .5);
    padding: 3px 5px;
    border: 1px solid rgba(255, 255, 255, .5);
    position: absolute;
    right: 10px;
    bottom: 10px;
    color: #fff;
    font-size: 15px;
    border-radius: 6px;
    font-weight: 700
}

ul.slide-bullet {
    position: absolute;
    bottom: 15px;
    left: 4%
}

ul.slide-bullet li {
    background: #bcbcbc;
    width: 8px;
    height: 8px;
    border-radius: 50%;
    float: left;
    margin-right: 5px;
    border: 2px solid transparent
}

ul.slide-bullet li.active {
    border: 2px solid #bcbcbc;
    background: #fff
}

.slide-content {
    padding: 15px 10px 10px;
    color: #000;
    line-height: 125%
}

.slide-content ul {
    list-style: disc;
    margin-bottom: 15px
}

.slide-content ul li {
    margin: 0 0 5px 18px
}

.slide-content ol li {
    margin: 0 0 5px 18px
}

.slide-content ol li ol,
.slide-content ul li ol,
.slide-content ul li ul {
    margin-top: 12px
}

.slide-content .embed {
    width: 95%!important
}

.nxt-prev {
    background: #f9f9f9;
    float: left;
    width: 100%
}

.nxt-prev li a {
    display: block;
    width: 100px;
    text-align: center;
    background: #a1a1a1;
    color: #fff!important;
    padding: 5px 0;
    font-weight: 700
}

.nxt-icon,
.prv-icon {
    background-position: -224px -130px;
    width: 11px;
    height: 19px;
    margin: 0 auto 3px;
    display: block;
    float: none
}

.nxt-icon {
    background-position: -236px -130px
}

.poll-box {
    background: #ecf1ff;
    padding: 12px;
    margin-bottom: 10px
}

.poll-box strong,
.poll-result strong {
    display: block;
    margin-bottom: 10px
}

.poll-box ul li,
.poll-result ul li {
    list-style: none;
    margin: 0 0 8px 0;
    padding: 0;
    clear: left
}

.poll-box input[type=radio] {
    width: 15px;
    float: left
}

.poll-box ul li p {
    margin: 0 0 0 20px
}

.poll-result {
    border: 1px solid #dedede;
    padding: 10px;
    margin: 25px 0
}

.poll-result ul li {
    margin-bottom: 18px
}

.poll-result p {
    margin-bottom: 3px
}

.poll-bar-1,
.poll-bar-2,
.poll-bar-3,
.poll-bar-4,
.poll-bar-5 {
    background: #c9dc82;
    float: left;
    padding: 0
}

.poll-bar-2 {
    background: #ff7e71
}

.poll-bar-3 {
    background: #53cbd9
}

.poll-bar-4 {
    background: #f3f789
}

.poll-bar-5 {
    background: #ffde9f
}

.comp-percent {
    margin-left: 5px
}

.article-content table {
    width: 100%;
    border-collapse: collapse;
    margin: 0!important
}

.article-content table td,
.article-content table th {
    padding: .5em
}

.article-content table tr:nth-of-type(even) {
    background: #f3f3f3
}

.article-content table td {
    min-height: 1.625em;
    word-wrap: break-word
}

.social-section {
    width: 100%;
    float: left;
    overflow-x: hidden
}

ul.social-links {
    display: block;
    width: 100%;
    margin: 15px 0
}

ul.social-links li {
    display: block
}

ul.social-links li a {
    text-align: center;
    display: block;
    padding: 8px 0;
    color: #fff;
    font-weight: 600;
    background: #8cc1db
}

ul.social-links li:first-child a {
    background: #3a589a
}

ul.social-links li:last-child a {
    background: #e5e5e4;
    color: #595a5c;
    text-shadow: none;
    font-size: 14px;
    font-weight: 400
}

.icon-facebook,
.icon-gplus,
.icon-tweet,
.icon-wtapp {
    background-position: -248px -130px;
    width: 9px;
    height: 15px;
    float: none;
    margin-right: 3px;
    vertical-align: baseline
}

.icon-tweet {
    background-position: -260px -130px;
    width: 14px;
    height: 11px
}

.icon-gplus {
    background-position: -277px -130px;
    width: 14px;
    height: 14px
}

.icon-wtapp {
    background-position: -32px -183px;
    width: 14px;
    height: 14px;
    top: 2px;
    margin-right: 4px
}

.scrollable.has-scroll:after {
    position: absolute;
    top: 0;
    left: 100%;
    width: 50px;
    height: 100%;
    border-radius: 10px 0 0 10px/50% 0 0 50%;
    box-shadow: -5px 0 10px rgba(0, 0, 0, .25);
    content: ''
}

.scrollable.has-scroll>div {
    overflow-x: auto
}

.scrollable>div::-webkit-scrollbar {
    height: 12px
}

.scrollable>div::-webkit-scrollbar-track {
    box-shadow: 0 0 2px rgba(0, 0, 0, .15) inset;
    background: #f0f0f0
}

.scrollable>div::-webkit-scrollbar-thumb {
    border-radius: 6px;
    background: #ccc
}

.qna-desc-title {
    font-weight: 700;
    font-style: italic;
    color: #979797;
    margin: 5px 0 10px 0
}

.qna-section {
    float: left;
    width: 100%;
    clear: left;
    margin-top: 8px
}

.qna-wrap {
    float: left;
    padding: 0;
    width: 100%;
    margin: 0 0 18px 0;
    list-style: none
}

.qna-icon {
    background: #cecece;
    border-radius: 50%;
    color: #fff;
    display: block;
    float: left;
    font: 700 20px/30px "Open Sans", sans-serif;
    height: 30px;
    text-align: center;
    width: 30px
}

.qna-details {
    color: #121212;
    margin: 5px 0 0 40px
}

.qna-box {
    width: 100%;
    float: left;
    margin-bottom: 5px
}

.embed iframe {
    width: 95%!important
}

.photo-widget {
    width: 100%!important
}

.photo-widget .figure {
    width: 100%!important;
    float: left
}

.photo-widget p span {
    float: none!important
}

.photo-widget p strong {
    display: block!important;
    margin-bottom: 5px!important
}

.photo-widget .figure img {
    width: 100%!important
}

.photo-widget p {
    width: 100%!important;
    background: #ddd;
    padding: 5px;
    -webkit-box-sizing: border-box;
    -moz-box-sizing: border-box;
    box-sizing: border-box
}

.photo-widget-full {
    width: 100%!important
}

.photo-widget-full .figure {
    width: 100%!important;
    float: left
}

.photo-widget-full p span {
    float: none!important
}

.photo-widget-full p strong {
    display: block!important;
    margin-bottom: 5px!important
}

.photo-widget-full .figure img {
    width: 100%!important
}

.photo-widget-full p {
    width: 100%!important;
    background: #ddd;
    padding: 5px;
    -webkit-box-sizing: border-box;
    -moz-box-sizing: border-box;
    box-sizing: border-box
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

.quote-box {
    quotes: "“" "”" "‘" "’"
}

.quote-box:before {
    content: open-quote;
    font: 700 22px Georgia;
    color: #666
}

.quote-box:after {
    content: close-quote;
    font: 700 22px Georgia;
    color: #666
}

.nextSlider-icon,
.prevSlider-icon {
    background-position: -294px -130px;
    width: 18px;
    height: 40px;
    position: absolute;
    top: 40%;
    margin-left: 5px
}

.nextSlider-icon {
    background-position: -318px -130px;
    right: 0;
    margin-right: 5px
}

.icon-entrance,
.icon-predictor {
    background-position: -401px -130px;
    height: 27px;
    width: 27px
}

.icon-entrance {
    background-position: -371px -130px
}

.college-predictor-tab,
.college-predictor-tab table td {
    height: auto!important
}

.college-predictor-tab table td {
    padding: 0 10px!important;
    vertical-align: top!important
}

.college-predictor-tab table td a {
    text-align: left!important
}

.serach-result .institute-name {
    color: #000;
    font-weight: 700;
    margin-bottom: 10px
}

.serach-result .institute-name span {
    color: #828282
}

.serach-result .course-name {
    font-weight: 700;
    color: #006fa2;
    margin-bottom: 6px
}

.helpful-box {
    background: #f7f6f6;
    padding-bottom: 1em
}

.helpful-box strong {
    color: #686868;
    display: block;
    margin-bottom: 8px
}

.icon-closerank {
    background-position: -399px -49px;
    width: 15px;
    height: 14px;
    left: 2px
}

.icon-round {
    background-position: -441px -82px;
    width: 13px;
    height: 17px;
    left: 2px
}

.thumbsdown-icon,
.thumbsup-icon {
    background-position: -418px -54px;
    width: 17px;
    height: 16px;
    float: none;
    top: 5px;
    margin-right: 4px
}

.thumbsdown-icon {
    background-position: -437px -54px
}

.email-icon {
    background-position: -437px -70px;
    width: 14px;
    height: 10px;
    margin-right: 4px;
    top: 5px
}

.email-result {
    background: url(//images.shiksha.ws/public/mobile5/images/email-result.png) 0 0 no-repeat;
    width: 36px;
    height: 168px;
    position: fixed;
    right: 0;
    top: 40%;
    z-index: 999;
    cursor: pointer
}

.clg-predctr * {
    font-family: 'Open Sans'
}

#clg-bg {
    background: #0a373a url(//images.shiksha.ws/public/mobile5/images/cp-banner.jpg) no-repeat top center;
    background-size: cover
}

.clg-bg {
    width: 100%;
    color: #fff
}

.clg-txBx {
    padding: 30px 10px 20px 12px
}

.clg-txBx p {
    text-align: left;
    line-height: 22px;
    font-size: 14px;
    margin-left: 6px
}

.clg-txBx p.sb-hdng {
    text-align: left;
    font-size: 14px;
    line-height: 21px;
    margin-top: 12px;
    margin-left: 5px
}

.clg-txBx p span {
    font-size: 20px;
    font-weight: 700;
    display: inline-block
}

.clg-txBx ul {
    margin: 5px 0 0 20px;
    font-size: 12px;
    font-weight: 400
}

.clg-txBx ul li {
    list-style: disc;
    margin-bottom: 3px
}

.recommen-head {
    background: #ebf6fc;
    -moz-box-shadow: 0 2px 2px #d6d6d6;
    -webkit-box-shadow: 0 2px 2px #d6d6d6;
    box-shadow: 0 2px 2px #d6d6d6;
    padding: 12px 10px;
    font-weight: 700;
    margin-bottom: 10px;
    color: #515151
}

ul.recommen-carausel li {
    float: left;
    width: 100%
}

.carausel-content {
    width: 100%
}

.carausel-content p {
    font-size: .9em
}

.carausel-content strong {
    display: block;
    line-height: 130%;
    margin-bottom: .3em
}

.carausel-content strong span {
    color: #838383;
    font-size: .9em
}

.recommen-bot-row,
.recommen-top-row {
    width: 100%
}

.recommen-bot-row {
    border-top: 1px solid #e3e3e3;
    padding: 15px 0 0 0;
    margin-top: 15px;
    width: 100%
}

.carausel-bullets {
    width: 64px;
    margin: 18px auto 5px auto;
    height: 17px
}

.carausel-bullets li {
    width: 8px;
    height: 8px;
    background: #bcbcbc;
    float: left;
    margin: 2px 8px 0 0;
    -moz-border-radius: 50%;
    -webkit-border-radius: 50%;
    border-radius: 50%;
    border: 2px solid transparent;
    cursor: pointer
}

.carausel-bullets li.active {
    border: 2px solid #bcbcbc;
    background: #fff;
    width: 11px;
    height: 11px;
    margin: 0 8px 0 0
}

.icon-tick2 {
    background-position: -260px -144px;
    width: 15px;
    height: 11px;
    top: 3px
}

.success-msg {
    margin-top: 12px;
    clear: left
}

.success-msg p {
    margin-left: 22px;
    color: #8a8a8a;
    font-weight: 700
}

a.popup-close {
    font-size: 35px;
    color: #666!important;
    margin-top: -2px
}

.register-steps {
    border-bottom: 1px solid #b2b2b2;
    margin: 25px 0;
    position: relative
}

.step-1,
.step-2 {
    position: absolute;
    top: -8px;
    left: 5%;
    width: 60px;
    text-align: center;
    font-weight: 700;
    color: #c8c8c8;
    font-size: .8em
}

.step-1 p,
.step-2 p {
    position: absolute;
    top: 20px;
    width: 100%;
    text-align: center
}

.step-2 {
    left: auto;
    right: 5%
}

.step-1 .point,
.step-2 .point {
    background: #c7c7c7;
    border-radius: 50%;
    width: 16px;
    height: 16px;
    display: inline-block
}

.step-1.active,
.step-2.active {
    color: #3b3b3b
}

.step-1.active .point,
.step-2.active .point {
    background: #fcd146;
    border-radius: 50%;
    width: 18px;
    height: 18px
}

.login-sub-title {
    color: #b3b3b3;
    font-size: .8em
}

ul.suggestion-box {
    width: 100%;
    border: 1px solid #b3b3b3;
    position: absolute;
    background: #fff;
    z-index: 10;
    top: 36px;
    box-sizing: border-box;
    -moz-box-sizing: border-box;
    -webkit-box-sizing: border-box
}

ul.suggestion-box li {
    margin-bottom: 0;
    border-bottom: 1px solid #e5e5e5;
    color: #121212;
    padding: 8px 0 8px 6px;
    font-size: .8em
}

ul.suggestion-box li:last-child {
    border-bottom: none
}

.cross-icon {
    color: #b2b2b2;
    font-size: 28px;
    font-weight: lighter;
    position: absolute;
    right: 66px;
    top: 1px;
    font-style: normal
}

.reg-sprite {
    background: url(//images.shiksha.ws/public/mobile5/images/icons-register.png);
    display: inline-block;
    position: relative;
    vertical-align: middle
}

.tick-mark {
    background-position: -377px 0;
    width: 11px;
    height: 10px;
    position: relative;
    top: 3px;
    left: 2px;
    display: none
}

.step-1.done .tick-mark,
.step-2.done .tick-mark {
    display: block
}

.step-arrow {
    background-position: -365px 0;
    width: 8px;
    height: 16px;
    position: absolute;
    top: -8px;
    left: 50%
}

.icon-wrap {
    background: #fff;
    position: absolute;
    height: 34px;
    border-right: 1px solid #ddd;
    top: 10px;
    z-index: 10;
    width: 35px;
    left: 1px;
    top: 1px;
    text-align: center
}

.calendar-icon {
    background-position: 0 0;
    width: 24px;
    height: 24px;
    top: 6px
}

.stream-icon {
    background-position: -28px 0;
    width: 19px;
    height: 23px;
    top: 4px
}

.completed-icon {
    background-position: -51px 0;
    width: 17px;
    height: 21px;
    top: 6px
}

.marks-icon {
    background-position: -73px 0;
    width: 18px;
    height: 20px;
    top: 6px
}

.study-loc-icon {
    background-position: -96px 0;
    width: 20px;
    height: 23px;
    top: 6px
}

.pref-loc {
    background-position: -121px 0;
    width: 18px;
    height: 21px;
    top: 7px
}

.degree-icon,
.edu-int-icon {
    background-position: -143px 0;
    width: 27px;
    height: 17px;
    top: 6px
}

.grad-details-icon {
    background-position: -173px 0;
    width: 23px;
    height: 23px;
    top: 6px
}

.grad-marks-icon {
    background-position: -201px 0;
    width: 19px;
    height: 22px;
    top: 6px
}

.des-course-icon {
    background-position: -226px 0;
    width: 17px;
    height: 20px;
    top: 7px
}

.fname-icon,
.lname-icon {
    background-position: -250px 0;
    width: 18px;
    height: 20px;
    top: 7px
}

.lname-icon {
    background-position: -271px 0;
    width: 20px
}

.mail-icon {
    background-position: -296px 0;
    width: 21px;
    height: 15px;
    top: 7px
}

.mob-icon {
    background-position: -325px 0;
    width: 18px;
    height: 18px;
    top: 6px
}

.res-loc-icon {
    background-position: -345px 0;
    width: 16px;
    height: 20px;
    top: 6px
}

.work-exp-icon {
    background-position: -392px 0;
    width: 21px;
    height: 18px;
    top: 6px
}

.city-icon {
    background-position: -418px 0;
    width: 21px;
    height: 21px;
    top: 7px
}

.area-icon {
    background-position: -443px 0;
    width: 23px;
    height: 19px;
    top: 7px
}

#registration-box .selectbox p,
#registration-box a.selectbox p {
    padding: 10px 4px 8px 6px
}

#studyIndiaForm .selectbox,
#studyIndiaForm .ui-input-text,
#studyIndiaForm .ui-select .ui-btn-text,
#studyIndiaForm a.selectbox {
    text-indent: 35px
}

#studyIndiaForm .ui-btn-inner,
.reset-styles .ui-btn-inner {
    padding-top: 9px!important;
    padding-bottom: 9px!important
}

#studyIndiaForm div.ui-input-text input.ui-input-text,
.reset-styles div.ui-input-text input.ui-input-text {
    padding: 9px 0!important
}

#studyIndiaForm .ui-btn-inner,
.reset-styles .ui-btn-inner {
    border: 0 none!important
}

.exam-layer-details-box {
    display: none
}

.percentile-icon {
    background-position: -468px 0;
    width: 21px;
    height: 20px;
    top: 7px
}

.appeared-icon {
    background-position: -492px 0;
    width: 15px;
    height: 22px;
    top: 5px
}

.flex-container a:active,
.flex-container a:focus,
.flexslider a:active,
.flexslider a:focus {
    outline: 0
}

.flex-control-nav,
.flex-direction-nav,
.slides {
    margin: 0;
    padding: 0;
    list-style: none
}

.flexslider {
    margin: 0;
    padding: 0
}

.flexslider .slides>li {
    display: none;
    -webkit-backface-visibility: hidden
}

.flexslider .slides img {
    width: 100%;
    display: block
}

.flex-pauseplay span {
    text-transform: capitalize
}

.slides:after {
    content: "\0020";
    display: block;
    clear: both;
    visibility: hidden;
    line-height: 0;
    height: 0
}

html[xmlns] .slides {
    display: block
}

* html .slides {
    height: 1%
}

.no-js .slides>li:first-child {
    display: block
}

.flexslider {
    margin: 2px 0 .9em;
    background: #fff;
    position: relative;
    -webkit-border-radius: 4px;
    -moz-border-radius: 4px;
    -o-border-radius: 4px;
    border-radius: 4px;
    zoom: 1;
    padding-bottom: 20px
}

.flex-viewport {
    max-height: 2000px;
    -webkit-transition: all 1s ease;
    -moz-transition: all 1s ease;
    -o-transition: all 1s ease;
    transition: all 1s ease
}

.loading .flex-viewport {
    max-height: 300px
}

.flexslider .slides {
    zoom: 1
}

.carousel li {
    margin-right: 5px
}

.flex-control-nav {
    width: 100%;
    position: absolute;
    bottom: 0;
    text-align: center
}

.flex-control-nav li {
    margin: 0 6px;
    display: inline-block;
    zoom: 1
}

.flex-control-paging li a {
    width: 11px;
    height: 11px;
    display: block;
    background: #bcbcbc;
    background: rgba(0, 0, 0, .5);
    cursor: pointer;
    text-indent: -9999px;
    -webkit-border-radius: 20px;
    -moz-border-radius: 20px;
    -o-border-radius: 20px;
    border-radius: 20px;
    -webkit-box-shadow: inset 0 0 3px rgba(0, 0, 0, .3) border:1px solid transparent
}

.flex-control-paging li a:hover {
    background: #000;
    background: rgba(0, 0, 0, .7)
}

.flex-control-paging li a.flex-active {
    background: #fff;
    border: 1px solid #9d9d9d;
    cursor: default
}

@media screen and (max-width:860px) {
    .flex-direction-nav .flex-prev {
        opacity: 1;
        left: 10px
    }
    .flex-direction-nav .flex-next {
        opacity: 1;
        right: 10px
    }
}

.shortlist-box .details {
    display: table
}

.comp-detail-item {
    padding: 0 5px 0 3px;
    width: 96%;
    display: table-cell;
    vertical-align: top
}

.short-list-box,
.side-col {
    font-size: 11px;
    text-align: center;
    width: 53px;
    display: table-cell;
    vertical-align: top;
    color: #aeadad
}

.short-list-box {
    width: 100%;
    display: block;
    font-size: 12px
}

.side-col a {
    color: #aeadad
}

.side-col strong {
    font: 700 22px "Open Sans", sans-serif
}

.pref-serialise {
    background: #707070;
    padding: 1px 3px;
    color: #fff;
    position: absolute;
    left: 0;
    top: 0;
    font-weight: 700
}

.gray {
    background-color: #e5e5e4;
    color: #5a595c!important;
    margin: 9px 0 3px;
    font-weight: 700;
    width: 92px;
    font-size: 12px;
    position: relative
}

.plus-icon {
    width: 19px;
    height: 19px;
    position: absolute;
    left: 7px;
    top: 6px;
    background-position: -237px -159px
}

.added-icn {
    background-position: -81px -174px;
    width: 17px;
    height: 14px;
    top: 4px;
    margin-right: 2px;
    left: 8px
}

.shortlist-star,
.shortlisted-star {
    background-position: -29px -160px;
    width: 24px;
    height: 24px;
    float: none
}

.shortlist-star,
.shortlisted-star {
    vertical-align: middle!important
}

.shortlist-star.mcpstar {
    position: absolute;
    z-index: 999999;
    width: 19px;
    height: 19px;
    background: url(//images.shiksha.ws/public/mobile5/images/mobile-sprite.png) no-repeat -116px -184px!important
}

.shortlisted-star {
    background-position: -55px -160px
}

.mylist-layer {
    width: 262px;
    -moz-border-radius: 7px;
    -webkit-border-radius: 7px;
    border-radius: 7px;
    position: absolute;
    left: -179px;
    top: 38px;
    -moz-box-shadow: 0 0 8px rgba(0, 0, 0, .4);
    -webkit-box-shadow: 0 0 8px rgba(0, 0, 0, .4);
    box-shadow: 0 0 8px rgba(0, 0, 0, .4)
}

.list-title {
    -moz-border-radius: 7px 7px 0 0;
    -webkit-border-radius: 7px 7px 0 0;
    border-radius: 7px 7px 0 0;
    background: #387ea9;
    color: #fff;
    padding: 6px 8px 6px 12px;
    font-weight: 700;
    text-align: left
}

.list-title .pointer {
    background-position: -81px -160px;
    width: 22px;
    height: 12px;
    position: absolute;
    top: -10px;
    right: 46px
}

.list-details {
    background: #fff;
    -moz-border-radius: 0 0 7px 7px;
    -webkit-border-radius: 0 0 7px 7px;
    border-radius: 0 0 7px 7px;
    padding: 8px 8px 4px;
    font-size: .9em
}

.list-details ul li {
    margin-bottom: 7px;
    border-bottom: 1px solid #e8e8e8;
    padding: 0 0 7px;
    float: left;
    width: 100%
}

.list-details ul li a span {
    position: relative;
    top: 1px;
    display: block;
    margin-left: 30px;
    text-align: left
}

.list-details ul li.last {
    border-bottom: 0 none;
    margin-bottom: 0
}

.comp-list-icon,
.shortlist-list-icon {
    background-position: -195px -159px;
    width: 19px;
    height: 19px;
    margin: 0 5px
}

.shortlist-list-icon {
    background-position: -216px -159px
}

.myList-icon {
    width: 16px;
    height: 17px;
    position: relative;
    background-position: -139px -51px
}

.mylist-head {
    right: 52px;
    width: 45px
}

.email-icon2 {
    background-position: -261px -157px;
    width: 30px;
    height: 21px;
    float: none;
    right: 5px;
    top: 3px
}

.email-btn {
    font-weight: 400;
    box-shadow: 0 -1px 2px #828282
}

.compare-table {
    border: 0 none;
    text-align: left
}

.compare-table tr td,
.compare-table tr th {
    background: #fff;
    padding: 5px 10px 10px 10px;
    font-weight: 400;
    color: #747474;
    width: 50%
}

.compare-table tr td {
    background: #fff;
    color: #595959;
    padding: 15px 15px;
    text-align: center;
    font-size: 14px;
    text-align: left;
    border: none!important
}

.compare-item {
    color: #000
}

.compare-item strong {
    margin-bottom: 0;
    display: block;
    font-weight: 400;
    font-size: 15px;
    overflow: hidden
}

.compare-item span {
    color: #828282
}

.compare-item p {
    font-size: .8em;
    color: #747371
}

.close-link {
    color: #aaa;
    font-size: 2em;
    float: right;
    margin: 12px 0 8px;
    line-height: 0
}

.compare-table tr td.border-right,
.compare-table tr.collegeListSec td.border-right {
    border-right: 1px solid #f1f1f1!important
}

.compare-table tr td.compare-title {
    padding: 0
}

.compare-table tr td.compare-title h2 {
    background: #f1f1f1;
    font-weight: 600;
    color: #5a595c;
    padding: 8px 0;
    text-align: center;
    text-shadow: 0 1px 0 #fff;
    font-size: 14px;
    float: left;
    width: 100%;
    display: block
}

.college-rank {
    color: #565656
}

.college-rank-icon {
    background-position: 0 -160px;
    width: 14px;
    height: 20px;
    margin-left: 5px;
    float: none;
    vertical-align: middle
}

.data-source-col {
    text-align: left
}

.data-source-col p {
    font-size: 11px;
    color: #676767;
    margin-bottom: 3px
}

.data-source {
    background-position: -105px -157px!important;
    width: 88px;
    height: 14px;
    top: 4px
}

.graph-bar {
    width: 100%;
    border: 1px solid #008489;
    height: 15px;
    margin: 5px 0 2px;
    -moz-box-sizing: border-box;
    -webkit-box-sizing: border-box;
    box-sizing: border-box
}

.graph-percent {
    background: #008489;
    height: 13px
}

.salary-bar p {
    color: #9f9f9f;
    margin-top: 5px
}

.exp-row {
    color: #9f9f9f;
    font-size: .8em;
    text-align: center
}

.compare-table tr td.message {
    background: #fff;
    padding: 15px 5px;
    color: #999;
    text-align: right;
    font-style: normal;
    font-size: 10px;
    border-top: 1px solid #f1f1f1
}

.compare-list p {
    margin-bottom: 5px
}

.compare-list p span {
    font-size: 1.3em
}

.approved-icon {
    background-position: -14px -160px;
    width: 15px;
    height: 12px;
    margin-right: 5px;
    float: none
}

.shorlist-icon {
    background-position: -45px -175px;
    width: 45px;
    height: 43px
}

.shorlist-box {
    margin-left: 40%
}

.shorlist-box p {
    color: #b8b8b8;
    position: relative;
    top: 4px
}

.shortlist-number {
    background-color: #fff;
    -moz-border-radius: 50%;
    -webkit-border-radius: 50%;
    border-radius: 50%;
    color: #999;
    font-size: 50px;
    height: 90px;
    line-height: 80px;
    margin: 5px auto 10px;
    text-align: center;
    width: 90px;
    border: 1px solid #f1f1f1
}

.shortlist-number.disable-numb {
    color: #ede8e8;
    border: 1px solid #ede8e8
}

.shortlist-field {
    padding: 4px 5px;
    border: 1px solid #b8b8b8;
    outline: 0;
    width: 100%;
    color: #c6c6c6;
    -moz-box-sizing: border-box;
    -webkit-box-sizing: border-box;
    box-sizing: border-box;
    -moz-border-radius: 0;
    -webkit-border-radius: 0;
    border-radius: 0;
    -moz-box-shadow: none;
    -webkit-box-shadow: none;
    box-shadow: none
}

a.vote-btn {
    background: #fcd146;
    border-radius: 6px;
    color: #656565;
    text-shadow: 0 1px 0 #fff;
    padding: .4em;
    font-weight: 700;
    display: block;
    text-align: center;
    -moz-box-shadow: 0 1px 1px #8e8e8e;
    -webkit-box-shadow: 0 1px 1px #8e8e8e;
    box-shadow: 0 1px 1px #8e8e8e;
    font-size: 16px
}

.coachmark {
    width: 287px;
    height: 248px;
    position: fixed;
    top: 20%;
    right: 0;
    z-index: 99999;
    cursor: pointer
}

.no-collge-to-compare-msg {
    background: #f2f2f2;
    font-size: 15px;
    line-height: 22px;
    padding: 10px
}

.alumini-cont {
    padding: 1em .625em .625em;
    clear: left
}

.alumini-title {
    background: #f2f2f2;
    box-shadow: 0 1px 3px #b1b0b0;
    color: #3b3b3b;
    font-size: 16px;
    font-weight: 700;
    padding: 8px 8px;
    text-shadow: 0 1px 0 #fff;
    margin: 12px 0 0;
    float: left;
    width: 100%;
    box-sizing: border-box
}

.alunimi-graph {
    margin: 10px 0
}

.alumini-sub-title {
    border-bottom: 1px solid #d6d6d6;
    font-size: 14px;
    margin: 7px 0 10px;
    padding-bottom: 7px;
    font-weight: 700
}

.sorting-spl {
    color: #858585;
    float: left;
    font-size: 14px;
    margin: 0 0 15px;
    position: relative;
    width: 100%
}

.sorting-spl label {
    display: block;
    float: left;
    font-size: 15px;
    margin: 5px 10px 0 0;
    color: #000
}

.alumini-sal-stats p {
    font-size: 14px
}

.caret {
    border-left: 4px solid transparent;
    border-right: 4px solid transparent;
    border-top: 4px solid;
    display: inline-block;
    height: 0;
    margin-left: 2px;
    vertical-align: middle;
    width: 0
}

.alumini-comp-details {
    float: left;
    margin-bottom: 20px;
    width: 100%
}

.alumini-comp-details p {
    color: #000;
    font-size: 14px
}

ul.company-list {
    float: left;
    margin: 10px 0 0 10px;
    padding: 0
}

ul.company-list li {
    margin-bottom: 5px;
    padding: 0!important;
    color: #636363;
    font-size: 13px;
    float: left;
    width: 100%
}

ul.company-list li label {
    display: block;
    margin-bottom: 5px;
    float: left;
    width: 100%
}

.blue-bar2,
.brown-bar {
    background: none repeat scroll 0 0 #7cc3e1;
    height: 12px;
    width: 200px
}

.bar-vals {
    margin-left: 3px;
    position: relative;
    top: -3px
}

.brown-bar {
    background: none repeat scroll 0 0 #dec897
}

.custom-select .ui-btn-inner {
    height: 16px!important
}

.exam-layer {
    border: 1px solid #ccc;
    background: #fff;
    position: absolute;
    left: 0;
    top: 36px;
    z-index: 999;
    width: 100%;
    -moz-box-sizing: border-box;
    -webkit-box-sizing: border-box;
    box-sizing: border-box
}

.exam-layer ul {
    padding: 10px;
    margin-bottom: 10px
}

.exam-layer ul li {
    margin: 0 0 8px 0!important;
    padding: 0;
    list-style: none
}

.exam-layer ul li label {
    display: block
}

.exam-layer ul li input[type=text] {
    width: 70%;
    padding: 4px 3px;
    font: 400 12px "Open Sans";
    border-radius: 0;
    border: 1px solid #ccc;
    box-sizing: border-box
}

.exam-layer ul li p {
    margin-left: 62px
}

.exam-layer .btn-row2 {
    background: #eee;
    padding: 2px 3px;
    text-align: center
}

.divider {
    background: #ccc;
    height: 1px;
    overflow: hidden;
    width: 100%;
    float: left;
    margin: 10px 0
}

.exam-name {
    padding: 5px 8px;
    border-bottom: 5px solid #3f3939;
    color: #797272;
    font-weight: 700;
    font-size: 14px;
    line-height: 15px;
    display: block
}

.exam-name span {
    font-weight: 400;
    font-size: 11px
}

.grids ul li {
    background: #8dbaca;
    color: #fff;
    font-size: 15px;
    float: left;
    width: 100%;
    -moz-box-sizing: border-box;
    -webkit-box-sizing: border-box;
    box-sizing: border-box;
    position: relative
}

.grids ul li a {
    padding: 8px;
    display: inline-block;
    width: 100%;
    -moz-box-sizing: border-box;
    -webkit-box-sizing: border-box;
    box-sizing: border-box;
    color: #fff
}

.grids ul li h2 {
    display: block;
    margin-bottom: 5px;
    font-size: 16px
}

.grids ul li p {
    margin-right: 60px;
    font-size: 14px
}

.exam-accrodian {
    padding: 10px;
    clear: left;
    font-size: 15px
}

.exam-accrodian dl {
    border-bottom: solid 2px #e3e1e5;
    float: left;
    width: 100%
}

.exam-accrodian dl dt {
    padding: 7px;
    display: block;
    float: left;
    width: 100%;
    color: #000333;
    -moz-box-sizing: border-box;
    -webkit-box-sizing: border-box;
    box-sizing: border-box
}

.exam-accrodian dl dt h1,
.exam-accrodian dl dt h2,
.exam-accrodian h1 {
    font-weight: 400;
    font-size: 15px
}

.exam-accrodian dl dt h2 {
    font-weight: 700
}

.exam-accrodian dl dd {
    padding: 10px 5px;
    color: #707070;
    clear: left
}

.exam-mini-sprite {
    background: url(//images.shiksha.ws/public/mobile5/images/exam-mini-sprite.png) no-repeat;
    display: inline-block;
    position: relative
}

.article-icon-large,
.college-icon-large,
.date-icon-large,
.discussion-icon-large,
.exam-icon-large,
.preptips-icon-large,
.result-icon-large,
.syllabus-icon-large {
    background-position: 0 0;
    width: 37px;
    height: 35px;
    position: absolute;
    right: 10px;
    bottom: 6px
}

.date-icon-large {
    background-position: -120px 0;
    width: 34px
}

.syllabus-icon-large {
    background-position: -43px 0;
    width: 37px
}

.college-icon-large {
    background-position: -195px 0;
    width: 37px
}

.article-icon-large {
    background-position: -86px 0;
    width: 27px
}

.result-icon-large {
    background-position: -161px 0;
    width: 30px
}

.preptips-icon-large {
    background-position: -211px -72px;
    width: 30px
}

.discussion-icon-large {
    background-position: -173px -72px;
    width: 37px
}

.college-icon,
.date-icon,
.discussion-icon,
.exam-icon,
.news-article-icon,
.prep-tip-icon,
.result-icon,
.syllabus-icon {
    background-position: 0 -44px;
    width: 21px;
    height: 19px
}

.result-icon {
    background-position: -158px -44px;
    width: 18px
}

.syllabus-icon {
    background-position: -56px -44px;
    width: 22px
}

.college-icon {
    background-position: -82px -44px;
    width: 23px
}

.date-icon {
    background-position: -28px -44px;
    width: 20px
}

.prep-tip-icon {
    background-position: -200px -44px;
    width: 15px
}

.discussion-icon {
    background-position: -218px -44px;
    width: 21px
}

.news-article-icon {
    background-position: -179px -44px;
    width: 18px
}

.exam-minus-icon,
.exam-plus-icon {
    background-position: -136px -44px;
    width: 18px;
    height: 18px
}

.exam-minus-icon {
    background-position: -113px -44px
}

.exam-nav-wrap {
    font-size: 9px;
    line-height: 12px;
    float: left;
    width: 100%;
    -moz-box-sizing: border-box;
    -webkit-box-sizing: border-box;
    box-sizing: border-box;
    margin-bottom: 10px;
    position: relative
}

ul.exam-nav {
    display: table;
    width: 100%;
    -moz-box-shadow: 0 0 3px #ccc;
    -webkit-box-shadow: 0 0 3px #ccc;
    box-shadow: 0 0 3px #ccc;
    background: #fff;
    padding: 2px;
    -moz-box-sizing: border-box;
    -webkit-box-sizing: border-box;
    box-sizing: border-box
}

ul.exam-nav li {
    display: table-cell;
    border-right: 1px solid #f4f4f4;
    width: 25%
}

ul.exam-nav li a {
    color: #9d9d9d;
    display: block;
    padding: 4px 4px 2px;
    text-align: center
}

ul.exam-nav li:nth-of-type(4) {
    border: 0 none
}

ul.exam-nav li.active {
    background: #f7f5fa
}

.similar-exam-wrap {
    padding: 10px 10px 15px
}

ul.exam-list {
    width: 100%
}

ul.exam-list li {
    -moz-border-radius: 4px;
    -webkit-border-radius: 4px;
    border-radius: 4px;
    margin-right: 10px;
    width: 30%;
    background: #dfeef8;
    color: #6ea7d0;
    float: left;
    margin-bottom: 0
}

ul.exam-list li.last {
    margin-right: 0
}

.exam-col {
    padding: 3px
}

.exam-col-img {
    padding: 6px 10px 3px;
    background: #fff;
    text-align: center;
    -moz-border-radius: 3px;
    -webkit-border-radius: 3px;
    border-radius: 3px
}

.exam-title {
    font-size: 16px;
    padding: 2px 0;
    text-align: center
}

.similar-exam-title {
    color: #80bae4;
    font-size: 16px;
    font-weight: 700;
    margin-bottom: 10px
}

a.imp-date-btn {
    display: block;
    padding: 8px 0;
    text-align: center;
    font-size: 12px;
    color: #fff;
    text-transform: uppercase;
    background: #008489
}

.viewedExam-icn {
    background-position: 0 -71px;
    width: 55px;
    height: 55px
}

.calender-blocks {
    margin: 20px 0 0 35px;
    position: relative
}

.badge2 {
    background: #f10816;
    color: #fff;
    font-size: 10px;
    text-transform: uppercase;
    font-weight: 400;
    border-radius: 3px;
    padding: 0 2px;
    position: absolute;
    right: 0;
    top: -16px
}

.calender-blocks ul li {
    float: left;
    width: 93px;
    height: 115px;
    background: #96a3aa;
    border-radius: 4px;
    padding: 5px;
    cursor: pointer;
    margin: 0 15px 23px 0;
    position: relative
}

.calender-blocks ul li.exp-date {
    background: #cad1d4
}

.calender-blocks ul li .date {
    background: #fff;
    padding: 8px 8px;
    border-radius: 4px;
    color: #96a3aa
}

.calender-blocks ul li.exp-date .date {
    color: #cad1d4
}

.calender-blocks ul li big {
    font-size: 40px;
    font-family: Tahoma;
    float: left;
    line-height: 25px
}

.calender-blocks ul li small {
    font-size: 26px;
    font-size: 14px;
    font-weight: 700;
    float: left;
    line-height: 12px;
    margin: 5px 0 0 5px
}

.calender-blocks ul li small span {
    font-size: 10px
}

.calender-blocks ul li .info {
    font-size: 11px;
    color: #fff;
    line-height: 14px;
    margin: 4px 0 0 8px
}

.calender-blocks ul li .info strong {
    display: block;
    font-weight: 700;
    line-height: 12px;
    margin-bottom: 4px;
    font-size: 11px
}

.mceContentBody {
    clear: both
}

.mceContentBody hr {
    border: 0;
    color: #bebebe;
    background-color: #bebebe;
    height: 1px;
    overflow: hidden
}

.mceContentBody img {
    border: 0 none!important;
    width: 100%;
    margin-bottom: 10px!important
}

.mceContentBody h2 {
    font-size: 20px;
    color: #000!important;
    margin-bottom: 3px!important;
    display: block!important
}

.mceContentBody p {
    margin-bottom: 10px;
    word-wrap: break-word
}

.mceContentBody ol,
.mceContentBody ul {
    width: 100%;
    float: left;
    margin: 0;
    padding: 0
}

.mceContentBody ol li,
.mceContentBody ul li {
    margin-bottom: 8px;
    margin-left: 20px;
    list-style: disc;
    clear: both
}

.mceContentBody table {
    width: 100%!important;
    border-collapse: collapse!important;
    font: 400 15px "Open Sans"!important;
    margin: 0!important;
    border: 1px solid #e3e3e3!important
}

.mceContentBody table tr td {
    border: 1px solid #e3e3e3!important;
    padding: 10px!important;
    font: 400 15px "Open Sans"!important
}

.mceContentBody table tr td p {
    margin: 0;
    padding: 0!important;
    width: auto!important
}

.mceContentBody table tr td p strong {
    font-size: 12px!important;
    font-weight: 400!important
}

.mceContentBody table tr td p span {
    font: 400 15px OpenSans!important
}

.mceContentBody table tr td strong {
    font-size: 16px;
    font-weight: 400
}

.exam-wrap {
    padding: 10px;
    clear: left;
    font-size: 12px
}

h1.examPage-title {
    font-weight: 400;
    margin-bottom: 12px;
    font-size: 16px
}

.widget-badge {
    color: #fff;
    background: #f10816;
    padding: 1px 3px;
    font-size: 11px;
    display: inline-block
}

ul.exam-news-box li {
    border: 1px solid #f4f4f4;
    padding: 8px;
    color: #505050;
    margin-bottom: 12px;
    float: left;
    width: 100%;
    -moz-box-sizing: border-box;
    -webkit-box-sizing: border-box;
    box-sizing: border-box;
    line-height: 18px
}

ul.exam-news-box li strong {
    color: #4d4d4d;
    font-size: 14px;
    display: block;
    margin-bottom: 1px;
    font-weight: 700
}

ul.exam-news-box li span {
    color: #8f8f8f;
    display: block;
    margin-bottom: 4px
}

ul.articleBox li {
    background: #f2f2f2
}

.result-tabs {
    margin: 3px 0
}

.result-info-title {
    display: inline-block
}

.exam-accrodian .result-info-title h1,
.result-info-title strong {
    font-size: 16px;
    font-weight: 400;
    color: #4b4b4b
}

.result-info-title span {
    color: #aeaeae;
    display: block;
    font-size: 12px
}

.exam-result-sprite {
    background: url(//images.shiksha.ws/public/mobile5/images/exam-result-sprite.jpg) no-repeat;
    display: inline-block;
    position: relative
}

.result-analysis-icn,
.result-declare-icn,
.stu-reaction-icn,
.toppers-intrvew-icn {
    background-position: 0 0;
    width: 53px;
    height: 32px;
    vertical-align: bottom;
    margin-right: 5px
}

.result-analysis-icn {
    background-position: -57px 0
}

.toppers-intrvew-icn {
    background-position: 0 -35px
}

.stu-reaction-icn {
    background-position: -57px -35px
}

ul.prep-tip-list li {
    border-bottom: 1px solid #f4f4f4;
    margin-bottom: 10px;
    padding-bottom: 10px
}

ul.prep-tip-list li.last {
    border: 0
}

ul.prep-tip-list li img {
    width: 87px;
    vertical-align: bottom;
    margin-right: 5px
}

.next-icon,
.prev-icon {
    background-position: -57px -72px;
    width: 6px;
    height: 9px
}

.next-icon {
    background-position: -65px -72px
}

ul.discussion-list {
    margin: 5px 0;
    float: left;
    width: 100%
}

ul.discussion-list li {
    width: 100%;
    float: left;
    margin-bottom: 30px
}

ul.discussion-list li dl dt {
    margin-bottom: 0
}

ul.discussion-list li dl dd {
    margin-left: 0
}

.thread-icon {
    background-position: -74px -72px;
    width: 43px;
    height: 43px;
    float: left
}

.thread-info {
    margin-left: 55px
}

.thread-title {
    font-size: 14px;
    margin-bottom: 3px;
    display: inline-block
}

.thread-info span {
    color: #c3c3c3;
    font-size: 11px;
    display: block
}

.comment-link {
    margin: 8px 0 3px;
    display: inline-block;
    font-size: 13px
}

.user-thumb {
    background-position: -120px -72px;
    width: 25px;
    height: 25px;
    float: left;
    margin-left: 15px
}

.last-comment-sec img {
    width: 25px;
    height: 25px;
    float: left;
    margin-left: 15px
}

.last-comment-info {
    margin-left: 55px;
    color: #000
}

.last-comment-info p {
    line-height: 130%;
    margin-top: 3px;
    color: #666;
    font-size: 12px
}

.last-comment-info span {
    font-size: 11px;
    color: #ccc
}

.comment-field {
    background: #fff;
    padding: 4px;
    width: 100%;
    border: 1px solid #f2f2f2;
    -moz-border-radius: 2px;
    -webkit-border-radius: 2px;
    border-radius: 2px;
    margin-top: 10px;
    -moz-box-sizing: border-box;
    -webkit-box-sizing: border-box;
    box-sizing: border-box
}

.comment-txt-field {
    margin-right: 80px
}

.comment-txt-field input {
    border: 0 none;
    outline: 0;
    width: 100%;
    color: #b2b2b2;
    position: relative;
    top: 1px
}

a.comment-btn {
    text-decoration: none;
    color: #7b7b7b;
    background: #efefef;
    padding: 3px 5px;
    -moz-border-radius: 2px;
    -webkit-border-radius: 2px;
    border-radius: 2px;
    float: right;
    width: 60px;
    font-size: 12px
}

a.show-more {
    background: #efefef;
    padding: 10px;
    border-radius: 2px;
    text-align: center;
    font-size: 12px;
    color: grey!important;
    text-decoration: none!important;
    display: inline-block;
    width: 100%;
    box-sizing: border-box
}

.exam-date {
    background: #c3c6c8;
    border: 1px solid #898b8b;
    padding: 1px 3px;
    color: #fff;
    text-align: center;
    line-height: 18px;
    float: right;
    font-size: 12px
}

.exam-nav-layer {
    background: #fff;
    padding: 10px 12px 3px;
    position: absolute;
    bottom: -10px;
    left: 0;
    width: 100%;
    box-sizing: border-box;
    z-index: 99;
    border-radius: 10px 10px;
    font-size: 14px
}

.exam-nav-layer ul li {
    border-bottom: 1px solid #e8e8e8
}

.exam-nav-layer ul li.last {
    border: 0 none;
    padding: 10px 0
}

.exam-nav-layer ul li a {
    display: block;
    color: #707070;
    padding: 10px 12px 14px 20px
}

.exam-nav-layer ul li a label {
    margin-left: 35px;
    display: block;
    padding-top: 2px
}

a.close-layer {
    width: 40px;
    height: 40px;
    background: #d1d1d1;
    color: #fff;
    -moz-border-radius: 50%;
    -webkit-border-radius: 50%;
    border-radius: 50%;
    text-align: center;
    line-height: 36px;
    font-size: 28px;
    margin: 0 auto;
    padding: 0!important;
    color: #fff!important
}

.more-exams {
    background-position: -149px -72px;
    width: 18px;
    height: 18px
}

h2.camp-connect-head {
    clear: both;
    margin-bottom: 10px;
    font-weight: 400
}

.camp-connect-head strong {
    font-size: 1.1em;
    display: block;
    margin-bottom: 5px
}

.ans-icon,
.ques-icon {
    width: 28px;
    height: 28px;
    background: #bdbdbd;
    color: #fff;
    float: left;
    border-radius: 50% 50%;
    text-align: center;
    font-size: 18px;
    line-height: 28px
}

.camp-qna-details {
    color: #564444;
    margin-left: 40px
}

.camp-qna-details p {
    margin-bottom: 8px
}

.posted-info {
    font-size: 14px;
    color: #4d4d4d;
    margin-bottom: 0!important
}

.posted-info span {
    display: block;
    margin-bottom: 6px
}

.posted-info label {
    color: #999
}

.ans-title {
    padding: .4em .6em;
    color: #fff;
    background: #4c6e94;
    font-size: 16px;
    margin-bottom: 5px;
    -moz-box-shadow: 0 1px 4px #c7c7c7;
    -webkit-box-shadow: 0 1px 4px #c7c7c7;
    box-shadow: 0 1px 4px #c7c7c7;
    font-weight: 700
}

.ans-title span {
    color: #d3d3d3
}

a.current-stu-btn {
    color: #555;
    font-size: 9px;
    padding: 2px 4px;
    border: 1px solid #c4c4c4;
    margin-left: 3px;
    background: #fff;
    display: inline-block
}

.posted-ans-detail {
    background: #f1f0f0;
    padding: 8px;
    color: #121212;
    border-left: 3px solid #e1e1e1;
    margin: 8px 0 15px
}

.other-ques-title {
    font-size: 16px;
    padding: .5em;
    background: #ededed;
    -moz-box-shadow: 0 1px 3px #c7c7c7;
    -webkit-box-shadow: 0 1px 3px #c7c7c7;
    box-shadow: 0 1px 3px #c7c7c7;
    color: #000;
    line-height: normal;
    font-weight: 700
}

.other-que-section ul {
    display: table;
    width: 100%
}

.other-que-section ul li {
    border-bottom: 1px solid #dadada;
    display: table-row;
    margin-bottom: 0!important;
    float: left;
    width: 100%
}

.que-col {
    background: #e5e5e5;
    padding: 10px;
    display: table-cell
}

.other-que-detail {
    display: table-cell;
    vertical-align: top;
    padding: 10px;
    width: 100%;
    color: #000
}

.other-que-detail strong {
    display: block;
    margin-bottom: 5px
}

.all-qna {
    margin-top: 20px
}

.all-qna dl {
    border-bottom: 1px solid #d6d6d6;
    padding-bottom: 15px;
    margin-bottom: 15px
}

.all-qna dl dt {
    margin-bottom: 12px
}

.all-qna dl dd,
.all-qna dl dt {
    width: 100%;
    clear: left
}

.ask-btn,
.que-btn {
    width: 100%;
    box-sizing: border-box;
    -moz-box-sizing: border-box;
    -webkit-box-sizing: border-box
}

.ask-btn {
    background: #008489
}

.stu-detail {
    background: #f6f6f6;
    padding: .4em;
    margin: 1em 0 0 0
}

.stu-image {
    float: left
}

.stu-info {
    margin-left: 50px
}

.stu-info p {
    margin-bottom: .4em
}

.comment-sec {
    background: #f4f4f4;
    padding: 10px;
    position: relative;
    margin-top: 10px
}

.comment-area,
.post-que-area {
    border: 1px solid #c3c1c1;
    background: #fff;
    width: 100%;
    -moz-box-shadow: none!important;
    -webkit-box-shadow: none!important;
    box-shadow: none!important;
    border-radius: 0!important;
    margin-bottom: 5px;
    -moz-box-sizing: border-box;
    -webkit-box-sizing: border-box;
    box-sizing: border-box;
    font-size: 14px
}

.comment-sec p {
    font-size: 14px;
    margin-bottom: 4px
}

.comment-sec .pointer {
    background-position: -105px -172px;
    width: 31px;
    height: 15px;
    position: absolute;
    top: -11px;
    left: 45px
}

.post-que-area {
    border: 1px solid #dbdada;
    background: #efefef;
    height: 100px;
    width: 100%;
    margin-bottom: 8px;
    font-family: inherit
}

.comment-icon {
    background-position: -138px -172px;
    width: 23px;
    height: 18px;
    margin-right: 5px
}

.que-count {
    margin: 8px 0 0;
    display: block
}

.que-head {
    padding-bottom: 10px;
    border-bottom: 1px solid #d6d6d6;
    margin: 10px 0 15px 0;
    line-height: 14px
}

.que-head span {
    display: block
}

a.load-more {
    background: #ededed;
    padding: .5em;
    text-align: center;
    margin-bottom: 10px;
    color: #000!important;
    display: block
}

.d-arrow {
    background-position: -164px -172px;
    width: 13px;
    height: 6px;
    float: none;
    margin: 0 0 0 2px;
    vertical-align: middle
}

.new-knob {
    background: #d4bc38;
    -moz-border-radius: 6px;
    -webkit-border-radius: 6px;
    border-radius: 6px;
    color: #fff;
    display: block;
    font-size: 12px;
    line-height: 11px;
    padding: 1px 7px 0;
    position: absolute;
    right: 5px;
    text-shadow: none;
    top: 4px
}

.category-tab-wrap {
    color: #fff;
    position: fixed;
    bottom: 0;
    width: 100%;
    display: none;
    z-index: 10000
}

ul.tab-list {
    width: 100%;
    display: table;
    box-sizing: border-box;
    height: 43px
}

ul.tab-list li {
    width: 20%;
    display: table-cell;
    list-style: none;
    background: #000;
    vertical-align: middle;
    border-right: 1px solid #fff
}

ul.tab-list li a {
    display: block;
    font-size: 9px;
    text-transform: uppercase;
    text-align: center;
    color: #fff!important;
    padding: 10px 8px
}

ul.tab-list li.active {
    background: #dededc
}

ul.tab-list li.active a {
    color: #000!important
}

.icon-colleges {
    background-position: -341px -161px;
    width: 19px;
    height: 19px;
    float: none
}

.icon-ranking {
    background-position: -365px -161px;
    width: 16px;
    height: 21px;
    float: none
}

.icon-rank-predictor {
    background-position: -494px -161px;
    width: 23px;
    height: 23px;
    float: none
}

.icon-news-article {
    background-position: -386px -161px;
    width: 24px;
    height: 18px;
    float: none
}

.icon-entrance-exam {
    background-position: -414px -161px;
    width: 22px;
    height: 20px;
    float: none
}

.icon-college-predictor {
    background-position: -436px -161px;
    width: 23px;
    height: 22px;
    float: none
}

ul.tab-list li.active .icon-colleges {
    background-position: -341px -185px
}

ul.tab-list li.active .icon-ranking {
    background-position: -365px -185px
}

ul.tab-list li.active .icon-news-article {
    background-position: -386px -185px
}

ul.tab-list li.active .icon-entrance-exam {
    background-position: -414px -185px
}

ul.tab-list li.active .icon-college-predictor {
    background-position: -436px -185px
}

ul.tab-list li.active .icon-rank-predictor {
    background-position: -494px -185px
}

.more-bullet {
    width: 2px;
    height: 2px;
    -moz-border-radius: 50%;
    -webkit-border-radius: 50%;
    border-radius: 50%;
    color: #fff;
    margin: 0 5px 3px 0;
    background: #fff;
    padding: 2px;
    display: inline-block
}

.compare-items ul li {
    color: #595a5c;
    font-size: 12px;
    margin-bottom: 25px;
    text-align: left
}

.compare-items ul li:last-child {
    margin-bottom: 0
}

.compare-items ul li span {
    width: 54%;
    display: inline-block;
    vertical-align: middle
}

.comp-rnk-title {
    color: #828282!important;
    font-size: 11px;
    padding: 10px 0 0;
    text-align: left
}

.compare-items ul li.last {
    margin-bottom: 0
}

.round-rank {
    width: 25px;
    height: 25px;
    padding: 2px;
    -moz-border-radius: 50%;
    -webkit-border-radius: 50%;
    border-radius: 50%;
    border: 1px solid #f6e0a3;
    display: inline-block;
    font-size: 14px;
    line-height: 23px;
    margin-left: 10px;
    color: #f29c4c!important;
    text-decoration: none!important;
    text-align: center
}

.comp-circle {
    width: 20px;
    height: 20px;
    margin-right: 2px;
    background-position: 0 -183px;
    float: none;
    vertical-align: middle;
    text-indent: -9999px
}

.review-form {
    padding: 10px
}

.review-title {
    font-size: 14px;
    font-weight: 700;
    color: #ddd;
    margin-top: 12px
}

.review-steps {
    border-bottom: 1px solid #e5e5e5;
    margin: 25px 0;
    position: relative;
    display: table;
    width: 100%
}

.review-boxes {
    display: table-cell
}

.review-steps .r-step-1,
.review-steps .r-step-2 {
    color: #a4a4a4;
    font-size: 13px;
    left: 5%;
    position: absolute;
    top: -8px
}

.review-steps .r-step-2 {
    left: auto;
    right: 5%
}

.review-steps .r-step-1.active,
.review-steps .r-step-2.active {
    color: #3b3b3b;
    font-weight: 600
}

.review-steps .r-step p {
    position: absolute;
    text-align: left;
    top: 20px;
    width: 100%
}

.review-steps .r-step-1 .point,
.review-steps .r-step-2 .point {
    background: #c7c7c7;
    border-radius: 50%;
    display: inline-block;
    height: 16px;
    width: 16px
}

.review-steps .r-step-1.active .point,
.review-steps .r-step-2.active .point {
    background: #fcd146;
    color: #3c3c3c
}

.review-steps .step-arrow2 {
    background-position: -22px -183px;
    height: 11px;
    position: absolute;
    top: -5px;
    width: 6px
}

.review-area {
    background: #efefef!important;
    border: 1px solid #c3c1c1!important;
    padding: 5px!important;
    border-radius: 0!important;
    box-shadow: none!important;
    width: 100%;
    box-sizing: border-box;
    text-shadow: 0 1px 0 #fff;
    color: #000!important;
    font: 400 .9em "Open Sans"!important;
    height: 72px!important;
    margin: 0 0 3px 0!important;
    overflow-y: scroll!important
}

.rateing-box {
    margin: 20px 10px 0 10px
}

.rateing-box ul li {
    list-style: lower-alpha;
    color: #979792;
    font-size: 13px;
    margin: 0 0 20px 18px
}

.rateing-box ul li label {
    margin-bottom: 6px;
    display: block
}

.rateing-box ul li p {
    margin: 7px 0 0 5px;
    color: #fe7c51;
    display: none
}

.filled,
.rating-star {
    background-position: -284px -185px;
    width: 26px;
    height: 25px;
    margin-right: 20px
}

.filled {
    background-position: -312px -185px
}

.rating-title {
    margin-bottom: 15px;
    font-size: 14px
}

.recommend-row {
    font-size: 12px;
    margin: 20px 0 20px;
    float: left;
    width: 100%
}

.recommend-row strong {
    display: block;
    margin-bottom: 8px;
    font-size: 15px
}

.recommend-row label {
    margin-right: 10px;
    vertical-align: middle;
    color: #747474;
    font-size: 14px
}

.recommend-row imput {
    vertical-align: middle
}

ul.review-step-block {
    border-bottom: 1px solid #e6e6e6;
    display: table;
    width: 100%;
    padding: 15px 0 20px
}

ul.review-step-block li {
    display: table-cell;
    width: 33%;
    text-align: center;
    color: #393939;
    font-size: 12px
}

.rev-stepbg {
    background: url(//images.shiksha.ws/public/mobile5/images/rev-stepbg.gif) no-repeat;
    display: inline-block
}

.play-bg,
.share-bg,
.write-bg {
    background-position: 0 0;
    width: 50px;
    height: 48px
}

.play-bg {
    background-position: -53px 0
}

.write-bg {
    background-position: -106px 0
}

.review-note {
    margin-left: 20px;
    font-size: 12px;
    color: #646464
}

.review-detail-col {
    color: #3c3c3c;
    font-size: 14px;
    margin: 10px 0 20px 0;
    line-height: 20px
}

.review-detail-col strong {
    display: block;
    margin-bottom: 5px
}

.compare-bro-icn {
    background-position: -448px -100px;
    width: 20px;
    height: 20px;
    position: relative;
    top: 0;
    margin-right: 1px
}

.review-sub-head {
    color: #535353;
    font-size: 12px;
    margin: 0 0 10px 10px
}

ul.course-ranking-list {
    margin-left: 10px
}

ul.course-ranking-list li {
    float: left;
    font-size: 14px;
    margin-bottom: 15px;
    width: 100%
}

ul.course-ranking-list li a {
    float: left;
    margin-right: 5px;
    width: 75%
}

.avg-rating-title {
    color: #989494;
    font-size: 13px;
    margin-right: 5px;
    padding-top: 2px;
    font-weight: 400
}

.ranking-bg {
    width: 45px;
    background: #bdbdbd;
    border-radius: 2px;
    color: #fff;
    float: left;
    font-size: 13px;
    padding: 1px 4px;
    font-weight: 400;
    text-align: center;
    text-shadow: none!important
}

.ranking-bg sub {
    font-size: 10px
}

.sorting-option-sec {
    background: #ddd;
    padding: 3px;
    -moz-border-radius: 3px;
    -webkit-border-radius: 3px;
    border-radius: 3px;
    border: 1px solid #d4d4d4;
    color: #626161;
    font-size: 12px
}

.sorting-option-sec label {
    color: #9c9b9b;
    font-size: 10px
}

.sorting-option-sec select {
    background: #ddd;
    border: 0;
    outline: 0
}

.naukri-inner-wrap {
    padding: 10px 12px;
    background: #fff
}

.selection-criteria {
    width: 68%
}

.selected-field {
    padding: 5px 3px;
    border: 1px solid #f0f0f0;
    display: inline-block;
    text-decoration: none;
    font-size: 10px;
    margin-right: 3px
}

.selected-text {
    width: 40px;
    display: inline-block;
    color: #999!important;
    text-decoration: none
}

.remove-field {
    font-size: 18px;
    color: #595959!important;
    text-decoration: none;
    line-height: 4px;
    position: relative;
    top: 2px
}

.modify-search-btn {
    color: #737373!important;
    border: 1px solid #a0adde;
    padding: 5px 8px;
    text-decoration: none!important;
    font-size: 11px
}

.dream-info-widget {
    padding: 15px 15px 0 15px;
    font-size: 14px;
    color: #666
}

.dream-info-widget p {
    margin-bottom: 4px
}

.source-text {
    font-size: 9px
}

ul.criteria-cards {
    background: #fff;
    color: #9f9e9e;
    -moz-border-radius: 4px;
    -webkit-border-radius: 4px;
    border-radius: 4px
}

ul.criteria-cards li {
    -moz-border-radius: 4px;
    -webkit-border-radius: 4px;
    border-radius: 4px;
    -moz-box-shadow: 0 2px 3px #cacacb;
    -webkit-box-shadow: 0 2px 3px #cacacb;
    box-shadow: 0 2px 3px #cacacb;
    padding: 15px 0 0;
    list-style: none
}

.criteria-title {
    font-size: 14px;
    padding: 0 15px 10px 15px
}

.criteria-detail {
    border-top: 1px solid #ebebeb
}

.select-job-title {
    padding: 10px 5px 5px 5px;
    text-align: center;
    font-size: 14px;
    font-weight: 400;
    float: none!important;
    -moz-box-sizing: border-box;
    -webkit-box-sizing: border-box;
    box-sizing: border-box
}

.filter-widget {
    color: #010101;
    font-size: 15px;
    line-height: 20px;
    padding: 5px 20px 5px
}

.filter-widget p {
    text-align: left
}

.filter-sec {
    margin-top: 8px
}

.filter-sec label {
    font-size: 12px;
    color: #000
}

.filter-select {
    border: 1px solid #cbcbcb;
    margin-left: 5px;
    padding: 2px 4px;
    color: #999;
    font-size: 14px
}

.naukri-ins-list {
    color: #999;
    font-size: 14px;
    background: #fff
}

.naukri-ins-list ul {
    background: #e6e6dc;
    width: 100%
}

.naukri-ins-list ul li {
    color: #616161;
    -moz-border-radius: 4px;
    -webkit-border-radius: 4px;
    border-radius: 4px;
    padding: 10px;
    list-style: none;
    background: #fff;
    -moz-box-shadow: 0 2px 3px #ccc;
    -webkit-box-shadow: 0 2px 3px #ccc;
    box-shadow: 0 2px 3px #ccc;
    display: table;
    width: 100%;
    margin-bottom: 8px;
    -moz-box-sizing: border-box;
    -webkit-box-sizing: border-box;
    box-sizing: border-box
}

.naukri-ins-list ul li.last {
    margin-bottom: 0
}

.ins-detail {
    margin-right: 20px;
    display: table-cell;
    vertical-align: top
}

.ins-detail strong {
    color: #616161;
    margin-bottom: 6px;
    display: inline-block
}

.ins-detail span {
    display: block;
    margin-bottom: 6px;
    font-size: 12px
}

.salary-info {
    font-size: 12px
}

.shortlist-btn {
    width: 5%;
    font-size: 11px;
    color: #999;
    display: table-cell;
    text-align: center
}

.star-shortlist-icon {
    background-position: -116px -28px;
    width: 21px;
    height: 20px;
    margin-bottom: 5px
}

.naukri-register-widget {
    background: #eaf3f8;
    color: #000;
    -moz-border-radius: 4px;
    -webkit-border-radius: 4px;
    border-radius: 4px;
    padding: 10px;
    -moz-box-shadow: 0 2px 3px #ccc;
    -webkit-box-shadow: 0 2px 3px #ccc;
    box-shadow: 0 2px 3px #ccc
}

.naukri-register-head {
    margin-bottom: 8px
}

.naukri-register-head strong {
    font-weight: 400;
    font-size: 14px;
    margin-bottom: 8px;
    display: block
}

.naukri-register-head span {
    display: block;
    font-size: 12px
}

.for-text {
    color: #aaa;
    font-size: 12px;
    margin-left: 15px
}

.tick-icon {
    background-position: -140px -28px;
    width: 9px;
    height: 9px;
    margin-right: 5px
}

ul.register-info-list {
    width: 100%;
    margin: 8px 0 8px 15px
}

ul.register-info-list li {
    color: #797878;
    font-size: 12px;
    margin-bottom: 10px;
    list-style: none
}

.register-btn {
    color: #fff!important;
    font-size: 12px;
    background: #008489;
    padding: 6px 10px;
    border: 1px solid #008489;
    text-decoration: none!important;
    margin: 5px 15px;
    display: inline-block;
    text-transform: uppercase
}

.institute-widget-sec {
    color: #999;
    background: #fff;
    padding: 10px;
    -moz-border-radius: 4px;
    -webkit-border-radius: 4px;
    border-radius: 4px;
    -moz-box-shadow: 0 2px 3px #ccc;
    -webkit-box-shadow: 0 2px 3px #ccc;
    box-shadow: 0 2px 3px #ccc
}

.detail-sec {
    border-bottom: 1px solid #efefef;
    display: table;
    width: 100%;
    padding-bottom: 5px
}

.institute-detail {
    display: table-cell;
    margin-right: 20px;
    vertical-align: top
}

.institute-link {
    font-size: 16px;
    text-decoration: none;
    color: #008489
}

.institute-detail span {
    display: block;
    font-size: 12px;
    margin: 4px 0 8px
}

.institute-detail p {
    margin-bottom: 8px;
    font-size: 12px
}

.institute-shortlist {
    display: table-cell;
    width: 5%;
    text-align: center;
    font-size: 11px;
    color: #3e3b3b
}

ul.rank-criteria {
    margin: 10px 0
}

ul.rank-criteria li {
    list-style: none
}

.rank-widget {
    width: 45%;
    margin-right: 10px;
    color: #999;
    font-size: 12px
}

ul.rank-criteria li label {
    font-size: 12px;
    color: #8c8c8c;
    float: left;
    width: 80px;
    margin-right: 3px;
    line-height: 18px
}

.rank-tag {
    -moz-border-radius: 50%;
    -webkit-border-radius: 50%;
    border-radius: 50%;
    border: 1px solid #8c8c8c;
    color: #8c8c8c;
    font-size: 16px;
    display: block;
    width: 30px;
    height: 30px;
    float: left;
    line-height: 28px;
    text-align: center;
    position: relative;
    top: 4px
}

.brouchure-icon {
    background-position: -38px -59px;
    width: 17px;
    height: 17px;
    margin-right: 5px;
    vertical-align: middle;
    position: relative;
    top: -2px
}

.brouchure-btn {
    background: #e5e5e4;
    border: 1px solid #c3c3c3;
    color: #5a595c!important;
    text-decoration: none;
    font-size: 12px;
    padding: 5px 68px;
    display: inline-block;
    text-align: center;
    margin: 10px auto;
    text-transform: uppercase
}

.shortlist-btn-area {
    padding: 20px 0 15px 0;
    float: left;
    width: 100%;
    text-align: center
}

.shortlist-big-btn,
.shortlist-enable-btn {
    text-decoration: none!important;
    background: #008489;
    padding: 7px 94px;
    min-width: 76px;
    font-size: 12px;
    display: inline-block;
    font-weight: 700
}

.shortlist-big-btn {
    color: #fff!important
}

.star-white-icon {
    background-position: -59px -59px;
    width: 16px;
    height: 16px;
    margin-right: 5px;
    vertical-align: middle;
    position: relative;
    top: -2px
}

.layer-header {
    background: #fff;
    padding: 0;
    border-bottom: 1px solid #aaa;
    font-size: 12px;
    font-weight: 700;
    width: 100%;
    display: table
}

.layer-header p {
    display: table-cell;
    line-height: 16px;
    padding: 8px 0 10px;
    vertical-align: middle;
    color: #646363
}

.back-box {
    padding: 13px 12px;
    display: table-cell;
    vertical-align: top;
    width: 35px
}

.back-icn {
    background-position: -78px -59px;
    width: 9px;
    height: 12px
}

.next-pre-sec {
    display: table-cell;
    padding: 13px 0 13px 10px;
    text-align: right;
    width: 80px
}

.next-icon,
.next-icon-a,
.prev-icon,
.prev-icon-a {
    background-position: -98px -59px;
    width: 9px;
    height: 12px;
    margin-right: 10px
}

.prev-icon-a {
    background-position: -78px -59px
}

.next-icon {
    background-position: -108px -59px
}

.next-icon-a {
    background-position: -88px -59px
}

.other-job-layer {
    background: #fff;
    padding: 10px
}

.job-textfield {
    color: #9f9e9e;
    font-size: 12px;
    background: #f8f6f6;
    border: 1px solid #c9c9c9;
    -moz-border-radius: 3px;
    -webkit-border-radius: 3px;
    border-radius: 3px;
    width: 100%;
    -moz-box-sizing: border-box;
    -webkit-box-sizing: border-box;
    box-sizing: border-box;
    padding: 4px
}

.other-job-layer ul {
    margin: 10px 0;
    width: 100%
}

.other-job-layer ul li {
    padding: 6px 0;
    border-bottom: 1px solid #f3f2f2;
    list-style: none
}

.other-job-layer ul li a {
    font-size: 14px;
    text-decoration: none
}

.alpha-criteria {
    border-bottom: 1px solid #e2e2e2;
    margin: 13px 0 10px
}

.alpha-criteria ul li {
    border-bottom: 0 none;
    padding-bottom: 0
}

.alpha-criteria ul li a {
    color: #a3a3a3;
    font-size: 14px;
    margin: 0 15px 8px 0;
    text-decoration: none!important;
    text-transform: uppercase;
    display: inline-block
}

.alpha-criteria ul li a.active {
    background: #e6e5e5;
    border-radius: 2px;
    color: #000;
    padding: 2px 10px
}

.filter-detail label {
    font-size: 12px;
    color: #000;
    float: left;
    padding-top: 7px;
    margin-right: 4px
}

.job-selectfield {
    margin-left: 102px
}

.recommended-title {
    margin: 10px 0;
    color: #989494;
    font-size: 12px
}

.user-name {
    font-size: 12px!important;
    font-weight: 400!important;
    color: #989494
}

.user-name a {
    font-size: 16px
}

.recommended-title strong {
    font-weight: 400
}

.thumb-dwn-icon,
.thumb-up-icon {
    background-position: -448px -130px;
    width: 16px;
    height: 16px;
    margin-right: 3px;
    margin-top: 3px!important
}

.thumb-dwn-icon {
    background-position: -231px -185px;
    top: 4px
}

.blue-tick {
    background-position: -448px -150px;
    width: 10px;
    height: 7px;
    vertical-align: middle;
    position: relative;
    top: 7px;
    margin-right: 3px
}

.rating-sprtr {
    color: #d9d5d5;
    margin: 0 10px;
    font-size: 14px!important
}

.review-detail {
    border: 1px solid #e2e2e2;
    color: #969691;
    padding: 10px;
    position: relative;
    width: 250px;
    box-sizing: border-box
}

.review-detail ol.rating-display li {
    margin-bottom: 8px;
    list-style: none
}

.review-detail ol.rating-display li label {
    font-size: 13px;
    width: 135px;
    float: left
}

.review-detail ol.rating-display li p {
    margin-left: 136px
}

.course-rating-star,
.course-rating-star-filled {
    background-position: -267px -185px;
    cursor: pointer;
    height: 15px;
    width: 15px;
    margin-right: 3px
}

.course-rating-star {
    background-position: -249px -185px
}

.font-11 {
    font-size: 11px!important
}

.review-detail-content {
    margin: 10px 0 0;
    color: #535353;
    font-size: 14px;
    line-height: 20px;
    padding: 0 10px 10px 0
}

.show-more {
    font-size: 14px;
    width: 100%;
    text-align: center;
    border: 1px solid #dbdbdb;
    -moz-box-sizing: border-box;
    -webkit-box-sizing: border-box;
    box-sizing: border-box;
    margin-bottom: 10px
}

.show-more a {
    color: #9e9e9e;
    padding: 10px;
    display: block
}

.icon-rating {
    background-position: -457px -35px;
    width: 12px;
    height: 17px;
    left: 4px
}

.career-title {
    font-size: 14px;
    text-transform: uppercase;
    color: #303030;
    text-align: center;
    margin: 3px 0;
    font-weight: 400
}

.msprite.naukri-sml-logo2 {
    background-position: -75px -84px;
    width: 48px;
    height: 8px;
    margin-top: 4px
}

.icon-career-compass {
    background-position: -463px -161px;
    width: 27px;
    height: 25px;
    float: none
}

ul.tab-list li.active .icon-career-compass {
    background-position: -463px -185px
}

.dream-widget-sec {
    background: #f5f8ff;
    border-top: 1px solid #d8d8d8;
    border-bottom: 1px solid #d8d8d8;
    padding: 15px 9px;
    color: #707070
}

.dream-widget-icon {
    background-position: -475px 0;
    width: 66px;
    height: 57px
}

.dream-widget-sec p {
    font-weight: 400
}

.font-20 {
    font-size: 20px
}

.dream-widget-info {
    margin-left: 85px;
    text-align: left
}

.dream-widget-btn {
    background: #f78640;
    color: #fff!important;
    text-decoration: none!important;
    padding: 6px 8px;
    display: inline-block;
    font-weight: 400;
    margin: 8px 0;
    font-size: 12px;
    text-shadow: none
}

.paytm-code-content {
    background: #f0fff5;
    color: #303030;
    font-size: 14px;
    padding: 10px;
    line-height: 20px
}

.paytm-code-content p {
    margin-bottom: 10px
}

.code-area {
    border: 1px dashed #d2d2d2;
    width: 100px;
    padding: 5px;
    width: 60px;
    display: inline-block
}

.unique-code {
    background: #e5eeff;
    font-weight: 700;
    padding: 2px 4px
}

.paytm-logo {
    background-position: -164px -185px;
    width: 47px;
    height: 15px;
    float: none;
    vertical-align: middle
}

.form-expiring-sec {
    background: #f8f7fc;
    border-top: 1px solid #e6e6e6;
    border-bottom: 1px solid #e6e6e6;
    padding: 10px;
    color: #000;
    font-size: 13px;
    margin-bottom: 1px
}

.form-expiring-sec p {
    margin-bottom: 8px
}

.form-expiring-sec label {
    color: #656363;
    font-size: 13px
}

.expire-sprtr {
    margin: 0 8px;
    display: inline-block;
    color: #cfcfd0
}

.font-15 {
    font-size: 15px
}

.sml-txt1 {
    color: #878787;
    font-size: 9px;
    padding: 10px 10px
}

.privacy-sec {
    margin-top: 5px;
    padding-left: 10px
}

.privacy-link {
    font-size: 12px
}

.privacy-content {
    background: #fff;
    color: silver;
    font-size: 10px;
    border: 1px solid #dbdbdb;
    -moz-box-shadow: -2px -2px 15px #f5f5f5 inset;
    -webkit-box-shadow: -2px -2px 15px #f5f5f5 inset;
    box-shadow: -2px -2px 15px #f5f5f5 inset;
    padding: 8px;
    margin-top: 4px
}

.rank-predictor-title {
    text-transform: uppercase;
    text-align: center;
    display: block;
    color: #000;
    font-weight: 700;
    font-size: 20px;
    line-height: 26px
}

.predictor-rank-box {
    border: 1px solid #d7d7d7;
    -moz-box-shadow: 0 2px 2px 0 rgba(0, 0, 0, .16), 0 0 0 1px rgba(0, 0, 0, .08);
    -webkit-box-shadow: 0 2px 2px 0 rgba(0, 0, 0, .16), 0 0 0 1px rgba(0, 0, 0, .08);
    box-shadow: 0 2px 2px 0 rgba(0, 0, 0, .16), 0 0 0 1px rgba(0, 0, 0, .08);
    background: #fff;
    color: #666;
    font-size: 12px;
    line-height: 18px;
    padding: 12px;
    -moz-border-radius: 3px;
    -webkit-border-radius: 3px;
    border-radius: 3px
}

.predictor-title-box {
    width: 70%;
    padding: 5px;
    line-height: 18px;
    font-size: 12px
}

.predictor-title-box p {
    margin-bottom: 3px
}

.powered-title {
    font-size: 10px;
    margin-bottom: 5px;
    text-align: center
}

ul.predictor-rank-form {
    width: 100%;
    float: left;
    margin: 15px 0 0
}

ul.predictor-rank-form li {
    width: 100%;
    float: left;
    list-style: none;
    margin-bottom: 10px
}

ul.predictor-rank-form li label {
    color: #666;
    font-size: 12px;
    line-height: 18px;
    margin-bottom: 6px;
    display: block
}

.rank-selectfield,
.rnk-predictor-textfield {
    background: #f7f5f5;
    border: 1px solid #dedede;
    -moz-border-radius: 2px;
    -webkit-border-radius: 2px;
    border-radius: 2px;
    padding: 8px;
    width: 100%;
    -moz-box-sizing: border-box;
    -webkit-box-sizing: border-box;
    box-sizing: border-box
}

.predict-rnk-btn {
    background: #008489;
    color: #fff!important;
    font-size: 14px;
    text-decoration: none;
    padding: 8px;
    display: inline-block;
    font-weight: 600;
    border-radius: 2px;
    width: 100%;
    text-transform: uppercase;
    text-align: center;
    margin-top: 20px;
    -moz-box-sizing: border-box;
    -webkit-box-sizing: border-box;
    box-sizing: border-box
}

.font-14 {
    font-size: 14px
}

.reset-btn2 {
    font-size: 14px;
    color: #575757!important;
    background: #ddd;
    border: 1px solid #d4d4d4;
    padding: 5px 13px;
    -moz-border-radius: 2px;
    -webkit-border-radius: 2px;
    border-radius: 2px;
    text-decoration: none;
    display: inline-block;
    margin: 5px 0 0
}

.predicted-rnk-info {
    font-size: 16px;
    color: #666
}

.predicted-rnk-info strong {
    color: #1b1b1b
}

.rnk-vote-section {
    color: #b8b7b7;
    font-size: 12px;
    margin: 15px 0
}

.rnk-vote-section label {
    margin-right: 10px
}

.rnk-vote-section a {
    text-decoration: none;
    color: #006fa2;
    font-weight: 700
}

.rnk-vote-dwn-icon,
.rnk-vote-dwn-icon-a,
.rnk-vote-up-icon,
.rnk-vote-up-icon-a {
    background-position: 0 -150px;
    width: 15px;
    height: 16px;
    vertical-align: middle;
    position: relative;
    top: -2px
}

.rnk-vote-dwn-icon {
    background-position: -20px -150px;
    top: 2px
}

.rnk-vote-up-icon-a {
    background-position: 0 -169px;
    top: -2px
}

.rnk-vote-dwn-icon-a {
    background-position: -20px -169px;
    top: 2px
}

.rnk-disclaimer-txt {
    font-size: 11px;
    color: #666
}

.admission-rank-head {
    background: #fff;
    padding: 10px 15px;
    margin-bottom: 10px;
    line-height: 20px
}

.rank-error-message {
    color: #ff0101;
    margin-top: 3px;
    font-size: 12px
}

.predictor-rank-widegt {
    background: #d2dfff;
    border: 1px solid #cacaca;
    -moz-border-radius: 3px;
    -webkit-border-radius: 3px;
    border-radius: 3px;
    padding: 13px;
    cursor: pointer
}

.predictor-rank-icon {
    background-position: -41px -150px;
    width: 40px;
    height: 30px;
    position: relative;
    top: 7px
}

.predictor-widget-info {
    font-size: 12px;
    margin-left: 50px;
    color: #373737;
    vertical-align: middle;
    line-height: 16px
}

.predictor-widget-info p {
    margin-bottom: 5px;
    font-weight: 700
}

.predictor-widget-info a {
    font-weight: 400;
    font-size: 14px
}

.more-layer {
    background: #fff;
    border: 1px solid #a5a6aa;
    color: #2d2d2d;
    position: absolute;
    bottom: 47px;
    right: 14px;
    width: 125px;
    -moz-box-shadow: 0 2px 2px #b3b3a4;
    -webkit-box-shadow: 0 2px 2px #b3b3a4;
    box-shadow: 0 2px 2px #b3b3a4;
    color: #2d2d2d;
    z-index: 1000;
    display: none
}

.more-layer ul {
    width: 100%
}

.more-layer ul li {
    background: #fff;
    float: left;
    width: 100%;
    border-bottom: 1px solid #a5a6aa
}

.more-layer ul li a {
    padding: 10px;
    color: #2d2d2d!important;
    font-size: 9px
}

.more-pointer {
    background-position: -296px -174px;
    width: 12px;
    height: 7px;
    position: absolute;
    bottom: -6px;
    right: 15px
}

.reg-course-listing {
    background: url(//images.shiksha.ws/public/mobile5/images/course-listing-bg.jpg) no-repeat center top;
    display: table;
    height: 316px;
    margin: auto;
    text-align: center;
    width: 305px
}

ul.reg-course-list {
    list-style: none;
    margin: 138px 0 0;
    padding: 0 18px 16px;
    text-align: center;
    box-sizing: border-box;
    -moz-box-sizing: border-box;
    -webkit-box-sizing: border-box;
    display: table;
    width: 100%
}

ul.reg-course-list li {
    list-style: none;
    margin: 5px 0 0;
    width: 100%;
    text-align: center;
    padding: 0;
    overflow: hidden
}

ul.reg-course-list .list-text {
    color: #630;
    text-align: center;
    font-size: 13px;
    line-height: 16px
}

ul.reg-course-list .list-text-hd {
    font-size: 16px;
    line-height: 20px
}

ul.reg-course-list .course-exam {
    height: 40px;
    float: left
}

ul.reg-course-list .course-exam div {
    font-size: 12px;
    margin: 0;
    padding: 0;
    color: red
}

ul.reg-course-list .course-city {
    float: left;
    width: 111px;
    background: #fff;
    border: #b5872a solid 1px;
    font-size: 12px;
    padding: 4px
}

ul.reg-course-list .exam-city {
    float: left;
    background: #fff;
    width: 150px;
    border: #b5872a solid 1px;
    font-size: 12px;
    padding: 4px;
    margin-left: 8px
}

ul.reg-course-list .list-bt {
    background: #ffd042;
    padding: 8px 0;
    color: #630;
    font-size: 14px;
    font-weight: 700;
    border-width: 0 0 2px;
    display: block;
    width: 100%;
    text-align: center;
    border-style: none;
    box-shadow: 0 1px 1px 1px rgba(0, 0, 0, .5);
    -moz-box-shadow: 0 1px 1px 1px rgba(0, 0, 0, .5);
    -webkit-box-shadow: 0 1px 1px 1px rgba(0, 0, 0, .3);
    margin-bottom: 4px
}

.reg-course-dt {
    display: block;
    border-bottom: #dcdcdc solid 1px
}

.reg-course-txt {
    font-size: 12px;
    color: #999;
    padding: 12px
}

ul.reg-course-dtls {
    list-style: none;
    margin: auto;
    padding: 0 5px 15px;
    width: 100%;
    display: table;
    box-sizing: border-box
}

ul.reg-course-dtls li {
    list-style: none;
    margin: 0;
    padding: 0;
    text-align: center;
    display: table-cell;
    width: 25%
}

ul.reg-course-dtls .even-box {
    width: 9%!important
}

.cours-dtls {
    margin: auto auto 2px;
    text-align: center;
    width: 50px;
    height: 50px;
    border: #fc6 solid 2px;
    box-sizing: border-box;
    -moz-box-sizing: border-box;
    -webkit-box-sizing: border-box;
    border-radius: 50%;
    -moz-border-radius: 50%;
    -webkit-border-radius: 50%
}

.cours-dtls-coll {
    margin-top: 6px;
    background-position: -1px -25px!important;
    width: 35px;
    height: 25px
}

.cours-dtls-place {
    margin-top: 7px;
    background-position: -37px -25px!important;
    width: 20px;
    height: 28px
}

.cours-dtls-rate {
    margin-top: 7px;
    background-position: -58px -25px!important;
    width: 32px;
    height: 28px
}

.cours-dtls-hd {
    font-size: 16px;
    color: #666
}

.cours-dtls-txt {
    font-size: 11px;
    color: #666
}

.cours-dtls-wth {
    font-size: 10px;
    color: #999
}

.categ-reg-bg {
    background: url(//images.shiksha.ws/public/mobile5/images/categ-reg-bg.jpg) no-repeat center top;
    display: table;
    height: 235px;
    margin: auto;
    text-align: center;
    width: 305px
}

ul.categ-reg-list {
    margin: 132px 0 0!important;
    padding: 0 14px 16px!important
}

.com-m-qry {
    width: 100%;
    border: #d5d5d5 solid 1px;
    background: #f3f3f3;
    margin-bottom: 5px;
    display: block;
    padding-bottom: 12px;
    color: #000
}

.com-m-qry-tab {
    width: 100%;
    background: #fff;
    overflow: hidden;
    -webkit-border-top-left-radius: 4px;
    -webkit-border-top-right-radius: 4px;
    -moz-border-radius-topleft: 4px;
    -moz-border-radius-topright: 4px;
    border-top-left-radius: 4px;
    border-top-right-radius: 4px
}

.com-m-qry-tab a {
    width: 100%;
    display: block;
    float: left;
    text-align: center;
    border-bottom: #e1e1e1 solid 1px;
    line-height: 39px;
    height: 39px;
    font-weight: 700;
    font-size: 14px;
    color: #000;
    box-sizing: border-box;
    -moz-box-sizing: border-box;
    -webkit-box-sizing: border-box
}

.com-m-bdr {
    box-sizing: border-box;
    -moz-box-sizing: border-box;
    -webkit-box-sizing: border-box;
    border-radius: 4px;
    -moz-border-radius: 4px;
    -webkit-border-radius: 4px
}

.com-m-rt-bdr {
    border-right: #e1e1e1 solid 1px
}

.com-m-qry-tab a:hover {
    text-decoration: none
}

.com-m-qry-tab .active {
    border-bottom: #3350b9 solid 3px!important
}

.com-m-hd {
    padding: 10px 10px 16px 22px;
    line-height: 18px;
    font-size: 14px
}

.com-m-l-icon {
    background-position: -100px -150px!important;
    width: 12px;
    height: 28px;
    cursor: pointer;
    margin-top: 15px;
    vertical-align: top
}

.com-m-r-icon {
    background-position: -114px -150px!important;
    width: 12px;
    height: 28px;
    cursor: pointer;
    margin-top: 15px;
    vertical-align: top;
    float: right
}

.com-m-l-dec {
    background-position: -128px -150px;
    width: 12px;
    height: 28px;
    cursor: pointer;
    margin-top: 15px;
    vertical-align: top
}

.com-m-r-dec {
    background-position: -142px -150px;
    width: 12px;
    height: 28px;
    cursor: pointer;
    margin-top: 15px;
    vertical-align: top;
    float: right
}

.com-m-stu-info {
    padding: 0 8px;
    display: block;
    overflow: hidden
}

.com-m-stu-l {
    float: left;
    width: 5%
}

.com-m-stu-r {
    float: right;
    width: 5%
}

.com-m-stu-lst {
    float: left;
    display: inline-block;
    width: 90%;
    overflow: hidden
}

ul.com-m-info {
    margin: 0 auto;
    padding: 0;
    list-style: none;
    width: 100%;
    display: block
}

.com-m-info li {
    margin: 0;
    padding: 0;
    list-style: none;
    text-align: center;
    overflow: hidden
}

.com-m-user {
    float: left;
    width: 49%;
    word-wrap: break-word;
    padding-bottom: 10px
}

.com-m-user img {
    height: 52px;
    display: block;
    width: 58px;
    text-align: center;
    margin: auto
}

.com-m-name {
    width: 60px;
    margin: auto;
    font-size: 11px;
    display: block;
    color: #282828;
    word-wrap: break-word;
    min-height: 30px
}

.com-m-ans {
    padding: 11px 14px 0;
    color: grey;
    line-height: 18px!important;
    font-size: 12px!important;
    margin: 0!important
}

.com-m-selt-bt {
    display: block;
    padding: 2px 12px
}

.com-m-qry-selt {
    border: #e7e7e7 solid 1px;
    width: 100%;
    padding: 8px 10px;
    line-height: 18px;
    margin-bottom: 8px;
    color: #999;
    font-size: 14px
}

.com-m-qry-bt {
    margin-top: 6px;
    background: #008489;
    color: #fff!important;
    font-size: 12px;
    display: block;
    height: 30px;
    font-weight: 700;
    width: 100%;
    text-align: center;
    text-transform: uppercase;
    padding: 7px 0;
    border-radius: 0
}

.com-m-qry-bt:hover {
    text-decoration: none
}

.com-mask-hd-txt {
    color: #000;
    display: block;
    font-size: 13px;
    padding: 0 0 3px
}

.mentorsip-inline-slider {
    width: 100%;
    float: left
}

.mentorsip-inline-slider .mentor-widget-title {
    font-family: "Open Sans", sans-serif
}

.middle-links {
    font-size: 12px;
    text-decoration: none;
    color: #006fa2
}

.mentorsip-inline-slider ul li {
    list-style: none;
    position: relative;
    margin-bottom: 15px;
    float: left;
    width: 100%;
    background: #fff
}

.mentorsip-inline-slider .mentor-widget-head {
    font-size: 16px;
    line-height: 26px;
    padding-top: 35px
}

.font-12 {
    font-size: 12px
}

strong+p.font-12 {
    box-sizing: border-box;
    padding: 0 15px
}

.mentorship-badge {
    background: #f78640;
    color: #fff;
    font-size: 12px;
    padding: 5px 15px;
    position: absolute;
    top: -1px;
    left: 0
}

.mentorsip-inline-slider .next-box,
.mentorsip-inline-slider .prev-box {
    background: #e0e6fd;
    padding: 5px 5px 5px 5px;
    position: absolute;
    top: 45%;
    left: 0;
    right: auto;
    text-align: center;
    cursor: pointer
}

.mentorsip-inline-slider .next-box {
    right: 0;
    left: auto
}

.mentorsip-inline-slider .next-icon,
.mentorsip-inline-slider .next-icon-a,
.mentorsip-inline-slider .prev-icon,
.mentorsip-inline-slider .prev-icon-a {
    background-position: -17px -92px;
    width: 11px;
    height: 17px;
    margin: 0
}

.mentorsip-inline-slider .next-icon {
    background-position: -31px -92px!important
}

.mentorsip-inline-slider .prev-icon-a {
    background-position: -46px -92px
}

.mentorsip-inline-slider .next-icon-a {
    background-position: -60px -92px
}

.mentorsip-inline-slider ul.mentor-widget-list {
    width: 222px
}

.mentorsip-inline-slider ul.mentor-widget-list li {
    list-style: disc!important;
    margin-bottom: 5px!important;
    margin-leeft: 10px
}

.free-prgm-title {
    font-size: 10px;
    color: #a6a6a6;
    margin-top: 2px
}

.mentorsip-inline-slider .get-mentor-sec strong {
    margin-bottom: 0
}

.mentorsip-inline-slider .mentor-widget-box ul {
    margin-top: 12px;
    margin-left: 40px
}

.mentorsip-inline-slider ol.flex-control-nav {
    display: none
}

p.work-detail-list {
    float: left;
    width: 100%;
    margin-bottom: 10px
}

p.work-detail-list span {
    width: 21%;
    display: inline-block;
    float: left;
    margin: 0;
    color: #747474!important;
    font-size: 15px!important
}

p.work-detail-list span.seperator {
    width: 8%;
    color: #e7e7e7!important;
    display: inline-block;
    font-size: 16px!important;
    margin-left: 0
}

.share-social-links {
    border-top: 1px solid #e1e1e1;
    width: 100%;
    float: left;
    margin: 20px 0 0
}

.share-social-links ul {
    width: 100%;
    float: left;
    margin-top: 10px
}

.share-social-links ul li {
    float: left;
    margin-right: 4px
}

.share-social-links ul li label {
    display: inline-block;
    color: #656565;
    font-size: 14px;
    padding-top: 3px
}

.facebook-share-icon,
.google-plus-icon,
.linkedin-share-icon,
.twitter-share-icon,
.whatsapp-share-icon {
    background-position: -480px -61px!important;
    width: 30px;
    height: 30px
}

.twitter-share-icon {
    background-position: -514px -61px!important
}

.linkedin-share-icon {
    background-position: -480px -100px!important
}

.whatsapp-share-icon {
    background-position: -514px -100px!important
}

.google-plus-icon {
    background-position: -514px -132px!important
}

.verification-mobile-share {
    border-radius: 0!important
}

.verification-mobile-share .layer-head {
    border-radius: 0!important;
    background: #fff;
    color: #010101;
    font-size: 16px
}

.verification-mobile-share a.close-box {
    background: #fff;
    color: #000;
    font-size: 19px
}

.verification-mobile-share .layer-content {
    font-size: 12px;
    color: #999
}

.verification-mobile-share .next-info {
    color: #666;
    font-size: 12px;
    margin: 6px 0 2px 0
}

.verification-mobile-share .share-link-box {
    background: #e9e9e9;
    padding: 8px;
    -webkit-border-radius: 4px;
    -moz-border-radius: 4px;
    border-radius: 4px;
    color: #666;
    font-size: 12px;
    vertical-align: middle;
    margin: 4px 0 6px
}

.verify-share-button {
    padding: 0 43px;
    margin: 10px 0;
    width: 337px
}

.share-button {
    width: 250px;
    background: #4460ae;
    color: #fff;
    padding: 5px 10px;
    -moz-border-radius: 4px;
    -webkit-border-radius: 4px;
    border-radius: 4px;
    font-size: 16px;
    cursor: pointer;
    margin-bottom: 10px
}

.fb-share-big-icn,
.gplus-share-big-icn,
.linkdin-share-big-icn {
    background: url(//images.shiksha.ws/public/mobile5/images/verify-fb-icon.jpg) no-repeat;
    width: 46px;
    height: 33px;
    display: inline-block;
    vertical-align: middle;
    margin-right: 5px
}

.linkdin-share-big-icn {
    background: url(//images.shiksha.ws/public/mobile5/images/verify-linkdin-icon.jpg) no-repeat
}

.gplus-share-big-icn {
    background: url(//images.shiksha.ws/public/mobile5/images/verify-gplus-icon.jpg) no-repeat
}

.newsfeed-info {
    border-top: 1px solid #ccc;
    padding-top: 10px;
    margin-top: 30px;
    font-size: 12px;
    color: #666
}

.permalink-sprite {
    background: url(//images.shiksha.ws/public/mobile5/images/permalink-review-sprite-new.png);
    display: inline-block;
    position: relative;
    float: left
}

.link-share-icon {
    background-position: -140px -40px!important;
    width: 14px;
    height: 14px;
    margin-right: 5px
}

ol.competition-layer-text {
    font-size: 12px;
    padding: 10px 20px
}

ol.competition-layer-text li {
    list-style: decimal;
    margin-bottom: 8px;
    line-height: 20px
}

#popupBasic.verification-share-layer {
    left: 248.6px!important;
    top: 15px!important;
    position: fixed!important
}

.permalink-share-list,
.permalink-share-list-2 {
    border-top: 1px solid #e1e1e1;
    border-bottom: 1px solid #e1e1e1;
    margin: 10px 0
}

.permalink-share-list ul,
.permalink-share-list-2 ul {
    width: 100%;
    float: left;
    padding: 8px 0;
    margin-left: 0!important
}

.permalink-share-list ul li,
.permalink-share-list-2 ul li {
    float: left;
    margin-right: 3px;
    font-size: 15px;
    color: #666;
    list-style: none!important
}

.permalink-fb-share-icon,
.permalink-gplus-share-icon,
.permalink-linkdin-share-icon,
.permalink-twt-share-icon {
    background-position: 0 -66px;
    width: 70px;
    height: 30px
}

.permalink-twt-share-icon {
    background-position: 0 -100px
}

.permalink-linkdin-share-icon {
    background-position: 0 -134px
}

.permalink-gplus-share-icon {
    background-position: 0 -169px
}

.review-submited {
    padding-top: 10px;
    font-weight: 400;
    color: #404041;
    font-size: 12px
}

.review-submited strong {
    font-size: 12px;
    font-weight: 600;
    color: #000
}

.review-publish {
    font-size: 10px;
    color: #404041;
    font-weight: 400
}

.review-publish em {
    font-size: 10px;
    color: #00000;
    font-weight: 600;
    font-style: normal
}

.review-publish strong {
    font-size: 10px;
    color: #00000;
    font-weight: 600
}

.h1 {
    font-size: 16px;
    font-weight: 700;
    color: #000;
    padding: 24px 0
}

.accnt-yes-box {
    background: #e9e9e9;
    padding: 8px;
    color: #666;
    font-size: 12px;
    vertical-align: middle;
    margin: 4px 0 6px
}

.accnt-yes-box input[type=text] {
    margin-right: 10px
}

.accnt-yes-box label {
    color: #eb9f34;
    font-size: 14px;
    font-weight: 400
}

.accnt-yes-box p {
    font-size: 12px;
    color: #404041;
    font-weight: 400;
    padding-left: 25px;
    margin-top: 10px
}

.accnt-yes-box p strong {
    font-size: 12px;
    color: #404041;
    font-weight: 600
}

.send-email-txt {
    width: 100%;
    padding: 8px 0;
    margin-top: 24px;
    text-indent: 15px;
    border: 1px solid #e2e3e4;
    display: block
}

.email-subt-btn {
    width: 100%!important;
    margin-top: 21px;
    padding: 8px!important;
    background-color: #eb9f34!important;
    font-weight: 700;
    color: #fff!important;
    font-size: 14px;
    border: none;
    display: inline-block;
    text-align: center;
    -moz-box-sizing: border-box;
    -webkit-box-sizing: border-box;
    box-sizing: border-box
}

.review-head {
    font-size: 16px;
    font-weight: 700;
    color: #000
}

.enter-email {
    font-size: 12px;
    font-weight: 400;
    color: #404041;
    margin: 15px 0
}

.enter-email strong {
    font-size: 14px;
    font-weight: 600;
    color: #000
}

.left-val {
    vertical-align: text-bottom;
    margin-right: 9px
}

.mobile-otp-layer {
    padding: 10px;
    font-size: .9em;
    line-height: 21px
}

.mobile-otp-layer strong {
    font-size: 17px;
    margin-bottom: 5px
}

.mobile-otp-layer p {
    margin-bottom: 4px
}

.otp-field {
    background: #fff;
    border: 1px solid #eee;
    border-radius: 0!important;
    width: 200px;
    float: left
}

.mobile-otp-layer .ui-shadow-inset {
    box-shadow: none!important
}

.mobile-otp-layer .ui-corner-all {
    border-radius: 0!important
}

a.otp-submit-btn {
    color: #fff;
    background: #008489;
    padding: 5px 10px;
    margin-top: 5px;
    display: inline-block;
    font-weight: 700;
    font-size: 12px;
    text-transform: uppercase
}

.img-box1 {
    padding: 0!important;
    height: 150px;
    background: url(//images.shiksha.ws/public/mobile5/images/categ-bg.png) no-repeat;
    background-size: 100% 100%;
    position: relative
}

.img-col p {
    font-size: 14px;
    color: #fff;
    font-weight: 700;
    text-align: left;
    position: absolute;
    left: 10px;
    right: 0;
    top: 64%
}

.img-col {
    position: absolute;
    height: 131px;
    background: rgba(23, 23, 23, 0);
    background: -moz-linear-gradient(top, rgba(23, 23, 23, 0) 0, rgba(23, 23, 23, .44) 74%, rgba(0, 0, 0, .59) 100%);
    background: -webkit-gradient(left top, left bottom, color-stop(0, rgba(23, 23, 23, 0)), color-stop(74%, rgba(23, 23, 23, .44)), color-stop(100%, rgba(0, 0, 0, .59)));
    background: -webkit-linear-gradient(top, rgba(23, 23, 23, 0) 0, rgba(23, 23, 23, .44) 74%, rgba(0, 0, 0, .59) 100%);
    background: -o-linear-gradient(top, rgba(23, 23, 23, 0) 0, rgba(23, 23, 23, .44) 74%, rgba(0, 0, 0, .59) 100%);
    background: -ms-linear-gradient(top, rgba(23, 23, 23, 0) 0, rgba(23, 23, 23, .44) 74%, rgba(0, 0, 0, .59) 100%);
    background: linear-gradient(to bottom, rgba(23, 23, 23, 0) 0, rgba(23, 23, 23, .44) 74%, rgba(0, 0, 0, .59) 100%);
    width: 100%
}

.cat-ile {
    position: relative;
    top: 77%;
    left: 10px;
    color: #fff;
    font-size: 11px
}

.container-bnr {
    width: 320px;
    height: 150px;
    background: url(//images.shiksha.ws/public/mobile5/images/categ-bg.png) 0 0 repeat;
    margin: auto
}

.mid-box-bnr {
    padding-bottom: 10px;
    text-align: center;
    width: 320px;
    margin: auto;
    box-sizing: border-box;
    -moz-box-sizing: border-box;
    -webkit-box-sizing: border-box
}

.mid-box-bnr a,
.mid-box-bnr a:hover {
    color: #fff;
    font-size: 13px;
    margin-bottom: 15px
}

.top-hd-bnr {
    padding: 10px 0 3px 0;
    text-align: center;
    font: 500 13px/18px "Open Sans";
    color: #3a3a3a;
    letter-spacing: -.5px
}

.top-hd-bnr span {
    font-family: inherit
}

.sml-txt-bnr {
    font: 500 11px/14px "Open Sans";
    color: #959697;
    width: 280px;
    margin: auto;
    text-align: center
}

.cat-bt-bnr {
    background: #eca740;
    font-size: 13px;
    text-decoration: none;
    color: #fff;
    padding: 2px 13px;
    display: inline-block;
    margin-top: 5px
}

.social-login-layer {
    width: 100%;
    border: 2px solid #ccc;
    -moz-border-radius: 2px;
    -webkit-border-radius: 2px;
    border-radius: 2px;
    background: #fff;
    padding: 10px 25px;
    margin: 0 auto;
    color: #999;
    font-size: 12px;
    -moz-box-sizing: border-box;
    -webkit-box-sizing: border-box;
    box-sizing: border-box
}

.social-login-title {
    font-size: 16px;
    color: #666;
    padding-bottom: 8px;
    width: 90%
}

.verify-share-button2 {
    margin: 10px 0;
    width: 337px;
    text-align: center
}

a.social-cross-mark {
    color: #5f5f5f;
    font-size: 26px;
    text-decoration: none!important;
    font-weight: 400
}

.share-button {
    width: 250px;
    background: #4460ae;
    color: #fff;
    padding: 5px 10px;
    -moz-border-radius: 4px;
    -webkit-border-radius: 4px;
    border-radius: 4px;
    font-size: 16px;
    cursor: pointer;
    margin-bottom: 10px
}

.fb-share-big-icn,
.gplus-share-big-icn,
.linkdin-share-big-icn {
    background: url(//images.shiksha.ws/public/mobile5/images/verify-fb-icon.jpg) no-repeat;
    width: 46px;
    height: 33px;
    display: inline-block;
    vertical-align: middle;
    margin-right: 5px
}

.linkdin-share-big-icn {
    background: url(//images.shiksha.ws/public/mobile5/images/verify-linkdin-icon.jpg) no-repeat
}

.gplus-share-big-icn {
    background: url(//images.shiksha.ws/public/mobile5/images/verify-gplus-icon.jpg) no-repeat
}

.newsfeed-info {
    border-top: 1px solid #ccc;
    padding-top: 10px;
    margin-top: 30px;
    font-size: 12px;
    color: #666
}

.next-info {
    color: #666;
    font-size: 14px;
    margin: 8px 0
}

.share-link-box {
    background: #e9e9e9;
    padding: 8px;
    -webkit-border-radius: 4px;
    -moz-border-radius: 4px;
    border-radius: 4px;
    color: #666;
    font-size: 12px;
    vertical-align: middle;
    margin: 8px 0 0
}

.comp-circle {
    width: 20px;
    height: 20px;
    margin-right: 2px;
    background-position: 0 -183px;
    float: none;
    vertical-align: middle;
    text-indent: -9999px;
    top: 2px
}

.link-share-icon {
    background-position: -140px -40px;
    width: 14px;
    height: 14px;
    margin-right: 5px
}

input[value="Searching..."] {
    display: none;
    background: #0a828c;
    text-shadow: none;
    color: #fff;
    padding: 0
}

.new-blue {
    border: 1px solid #0ba5b5;
    color: #0ba5b5!important;
    font-size: 14px;
    font-weight: 300;
    width: 130px;
    box-sizing: border-box;
    padding: 6px 5px;
    text-align: center;
}

.year-est {
    font-size: 10px!important;
    font-weight: 400
}

.compare-items ul li.last span a {
    color: #595a5c;
    font-weight: 300;
    font-size: 12px
}

.compare-items ul li span a {
    color: #595a5c;
    font-weight: 300;
    font-size: 12px
}

.still-questions {
    color: #5a595c;
    font-size: 12px;
    font-weight: 300
}

.ask-stdnts {
    color: #5a595c;
    font-size: 14px;
    font-weight: 600
}

.bold-class {
    color: #1c252b;
    font-size: 14px;
    font-weight: 400
}

.sticky-head {
    background: #008489;
    display: block
}

.clg-selection {
    display: inline-block;
    width: 50%;
    border-right: 1px solid #bbbbbd;
    color: #fff;
    font-size: 10px;
    padding: 1em .7em
}

.link-share-icon {
    background-position: -140px -40px;
    width: 14px;
    height: 14px;
    margin-right: 5px
}

#comparePageDataTable {
    display: table
}

.compare-table {
    font-family: "Open Sans"
}

.compare-item {
    min-height: 74px;
    position: relative;
    top: 0
}

.show-year {
    display: block;
    position: relative;
    top: 0
}

.show-loc {
    display: block;
    position: relative;
    top: 0
}

.compare-table tr td .ui-select {
    background: #fff;
    border: 1px solid #bbbbbd
}

.college-rank {
    color: #5a595c;
    text-align: left;
    font-size: 12px;
    position: relative;
    margin-bottom: 5px
}

.college-rank:last-child {
    margin-bottom: 0
}

.border-right .ui-select {
    background: #fff;
    border: 1px solid #bbbbbd
}

.cc-clg-title {
    color: #999!important;
    font-size: 14px;
    font-weight: 400;
    -webkit-hyphens: auto;
    -moz-hyphens: auto;
    -ms-hyphens: auto;
    hyphens: auto
}

.estd-year {
    color: #b2b2b2!important;
    font-weight: 400;
    font-size: 10px!important;
    padding-top: 8px
}

.cc-btn {
    width: 100%;
    height: 30px;
    display: inline-block;
    text-align: center;
    background: #e5e5e4;
    color: #5a5959;
    font-size: 14px;
    font-weight: 400;
    box-sizing: border-box;
    -webkit-box-sizing: border-box;
    -moz-box-sizing: border-box;
    line-height: 30px
}

a.cc-btn:active,
a.cc-btn:focus,
a.cc-btn:hover,
a.cc-btn:visited {
    color: #5a5959
}

.disabled-btn {
    background: #ede8e8!important;
    color: #fff!important;
    pointer-events: none;
    font-weight: lighter!important
}

.new-orng {
    border: 1px solid #eea234;
    border-radius: 0;
    color: #eea234!important;
    font-size: 10px!important;
    text-shadow: none!important;
    box-shadow: none!important;
    -webkit-box-shadow: none!important;
    font-weight: 400!important;
    padding: 5px 7px;
    line-height: 18px;
    height: auto
}

.new-orng:active,
.new-orng:hover,
.new-orng:visited {
    box-shadow: none!important;
    color: #eea234
}

.new-orng .icon-pencil {
    background-image: url(//images.shiksha.ws/public/mobile5/images/mobile-sprite.png);
    position: relative;
    top: 0;
    background-position: -78px -182px;
    width: 16px;
    height: 19px
}

.new-blue {
    background: #008489;
    color: #fff!important;
    border-radius: 0;
    text-shadow: none;
    width: 100%!important;
    box-shadow: none;
    -webkit-box-shadow: none;
    box-sizing: border-box;
    -webkit-box-sizing: border-box;
    -moz-box-sizing: border-box
}

.new-blue:active,
.new-blue:hover,
.new-blue:visited {
    box-shadow: none;
    color: #fff
}

.new-msg {
    padding: 10px 0 0!important;
    color: #5a595c;
    font-size: 12px;
    font-weight: lighter;
    text-align: center;
    border-top: 1px solid #f1f1f1
}

.new-msg1 {
    padding: 0 0 10px!important;
    color: #5a595c;
    font-size: 12px;
    font-weight: 600;
    text-align: center;
    border-bottom: 1px solid #f1f1f1
}

.compare-items ul li>span>a {
    color: #595a5c
}

.get-loc {
    color: #999!important;
    font-weight: 300;
    padding: 10px 0;
    font-size: 13px;
    display: block;
    line-height: 20px;
    padding-left: 4px
}

.icon-location {
    background-image: url(//images.shiksha.ws/public/mobile5/images/mobile-sprite.png);
    position: relative;
    top: 1px;
    background-position: 0 -185px;
    width: 11px;
    height: 19px;
    left: -4px
}

.icon-share {
    background-image: url(//images.shiksha.ws/public/mobile5/images/mobile-sprite.png);
    width: 17px;
    height: 19px;
    float: none;
    vertical-align: baseline;
    background-position: -97px -181px;
    display: inline-block;
    position: relative;
    top: 3px;
    left: -11px
}

.compare-table tr td .shortlist-star,
.compare-table tr td .shortlisted-star {
    background-image: url(//images.shiksha.ws/public/mobile5/images/mobile-sprite.png)!important;
    width: 19px;
    height: 19px;
    float: none;
    vertical-align: baseline!important;
    background-position: -116px -184px;
    display: inline-block;
    position: relative;
    top: 3px;
    left: 0
}

.compare-table tr td .shortlisted-star {
    background-position: -136px -183px
}

.clg-rcgn {
    background: url(//images.shiksha.ws/public/images/co_dot.jpg) left 6px no-repeat;
    padding-left: 15px;
    margin-bottom: 2px;
    vertical-align: middle;
    position: absolute;
    width: 3px;
    height: 15px
}

p.recognition {
    text-align: left
}

.p-left {
    padding-left: 15px
}

.infra {
    display: block
}

ul.infra-list {
    list-style: none
}

ul.infra-list li {
    display: block;
    text-align: left;
    padding: 5px 5px
}

ul.infra-list li.crs-txt {
    text-decoration: line-through;
    color: #babbbd!important
}

.clg-review-box {
    display: block
}

.clg-review-box .std-name {
    color: #5a595c;
    font-size: 12px;
    line-height: 16px;
    font-weight: 600;
    text-align: left
}

.clg-review-box .year-class {
    color: #999;
    font-size: 10px;
    line-height: 16px;
    font-weight: 400;
    text-align: left
}

.clg-review-box .review-sum {
    margin: 15px 0;
    display: block;
    color: #5a595c;
    text-align: left;
    font-size: 12px;
    line-height: 16px;
    font-weight: 300
}

.clg-review-box .view-review {
    color: #008489;
    font-size: 11px
}

.border-right .compare-list {
    text-align: left
}

.custome-compare-dropdown {
    position: relative;
    float: right;
    width: 100%;
    font-size: 11px;
    color: #000333;
    z-index: 99;
    text-align: left
}

.custome-compare-dropdown>a {
    border: 1px solid #ccc;
    background: #fff;
    height: 25px;
    display: block;
    color: #000333!important;
    overflow: hidden;
    padding: 0 0 0 3px
}

.custome-compare-dropdown .arrow {
    width: 19px;
    height: 23px;
    float: right;
    position: relative
}

.custome-compare-dropdown .arrow .caret {
    display: inline-block;
    width: 0;
    height: 0;
    vertical-align: middle;
    border-left: 5px solid transparent;
    border-bottom: 4px solid transparent;
    border-right: 4px solid transparent;
    border-top: 4px solid;
    color: #000333;
    position: absolute;
    left: 3px;
    top: 10px
}

.custome-compare-dropdown .display-area {
    display: block;
    line-height: 24px;
    font-size: 12px;
    font-weight: 400;
    color: #595a5c
}

.custome-compare-dropdown .drop-layer {
    position: absolute;
    right: 0;
    top: 24px;
    left: 0;
    background: #fff;
    border: 1px solid #ccc;
    max-height: 144px;
    overflow: auto
}

.custome-compare-dropdown .drop-layer ul {
    width: 100%
}

.custome-compare-dropdown .drop-layer ul li {
    list-style: none
}

.custome-compare-dropdown .drop-layer ul li a {
    padding: 6px 6px;
    display: block;
    border-bottom: 1px solid #ccc;
    color: #000333!important
}

.custome-compare-dropdown .drop-layer ul li:last-child a {
    border: 0 none
}

.compare-table th .close-link {
    font-size: 1.5em;
    margin: 12px 0 15px!important
}

.compare-table tr td p.alignText {
    text-align: center;
    font-size: 14px;
    font-weight: 600;
    color: #1c252b
}

.compare-table tr td p.alignText span {
    color: #595a5c;
    font-size: 10px;
    font-weight: 300
}

.compare-table tr td p.examscut-off {
    color: #595a5c;
    font-size: 12px;
    font-weight: 400;
    line-height: 15px
}

.compare-table tr td p.examscut-off span {
    color: #1c252b;
    font-size: 14px;
    font-weight: 600;
    margin: 0 3px
}

.compare-table tr td p.fees-inr {
    color: #1c252b;
    font-size: 14px;
    font-weight: 600
}

.compare-table tr td p.fees-inr strong {
    color: #595a5c;
    font-size: 10px;
    font-weight: 300;
    margin-right: 5px
}

.compare-table tr td p.fees-inr span {
    color: #595a5c;
    font-size: 10px;
    font-weight: 300
}

.social-links1 {
    position: fixed;
    width: 100%;
    background: rgba(0, 0, 0, .59);
    height: 100%;
    top: 0;
    left: 0;
    bottom: 0;
    right: 0;
    z-index: 99996;
    display: none
}

.scl {
    position: fixed;
    bottom: 0;
    background: #fff;
    width: 100%;
    left: 0;
    right: 0;
    z-index: 99999;
    height: 150px
}

.scl p {
    color: #999;
    font-size: 14px;
    display: block;
    font-weight: 400;
    padding: 10px 15px;
    font-family: "Open Sans";
    line-height: 20px
}

.scl ul.foote-scl {
    list-style: none;
    min-height: 100px;
    margin: 10px 0;
    padding: 0;
    display: table;
    width: 100%
}

ul.foote-scl li {
    width: 33.33%;
    text-align: center;
    display: table-cell
}

ul.foote-scl li a {
    position: relative;
    text-decoration: none;
    color: #999;
    font-size: 12px;
    font-weight: 400;
    font-family: "Open Sans";
    display: block;
    text-align: center
}

ul.foote-scl li a p {
    position: relative;
    top: 45px
}

.spriteSocialIcons {
    background: url(//images.shiksha.ws/public/mobile5/images/social-sprite.png);
    display: inline-block;
    position: relative
}

.ic-fb {
    background-position: 1px 0;
    width: 38px;
    height: 38px;
    position: absolute;
    left: 0;
    right: 0;
    top: 0;
    bottom: 0;
    margin: 0 auto
}

.ic-tweet {
    background-position: -37px 0;
    width: 38px;
    height: 38px;
    position: absolute;
    left: 0;
    right: 0;
    top: 0;
    bottom: 0;
    margin: 0 auto
}

.ic-wa {
    background-position: -77px 0;
    width: 38px;
    height: 38px;
    position: absolute;
    left: 0;
    right: 0;
    top: 0;
    bottom: 0;
    margin: 0 auto
}

.compare-table tr.collegeListSec td {
    padding: 0 15px 5px 15px;
    vertical-align: middle;
    border: none!important
}

.compare-table tr.collegeListSec.first td {
    vertical-align: top;
    padding-top: 10px
}

.compare-table tr.collegeListSec.first td.empty {
    vertical-align: middle;
    position: relative
}

.compare-table tr.collegeListSec.last td {
    padding-bottom: 20px
}

.compare-table tr.collegeListSec td a {
    display: block
}

.compare-table tr.collegeListSec td a.close-link {
    display: block;
    padding: 5px;
    margin: 5px 0 15px 0;
    float: none;
    text-align: right;
    font-size: 18px
}

.compare-table tr.collegeListSec td p {
    padding: 0
}

.data-source-col {
    color: #babbbd;
    font-size: 10px
}

.doted-txt {
    font-size: 22px;
    color: #595a5c;
    text-align: center;
    display: block
}

.dis-brcher-btn {
    background: rgba(204, 204, 204, .15);
    border: 1px solid rgba(204, 204, 204, .35);
    color: rgba(0, 0, 0, .38)!important;
    padding: 5px 7px;
    line-height: 18px
}

.sticky-header {
    width: 100%;
    display: table;
    background: #008489
}

.sticky-header .compare-table {
    width: 100%;
    display: table
}

.border-new {
    border-right: 1px solid #104f5f
}

.border-new:last-child {
    border: 0
}

.sticky-header .compare-table tr th {
    padding: 7px 15px
}

.sticky-header .compare-table tr th.border-new {
    background: #0ba5b5;
    color: #fff;
    font-size: 14px;
    line-height: 12px;
    border-top: 0;
    border-bottom: 0;
    border-left: 0
}

.location-colg {
    color: #fff;
    line-height: 12px;
    font-size: 12px;
    margin-top: 5px
}

.clg-name {
    line-height: 15px;
    -webkit-hyphens: auto;
    -moz-hyphens: auto;
    -ms-hyphens: auto;
    hyphens: auto
}

.collegeListSec .disabled {
    display: block!important;
    font-size: 10px;
    color: #b7b7b7!important;
    text-align: center;
    background: #f2f1f0;
    border: none;
    font-weight: 400;
    padding: 5px 7px;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
    line-height: 19px
}

.collegeListSec .disabled,
.compare-table .collegeListSec .applynow-txt {
    border: 1px solid #f2f1f0
}

.applynow-txt {
    display: block!important;
    line-height: 25px;
    font-size: 10px;
    color: #b7b7b7!important;
    text-align: center;
    background: #f2f1f0;
    font-weight: 400;
    padding: 5px 7px;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap
}

.apply-btn-noui,
a.apply-btn-noui,
a.apply-btn-noui:hover {
    display: block;
    padding: 7px 7px 7px 7px;
    text-align: center;
    color: #666;
    text-decoration: none
}

i.readonly {
    background: url(//images.shiksha.ws/public/mobile5/images/mobile-sprite.png);
    display: inline-block;
    position: relative;
    width: 15px;
    height: 18px;
    background-position: -34px -199px;
    top: -1px;
    left: 0;
    float: left
}

.verticalalign {
    vertical-align: middle
}

.r-h * {
    margin: 0;
    padding: 0;
    font-family: 'open sans';
    font-weight: 400
}

.r-h {
    background: #f5f5f5;
    padding: 10px;
    color: #7d7d7d;
    font-size: 13px;
    line-height: 20px;
    border-bottom: 1px solid #cececc;
    position: fixed;
    z-index: 1;
    width: 100%
}

#addToCompareLayerContent .r-h {
    position: static;
    width: auto
}

.remove-tab {
    border-left: 1px solid #ccc;
    display: inline-block;
    font-size: 24px;
    height: 20px;
    line-height: 20px;
    position: relative;
    text-decoration: none;
    top: 4px;
    width: 20px;
    float: right;
    padding-left: 10px;
    color: #8e8e8e!important
}

#addToCompareLayerContent .remove-tab {
    border-left: 0;
    color: #fff!important;
    top: 0
}

.reviewed-ins-title {
    font-size: 14px;
    color: #5d5d5d;
    line-height: 25px;
    width: 100%;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    display: block
}

.r-h p {
    display: block;
    width: 100%;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap
}

#rldp {
    vertical-align: middle
}

.collge-review-list {
    width: 100%;
    float: left;
    padding: 20px 10px;
    -moz-box-sizing: border-box;
    -webkit-box-sizing: border-box;
    box-sizing: border-box;
    background: #fff;
    margin: 56px 0 0
}

.collge-review-list ul {
    width: 100%;
    float: left;
    padding: 0;
    margin: 0
}

.collge-review-list ul li {
    list-style: none;
    margin-bottom: 20px;
    padding-bottom: 20px;
    border-bottom: 1px solid #e6e6dc;
    width: 100%;
    float: left
}

.collge-review-list ul li.last-child {
    border-bottom: none;
    padding: 0;
    margin: 0
}

.review-list-detail {
    width: 100%;
    float: left;
    margin-bottom: 15px
}

.user-initial {
    color: #6d6d6d;
    font-size: 24px;
    font-family: 'open sans';
    float: left;
    background: #e1e0e0;
    width: 40px;
    height: 40px
}

.user-initial img {
    width: 40px;
    height: 40px
}

.user-initial span {
    text-align: center;
    margin-top: 4px;
    display: block
}

.user-info {
    margin-left: 50px;
    color: #999;
    font-size: 12px;
    line-height: 18px;
    font-family: 'open sans'
}

.font-11 {
    font-size: 11px
}

.user-info p {
    margin: 0;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap
}

.user-title {
    color: #666;
    font-size: 14px;
    font-weight: 600
}

.user-title span {
    color: #b3b3b3;
    font-size: 12px
}

.review-rating-div {
    width: 100%;
    float: left;
    color: #999;
    font-size: 10px
}

.review-rating {
    background: #eea234;
    color: #fff;
    font-size: 11px;
    padding: 1px 4px;
    border-radius: 2px;
    text-align: center;
    display: inline-block;
    width: 26px
}

.sml-entity {
    font-size: 10px;
    font-style: normal
}

.review-sprtr {
    color: #cececc;
    font-size: 15px
}

.review-list-content {
    color: #5d5d5d;
    font-family: 'open sans';
    font-size: 13px;
    line-height: 20px;
    width: 100%;
    float: left
}

.review-list-content p {
    margin: 0
}

.view-more-link {
    color: #008489;
    text-decoration: none!important
}

.non-reco-icon,
.reco-icon {
    background-position: -57px -185px;
    width: 8px;
    height: 10px;
    vertical-align: middle;
    margin-right: 2px;
    top: -2px
}

.non-reco-icon {
    background-position: -60px -203px;
    top: 0
}

.clock-icon {
    background-position: -67px -185px;
    width: 9px;
    height: 9px;
    vertical-align: middle;
    margin-right: 3px;
    top: -1px
}

.mt2 {
    margin-top: 2px
}

.que-textarea {
    border: 10x solid #5d5d5d;
    padding: 10px;
    color: #b3b3b3;
    height: 100px;
    width: 100%;
    -moz-box-sizing: border-box;
    -webkit-box-sizing: border-box;
    box-sizing: border-box;
    font-family: 'open sans';
    border-radius: 0;
    box-shadow: none
}

.avlb-char {
    color: #b3b3b3;
    font-size: 12px;
    margin-bottom: 10px!important
}

.collge-review-list .green-btn {
    font-size: 14px;
    color: #fff;
    background-color: #0ba5b5;
    cursor: pointer;
    padding: 8px 15px;
    display: inline-block;
    border: 0;
    text-decoration: none;
    width: 100%;
    font-weight: 400;
    text-align: center;
    -moz-box-sizing: border-box;
    -webkit-box-sizing: border-box;
    box-sizing: border-box;
    font-family: 'open sans'
}

.spanForCampusRepName {
    color: #666!important;
    font-size: 14px
}

.collge-comparison-search {
    padding: 1em;
    background: #fff;
    font-family: "Open Sans", sans-serif
}

.cc_inputsrchBx {
    position: relative;
    display: inline-block;
    background-color: #fff;
    width: 100%;
    border: 1px solid #bbbbbd
}

.collge-comparison-search .cc_inputsrchBx {
    width: 100%
}

. .cc_inputsrchBxInnr {
    position: relative;
    display: inline-block;
    width: 100%;
    box-shadow: 0 0 4px #dadada
}

.cc_inputsrchBx input {
    color: #b3b3b3;
    font-size: 12px;
    border: 0;
    font-weight: 400;
    width: 75%;
    line-height: normal;
    display: block;
    padding: 10px 0 10px 0;
    outline: 0 none;
    margin: 0 auto
}

.collge-comparison-search .cc_sugestd {
    width: 100%;
    position: absolute;
    background: #fff;
    right: 0;
    left: -1px;
    border: 1px solid #bbbbbd;
    display: none;
    z-index: 1
}

.sugsted-ul {
    list-style: none;
    width: 100%;
    margin: 0;
    padding: 0
}

.cc_inputsrchBx .cc_sugestd ul li span {
    font-size: 12px;
    color: grey;
    display: block;
    text-decoration: none;
    cursor: pointer;
    text-align: left;
    padding: 12px 10px
}

.cc_inputsrchBx .cc_sugestd ul li span:hover {
    background: #f1f1f1
}

.recomnded-colgs {
    display: block;
    background: #fff;
    font-family: "Open Sans", sans-serif
}

.msprite.cc-search {
    background-position: -92px -22px;
    width: 24px;
    height: 24px;
    position: absolute;
    top: 3px;
    left: 10px
}

.cc-locality {
    background-position: 3px -185px;
    width: 15px;
    height: 15px;
    position: absolute;
    top: 0;
    left: 0
}

.recomnded-h1 {
    display: block;
    text-transform: uppercase;
    padding: 14px 20px;
    background: #f7f7f7;
    font-size: 12px;
    font-family: open sans;
    font-weight: 300;
    color: #707070;
    margin-bottom: 0
}

.recomnded-tab {
    display: block;
    text-transform: uppercase;
    padding: 14px 20px;
    background: #f7f7f7;
    font-size: 12px;
    font-family: open sans;
    font-weight: 300;
    color: #707070;
    margin: 0
}

.cntr-txt {
    display: block;
    text-align: center;
    margin: 10px 0 0
}

.recmnded-colg-slist {
    display: block;
    font-family: Open Sans
}

.recmnded-colg-slist ul {
    list-style: none;
    margin: 0;
    padding: 0
}

.recmnded-colg-slist ul li a {
    font-size: 12px;
    color: #666;
    border-bottom: 1px solid #f6f6f6;
    display: block;
    text-decoration: none;
    cursor: pointer;
    font-weight: 400;
    text-align: left;
    padding: 17px 21px
}

.recmnded-colg-slist ul li a:hover {
    background: #ececec
}

.recmnded-colg-slist ul li a .locality {
    display: block;
    color: #b3b3b3;
    font-weight: 400;
    position: relative;
    margin-top: 10px;
    padding-left: 25px;
    font-size: 12px
}

.cls-tab {
    display: inline-block;
    font-size: 24px;
    height: 20px;
    line-height: 20px;
    position: relative;
    text-decoration: none;
    top: -4px;
    width: 20px;
    left: 15px;
    float: right;
    padding-left: 10px;
    color: #8e8e8e!important
}

.cls-clg {
    float: right;
    font-size: 20px;
    font-weight: 400;
    color: #999;
    font-family: Open Sans
}

.wrapp {
    background: rgba(0, 0, 0, .8) none repeat scroll 0 0;
    height: 100%;
    left: 0;
    padding: 10px 15px 35px;
    position: fixed;
    right: 0;
    top: 0;
    z-index: 99999;
    display: none;
    box-sizing: border-box
}

.wrapp .layerBlock {
    height: 100%;
    overflow: hidden;
    background: #fff;
    width: 100%
}

.wrapp .recmnded-colg-slist {
    height: 235px;
    overflow: auto
}

.wrapp .cc_inputsrchBx .cc_sugestd ul li span {
    padding: 0 10px;
    box-sizing: border-box;
    width: 100%;
    height: 34px;
    display: table-cell;
    vertical-align: middle
}

.wrapp ul.suggestion-box {
    max-height: 139px;
    overflow: auto
}

.compare-table tr td .short-list-box,
.compare-table tr td .side-col {
    font-size: 11px;
    text-align: center;
    display: block;
    vertical-align: top;
    color: #008489
}

.reviewLoadMore {
    width: 100%;
    display: block;
    text-align: center;
    padding: 10px 0
}

#successMsgForCompare {
    font-size: 12px;
    margin-top: 4px;
    float: left;
    color: #0ba5b5
}

.marginTop {
    margin-top: 46px!important
}

.h-l {
    display: block;
    height: 45px;
    overflow: hidden
}

.review-btn-col {
    background: #0ea4b4;
    width: 100%;
    display: table;
    color: #fff;
    position: fixed;
    bottom: 0
}

.review-btn-col a {
    padding: 13px 10px;
    color: #fff!important;
    display: table-cell;
    width: 50%;
    text-align: center;
    font-size: 12px;
    border-right: 1px solid #fff
}

.noBrder {
    border-right: none!important
}

input,
option,
select,
text-area {
    font-family: "Open Sans"
}

.ui-input-text input[validate=validateMobileInteger] {
    padding: 10px 4px 10px 6px!important
}

.selctnTool .selctnToolInner .slctnGird h2,
.selctnTool .selctnToolInner .slctnGird h3 {
    height: 18px!important;
    line-height: 18px!important;
    overflow-y: hidden;
    margin-bottom: 1px!important
}

tr#askStudents2,
tr#askStudents2 td {
    overflow-x: hidden
}

tr#askStudents2 td.border-right+.border-right,
tr#collegeReviewContent td.border-right+.border-right,
tr#row7_C td.border-right+.border-right {
    border-right: none
}

.full-width .addACollegeButton {
    height: 31px
}

h1.title-ellipsis {
    display: inline-block;
    text-align: left;
    text-overflow: ellipsis;
    white-space: nowrap;
    overflow: hidden;
    width: 100%;
    line-height: 23px
}

h1 a.change-loc {
    line-height: 20px;
    padding: 2px 4px 4px;
    font-size: 13px;
    display: inline-block;
    font-weight: 700;
    right: 17px
}

.qna-dtls {
    display: block;
    background: #fff;
    margin: 10px 0 0
}

.qna-block {
    display: block
}

.qna-head {
    display: block;
    padding: 5px 15px;
    border-top: 1px solid #c1c1c1;
    background: #f2f2f2;
    border-bottom: 1px solid #c1c1c1;
    margin: 0 -10px
}

.qna-head p {
    font-size: 12px;
    font-weight: 400;
    color: #000;
    display: inline-block
}

.qna-head .right-span {
    width: 100px;
    font-size: 11px;
    float: right;
    line-height: 24px;
    display: inline-block;
    color: #999;
    font-weight: 400
}

.time-view {
    font-size: 9px;
    color: rgba(0, 0, 0, .69);
    line-height: 18px;
    display: table-cell;
    vertical-align: top;
    width: 23%;
    text-align: right
}

.like-col {
    display: block;
    margin: 15px 0
}

.bottom-col {
    padding: 10px 0 0;
    border-top: 1px solid #e1e1e1
}

a.cmnt-btn {
    padding: 5px 15px;
    background: #e5e5e4;
    text-align: center;
    display: inline-block;
    text-decoration: none;
    color: #000;
    font-size: 11px;
    font-weight: 600;
    text-transform: uppercase
}

.l-more {
    border: 1px solid #008489;
    background: 0 0;
    font-size: 12px;
    font-weight: 700;
    color: #008489;
    margin-top: 7px
}

.qna-head .qa-dropdown {
    position: relative;
    float: right;
    width: 56px;
    font-size: 11px;
    color: #000333;
    z-index: 99;
    width: 65px
}

.qna-head .qa-dropdown>a {
    height: 25px;
    display: block;
    font-size: 11px;
    text-decoration: none;
    color: #000333!important
}

.qna-head .qa-dropdown div.arrow {
    width: 6px;
    height: 23px;
    float: right;
    position: relative
}

.qna-head .qa-dropdown .arrow .caret {
    display: inline-block;
    width: 0;
    height: 0;
    vertical-align: middle;
    border-left: 3px solid transparent;
    border-bottom: 3px solid transparent;
    border-right: 3px solid transparent;
    border-top: 4px solid;
    color: #000333;
    position: absolute;
    left: 4px;
    top: 10px
}

.qna-head .qa-dropdown .display-area {
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    display: block;
    margin-right: 0;
    padding: 3px 3px 0 5px;
    line-height: 18px
}

.qna-head .qa-dropdown .drop-layer {
    position: absolute;
    right: -11px;
    top: 6px;
    background: #fff;
    border: 1px solid #ccc;
    width: 75px;
    z-index: 99;
    display: block;
    -webkit-box-shadow: 1px 1px 4px 0 #e0e0e0;
    -moz-box-shadow: 1px 1px 4px 0 #e0e0e0;
    box-shadow: 1px 1px 4px 0 #e0e0e0;
    display: none
}

.qna-head .qa-dropdown .drop-layer ul {
    list-style: none
}

.qna-head .qa-dropdown .drop-layer ul li a {
    line-height: 18px;
    padding: 5px 6px;
    display: block;
    border-bottom: 1px solid #ccc;
    color: #000333!important;
    text-decoration: none
}

.qna-head .qa-dropdown .drop-layer ul li:last-child a {
    border-bottom: 0
}

#qna_div>.card-data {
    padding: 15px 15px;
    border-bottom: 3px solid #e2e2e2;
    margin: 0 -10px
}

#qna_div>.card-data>.type-of-que a.a {
    font-size: 11px;
    font-weight: 600;
    margin-right: 8px;
    color: #008489;
    display: inline-block;
    text-decoration: none;
    padding-bottom: 5px;
    border-bottom: 1px solid #008489
}

#qna_div>.card-data>.type-of-que .d-txt {
    font-size: 13px;
    color: #000;
    font-weight: 600;
    display: block;
    line-height: 18px;
    margin: 10px 0 0;
    text-decoration: none;
    word-break: break-all
}

.qna-cmp-dtls:last-child {
    border: 0
}

.qna-cmp-dtls {
    padding: 3px
}

#qna_div>.card-data>.type-of-que .a-span {
    font-weight: 400;
    display: table-cell;
    vertical-align: top;
    font-size: 12px;
    color: #000;
    opacity: .4;
    position: relative;
    margin-bottom: 5px;
    width: 75%
}

#qna_div>.card-data>.box-col {
    display: block;
    margin-top: 20px
}

#qna_div>.card-data>.box-col>div>.user-list-dtls {
    display: block
}

.card-data>.box-col>div>.user-list-dtls>.user-pic-col {
    width: 55px;
    height: 55px;
    background: #e1e2e2;
    margin: 0;
    display: inline-block;
    float: left;
    text-align: center;
    line-height: 55px;
    font-size: 24px;
    overflow: hidden;
    color: #777
}

.card-data>.box-col>div>.user-list-dtls>.user-inf-col {
    margin: 0;
    display: inline-block;
    padding-left: 15px;
    float: left;
    width: 73%
}

.card-data>.box-col>div>.user-list-dtls>.user-inf-col .name {
    font-size: 11px;
    font-weight: 600;
    line-height: 14px;
    text-transform: uppercase;
    display: block;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    color: #555
}

.card-data>.box-col>div>.user-list-dtls>.user-inf-col .e-level {
    color: #000;
    font-size: 11px;
    opacity: .4;
    font-weight: 400;
    line-height: 13px;
    border: none
}

.card-data>.box-col>div>.user-review {
    word-break: break-all;
    display: block;
    margin: 10px 0 5px;
    font-size: 14px;
    font-weight: 400;
    line-height: 21px;
    opacity: .8;
    text-decoration: none;
    color: #000!important
}

.box-view {
    color: #008489;
    outline: medium none;
    text-decoration: none;
    font-size: 13px!important;
    word-break: break-word
}

.wrtn-btn {
    display: inline-block;
    padding: 5px 8px;
    text-transform: uppercase;
    text-decoration: none!important;
    background: #008489;
    color: #fff;
    font-size: 11px;
    font-weight: 600
}

a.flw-txt {
    margin-left: 10px;
    font-size: 11px;
    font-weight: 600;
    display: inline-block;
    color: #000;
    text-decoration: none;
    text-transform: uppercase
}

a.u-flw-txt {
    margin-left: 10px;
    font-size: 11px;
    font-weight: 600;
    display: inline-block;
    color: #008489;
    text-decoration: none;
    text-transform: uppercase
}

.share-tag {
    margin-left: 10px;
    position: relative;
    top: 0
}

.more-tag {
    display: table-cell;
    vertical-align: middle;
    text-align: right;
    width: 5%
}

.clr {
    clear: both;
    display: block;
    height: 0;
    width: 100%
}

a.like-a {
    text-decoration: none;
    margin-right: 10px;
    font-size: 13px;
    color: #000;
    padding-left: 0;
    position: relative
}

a.like-d {
    text-decoration: none;
    font-size: 13px;
    color: #000
}

.view-ans {
    border: 1px solid #008489;
    text-align: center;
    display: block;
    font-size: 11px;
    color: #008489;
    font-weight: 600;
    margin: 20px 0 0;
    padding: 7px;
    text-decoration: none
}

.left-col {
    display: table-cell;
    width: 94%;
    margin: 0;
    vertical-align: top
}

.left-col .span-col {
    background: #e0e0e0;
    padding: 5px 6px;
    width: 100%;
    margin: 0
}

.left-col .span-col p {
    font-size: 11px;
    opacity: .8;
    font-weight: 400;
    display: block
}

.span-col a {
    color: #008489!important;
    font-size: 11px;
    font-weight: 600;
    text-decoration: none!important
}

.write-txt {
    border: 1px solid #a3a9ac;
    border-radius: 0;
    box-shadow: none;
    color: #666;
    font-size: 12px;
    font-weight: 400;
    margin: 5px 0 0;
    opacity: 1;
    outline: 0 none;
    padding: 10px;
    resize: none;
    width: 92%
}

.clock-ico {
    background: url(data:image/svg+xml;base64,PD94bWwgdmVyc2lvbj0iMS4wIiBlbmNvZGluZz0idXRmLTgiPz4NCjwhLS0gR2VuZXJhdG9yOiBBZG9iZSBJbGx1c3RyYXRvciAxNi4wLjAsIFNWRyBFeHBvcnQgUGx1Zy1JbiAuIFNWRyBWZXJzaW9uOiA2LjAwIEJ1aWxkIDApICAtLT4NCjwhRE9DVFlQRSBzdmcgUFVCTElDICItLy9XM0MvL0RURCBTVkcgMS4xLy9FTiIgImh0dHA6Ly93d3cudzMub3JnL0dyYXBoaWNzL1NWRy8xLjEvRFREL3N2ZzExLmR0ZCI+DQo8c3ZnIHZlcnNpb249IjEuMSIgaWQ9IkxheWVyXzEiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyIgeG1sbnM6eGxpbms9Imh0dHA6Ly93d3cudzMub3JnLzE5OTkveGxpbmsiIHg9IjBweCIgeT0iMHB4Ig0KCSB3aWR0aD0iMTBweCIgaGVpZ2h0PSIxMHB4IiB2aWV3Qm94PSIwIDAgMTAgMTAiIGVuYWJsZS1iYWNrZ3JvdW5kPSJuZXcgMCAwIDEwIDEwIiB4bWw6c3BhY2U9InByZXNlcnZlIj4NCjxjaXJjbGUgZmlsbD0ibm9uZSIgc3Ryb2tlPSIjMDAwMDAwIiBzdHJva2UtbWl0ZXJsaW1pdD0iMTAiIGN4PSI1IiBjeT0iNSIgcj0iNCIvPg0KPGc+DQoJPGc+DQoJCTxnPg0KCQkJPHJlY3QgeD0iNC43NDciIHk9IjIuOTUxIiB3aWR0aD0iMC41MDUiIGhlaWdodD0iMi43MzciLz4NCgkJPC9nPg0KCQk8Zz4NCgkJCTxwb2x5Z29uIHBvaW50cz0iNC43NDcsNS42NjkgNS4xMDQsNS4zMTIgNy4yNDMsNi41NDEgNi45NzgsNi45NzkgCQkJIi8+DQoJCTwvZz4NCgk8L2c+DQo8L2c+DQo8L3N2Zz4NCg==) no-repeat;
    width: 13px;
    position: relative;
    height: 15px;
    vertical-align: middle;
    top: 2px;
    opacity: .4;
    left: -1px;
    display: inline-block
}

.flw-ico {
    background: url(data:image/svg+xml;base64,PD94bWwgdmVyc2lvbj0iMS4wIiBlbmNvZGluZz0idXRmLTgiPz4NCjwhLS0gR2VuZXJhdG9yOiBBZG9iZSBJbGx1c3RyYXRvciAxNi4wLjAsIFNWRyBFeHBvcnQgUGx1Zy1JbiAuIFNWRyBWZXJzaW9uOiA2LjAwIEJ1aWxkIDApICAtLT4NCjwhRE9DVFlQRSBzdmcgUFVCTElDICItLy9XM0MvL0RURCBTVkcgMS4xLy9FTiIgImh0dHA6Ly93d3cudzMub3JnL0dyYXBoaWNzL1NWRy8xLjEvRFREL3N2ZzExLmR0ZCI+DQo8c3ZnIHZlcnNpb249IjEuMSIgaWQ9IkxheWVyXzEiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyIgeG1sbnM6eGxpbms9Imh0dHA6Ly93d3cudzMub3JnLzE5OTkveGxpbmsiIHg9IjBweCIgeT0iMHB4Ig0KCSB3aWR0aD0iMTBweCIgaGVpZ2h0PSIxMHB4IiB2aWV3Qm94PSIwIDAgMTAgMTAiIGVuYWJsZS1iYWNrZ3JvdW5kPSJuZXcgMCAwIDEwIDEwIiB4bWw6c3BhY2U9InByZXNlcnZlIj4NCjxnIG9wYWNpdHk9IjAuMiI+DQoJPGVsbGlwc2UgY3g9IjEuOTYyIiBjeT0iMS41NTIiIHJ4PSIxLjE2NSIgcnk9IjEuMTkzIi8+DQoJPGVsbGlwc2UgY3g9IjguMDM1IiBjeT0iMS41NTIiIHJ4PSIxLjE2NSIgcnk9IjEuMTkzIi8+DQoJPHBhdGggZD0iTTYuODg4LDUuNTMyYy0wLjAwNywwLjAwNi0wLjEsMC4wNjgtMC4xNzUsMC4xMTlDNi4zOTUsNS44Nyw1LjgwMiw2LjI3Niw0Ljk5OSw2LjI3NmMtMC44MDQsMC0xLjM5Ni0wLjQwNi0xLjcxNC0wLjYyNQ0KCQlDMy4yMSw1LjYwMSwzLjExNyw1LjUzNiwzLjA4Nyw1LjUyNkMxLjY4Niw1LjUzMiwxLjQ3NCw3LjM1MSwxLjQ3NCw4LjQzM2MwLDAuNzU4LDAuNDU3LDEuMjA5LDEuMjIzLDEuMjA5aDQuNjA2DQoJCWMwLjc1NCwwLDEuMjIzLTAuNDYzLDEuMjIzLTEuMjA5QzguNTI1LDcuMzUxLDguMzEzLDUuNTMyLDYuODg4LDUuNTMyeiIvPg0KCTxwYXRoIGQ9Ik0wLjEyNCw0LjgwNGMwLDAuNjUyLDAuNjQxLDAuNzAxLDAuODM4LDAuNzAxaDAuNjIxYzAuMzEzLTAuMzUyLDAuNzIxLTAuNTgsMS4xNzYtMC42NTgNCgkJQzIuNTYsNC40NjgsMi40NTMsNC4wNDIsMi40NTMsMy42MjJjMC0wLjAzNywwLjAwMS0wLjA3LDAuMDAxLTAuMTA0QzIuMjkyLDMuNTU0LDIuMTMsMy41NzMsMS45NjIsMy41NzMNCgkJYy0wLjU1NywwLTEuMDE4LTAuMjU0LTEuMjY2LTAuMzkxQzAuNjM2LDMuMTQ5LDAuNTkxLDMuMTIsMC41NjEsMy4xMUMwLjM3LDMuMTE2LDAuMjU3LDMuNDQ4LDAuMjAxLDMuNzI4DQoJCUMwLjEyMyw0LjEyLDAuMTIzLDQuNTgxLDAuMTI0LDQuODA0eiIvPg0KCTxwYXRoIGQ9Ik05LjAzNiw1LjUwNWMwLjE5NywwLDAuODM5LTAuMDQ5LDAuODM5LTAuNjcyQzkuODc2LDQuNTgxLDkuODc2LDQuMTIsOS43OTgsMy43MjhDOS43NDMsMy40NDgsOS42MywzLjExNiw5LjQwNSwzLjExNg0KCQlDOS40MDcsMy4xMiw5LjM2MiwzLjE0OSw5LjMwMiwzLjE4M0M5LjA1NCwzLjMxOSw4LjU5MywzLjU3Myw4LjAzNSwzLjU3M2MtMC4xNjMsMC0wLjMyOS0wLjAyLTAuNDkxLTAuMDU1DQoJCWMwLjAwMiwwLjAzMywwLjAwMiwwLjA2NiwwLjAwMiwwLjEwNGMwLDAuNDItMC4xMDYsMC44NDgtMC4zMDUsMS4yMjVjMC40NTMsMC4wNzgsMC44NiwwLjMwNywxLjE3NSwwLjY1OEg5LjAzNnoiLz4NCgk8cGF0aCBkPSJNNC45OTksMS43MzVjLTEuMDE0LDAtMS44MzgsMC44NDYtMS44MzgsMS44ODdjMCwxLjAzNywwLjgyNCwxLjg4MywxLjgzOCwxLjg4M3MxLjgzOC0wLjg0NiwxLjgzOC0xLjg4Mw0KCQlDNi44MzcsMi41ODEsNi4wMTMsMS43MzUsNC45OTksMS43MzV6Ii8+DQo8L2c+DQo8L3N2Zz4NCg==) no-repeat;
    width: 14px;
    height: 13px;
    vertical-align: middle;
    display: inline-block
}

.share-ico {
    background: url(data:image/svg+xml;base64,PD94bWwgdmVyc2lvbj0iMS4wIiBlbmNvZGluZz0idXRmLTgiPz4NCjwhLS0gR2VuZXJhdG9yOiBBZG9iZSBJbGx1c3RyYXRvciAxNi4wLjAsIFNWRyBFeHBvcnQgUGx1Zy1JbiAuIFNWRyBWZXJzaW9uOiA2LjAwIEJ1aWxkIDApICAtLT4NCjwhRE9DVFlQRSBzdmcgUFVCTElDICItLy9XM0MvL0RURCBTVkcgMS4xLy9FTiIgImh0dHA6Ly93d3cudzMub3JnL0dyYXBoaWNzL1NWRy8xLjEvRFREL3N2ZzExLmR0ZCI+DQo8c3ZnIHZlcnNpb249IjEuMSIgaWQ9IkxheWVyXzEiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyIgeG1sbnM6eGxpbms9Imh0dHA6Ly93d3cudzMub3JnLzE5OTkveGxpbmsiIHg9IjBweCIgeT0iMHB4Ig0KCSB3aWR0aD0iMTRweCIgaGVpZ2h0PSIxNnB4IiB2aWV3Qm94PSIwIDAgMTQgMTYiIGVuYWJsZS1iYWNrZ3JvdW5kPSJuZXcgMCAwIDE0IDE2IiB4bWw6c3BhY2U9InByZXNlcnZlIj4NCjxnPg0KCTxnPg0KCQk8cGF0aCBmaWxsLXJ1bGU9ImV2ZW5vZGQiIGNsaXAtcnVsZT0iZXZlbm9kZCIgZmlsbD0iIzAwQTVCNSIgZD0iTTQuNjM0LDYuNDQ3YzEuMzgtMC43OTUsMi43NDQtMS41ODYsNC4xMTItMi4zNjcNCgkJCWMwLjE0Ni0wLjA4NCwwLjEwNC0wLjE3NCwwLjA3Ny0wLjI5MWMtMC4yODEtMS4yMjEsMC40Ni0yLjQzNCwxLjY2My0yLjcyNWMxLjIxNi0wLjI5NSwyLjQzNSwwLjQ1MywyLjczMiwxLjY3OA0KCQkJYzAuMjMzLDAuOTU1LTAuMTksMS45NDUtMS4wNDksMi40NjFjLTAuODM5LDAuNTA0LTEuOTA2LDAuNDAyLTIuNjQ0LTAuMjQ4QzkuNDY0LDQuODk2LDkuNDAxLDQuODQsOS4zMyw0Ljc3NQ0KCQkJYy0wLjU4MSwwLjMzNi0xLjE1OSwwLjY2Ni0xLjczNywxYy0wLjc2LDAuNDM5LTEuNTE3LDAuODgxLTIuMjgsMS4zMTRDNS4xNjMsNy4xNzQsNS4xMDYsNy4yNDQsNS4xNTgsNy40Mw0KCQkJYzAuMTM2LDAuNDc5LDAuMDk2LDAuOTUzLTAuMDk1LDEuNDUxYzEuMzk1LDAuODA1LDIuNzksMS42MTEsNC4xOTksMi40MjRjMC4zMzItMC4zNTksMC43MTgtMC42MzEsMS4yMDEtMC43NTYNCgkJCWMxLjI0Ny0wLjMyMiwyLjU1NSwwLjUwNiwyLjc3NywxLjc2YzAuMjM3LDEuMzM4LTAuNzAzLDIuNTc2LTIuMDM4LDIuNjg0Yy0xLjI5OSwwLjEwNS0yLjQzMS0wLjkyOC0yLjQzOC0yLjIyNw0KCQkJYy0wLjAwMS0wLjExNy0wLjAxNS0wLjI0NCwwLjAyMy0wLjM0OGMwLjA5LTAuMjQ4LTAuMDA0LTAuMzYxLTAuMjIxLTAuNDg0Yy0xLjI3Ny0wLjcyMy0yLjU0Ni0xLjQ2MS0zLjgxNC0yLjE5Nw0KCQkJYy0wLjEzMy0wLjA3OC0wLjIyLTAuMS0wLjM1OSwwLjAxNGMtMC43MzgsMC42MDQtMS43NTUsMC42NzQtMi41NSwwLjIwMUMxLjAyLDkuNDYzLDAuNTkyLDguNTQ1LDAuNzUsNy42MDkNCgkJCWMwLjE1Ni0wLjkxLDAuODc0LTEuNjUyLDEuODAyLTEuODI2YzAuNzExLTAuMTMzLDEuMzU0LDAuMDQxLDEuOTEsMC41MTZDNC41MTUsNi4zNDQsNC41NjYsNi4zOTEsNC42MzQsNi40NDd6Ii8+DQoJPC9nPg0KPC9nPg0KPC9zdmc+DQo=) no-repeat;
    width: 18px;
    height: 16px;
    display: inline-block;
    vertical-align: middle
}

.more-ico {
    background: url(data:image/svg+xml;base64,PD94bWwgdmVyc2lvbj0iMS4wIiBlbmNvZGluZz0idXRmLTgiPz4NCjwhLS0gR2VuZXJhdG9yOiBBZG9iZSBJbGx1c3RyYXRvciAxNi4wLjAsIFNWRyBFeHBvcnQgUGx1Zy1JbiAuIFNWRyBWZXJzaW9uOiA2LjAwIEJ1aWxkIDApICAtLT4NCjwhRE9DVFlQRSBzdmcgUFVCTElDICItLy9XM0MvL0RURCBTVkcgMS4xLy9FTiIgImh0dHA6Ly93d3cudzMub3JnL0dyYXBoaWNzL1NWRy8xLjEvRFREL3N2ZzExLmR0ZCI+DQo8c3ZnIHZlcnNpb249IjEuMSIgaWQ9IkxheWVyXzEiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyIgeG1sbnM6eGxpbms9Imh0dHA6Ly93d3cudzMub3JnLzE5OTkveGxpbmsiIHg9IjBweCIgeT0iMHB4Ig0KCSB3aWR0aD0iNXB4IiBoZWlnaHQ9IjE2cHgiIHZpZXdCb3g9IjAgMCA1IDE2IiBlbmFibGUtYmFja2dyb3VuZD0ibmV3IDAgMCA1IDE2IiB4bWw6c3BhY2U9InByZXNlcnZlIj4NCjxnIG9wYWNpdHk9IjAuNyI+DQoJPHBhdGggZmlsbC1ydWxlPSJldmVub2RkIiBjbGlwLXJ1bGU9ImV2ZW5vZGQiIGZpbGw9IiMzRDNEM0QiIGQ9Ik00LjQyNywyLjQ2OGMtMC4wMDYsMS4wNjItMC44ODksMS45My0xLjk0NywxLjkyNA0KCQlDMS40MjgsNC4zODQsMC41NDIsMy40ODcsMC41NTEsMi40MzlDMC41NjIsMS4zODgsMS40NTksMC41MDMsMi41MSwwLjUxM0MzLjU2MiwwLjUyMiw0LjQzNSwxLjQxMyw0LjQyNywyLjQ2OHoiLz4NCgk8cGF0aCBmaWxsLXJ1bGU9ImV2ZW5vZGQiIGNsaXAtcnVsZT0iZXZlbm9kZCIgZmlsbD0iIzNEM0QzRCIgZD0iTTQuNDI3LDEzLjQ0YzAuMDExLDEuMDU2LTAuODU5LDEuOTQ1LTEuOTE1LDEuOTU5DQoJCWMtMS4wNDUsMC4wMTQtMS45NDgtMC44NzItMS45NjEtMS45MjJjLTAuMDExLTEuMDQ4LDAuODctMS45NDMsMS45MjQtMS45NTJDMy41MzYsMTEuNTE1LDQuNDE4LDEyLjM3OCw0LjQyNywxMy40NHoiLz4NCgk8cGF0aCBmaWxsLXJ1bGU9ImV2ZW5vZGQiIGNsaXAtcnVsZT0iZXZlbm9kZCIgZmlsbD0iIzNEM0QzRCIgZD0iTTQuNDI3LDcuOTczQzQuNDIxLDkuMDQ0LDMuNTU5LDkuODg0LDIuNDc4LDkuODc4DQoJCUMxLjQwNSw5Ljg3LDAuNTQxLDksMC41NTEsNy45NGMwLjAxNC0xLjA2NiwwLjg3OS0xLjkxLDEuOTYtMS45MDJDMy41ODcsNi4wNDQsNC40MzUsNi45MDEsNC40MjcsNy45NzN6Ii8+DQo8L2c+DQo8L3N2Zz4NCg==) no-repeat;
    width: 5px;
    height: 16px;
    display: inline-block;
    vertical-align: middle
}

.like-actv-ico {
    background: url(data:image/svg+xml;base64,PD94bWwgdmVyc2lvbj0iMS4wIiBlbmNvZGluZz0idXRmLTgiPz4NCjwhLS0gR2VuZXJhdG9yOiBBZG9iZSBJbGx1c3RyYXRvciAxNi4wLjAsIFNWRyBFeHBvcnQgUGx1Zy1JbiAuIFNWRyBWZXJzaW9uOiA2LjAwIEJ1aWxkIDApICAtLT4NCjwhRE9DVFlQRSBzdmcgUFVCTElDICItLy9XM0MvL0RURCBTVkcgMS4xLy9FTiIgImh0dHA6Ly93d3cudzMub3JnL0dyYXBoaWNzL1NWRy8xLjEvRFREL3N2ZzExLmR0ZCI+DQo8c3ZnIHZlcnNpb249IjEuMSIgaWQ9IkxheWVyXzEiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyIgeG1sbnM6eGxpbms9Imh0dHA6Ly93d3cudzMub3JnLzE5OTkveGxpbmsiIHg9IjBweCIgeT0iMHB4Ig0KCSB3aWR0aD0iMTRweCIgaGVpZ2h0PSIxNXB4IiB2aWV3Qm94PSIwIDAgMTQgMTUiIGVuYWJsZS1iYWNrZ3JvdW5kPSJuZXcgMCAwIDE0IDE1IiB4bWw6c3BhY2U9InByZXNlcnZlIj4NCjxnPg0KCTxyZWN0IHg9IjAuNzcxIiB5PSI1LjcxOSIgZmlsbD0iIzAwQTVCNSIgd2lkdGg9IjIuMjUyIiBoZWlnaHQ9IjguNzciLz4NCgk8cGF0aCBmaWxsPSIjMDBBNUI1IiBkPSJNMTMuMDk4LDUuODc1Yy0wLjI2OS0wLjE3OC0wLjgzLTAuMTU2LTEuMDgyLTAuMTU2SDguNjk0YzAuMTIyLTEuMTE1LDAuMjQyLTIuMjI5LDAuMzY0LTMuMzQNCgkJYzAuMDM5LTAuMzU5LDAuMTQtMC43NTQsMC4xMTktMS4xMTNDOS4xNTYsMC44NjksOC40NjEsMC42NjQsOC4xNiwwLjVMNC41NDMsNS43MTl2OC4yMTdjMCwwLDAuMTI2LDAuNTYzLDAuODAyLDAuNTUzDQoJCWMxLjI2NC0wLjAyMywyLjUyOCwwLDMuNzkyLDBjMC40MDksMCwxLjYxMSwwLjA4NiwxLjc2NC0wLjIxNWMwLjI3OS0wLjU1NSwxLjAzOS0zLjMwMSwxLjI1Ni0zLjk5Ng0KCQljMC4zODktMS4yNDYsMC43NDYtMi41LDEuMDI5LTMuNzczYzAuMDMzLTAuMTUyLDAuMDY5LTAuMzUyLDAuMDEzLTAuNTA2QzEzLjE3OSw1Ljk0NywxMy4xNDUsNS45MDYsMTMuMDk4LDUuODc1eiIvPg0KPC9nPg0KPC9zdmc+DQo=) no-repeat;
    width: 19px;
    position: relative;
    height: 15px;
    vertical-align: middle;
    display: inline-block;
    top: -2px
}

.like-ico {
    background: url(data:image/svg+xml;base64,PD94bWwgdmVyc2lvbj0iMS4wIiBlbmNvZGluZz0idXRmLTgiPz4NCjwhLS0gR2VuZXJhdG9yOiBBZG9iZSBJbGx1c3RyYXRvciAxNi4wLjAsIFNWRyBFeHBvcnQgUGx1Zy1JbiAuIFNWRyBWZXJzaW9uOiA2LjAwIEJ1aWxkIDApICAtLT4NCjwhRE9DVFlQRSBzdmcgUFVCTElDICItLy9XM0MvL0RURCBTVkcgMS4xLy9FTiIgImh0dHA6Ly93d3cudzMub3JnL0dyYXBoaWNzL1NWRy8xLjEvRFREL3N2ZzExLmR0ZCI+DQo8c3ZnIHZlcnNpb249IjEuMSIgaWQ9IkxheWVyXzEiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyIgeG1sbnM6eGxpbms9Imh0dHA6Ly93d3cudzMub3JnLzE5OTkveGxpbmsiIHg9IjBweCIgeT0iMHB4Ig0KCSB3aWR0aD0iMTRweCIgaGVpZ2h0PSIxNXB4IiB2aWV3Qm94PSIwIDAgMTQgMTUiIGVuYWJsZS1iYWNrZ3JvdW5kPSJuZXcgMCAwIDE0IDE1IiB4bWw6c3BhY2U9InByZXNlcnZlIj4NCjxnIG9wYWNpdHk9IjAuNiI+DQoJPHJlY3QgeD0iMC43NzEiIHk9IjUuNzE5IiBmaWxsPSIjNUE1OTVDIiB3aWR0aD0iMi4yNTIiIGhlaWdodD0iOC43NyIvPg0KCTxwYXRoIGZpbGw9IiM1QTU5NUMiIGQ9Ik0xMy4wOTgsNS44NzVjLTAuMjY5LTAuMTc4LTAuODMtMC4xNTYtMS4wODItMC4xNTZIOC42OTRjMC4xMjItMS4xMTUsMC4yNDItMi4yMjksMC4zNjQtMy4zNA0KCQljMC4wMzktMC4zNTksMC4xNC0wLjc1NCwwLjExOS0xLjExM0M5LjE1NiwwLjg2OSw4LjQ2MSwwLjY2NCw4LjE2LDAuNUw0LjU0Myw1LjcxOXY4LjIxN2MwLDAsMC4xMjYsMC41NjMsMC44MDIsMC41NTMNCgkJYzEuMjY0LTAuMDIzLDIuNTI4LDAsMy43OTIsMGMwLjQwOSwwLDEuNjExLDAuMDg2LDEuNzY0LTAuMjE1YzAuMjc5LTAuNTU1LDEuMDM5LTMuMzAxLDEuMjU2LTMuOTk2DQoJCWMwLjM4OS0xLjI0NiwwLjc0Ni0yLjUsMS4wMjktMy43NzNjMC4wMzMtMC4xNTIsMC4wNjktMC4zNTIsMC4wMTMtMC41MDZDMTMuMTc5LDUuOTQ3LDEzLjE0NSw1LjkwNiwxMy4wOTgsNS44NzV6Ii8+DQo8L2c+DQo8L3N2Zz4NCg==) no-repeat;
    width: 19px;
    height: 16px;
    vertical-align: middle;
    position: relative;
    display: inline-block;
    top: -2px
}

.dislike-ico {
    background: url(data:image/svg+xml;base64,PD94bWwgdmVyc2lvbj0iMS4wIiBlbmNvZGluZz0idXRmLTgiPz4NCjwhLS0gR2VuZXJhdG9yOiBBZG9iZSBJbGx1c3RyYXRvciAxNi4wLjAsIFNWRyBFeHBvcnQgUGx1Zy1JbiAuIFNWRyBWZXJzaW9uOiA2LjAwIEJ1aWxkIDApICAtLT4NCjwhRE9DVFlQRSBzdmcgUFVCTElDICItLy9XM0MvL0RURCBTVkcgMS4xLy9FTiIgImh0dHA6Ly93d3cudzMub3JnL0dyYXBoaWNzL1NWRy8xLjEvRFREL3N2ZzExLmR0ZCI+DQo8c3ZnIHZlcnNpb249IjEuMSIgaWQ9IkxheWVyXzEiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyIgeG1sbnM6eGxpbms9Imh0dHA6Ly93d3cudzMub3JnLzE5OTkveGxpbmsiIHg9IjBweCIgeT0iMHB4Ig0KCSB3aWR0aD0iMTRweCIgaGVpZ2h0PSIxNXB4IiB2aWV3Qm94PSIwIDAgMTQgMTUiIGVuYWJsZS1iYWNrZ3JvdW5kPSJuZXcgMCAwIDE0IDE1IiB4bWw6c3BhY2U9InByZXNlcnZlIj4NCjxnIG9wYWNpdHk9IjAuNiI+DQoJPHJlY3QgeD0iMTAuOTc2IiB5PSIwLjUxMSIgZmlsbD0iIzVBNTk1QyIgd2lkdGg9IjIuMjUyIiBoZWlnaHQ9IjguNzciLz4NCgk8cGF0aCBmaWxsPSIjNUE1OTVDIiBkPSJNMC45MDIsOS4xMjVjMC4yNjksMC4xNzgsMC44MywwLjE1NiwxLjA4MiwwLjE1NmgzLjMyMWMtMC4xMjIsMS4xMTUtMC4yNDIsMi4yMjktMC4zNjQsMy4zNA0KCQljLTAuMDM5LDAuMzU5LTAuMTQsMC43NTQtMC4xMTksMS4xMTNjMC4wMjEsMC4zOTYsMC43MTcsMC42MDIsMS4wMTgsMC43NjZsMy42MTctNS4yMTlWMS4wNjRjMCwwLTAuMTI2LTAuNTYzLTAuODAyLTAuNTUzDQoJCWMtMS4yNjQsMC4wMjMtMi41MjgsMC0zLjc5MiwwYy0wLjQwOSwwLTEuNjExLTAuMDg2LTEuNzY0LDAuMjE1QzIuODIsMS4yODEsMi4wNiw0LjAyNywxLjg0Myw0LjcyMg0KCQljLTAuMzg5LDEuMjQ2LTAuNzQ2LDIuNS0xLjAyOSwzLjc3M0MwLjc4MSw4LjY0OCwwLjc0NSw4Ljg0NywwLjgwMSw5LjAwMUMwLjgyMSw5LjA1MiwwLjg1NSw5LjA5MywwLjkwMiw5LjEyNXoiLz4NCjwvZz4NCjwvc3ZnPg0K) no-repeat;
    width: 19px;
    height: 16px;
    vertical-align: middle;
    position: relative;
    display: inline-block;
    top: 0
}

.dislike-act-ico {
    background: url(data:image/svg+xml;base64,PD94bWwgdmVyc2lvbj0iMS4wIiBlbmNvZGluZz0idXRmLTgiPz4NCjwhLS0gR2VuZXJhdG9yOiBBZG9iZSBJbGx1c3RyYXRvciAxNi4wLjAsIFNWRyBFeHBvcnQgUGx1Zy1JbiAuIFNWRyBWZXJzaW9uOiA2LjAwIEJ1aWxkIDApICAtLT4NCjwhRE9DVFlQRSBzdmcgUFVCTElDICItLy9XM0MvL0RURCBTVkcgMS4xLy9FTiIgImh0dHA6Ly93d3cudzMub3JnL0dyYXBoaWNzL1NWRy8xLjEvRFREL3N2ZzExLmR0ZCI+DQo8c3ZnIHZlcnNpb249IjEuMSIgaWQ9IkxheWVyXzEiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyIgeG1sbnM6eGxpbms9Imh0dHA6Ly93d3cudzMub3JnLzE5OTkveGxpbmsiIHg9IjBweCIgeT0iMHB4Ig0KCSB3aWR0aD0iMTRweCIgaGVpZ2h0PSIxNXB4IiB2aWV3Qm94PSIwIDAgMTQgMTUiIGVuYWJsZS1iYWNrZ3JvdW5kPSJuZXcgMCAwIDE0IDE1IiB4bWw6c3BhY2U9InByZXNlcnZlIj4NCjxnPg0KCTxyZWN0IHg9IjEwLjk3NiIgeT0iMC41MTEiIGZpbGw9IiMwMEE1QjUiIHdpZHRoPSIyLjI1MiIgaGVpZ2h0PSI4Ljc3Ii8+DQoJPHBhdGggZmlsbD0iIzAwQTVCNSIgZD0iTTAuOTAyLDkuMTI1YzAuMjY5LDAuMTc4LDAuODMsMC4xNTYsMS4wODIsMC4xNTZoMy4zMjFjLTAuMTIyLDEuMTE1LTAuMjQyLDIuMjI5LTAuMzY0LDMuMzQNCgkJYy0wLjAzOSwwLjM1OS0wLjE0LDAuNzU0LTAuMTE5LDEuMTEzYzAuMDIxLDAuMzk2LDAuNzE3LDAuNjAyLDEuMDE4LDAuNzY2bDMuNjE3LTUuMjE5VjEuMDY0YzAsMC0wLjEyNi0wLjU2My0wLjgwMi0wLjU1Mw0KCQljLTEuMjY0LDAuMDIzLTIuNTI4LDAtMy43OTIsMGMtMC40MDksMC0xLjYxMS0wLjA4Ni0xLjc2NCwwLjIxNUMyLjgyLDEuMjgxLDIuMDYsNC4wMjcsMS44NDMsNC43MjINCgkJYy0wLjM4OSwxLjI0Ni0wLjc0NiwyLjUtMS4wMjksMy43NzNDMC43ODEsOC42NDgsMC43NDUsOC44NDcsMC44MDEsOS4wMDFDMC44MjEsOS4wNTIsMC44NTUsOS4wOTMsMC45MDIsOS4xMjV6Ii8+DQo8L2c+DQo8L3N2Zz4NCg==) no-repeat;
    width: 19px;
    height: 16px;
    vertical-align: middle;
    position: relative;
    display: inline-block;
    top: 0
}

.disable-like {
    background: url(data:image/svg+xml;base64,PD94bWwgdmVyc2lvbj0iMS4wIiBlbmNvZGluZz0idXRmLTgiPz4NCjwhLS0gR2VuZXJhdG9yOiBBZG9iZSBJbGx1c3RyYXRvciAxNi4wLjAsIFNWRyBFeHBvcnQgUGx1Zy1JbiAuIFNWRyBWZXJzaW9uOiA2LjAwIEJ1aWxkIDApICAtLT4NCjwhRE9DVFlQRSBzdmcgUFVCTElDICItLy9XM0MvL0RURCBTVkcgMS4xLy9FTiIgImh0dHA6Ly93d3cudzMub3JnL0dyYXBoaWNzL1NWRy8xLjEvRFREL3N2ZzExLmR0ZCI+DQo8c3ZnIHZlcnNpb249IjEuMSIgaWQ9IkxheWVyXzEiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyIgeG1sbnM6eGxpbms9Imh0dHA6Ly93d3cudzMub3JnLzE5OTkveGxpbmsiIHg9IjBweCIgeT0iMHB4Ig0KCSB3aWR0aD0iMTRweCIgaGVpZ2h0PSIxNXB4IiB2aWV3Qm94PSIwIDAgMTQgMTUiIGVuYWJsZS1iYWNrZ3JvdW5kPSJuZXcgMCAwIDE0IDE1IiB4bWw6c3BhY2U9InByZXNlcnZlIj4NCjxnIG9wYWNpdHk9IjAuMyI+DQoJPHJlY3QgeD0iMC43NzIiIHk9IjUuNzE5IiBmaWxsPSIjNUE1OTVDIiB3aWR0aD0iMi4yNTIiIGhlaWdodD0iOC43NyIvPg0KCTxwYXRoIGZpbGw9IiM1QTU5NUMiIGQ9Ik0xMy4wOTgsNS44NzVjLTAuMjY5LTAuMTc4LTAuODMtMC4xNTYtMS4wODItMC4xNTZIOC42OTVjMC4xMjItMS4xMTUsMC4yNDItMi4yMjksMC4zNjQtMy4zNA0KCQljMC4wMzktMC4zNTksMC4xNC0wLjc1NCwwLjExOS0xLjExM0M5LjE1NywwLjg2OSw4LjQ2MSwwLjY2NCw4LjE2MSwwLjVMNC41NDMsNS43MTl2OC4yMTdjMCwwLDAuMTI2LDAuNTYzLDAuODAyLDAuNTUzDQoJCWMxLjI2NC0wLjAyMywyLjUyOCwwLDMuNzkyLDBjMC40MDksMCwxLjYxMSwwLjA4NiwxLjc2NC0wLjIxNWMwLjI3OS0wLjU1NSwxLjAzOS0zLjMwMSwxLjI1Ni0zLjk5Ng0KCQljMC4zODktMS4yNDYsMC43NDYtMi41LDEuMDI5LTMuNzczYzAuMDMzLTAuMTUyLDAuMDY5LTAuMzUyLDAuMDEzLTAuNTA2QzEzLjE3OSw1Ljk0NywxMy4xNDUsNS45MDYsMTMuMDk4LDUuODc1eiIvPg0KPC9nPg0KPC9zdmc+DQo=) no-repeat;
    width: 19px;
    height: 16px;
    vertical-align: middle;
    position: relative;
    display: inline-block;
    top: 0
}

.disable-unlike {
    background: rgba(0, 0, 0, 0) url(data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHdpZHRoPSIxNCIgaGVpZ2h0PSIxNSI+PGcgZmlsbD0iIzVBNTk1QyIgb3BhY2l0eT0iLjMiPjxwYXRoIGQ9Ik0xMC45OC41aDIuMjVWOS4zaC0yLjI1ek0uOSA5LjEzYy4yNy4xNy44My4xNSAxLjA4LjE1SDUuM2MtLjEyIDEuMTItLjI0IDIuMjMtLjM2IDMuMzQtLjA0LjM2LS4xNC43Ni0uMTIgMS4xLjAyLjQuNzIuNjIgMS4wMi43OGwzLjYyLTUuMjJWMS4wNlM5LjMzLjUgOC42Ni41QzcuNC41NCA2LjEzLjUgNC44Ni41Yy0uNCAwLTEuNi0uMDctMS43Ni4yMy0uMjguNTUtMS4wNCAzLjMtMS4yNiA0QzEuNDQgNS45NiAxLjEgNy4yMi44IDguNWMtLjAyLjE1LS4wNi4zNSAwIC41LjAyLjA1LjA1LjEuMS4xM3oiLz48L2c+PC9zdmc+) no-repeat scroll 0 0;
    display: inline-block;
    height: 16px;
    position: relative;
    top: 0;
    vertical-align: middle;
    width: 19px
}

.u-btns {
    width: 100%;
    display: inline-block;
    height: 32px;
    line-height: 32px;
    text-transform: uppercase;
    text-decoration: none;
    text-align: center
}

.p-btn {
    background: #008489;
    color: #fff;
    font-size: 12px;
    font-weight: 700;
    margin: 20px 0 0
}

.home-btn {
    background: #e7e7e7;
    color: #606060;
    font-size: 12px;
    font-weight: 700;
    margin: 20px 0 0
}

.small-loader {
    border-radius: 50%;
    box-shadow: 0 0 8px 2px #b5b5b5;
    height: 30px;
    width: 40px
}

.fees-disclaimer-text {
    color: #afafaf;
    font-size: 12px
}

.content-wrap2>div.head-group {
    background: 0 0
}

.content-wrap2>div.head-group>h1 {
    font-size: 14px
}

.content-wrap2>div.head-group>h1>.left-align {
    text-align: center!important;
    margin-left: 0!important
}

.review-step-block {
    border-bottom: 0
}

.new-wrap {
    margin-bottom: .4em
}

.college-review-info {
    margin: 70px 0 0
}

.review-steps .r-step-1 {
    left: 0
}

.r-step-1.active>p {
    font-weight: 600
}

.ol-list {
    margin: 30px 0 0;
    list-style: none;
    padding-top: 20px;
    margin-top: 20px;
    border-top: 1px solid #e6e6e6
}

.ol-list ul.suggestion-box {
    top: 61px
}

ol.form-item li label.li-label {
    color: #000;
    font-weight: 600;
    margin-bottom: 5px
}

.li-label span.star-r {
    color: red;
    padding-left: 3px
}

.li-label span {
    font-weight: 400;
    font-size: 12px;
    color: #8c8c8c
}

ol.form-item li {
    margin-bottom: 20px
}

.rateing-box ul li label {
    font-weight: 600;
    margin-bottom: 10px;
    color: #000
}

.recommend-row label:nth-child(2n+1) {
    margin-left: 20px
}

.recommend-row {
    margin-bottom: 0
}

.btn-b,
.college-review-info .yellow {
    padding: 13px 10px!important;
    background: #f37921!important
}

.clearfix>span.flLt {
    margin-top: 5px;
    line-height: 18px;
    display: block;
    width: 100%
}

.flLt.rating-sprtr {
    width: auto!important
}

.para-list>p {
    margin-bottom: 5px;
    line-height: 16px
}

.para-list>p:last-child {
    margin-bottom: 0
}

.mobile-p {
    font-size: 11px;
    color: #8c8c8c;
    margin-bottom: 5px
}

.form-titl {
    margin-top: 20px
}

.n0-b .ui-input-text,
.ui-select {
    border-right: none!important
}

.btn-b,
.college-review-info .yellow {
    height: auto;
    line-height: inherit
}

.whtBx {
    background-color: #fff
}

.colgRvwSlidrBx .rv_midd {
    border-top: 0;
    margin-top: 0;
    padding-top: 0
}

.revwListBx {
    padding: 10px;
    margin-bottom: 10px;
    box-shadow: 3px 3px 5px #e0e0e0
}

.revwListBx .rv_date {
    color: #c2c0c0;
    font-size: 12px;
    display: inline-block;
    line-height: 22px;
    float: right
}

.revwListBx .rv_ratng {
    color: #989494;
    font-size: 12px;
    display: inline-block;
    vertical-align: middle
}

.revwListBx .rv_ratng span {
    display: inline-block;
    background-color: #a5a4a4;
    color: #fff!important;
    font-size: 13px;
    border-radius: 3px;
    -moz-border-radius: 3px;
    -webkit-border-radius: 3px;
    padding: 3px;
    margin-left: 2px;
    width: 32px;
    text-align: center
}

.revwListBx .rv_ratng span b {
    font-size: 10px;
    font-weight: 400;
    color: #fff;
    position: relative;
    top: 2px;
    clear: both
}

.revwListBx .rv_title {
    color: #006fa2;
    font-size: 14px;
    font-weight: 700
}

.revwListBx .rv_add {
    color: #999;
    font-size: 12px;
    display: block;
    float: none;
    padding-right: 30px;
    line-height: 20px
}

.revwListBx .rv_course {
    font-size: 12px;
    color: #006fa2;
    display: block;
    line-height: 20px
}

.revwListBx .rv_nav {
    height: 16px;
    display: inline-block;
    margin-top: 4px
}

.revwListBx .rv_nav li {
    list-style-type: none;
    float: left;
    display: inline-block
}

.revwListBx .rv_nav li:first-child {
    border-right: 0 solid #989494;
    padding-right: 8px;
    margin-right: 0
}

.revwListBx .rv_nav li:first-child i {
    height: 10px;
    background-color: #aeaeae;
    display: inline-block;
    width: 1px;
    margin-left: 10px
}

.revwListBx .rv_nav li span {
    color: #989494;
    font-size: 12px
}

.revwListBx .rv_desc {
    color: #5e5e5e;
    font-size: 12px;
    padding: 8px 0 10px 0;
    line-height: 20px;
    border-top: 1px solid #f0f0f0
}

.revwListBx .rv_desc a {
    text-decoration: none;
    color: #006fa2;
    font-size: 12px;
    cursor: pointer
}

.revwListBx2 {
    border: 1px solid #d7d7d7;
    box-shadow: 1px 1px 1px #ececec
}

.revwListBx2 .rv_nav {
    border-bottom: 1px solid #f0f0f0;
    padding-bottom: 10px;
    margin-bottom: 0;
    margin-top: 4px;
    display: block
}

.revwListBx .review-detail {
    border: none;
    margin: auto
}

.rv_midd {
    border-top: 1px solid #f0f0f0;
    margin: 4px 0;
    padding: 4px 0
}

.rv_midd .rv_midd1 {
    font-size: 14px;
    color: #000333;
    display: inline-block;
    margin-right: 10px
}

.rv_midd .rv_midd2 {
    font-size: 12px;
    color: #989494;
    display: inline-block
}

.rv_hlpful {
    display: inline-block;
    width: 100%
}

.rv_hlpful p {
    font-size: 12px;
    color: #c2c0c0;
    display: inline-block;
    float: left;
    line-height: 22px
}

.rv_hlpful p a {
    cursor: pointer;
    font-weight: 700;
    font-size: 12px;
    color: #006fa2;
    text-decoration: none;
    margin-left: 8px
}

.clg-review-Rmore .clg-rvew-top1 {
    padding-left: 40px
}

.clg-review-Rmore .clg-rvew-top1 h2 {
    font-size: 12px;
    margin-bottom: 6px
}

.clg-review-Rmore .clg-rvew-top1 p {
    font-size: 12px;
    padding-bottom: 5px
}

.clg-review-Rmore .head-icon-b {
    top: 31px
}

.clg-review-Rmore .revwListBx2 {
    box-shadow: none;
    border: 0;
    margin: 0 5px
}

.clg-review-Rmore .rv_midd {
    border-top: 0;
    margin-top: 0
}

.clg-review-Rmore .rv_nav {
    border-bottom: 0
}

.rv_hlpfull {
    margin: 10px 0
}

.dull-round-col {
    text-align: right;
    width: 25px;
    height: 25px;
    padding: 2px;
    -moz-border-radius: 50%;
    -webkit-border-radius: 50%;
    border-radius: 50%;
    border: 1px solid #f1f1f1;
    display: inline-block;
    font-size: 14px;
    font-weight: 600;
    margin-left: 16px;
    line-height: 25px;
    color: #999!important;
    text-decoration: none!important;
    text-align: center;
    cursor: default
}

.compare-items ul li>span>a.dull-txt {
    text-decoration: none;
    color: #babbbd;
    font-weight: 400;
    font-size: 12px;
    line-height: 16px;
    cursor: default
}

.facilities-list {
    margin: 0;
    padding: 0;
    list-style: none
}

.facilities-list li {
    display: block
}

tr.facility-row>td {
    text-decoration: none;
    color: #6b6b6d;
    font-size: 12px;
    font-weight: 400;
    cursor: default;
    line-height: 20px;
    margin: 0!important;
    padding: 0!important
}

tr.facility-row>td {
    padding: 1px 15px!important;
    vertical-align: middle
}

tr.facility-row>td.crs-txt {
    text-decoration: line-through;
    color: #babbbd!important
}

.compare-table>tr.facility-row:first-child>td {
    background: red
}

#noHeader {
    background: #fff!important
}

.search-block {
    background-color: #fff;
    padding: 15px 10px;
    border: 1px solid #e6e5e5;
    width: 100%;
    box-sizing: border-box;
    -moz-box-sizing: border-box
}

.txt-cntr {
    text-align: center
}

.nw-btn {
    background: #008489;
    padding: 6px 19px;
    color: #fff;
    font-size: 16px;
    font-weight: 600;
    display: inline-block;
    margin: 12px 0 16px;
    border-radius: 2px
}

.nw-btn:hover {
    color: #fff
}

.inf-li>li {
    display: inline-block;
    padding: 0 8px 0 8px;
    font-size: 16px;
    color: #000;
    position: relative;
    line-height: 18px;
    width: 18%
}

.inf-li>li:before {
    content: '';
    position: absolute;
    width: 5px;
    height: 5px;
    border-left: 1px solid #ccc;
    left: 0;
    top: 0;
    height: 100%
}

.inf-li>li>strong {
    display: block;
    font-weight: 600;
    font-size: 16px
}

.inf-li>li:first-child:before {
    width: 0;
    height: 0;
    border: transparent
}

.background {
    position: relative;
    z-index: 1;
    text-align: center;
    margin: 0 0 15px
}

.background:before {
    border-top: 1px solid #dfdfdf;
    content: "";
    margin: 0 auto;
    position: absolute;
    top: 50%;
    left: 0;
    right: 0;
    bottom: 0;
    width: 85%;
    z-index: -1
}

.background>span {
    background: #fff;
    padding: 0 8px;
    color: #666
}

.inf-txts {
    font-size: 14px;
    color: #000;
    font-weight: 400
}

.inf-txts>strong {
    display: inline-block;
    font-weight: 600;
    text-decoration: underline
}

.content-wrap2 h3.signup-h3 {
    float: none;
    font-weight: 600;
    font-size: 18px;
    color: #000;
    margin-bottom: 5px
}

.reviewTitle {
    overflow-x: scroll!important
}

.z-ind {
    z-index: 0
}

.article-readmore {
    width: 100%;
    text-align: center;
    padding: 60px 0 30px;
    background: linear-gradient(to bottom, rgba(255, 255, 255, 0) 0, rgba(255, 255, 255, .79) 30%, rgba(255, 255, 255, 1) 98%, rgba(255, 255, 255, 1) 100%);
    position: absolute;
    bottom: 0
}

a.link-blue-medium.articleViewMore {
    padding: 11px 20px;
    display: inline-block;
    border: 1px solid #008489;
    line-height: 16px;
    font-weight: 600;
    font-size: 16px;
    background: #fff
}

.articleDetails {
    height: 550px;
    overflow: hidden
}

.article-readmore {
    display: none
}

table {
    display: block;
    overflow: auto;
    width: 100%!important
}

.cmpr-head {
    margin-bottom: 5px;
    font-size: 20px;
    line-height: 26px;
    font-weight: 600;
    color: #666
}

.h-solid {
    padding: 0 0 10px 0;
    color: #666;
    text-transform: uppercase;
    font-size: 18px;
    font-weight: 300
}

.contUs-wrapper {
    background: #f1f1f1
}

p.f22__clr3 {
    font-size: 22px;
    color: #000;
    font-weight: 400
}

p.f22__clr3 strong {
    margin: 0 5px
}

.input__block {
    display: block;
    width: 95%;
    margin: 0 auto
}

.input__block textarea {
    width: 100%;
    font-family: 'Open Sans', sans-serif;
    border: 1px solid #ccc;
    padding: 10px;
    color: #000;
    font-size: 12px;
    font-weight: 400;
    box-shadow: none;
    margin-bottom: 10px;
    resize: none;
    border-radius: 2px;
    box-sizing: border-box
}

a.btn__prime:hover {
    background: #ee9521
}

a.btn__prime {
    color: #fff;
    border: 1px solid #f37921;
    background: #f37921;
    padding: 0;
    text-align: center;
    vertical-align: middle;
    font-size: 14px;
    font-weight: 600;
    cursor: pointer;
    display: inline-block;
    height: 30px;
    line-height: 28px;
    padding: 0 20px;
    border-radius: 2px
}

p.call__us {
    font-size: 14px;
    color: #666;
    margin: 25px 0 0 0
}

span.num__txt {
    display: inline-block;
    font-size: 14px;
    color: #000;
    margin: 0 1px
}

span.timings {
    font-size: 12px;
    color: #666;
    display: inline-block
}

.input__block textarea::-webkit-input-placeholder {
    color: #999
}

.img__sprite {
    background: url(//images.shiksha.ws/public/mobile5/images/contactus_new.svg) no-repeat;
    display: inline-block
}

.img__story {
    margin: 25px auto 1px;
    min-height: 50px;
    width: 85%
}

.img__story:after,
.img__story:before {
    content: '';
    display: table
}

.img__story:after {
    clear: both
}

.img__story div {
    font-size: 16px;
    position: relative;
    float: left;
    font-weight: 600;
    color: #666
}

.img__story div.answer {
    width: 40%
}

.img__story div.reliable {
    width: 27%
}

.img__story div.experts {
    width: 30%
}

.img__story div.answer p {
    padding-left: 63px
}

.img__story div.experts p,
.img__story div.reliable p {
    padding-left: 30px
}

.img__story div:before {
    content: '';
    position: absolute;
    top: 0;
    background: url(//images.shiksha.ws/public/mobile5/images/contactus_new.svg) no-repeat
}

.img__story div.answer:before {
    background-position: 1px -2px;
    width: 60px;
    height: 59px;
    top: -14px;
    left: 44px
}

.img__story div.reliable:before {
    background-position: -64px -4px;
    height: 50px;
    width: 50px;
    top: -13px;
    left: 0
}

.img__story div.experts:before {
    background-position: -120px -2px;
    height: 53px;
    width: 48px;
    left: 18px;
    top: -17px
}

.input__block a#questionText_contact {
    display: block;
    outline: 0
}

@media (min-width:320px) and (max-width:767px) {
    .col-lg-12.tac.new__col {
        padding: 0
    }
    .col-lg-12.tac.new__col .stu-colDiv {
        padding: 10px;
        margin: 0
    }
    .f22__clr3 {
        font-size: 18px
    }
    .stu-colDiv p.f22__clr3 {
        font-size: 18px
    }
    .img__story {
        display: table;
        width: 100%;
        margin: 15px 0
    }
    .img__story div.answer,
    .img__story div.experts,
    .img__story div.reliable {
        display: table-cell;
        width: 33%;
        float: none;
        font-size: 12px;
        vertical-align: top
    }
    .img__story div.answer p,
    .img__story div.experts p,
    .img__story div.reliable p {
        padding: 0 6px
    }
    .img__story div:before {
        background: url(//images.shiksha.ws/public/mobile5/images/contactus_new.svg) no-repeat
    }
    .img__story div.answer:before,
    .img__story div.experts:before,
    .img__story div.reliable:before {
        left: 0;
        display: inline-block;
        right: 0;
        margin: 0 auto;
        position: relative;
        top: 0;
        width: 60px;
        height: 60px
    }
    .img__story div.reliable:before {
        background-position: -66px 2px
    }
    .img__story div.reliable:after,
    .img__story div:first-child:after {
        content: '';
        border-right: 1px solid #e6e5e5;
        right: 0;
        top: 17px;
        height: 65%;
        width: 2px;
        position: absolute
    }
    .img__story div:first-child:after {
        right: -5px
    }
    .input__block textarea {
        font-size: 14px;
        border-radius: 2px
    }
    .call__us,
    span.num__txt {
        font-size: 12px
    }
}

.stuhelp-wrapper {
    background: #efefee;
    padding: 10px 10px;
    font-family: 'open sans'
}

.stuhelp-wrapper a {
    text-decoration: none
}

.stuhelp-wrapper .head-L1 {
    margin-bottom: 10px
}

.stuhelp-wrapper .scard {
    padding: 20px;
    margin-bottom: 0
}

.stuhelp-wrapper .scard:hover {
    box-shadow: none
}

.stuhelp-wrapper .lcard {
    margin-top: 0
}

.stuhelp-wrapper .scard {
    height: auto;
    margin: 10px 0
}

.lcard,
.scard,
.stu-colDiv {
    margin: 20px 0
}

.lcard,
.scard,
.stu-colDiv {
    background: #fff;
    padding: 20px;
    border: 1px solid;
    border-color: #e5e6e9 #dfe0e4 #e6e5e5 #dfe0e4
}

.new-row {
    margin-left: -10px;
    margin-right: -10px
}

.new-row:after,
.new-row:before {
    content: " ";
    display: table
}

.new-row:after {
    clear: both
}

.col-lg-1,
.col-lg-10,
.col-lg-11,
.col-lg-12,
.col-lg-2,
.col-lg-3,
.col-lg-4,
.col-lg-5,
.col-lg-6,
.col-lg-7,
.col-lg-8,
.col-lg-9,
.col-md-1,
.col-md-10,
.col-md-11,
.col-md-12,
.col-md-2,
.col-md-3,
.col-md-4,
.col-md-5,
.col-md-6,
.col-md-7,
.col-md-8,
.col-md-9,
.col-sm-1,
.col-sm-10,
.col-sm-11,
.col-sm-12,
.col-sm-2,
.col-sm-3,
.col-sm-4,
.col-sm-5,
.col-sm-6,
.col-sm-7,
.col-sm-8,
.col-sm-9,
.col-xs-1,
.col-xs-10,
.col-xs-11,
.col-xs-12,
.col-xs-2,
.col-xs-3,
.col-xs-4,
.col-xs-5,
.col-xs-6,
.col-xs-7,
.col-xs-8,
.col-xs-9 {
    position: relative;
    min-height: 1px;
    padding-left: 10px;
    padding-right: 10px
}

.head-L1,
.para-L2 {
    font-weight: 400
}

.head-L1 {
    font-size: 20px
}

.hid {
    display: none
}

.dfp-add>div {
    margin: 12px auto
}

.dfp-add>div iframe {
    display: block;
    margin: 0 auto
}

.ht-dfp [id^=google_ads_iframe] {
    width: 100%!important
}

.dfp-wraper.ht-dfp~.dfp-wraper.ht-dfp {
    margin-top: -18px
}

.dfp-wraper:after,
.dfp-wraper:before {
    content: '';
    display: table
}

.dfp-wraper:after {
    clear: both
}

.flLt.predictor-title-box h2 {
    margin: 0;
    font-size: 16px
}

.button.blue.small {
    padding: 0 15px
}

.breadcrumb2 {
    padding: 12px
}

.breadcrumb2>span {
    font-size: 13px;
    line-height: 21px
}

.breadcrumb2 a {
    color: #008489
}

/*mcommon*/

/*override css*/
.graylayer{z-index: 9999;}
.text-show.fixed{z-index: 9999;}

#shikshaHelpTextLayer .lcard,#shikshaHelpTextLayer .scard{border:none;padding-bottom:0}
#shikshaHelpTextLayer .hlp-popup{max-height:75%;min-height:40%;bottom:initial;overflow:auto}
.nopadng{padding:0!important;display:block;position:fixed;left:20px;right:20px;top:55px;bottom:40px}
.hlp-popup{background:#fff;padding:15px 10px;margin:0 auto;border:1px solid #babbbd;box-sizing:border-box}
.lcard{background:#fff;padding:20px;margin:20px 0}
#shikshaHelpTextLayer .head-L3{color:#666;font-size:14px;font-weight:600;margin:0 0 10px}
#shikshaHelpTextLayer .para-L3,.hlp-popup .hlp-info .para-L3{margin-bottom:20px;color:#999;line-height:19px;word-break:break-word;padding-bottom:10px;font-size:12px}
#shikshaHelpTextLayer .hlp-rmv{font-size:26px}
.hlp-rmv{float:right;position:fixed;top:10px;right:20px;font-size:35px;line-height:35px;color:#fff;text-decoration:none}
.reg-form.invalid.filled{width: 100%;height: 51px;margin-right: 20px;}
.isdC.filled{height: 51px;}