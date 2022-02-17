export function createObserver() {
    if(typeof window == 'undefined'){
        return;
    }
	let options = {
		root: null, //on coming into viewport
		rootMargin: "0px",
		threshold: [0]
	};
	if(typeof window.observer == 'undefined'){
        if (!('IntersectionObserver' in window)) {
            //console.log('IntersectionObserver does not exist');
        }else {
            //console.log('observer created');
            window.observer = new IntersectionObserver(handleIntersectCallback, options);
        }
        loadResources();
	}
}
export function destroyObserver() {
	//console.log('in observer destroy');
	if(typeof window == 'undefined' || typeof window.observer == 'undefined'){
		return;
	}
	//console.log('observer destroyed');
	window.observer.disconnect();
}
export function loadResources() {
    if(typeof window == 'undefined'){
        return;
    }
    if ('IntersectionObserver' in window && typeof window.observer != 'undefined') {
        attachEventToObserver();
    }else{
        loadElements();
    }
}
function loadElements() {
    const elements = ['img', 'iframe'];
    elements.forEach(elem => {
        let str = elem+".lazy";
        document.querySelectorAll(str).forEach(tag => {
            showElement(tag);
            //document.getElementById('consoleLog').innerHTML += tag.getAttribute('data-original')+'==='+tag.getAttribute('src') + '<br>';
        });
    });
    //console.log('tags loaded on fallback');
}
export function attachEventToObserver() {
	//console.log('in attach event');
    if(typeof window == 'undefined' || typeof window.observer == 'undefined'){
		return;
	}
    const elements = ['img', 'iframe'];
    elements.forEach(elem => {
        let str = elem+".lazy";
        //console.log(elem+' attached on observer');
        document.querySelectorAll(str).forEach(tag => {
            window.observer.observe(tag);
        });
    });
	//console.log('attach event done');
}
function handleIntersectCallback(entries){
	//console.log('lazy entries');
	entries.forEach(entry => {
		if(entry.isIntersecting){
		    //console.log('lazy loaded');
            showElement(entry.target);
		}
	});
}
function showElement(elem) {
    //console.log('in showElement function');
    let src = elem.getAttribute('data-original');
    if(typeof  src != 'undefined' && src != null){
        elem.setAttribute('src', src);
        //elem.removeAttribute('data-original');
        //elem.classList.remove('lazy');
    }
}