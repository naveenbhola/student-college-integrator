import {Component, Input, Output, EventEmitter,ElementRef,Injectable, ViewChild,OnInit} from '@angular/core';
import {MODAL_DIRECTIVES, BS_VIEW_PROVIDERS} from 'ng2-bootstrap/ng2-bootstrap';
import {ModalDirective} from '../Common/Components/modal/modal.component';

@Component({
	selector:'shiksha-modal',
	template:`
    <div bsModal #shikshaModel="bs-modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true" style="z-index:9999" [config]="{backdrop: 'static',keyboard: false}">
	    <div class="modal-dialog {{modalClass}}">
	      <div class="modal-content">
	        <div class="modal-header">
	          <button type="button" class="close" aria-label="Close" (click)="finalDecision('no')">
	            <span aria-hidden="true">&times;</span>
	          </button>
	          <h4 class="modal-title" id="myModalLabel">{{modalTitle}}</h4>
	        </div>
		      <div class="modal-body">
		        <h4>{{modalContent}}</h4>		        
		      </div>
		      <div class="modal-footer" *ngIf="showYesNo">		        
		        <button type="button" (click) = "finalDecision('yes')" class="btn cmsButton cmsFont">Yes</button>
		        <button type="button" (click) = "finalDecision('no')" class="btn cmsButton cmsFont">No</button>
		      </div>               
	       </div>
	    </div>
    </div>
		`,
	directives:[MODAL_DIRECTIVES],
	providers:[BS_VIEW_PROVIDERS]
	})

export class WarningModal implements OnInit	
{
	@Output() modalResponse = new EventEmitter();
	@Output() modalHideResponse = new EventEmitter();

	@Input() set isModalOpened(val){
		this.checkModalOpen(val);	
	}
	@Input() modalTitle = '';
	@Input() modalContent = '';
	@Input() modalSize = '';
	@Input() elementId = '';
	@Input() showYesNo = true;

	modalClass:string = '';
	ngOnInit(){
		if(this.modalSize == 'small'){
			this.modalClass = 'modal-sm';
		}else if(this.modalSize == 'large'){
			this.modalClass = 'modal-lg';
		}
	}

	checkModalOpen(val){
		if(val == true){
			this.showModal();
		}else{
			this.hideModal();
		}
	}

	

	fd = 'no';
	finalDecision(decision){
		this.fd = decision;
		this.hideModal();
    	this.modalResponse.emit(this.fd);
		
	}

	
	@ViewChild('shikshaModel') public sModel:ModalDirective;


    public showModal():void {
      this.sModel.show();
    }

    public hideModal():void {
      this.sModel.hide();
    }

    

    onShow(){
    	console.log('show');
    }
}
