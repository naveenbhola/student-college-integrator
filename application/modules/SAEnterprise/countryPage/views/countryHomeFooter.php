<?php
    // load footer file from listingPosting module
    $this->load->view('listingPosting/abroad/abroadCMSFooter');
?>
<script type="text/javascript" src="//<?php echo JSURL; ?>/public/js/jquery_ui/jquery-ui.min.js"></script>
<script type="text/javascript" src="//<?php echo JSURL; ?>/public/js/datatable/jquery.dataTables.min.js"></script>

<script>
$j(document).ready(function($j)
{

intializeCountryHomeFooter();

$j('.linkField').each(function(){
  $j(this).trigger('onblur');
}); 
  
 tinymce.init({
            selector: ".tinymce-textarea-limitedoptions",
            theme: "modern",
            plugins: [
                "advlist lists preview"
            ],
            file_browser_callback: false,
            toolbar1: /*""*/ "bullist numlist | preview ",
            relative_urls : false,
            menubar: false,
            rel_list: [
                        {title: 'alternate'    , value: 'alternate' },
                        {title: 'author'       , value: 'author'    },
                        {title: 'bookmark'     , value: 'bookmark'  },
                        {title: 'help'         , value: 'help'      },
                        {title: 'license'      , value: 'license'   },
                        {title: 'next'         , value: 'next'      },
                        {title: 'nofollow'     , value: 'nofollow'  },
                        {title: 'noreferrer'   , value: 'noreferrer'},
                        {title: 'prefetch'     , value: 'prefetch'  },
                        {title: 'prev'         , value: 'prev'      },
                        {title: 'search'       , value: 'search'    },
                        {title: 'tag'          , value: 'tag'       }
                    ],
            // added event handlers for focus & blur events to show/hide tooltips
            init_instance_callback : function(editor) {
                editor.on('focus', function(e) {
                    toolTipFlag = true;
                    studyabroadtooltipshow(e,document.getElementById(editor.id));
                    showErrorMessage(document.getElementById(editor.id), formname );
                });
                editor.on('change',function(e){
                    $j("#"+editor.id).html(editor.getContent());
                    showErrorMessage(document.getElementById(editor.id), formname );
                    });
                editor.on('blur', function(e) {
                   
                    tinyMceToolTipHideFlag = true;
                    $j("#"+editor.id).html(editor.getContent());
                    showErrorMessage(document.getElementById(editor.id), formname );
                    studyabroadtooltiphide();
                    
                });
                //console.log("Editor: " + editor.id + " is now initialized.");
            }
        
        }); 
});  
</script>