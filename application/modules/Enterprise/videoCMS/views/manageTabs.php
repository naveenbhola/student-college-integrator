 <style>
.mnge-dv{border: 1px solid #ccc;width: auto;float: right;margin-bottom: 15px;}
.mnge-dv a{border-right: 1px solid #ccc; color: #333;float: left;padding: 8px 12px; text-decoration: none;font-weight: bold;}
.mnge-dv a:last-child{border: none;}
.mnge-dv a.active{background: #fc8104;color: #fff; font-weight: bold;}
.orange-btn, .gray-btn{background:#FC8104; color:#fff !important; padding:8px 14px; display:inline-block; font-size:14px; text-decoration:none !important; margin:0; border-radius:3px; font-weight:bold;margin-left:7px;}
.abroad-cms-content{    padding: 0 35px;margin: 15px 0 10px;}
.abroad-cms-rt-box:after {clear: both;}
.abroad-cms-rt-box{padding-bottom: 30px;}
.abroad-cms-rt-box:before, .abroad-cms-rt-box:after {content: '';display: table;}
 </style>
<?php if($activePage=='videoList'){?>
 <div class="mnge-dv">
    <a href="/videoCMS/VideoCMS/addEditVideoContent">+ Add Video</a>
</div>
<?php } ?>

<?php if($activePage=='back'){?>
 <div class="mnge-dv">
    <a class="active" href="/videoCMS/VideoCMS/addEditVideoContent">< Back</a>
</div>
<?php } ?>
