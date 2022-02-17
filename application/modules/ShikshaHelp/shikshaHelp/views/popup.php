<html>
    <head>
        <title>Shiksha.com</title>
        <script>
            //Functions to close overlay without gray background end
            var bannerPool = new Array();
            function pushBannerToPool(bannerId, bannerUrl){
                if(bannerId != '') bannerPool[bannerId] = bannerUrl;
            }
            function publishBanners(){
                for(var bannerId in bannerPool) if(document.getElementById(bannerId) != null) document.getElementById(bannerId).src = bannerPool[bannerId];
            }
        </script>
    </head>
    <body align="center">
            <?php
            $bannerProperties = array('pageId'=>'HOME', 'pageZone'=>'POPUP'); 
            $this->load->view('common/banner',$bannerProperties); 
             ?>
    </body>
</html>
<script>
publishBanners();
</script>
