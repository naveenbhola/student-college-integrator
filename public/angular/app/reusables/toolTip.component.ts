import {Component, Input,ElementRef} from '@angular/core';
/*import {MODAL_DIRECTIVES, BS_VIEW_PROVIDERS} from 'ng2-bootstrap/ng2-bootstrap';
import {ModalDirective} from '../Common/Components/modal/modal.component';*/

@Component({
	selector:'toolTipModal',
	template:`
	<button *ngIf="isToolTipShow" type="button" class="btn close" (click)="closeToolTip()" aria-label="Close" style="background:#1E242B;border : 1px solid #1E242B;position: absolute;opacity:0.5;
    	padding: 0px 4px;font-size:16px;z-index:1070;border-radius: 50%;" [ngStyle]="{top:tooltipPositionX+3+'px',left:tooltipPositionY+closeY_padding+'px'}">
          <span aria-hidden="true">&times;</span>
        </button>
    <div [offClick]="clickedOutside" *ngIf="isToolTipShow" class="tooltip fade right in" data-toggle="tooltip" [ngStyle]="{top: tooltipPositionX+x_padding+'px', left: tooltipPositionY+y_padding+'px'}" style="padding:11px">
     		<span *ngIf="toolTipMsg" [innerHTML]="toolTipMsg">
    		</span><br/>
    </div>
		`,
	styles:[`
		div {    position: absolute;
    background: #babbbd;
    color:#000;
    border: 1px solid black;
    font-size: 13px;
    border-radius: 5px;
    padding: 10px;
    z-index: 1070;
    width: 654px;
    overflow:auto;
    min-height: 50px;
    max-height:200px;
    opacity: 1;
    display: block;
    padding:10px;
/*    border-bottom: 1px solid #e5e5e5;*/
		}
	`],
	})

export class toolTipComponent	
{
	isToolTipShow : boolean = false;
	x_padding = 20;
	y_padding = 20;
	closeY_padding = 653.531;
	@Input() toolTipMsg:any;
	@Input() tooltipPositionX;
	@Input() tooltipPositionY;
	@Input() set isModalOpened(val){
		this.isToolTipShow = val;
	}

	_onTouchedCallback: () => any = () => {};
	closeToolTip()
	{
		this.isToolTipShow = false;		
	}

	public element:ElementRef;
	constructor(element:ElementRef) {
   		this.element = element;
    	this.clickedOutside = this.clickedOutside.bind(this);
  	}
  	public clickedOutside():void  {
    	this.isToolTipShow = false;
    	this._onTouchedCallback();
 	}
}