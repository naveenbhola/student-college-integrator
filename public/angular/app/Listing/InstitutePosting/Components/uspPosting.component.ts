import { Component,OnInit,Input,Output,EventEmitter,forwardRef,Provider } from '@angular/core';
import {Posting} from '../../../Common/Classes/Posting';
import { RegisterFormModelDirective } from '../../../reusables/registerForm';
import {REACTIVE_FORM_DIRECTIVES} from "@angular/forms";
import { MY_VALIDATORS } from '../../../reusables/Validators';
import { getHtmlVersion } from '../../../Common/utils/htmlVersioning';
import {ControlValueAccessor,NG_VALUE_ACCESSOR} from "@angular/forms";

export const USP_CONTROL_VALUE_ACCESSOR = new Provider(NG_VALUE_ACCESSOR,{useExisting:forwardRef(() => UspPostingComponent),multi:true});

@Component({
	selector : "usp-posting",
	templateUrl: '/public/angular/app/Listing/InstitutePosting/Views/'+getHtmlVersion('uspposting.component.html'),
	directives:[RegisterFormModelDirective,REACTIVE_FORM_DIRECTIVES,MY_VALIDATORS],
	providers:[USP_CONTROL_VALUE_ACCESSOR]
})

export class UspPostingComponent extends Posting implements OnInit,ControlValueAccessor{

	@Input() instituteObj;
	@Input() mode;
	@Input() errors;
	uspArr:any = [];
	activateUsp = true;
	@Input() form;

	_onTouchedCallback: () => any = () => {};
	_onChangeCallback: (value: any) => void = (value) => {};


	@Output() toolTipEvent: EventEmitter<any> = new EventEmitter<any>();
	ngOnInit(){
		if(this.mode == 'add'){
			this.instituteObj.addUsp();
		}
	}

		get usp(){
		    return this.uspArr;
		}

	set usp(value){
	    this.uspArr = value;
	    this._onChangeCallback(this.uspArr);
	}

	writeValue(value : any){
	    this.usp = value;
	}

	registerOnChange(fn:any){
	    this._onChangeCallback = fn;
	}

	registerOnTouched(fn:any){
	    this._onTouchedCallback = fn;
	}

	removeUspControl(index)
	{
		this.form.form.removeControl('uspKey'+(index+1));
		/*for (let i=1;i<=this.instituteObj.usp.length;i++)
			this.form.form.controls['uspKey'+i].updateValueAndValidity();*/
		//this.instituteObj.removeUsp(index);
		this.removeUsp(index);
	}

	addUsp(){
	    setTimeout(() => {
	        this.usp.push({'value':''});
	    },0);
	    this.changedetected();
	}

	changedetected(){
	    this.usp = this.usp.filter(function(){return true;});
	    setTimeout(() => {},0);
	    //this.locationService.pushEventChange({});
	}

	removeUsp(position,flag = true){
		this.usp.splice(position,1);
		this.changedetected();
	}
}
