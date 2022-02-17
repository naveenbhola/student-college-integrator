import { Component,OnInit,Input,Provider,forwardRef,Output,EventEmitter } from '@angular/core';
import {Posting} from '../../../Common/Classes/Posting';
import { MY_VALIDATORS } from '../../../reusables/Validators';
import { InstituteScholarship } from '../Classes/InstituteEntities';

import {REACTIVE_FORM_DIRECTIVES,ControlValueAccessor,NG_VALUE_ACCESSOR} from "@angular/forms";
import { getHtmlVersion } from '../../../Common/utils/htmlVersioning';

export const SCHOLARSHIPS_CONTROL_VALUE_ACCESSOR = new Provider(NG_VALUE_ACCESSOR,{useExisting:forwardRef(() => ScholarshipPostingComponent),multi:true});

@Component({
	moduleId: module.id,
	selector: 'scholarship-posting',
	templateUrl: '/public/angular/app/Listing/InstitutePosting/Views/'+getHtmlVersion('scholarship-posting.component.html'),
	directives:[MY_VALIDATORS,REACTIVE_FORM_DIRECTIVES],
	providers:[SCHOLARSHIPS_CONTROL_VALUE_ACCESSOR]
})

export class ScholarshipPostingComponent extends Posting implements OnInit,ControlValueAccessor {

	@Input() instituteStaticData;
	@Input() errors;
	@Input() mode;
	@Input() form;
	activateScholarships = true;
	scholarshipArr:any = [];

	@Output() toolTipEvent: EventEmitter<any> = new EventEmitter<any>();
	
	_onTouchedCallback: () => any = () => {};
	_onChangeCallback: (value: any) => void = (value) => {};

	ngOnInit(){
	    if(this.mode == 'add'){
	    	this.addScholarship();
	    }
	}

	get scholarships(){
	    return this.scholarshipArr;
	}

	changedetected(){
	    this.scholarships = this.scholarships.filter(function(){return true;});
	    setTimeout(() => {},0);
	}

	set scholarships(value){
	    this.scholarshipArr = value;
	    this._onChangeCallback(this.scholarshipArr);
	}

	writeValue(value : any){
	    this.scholarships = value;
	}

	registerOnChange(fn:any){
	    this._onChangeCallback = fn;
	}

	registerOnTouched(fn:any){
	    this._onTouchedCallback = fn;
	}

	addScholarship(){
	    setTimeout(() => {
	        this.scholarships.push(new InstituteScholarship());
	    },0);
	    this.changedetected();
	}

	removeScholarship(position,flag = true){
	    setTimeout(() => {
	        this.scholarships.splice(position,1);
	    },0);
	    setTimeout(() =>{this._onChangeCallback(this.scholarships); },0);
	    this.changedetected();
	}

}
