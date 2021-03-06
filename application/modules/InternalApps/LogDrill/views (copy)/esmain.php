<!--
Copyright 2013 Roy Russo

Licensed under the Apache License, Version 2.0 (the "License");
you may not use this file except in compliance with the License.
You may obtain a copy of the License at

http://www.apache.org/licenses/LICENSE-2.0

Unless required by applicable law or agreed to in writing, software
distributed under the License is distributed on an "AS IS" BASIS,
WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
See the License for the specific language governing permissions and
limitations under the License.

Latest Builds: https://github.com/royrusso/elasticsearch-HQ
-->
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/html">
<head>
    <title>Elastic HQ</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

<link href='https://fonts.googleapis.com/css?family=Open+Sans' rel='stylesheet' type='text/css'>    
<style type="text/css">
  ul.appTabs { margin:0; padding: 0;}
ul.appTabs li {float:left; list-style:none; margin:0; padding:0;}
ul.appTabs li a{display:block; padding:7px 20px 5px 20px; font-size:12px; text-transform: uppercase; color:#7e929a; border:0px solid red; text-decoration: none; font-family: "Open Sans"}
ul.appTabs li a:hover{background: #1e4d61; text-decoration: none;}
ul.appTabs li a.appActive{background: #04599e; color:#ccc;}
  </style>
    <link href="/public/elastichq/css/all.min.css" rel="stylesheet" media="screen">
    
</head>
<body>

<div style="background:#253840; height:31px; display:block; border-bottom:1px solid #2c4b58">
  <div style="margin:0 0; width:1200px; border:0px solid red;">
      <ul class="appTabs">
          <li><a href="http://www.shiksha.com/AppMonitor/Dashboard">AppMonitor</a></li>
          <!--li><a href="http://localhost:3000">Server Monitoring</a></li-->
          <li><a href="http://www.shiksha.com/FailureMatrix/FailureMatrix">Failure Matrix</a></li>
          <li><a href="http://www.shiksha.com/shikshaSchema/ShikshaSchema/index">Documentation</a></li>
          <li><a href="/ServiceMonitor/ElasticSearch/index?url=http://10.10.16.72:9200" class="appActive">ElasticSearch</a></li>
        <div style="clear:both;"></div>
      </ul>
    <div style="clear:both;"></div>
  </div>
</div>
<div style="clear:both;"></div>

<div class="navbar" style="margin-bottom: 0;">
    <div class="navbar-inner" style="padding-left:15px;">
        <div class="container-fluid" style="padding-left:0px;">
            <a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse"> <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </a>
            <!--a class="brand" href="http://www.elastichq.org" target="_blank"><i class="icon-dashboard"></i> Elastic
                HQ</a-->

            <div class="nav-collapse collapse">
                <ul class="nav">
                    <li>
                        <form class="form-search" style="margin:0;padding: 0;" id="connform_top">
                            <div class="input-prepend input-append" style="width: 350px;padding-top: 5px;">
                                <span class="add-on"><i class="icon-sitemap"></i></span>
                                <input type="text" placeholder="Enter http://domain:port" name="connectionURL"
                                       style="width: 220px;"
                                       id="connectionURL">
                                <button class="btn btn-info" type="button" id="connectButton">Connect</button>
                            </div>
                        </form>

                    </li>
                    <li>
                        <div id="ajaxindicator" style="padding-top:8px;">
                            <i class="icon-spinner icon-spin icon-large" style="color: #349BB9;"></i>
                            <!--<img src="/public/elastichq/images/ajax-loader.gif"/>-->
                        </div>
                    </li>

                </ul>

                <ul class="nav pull-right">
                    <!--                    <li><a style="visibility: hidden;" id="settingsButton" href="#settings" rel="tipRight" data-placement="bottom" data-title="Current Settings"><i
                                                class="icon-cogs icon-large"></i> Settings</a></li>-->
                    <li><a href="#viewsettings" rel="tipRight" id="settings" style="visibility: visible;"
                           data-placement="bottom" data-title="Personalize ElasticHQ"><i
                            class="icon-cog icon-large" style="padding-right:5px; vertical-align: -15% !important"></i>Settings</a></li>
                    <!--li><a href="http://www.elastichq.org/support.html" target="_blank" rel="tipRight"
                           data-placement="bottom" data-title="Have a question?"><i
                            class="icon-comments-alt icon-large"></i> Get Help</a></li>
                    <li><a href="https://github.com/royrusso/elasticsearch-HQ" target="_blank" rel="tipRight"
                           data-placement="bottom" data-title="Thank you for your support!"><i
                            class="icon-github icon-large"></i> Star us on GitHub</a></li>
                    <li><a href="http://www.elastichq.org/blog" target="_blank" rel="tipRight" id="blog"
                           style="visibility: visible;"
                           data-placement="bottom" data-title="Tips and Hints"><i
                            class="icon-bullhorn icon-large"></i> Blog</a></li-->
                </ul>
            </div>
            <!--/.nav-collapse -->
        </div>
    </div>
</div>

<div id="error-loc"></div>

<div class="container-fluid subnav">

    <div class="row-fluid">
        <div class="pull-left" id="clusterHealth-loc" style="padding-bottom: 8px;">

        </div>

        <div id="toolbar" style="padding:0;margin:0;visibility: hidden;">
            <div class="btn-group pull-right ">
                <a href="#indices" class="btn btn-success" rel="tipRight" data-placement="bottom"
                   data-title="Indices Management"><i
                        class="icon-list"></i> Indices
                </a>
                <a href="#documents" class="btn btn-success" rel="tipRight" data-placement="bottom" data-html="true"
                   data-title="Browse Documents"><i
                        class="icon-search"></i> Query</a>
                <a href="#mappings" class="btn btn-success" rel="tipRight" data-placement="bottom" data-html="true"
                   data-title="Type Mappings"><i
                        class="icon-map-marker"></i> Mappings</a>
                <a href="#restapi" class="btn btn-success" rel="tipRight" data-placement="bottom" data-html="true"
                   data-title="REST Console.<br/>Ya, for geeks."><i
                        class="icon-code"></i> REST</a>
                <!--
                                <a href="#snapshots" class="btn btn-success" rel="tipRight" data-placement="bottom" data-html="true"
                                   data-title="Snapshots"><i
                                        class="icon-camera"></i> Snap!</a>
                -->

            </div>
        </div>
    </div>


    <div class="row-fluid" style="padding:0;margin:0;">
        <div class="pull-left" id="nodeList-loc" style="padding-bottom: 8px;"></div>
    </div>
</div>

<!--<ul class="nav nav-list">
    <li class="divider"></li>
</ul>-->

<div class="container-fluid">
    <div class="row-fluid">
        <div id="workspace">
            <div class="container" style="padding-top: 20px;">
                <h1>Getting Started...</h1>

                <div class="row">
                    <div class="span4" style="padding:10px;border-right: 1px solid #cccccc;"><h3>Step 1</h3>Input
                        ElasticSearch REST End-point URL in the input field at the top of this page.<br/>
                        <small>(ie. <code>http://localhost:9200</code>)</small>
                        <br/>
                        <img src="/public/elastichq/images/gettingstarted1.png" style="padding-top: 30px;">
                        <br/>
                        <span class="label label-info" style="margin-bottom: 5px;">Optional:</span>

                        <p>Quick Connect with 'URL' Parameter:<br/><code>http://domain/?url=http://localhost:9200</code>
                        </p>
                    </div>
                    <div class="span4" style="padding:10px;border-right: 1px solid #cccccc;"><h3>Step 2</h3>Cluster is
                        the green button. Nodes are blue. <i class="icon-bolt"></i> is the Master Node.<br/>
                        <img src="/public/elastichq/images/gettingstarted2.png" class="pull-left" style="padding-top:20px;">
                    </div>
                    <div class="span4" style="padding:10px;"><h3>End</h3>ElasticHQ polls your cluster every 5 seconds;
                        So sit back and watch the pretty colors.<br/>
                        <img src="/public/elastichq/images/screenie.png" class="pull-left" style="padding-top:20px;"></div>
                </div>
                <div class="row">

                    <div class="span4"></div>
                </div>
                <hr/>

            </div>
        </div>
    </div>
</div>

<div id="infoModal-loc"></div>


<a class="scrollup" href="#">Scroll</a>
<a class="scrollupLeft" href="#">Scroll</a>

<!-- Footer
================================================== -->
<footer class="footer" style="display:none">
    <div class="container">
        <ul class="footer-links">
            <li><a href="http://www.elastichq.org" target="_blank">ElasticHQ.org</a></li>
            <li class="muted">&middot;</li>
            <li><a href="https://github.com/royrusso/elasticsearch-HQ" target="_blank">GitHub Page</a></li>
            <li class="muted">&middot;</li>
            <li><a href="https://twitter.com/ElasticHQ" target="_blank">@ElasticHQ</a></li>
            <li class="muted">&middot;</li>
            <li><a href="https://groups.google.com/d/forum/elastichq" target="_blank">Google Group</a></li>
        </ul>
        <p>
            <small>Current versions can always be found running at <a href="http://www.elastichq.org"
                                                                      target="_blank">ElasticHQ</a>.
            </small>
            <br/>
            <small><a href="#legalModal" data-toggle="modal" role="button">TOS & Privacy Policy Stuff</a> | Code
                licensed under
                <a href="http://www.apache.org/licenses/LICENSE-2.0" target="_blank">Apache License
                    v2.0</a>
            </small>
            <br/>
            <small>&copy;Razorback, LLC. All Rights Reserved.</small>
            <br/>
            <small>We are not affiliated with Elastic, Inc.</small>
        </p>
    </div>
</footer>

<script src="/public/elastichq/js/util/constants.js"></script>


<!-- JQuery -->
<script src="/public/elastichq/js/lib/jquery/jquery.1.9.1.min.js"></script>
<script src="/public/elastichq/js/lib/jquery/jquery.cookie.min.js"></script>
<script src="/public/elastichq/js/lib/jquery/jquery.tablesorter.min.js"></script>
<script src="/public/elastichq/js/lib/jquery/jquery.metadata.min.js"></script>
<script src="/public/elastichq/js/lib/jquery/jquery.tablesorter.pager.min.js"></script>

<!-- ES -->
<script src="/public/elastichq/js/lib/elasticsearch/elasticsearch.jquery.min.js"></script>

<!-- Twitter Bootstrap -->
<script src="/public/elastichq/js/lib/bootstrap/bootstrap.min.js"></script>
<script src="/public/elastichq/js/lib/bootstrap-select/bootstrap-select.min.js"></script>
<script src="/public/elastichq/js/lib/prettify/prettify.min.js"></script>

<!-- Backbone -->
<script src="/public/elastichq/js/lib/underscore/underscore-min.js"></script>
<script src="/public/elastichq/js/lib/backbone/backbone-min.js"></script>
<script src="/public/elastichq/js/lib/backbone-poller/backbone.poller.min.js"></script>
<script src="/public/elastichq/js/lib/backbone-validation/backbone-validation-min.js"></script>
<script src="/public/elastichq/js/lib/backbone-validation/backbone.validation.bootstrap.min.js"></script>

<!-- Lodash -->
<script src="/public/elastichq/js/lib/lodash/lodash.min.js"></script>
<script>
    var lodash = _.noConflict();
</script>

<!-- Notify -->
<script src="/public/elastichq/js/lib/pnotify/pnotify.min.js"></script>

<!-- Numeral -->
<script src="/public/elastichq/js/min/numeral.min.js"></script>

<!-- load ace -->
<script src="/public/elastichq/js/lib/ace/src-noconflict/ace.js"></script>
<script src="/public/elastichq/js/lib/ace/src-noconflict/ext-language_tools.js"></script>




<!-- Overrides/Extensions must go first! -->
<script src="/public/elastichq/js/model/util/ModelUtil.js"></script>

<!-- App -->
<script src="/public/elastichq/js/router.js"></script>
<script src="/public/elastichq/js/elastichq.js"></script>


<script src="/public/elastichq/js/all.min.js"></script>





<!-- Charts -->
<script language="javascript" type="text/javascript" src="/public/elastichq/js/lib/flot/jquery.flot.min.js"></script>




<script language="javascript" type="text/javascript" src="/public/elastichq/js/lib/flot/plugin/jquery.flot.time.js"></script>
<script language="javascript" type="text/javascript" src="/public/elastichq/js/lib/flot/plugin/jquery.flot.tooltip.min.js"></script>
<script language="javascript" type="text/javascript" src="/public/elastichq/js/lib/flot/plugin/curvedlines.js"></script>
<script language="javascript" type="text/javascript" src="/public/elastichq/js/charts.js"></script>
<script language="javascript" type="text/javascript" src="/public/elastichq/js/lib/d3/d3.v3.min.js"></script>

<script>
    $(document).ready(function () {
        $('#connectionURL').focus();
        ajaxloading.hide();
        scrollToTop.activate();

        $("[rel=tipRight]").tooltip();
        $("[rel=popRight]").popover(
                {
                    'trigger': 'hover',
                    'animation': true
                }
        );
    });
</script>


<div class="modal hide fade" id="legalModal">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h3>The Legal Stuff</h3>
    </div>
    <div class="modal-body">
        <p>By using ElasticHQ, you agree to everything on this page. We have to do this to protect both you and us and
            make running this
            FREE software service possible. If you break these terms, you can't use ElasticHQ anymore.</p>

        <h3>So here it is, in plain english...</h3>

        <h4>1. We are not liable for misuse of ElasticHQ, or for it not meeting your needs. </h4>
        <h4>2. We don't store PII(Personally Identifiable Information). From time to time, the software will collect
            anonymous usage data. None of it is being sold to anyone, so chill. The data collected gives us the
            information we need to
            customize the software for our users.</h4>
        <h4>3. We are not responsible for any possible downtime on the hosted version.</h4>
    </div>
    <div class="modal-footer">
        <a href="#" class="btn" data-dismiss="modal">Close</a>
    </div>
</div>

</body>
</html>
