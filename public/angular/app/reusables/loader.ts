import {Component, Input, Output, EventEmitter,ElementRef,Injectable, ViewChild,OnInit} from '@angular/core';
import {MODAL_DIRECTIVES, BS_VIEW_PROVIDERS} from 'ng2-bootstrap/ng2-bootstrap';
import {ModalDirective} from '../Common/Components/modal/modal.component';

@Component({
	selector:'shiksha-loader',
	template:`
    <div bsModal #shikshaLoader="bs-modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true" style="z-index:9999" [config]="{backdrop: 'static'}">
	    <div style="height:28px;width:28px;margin:20% auto">
	    	<img src="/public/images/shiksha-search-loader.gif">
	    </div>
    </div>
		`,
	directives:[MODAL_DIRECTIVES],
	providers:[BS_VIEW_PROVIDERS]
	})

export class Loader
{
	@Input() set showLoader(val){
		this.checkLoaderOpen(val);	
	}

	
	checkLoaderOpen(val){
		if(val == 1){
			this.showModal();
		}else{
			this.hideModal();
		}
	}
	
	@ViewChild('shikshaLoader') public sLoader:ModalDirective;


    public showModal():void {
      this.sLoader.show();
    }

    public hideModal():void {
      this.sLoader.hide();
    }

    

}
