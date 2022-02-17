import {Component, Input, Output, EventEmitter,ElementRef,Injectable, OnInit,Provider,forwardRef } from '@angular/core';
import { ArraySearchPipe } from '../pipes/arraypipes.pipe';
import {ControlValueAccessor,NG_VALUE_ACCESSOR} from "@angular/forms";

export const MULTI_SELECT_CONTROL_VALUE_ACCESSOR = new Provider(NG_VALUE_ACCESSOR,{useExisting:forwardRef(() => Multiselect),multi:true});



@Component({
	selector:'mymultiselect',
	template:`
	<div class="col-md-{{size}}">
		<div  class="btn-group" [offClick]="clickedOutside">	  
		  <button type="button" (click)="showOptionsMenu()" class="btn btn-default" [disabled]="disabled" title="{{buttonTitle}}">{{_placeholder}} <span class="caret"></span></button>
		  <ul class="dropdown-menu multiSelCustom" [style.display]="showOptions?'block':'none'">
		  	<p><input name="multiSearch" [(ngModel)]="multiSearchQuery" /></p>
		    <li (click)="setChecked(item)" *ngFor="let option of data|arraysearch:multiSearchQuery:'label'">
		    	<input value="{{option.value}}" #item   [checked]="selected  && selected.indexOf(option.value) != -1" type="checkbox" [disabled]="option.disable"/>&nbsp;{{option.label}}
		    </li>
		  </ul>
		</div>
	</div>
	`,
	pipes:[ArraySearchPipe],
	providers:[MULTI_SELECT_CONTROL_VALUE_ACCESSOR]
})


export class Multiselect implements OnInit,ControlValueAccessor{
	
	_placeholder:string;
	showOptions:boolean=false;
	@Output() selectedValues = new EventEmitter();

	@Input() size:number = 2;
	@Input() selected:Array<any> = [];
	@Input() dispCount:number;
	@Input() defaultPlaceholder:string = '';
	@Input() itemKey;
	@Input() data:any;
	@Input() disabled:boolean =false;
	@Input() buttonTitle:string ='';
	@Input() showOnlyCount:boolean =false;


    _onTouchedCallback: () => any = () => {};
    _onChangeCallback: (value: any) => void = (value) => {};
	
    public element:ElementRef;

    constructor(element:ElementRef) {
   		this.element = element;
    	this.clickedOutside = this.clickedOutside.bind(this);
  	}

  	public clickedOutside():void  {
    	this.showOptions = false;
    	this._onTouchedCallback();
 	}

 	@Input() set placeholder(value){
 		this.defaultPlaceholder = value;
 		this._placeholder = value;
 	}


 	ngOnInit(){
 		this.updatePlaceholder(); 	
 	}


	setChecked(item){
		if(item.disabled){
			return false;
		}

		if(this.selected && this.selected.indexOf(item.value) != -1){
			this.selected.splice(this.selected.indexOf(item.value),1);
		}
		else{
			if(typeof this.selected == 'undefined' || this.selected == null) {
				this.selected = [];
			}
			this.selected.push(item.value);
		}

		
		this.selectedValues.emit({selected:this.selected,type:this.itemKey});
		this._onChangeCallback(this.selected);
		this._onTouchedCallback();
		this.updatePlaceholder();
	}

	showOptionsMenu(){
		document.body.click();
		this.showOptions = true;
	}


	updatePlaceholder(){		
		if(this.selected && this.selected.length > 1){
			this._placeholder = this.selected.length + ' selected';
		}
		else if(this.selected && this.selected.length == 1){
			if(this.data == null || this.showOnlyCount == true){
				this._placeholder = this.selected.length + ' selected';
			}
			else{
				this.data.forEach((val) => {
					if(val.value == this.selected[0]){
						this._placeholder = val.label;
					}
				});
			}
		}
		else{
			this._placeholder = this.defaultPlaceholder.charAt(0).toUpperCase() + this.defaultPlaceholder.slice(1);
		}
	}



    writeValue(value : any){
    	if(typeof value != 'object'){
    		value =[];
    	}
        this.selected = value;
        this.updatePlaceholder();
    }

    registerOnChange(fn:any){
        this._onChangeCallback = fn;
    }

    registerOnTouched(fn:any){
        this._onTouchedCallback = fn;
    }


}