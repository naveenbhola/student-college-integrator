	var scrollPosition = 0;
    function canUseDOM()
    {
        return !!(typeof window !== 'undefined' && window.document && window.document.createElement);
    }
	function addClassName(className)
    {
        if(!canUseDOM()) return;
        return !(document.getElementById('root').classList.contains(className)) ? document.getElementById('root').classList.add(className) : '';
    }
    function enableScrolling(isFullLayer = false,isforce= false, scroll = null)
    {
        if(!canUseDOM()) return;
        if(document.getElementsByClassName('bcglayer').length == 0 || (isFullLayer && document.getElementsByClassName('bcglayer').length == 1) || isforce)
        {
            removeClassName('disable-scroll');
            if(scroll != null){
                console.log("Inside prevent scrolling",scroll);
                window.scrollTo(0,scroll);
            }else{
                window.scrollTo(0,scrollPosition);
            }
            //scrollPosition = 0;
        }
    }
    function removeClassName(className)
    {
        if(!canUseDOM()) return;
        //return (document.body.classList.contains(className)) ? document.body.classList.remove(className) : '';
        return (document.getElementById('root').classList.contains(className)) ? document.getElementById('root').classList.remove(className) : '';
    }
    function disableScrolling()
    {
        if(!canUseDOM()) return;
        if(!document.getElementById('root').classList.contains('disable-scroll'))
            scrollPosition = window.scrollY;
        var self = this;
        if(document.getElementById('fixed-card') && document.getElementById('fixed-card').style.display == 'block')
        {
            document.getElementById('fixed-card').style.display = 'none';    
        }
        setTimeout(function(){
            addClassName('disable-scroll')
        },200);
    }

    function disableFormScrolling() {
        if(document.getElementsByClassName('scrolable-vport').length > 0 && document.getElementsByClassName('scrolable-vport')[0].classList.contains('form-scroll')) {
            document.getElementsByClassName('scrolable-vport')[0].classList.remove('form-scroll');
        }
    }

    function enableFormScrolling() {
        if(document.getElementById('common-loader') == null && document.getElementsByClassName('scrolable-vport').length > 0) {
            document.getElementsByClassName('scrolable-vport')[0].classList.add('form-scroll');
        }
    }

export default {addClassName,enableScrolling,removeClassName,disableScrolling,canUseDOM,disableFormScrolling,enableFormScrolling};