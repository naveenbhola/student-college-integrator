import {Component, Input, Output, EventEmitter,ElementRef,Injectable, ViewChild,OnInit} from '@angular/core';
import {MODAL_DIRECTIVES, BS_VIEW_PROVIDERS} from 'ng2-bootstrap/ng2-bootstrap';
import {ModalDirective} from '../Common/Components/modal/modal.component';
import { MapToIterable , SortArrayPipe} from '../pipes/arraypipes.pipe';

@Component({
	selector:'locality-modal',
	template:`
    <div bsModal #localityModel="bs-modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true" style="z-index:9999" [config]="{backdrop: 'static',keyboard: false}">
	    <div class="modal-dialog modal-sm">
	      <div class="modal-content">
	        <div class="modal-header">
	          <button type="button" class="close" aria-label="Close" (click)="hideLayer();">
	            <span aria-hidden="true">&times;</span>
	          </button>
	          <h4 class="modal-title" id="myModalLabel">{{modalTitle}}</h4>
	        </div>
		      <div class="modal-body" styless="max-height:400px;overflow:scroll;">
		      		<div *ngFor="let localityObj of modalContent | mapToIterable | sortArrByColumn:'asc':'val.name'">
	                    <div>
	                        <label class="checkbox-inline">
	                        	<input type="checkbox" name="localityLayer_{{localityObj.val.id}}" [(ngModel)]="localityObj.val.state" [disabled]="localityObj.val.disabled">
	                        {{localityObj.val.name}}
	                        </label>
	                    </div>
	                </div>
		      </div>
		      <div class="modal-footer">		        
		        <button type="button" (click) = "updateLocalities();" class="btn cmsButton cmsFont">Update</button>
		      </div>               
	       </div>
	    </div>
    </div>
		`,
		styles:[`
		.modal-dialog, .modal-content{height:80%;}
		.modal-body{max-height:calc(100% - 120px); overflow-y:scroll;}
		`],
	directives:[MODAL_DIRECTIVES],
	providers:[BS_VIEW_PROVIDERS],
	pipes:[MapToIterable,SortArrayPipe],
	})

export class LocalityModal implements OnInit	
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
	@Input() selectedLocalities = [];

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

	hideLayer(){
		this.sModel.hide();
		this.modalHideResponse.emit(this.fd);
	}

	updateLocalities(){
		this.sModel.hide();
		this.modalResponse.emit(this.modalContent);	
	}
	
	@ViewChild('localityModel') public sModel:ModalDirective;


    public showModal():void {
      this.sModel.show();
    }

    public hideModal():void {
      this.sModel.hide();
    }

    onShow(){
    }
}
