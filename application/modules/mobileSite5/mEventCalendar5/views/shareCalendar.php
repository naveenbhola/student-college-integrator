<div class="cale-ovrlySocial" id="shareBoxAndOverlay">
    <div class="cale-share">
        <a class="shareClse" href="javascript:void(0);" onclick="toggleShareBoxAndOverlay('hide');"><i class="cale-sprite cl-close"></i></a>
        <h2>Share Calendar</h2>
        <ul class="shre">
            <li><a href="whatsapp://send?text=<?=$shareUrl?>" class="shr1" data-action="share/whatsapp/share"><i class="cale-sprite cl-whtsapp"></i>WhatsApp</a></li>
            <li><a href="javascript:void(0);" class="shr2" onclick="window.open('https://www.facebook.com/sharer.php?u=<?=$shareUrl?>', 'sharer', 'toolbar=0,status=0,width=620,height=280');"><i class="cale-sprite cl-fcebuk"></i>Facebook</a></li>
            <li><a href="javascript:void(0);" class="shr3" onclick="window.open('https://twitter.com/intent/tweet?url=<?=$shareUrl?>', 'sharer', 'toolbar=0,status=0,width=620,height=280');"><i class="cale-sprite cl-twiter"></i>Twitter</a></li>
        </ul>
    </div>
</div>