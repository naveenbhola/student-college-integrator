import { Component,Input,Output,OnInit,AfterViewInit,forwardRef,AfterContentInit,EventEmitter,Provider,ViewChild,ElementRef,Renderer,Directive } from '@angular/core';
import {NG_VALUE_ACCESSOR, ControlValueAccessor,NgModel} from '@angular/forms';
import {TOOLTIP_DIRECTIVES} from 'ng2-bootstrap/ng2-bootstrap';

@Directive({
	selector: "input[type=text]:not([noBootstrap]),input[type=number]:not([noBootstrap]),textarea:not([noBootstrap]),select:not([noBootstrap])"
})
export class BootstrapInputDirective implements OnInit{
	constructor(public el : ElementRef, public renderer:Renderer){}

	ngOnInit(){
		this.renderer.setElementClass(this.el.nativeElement,'form-control',true);
	}
}

@Directive({
	selector: 'label:not([noBootstrap])'
})
export class BootstrapLabelDirective implements OnInit{
	constructor(public el : ElementRef, public renderer:Renderer){}

	ngOnInit(){
		this.renderer.setElementClass(this.el.nativeElement,'control-label',true);
	}
}

@Directive({
	selector: '[mysize]'
})
export class BootstrapClassDirective implements OnInit{
	private _size = 6;
	private _offset;
	@Input() set mysize(size){
		this._size = +size;
	}
	@Input() set myclassoffset(offset){
		this._offset = +offset ? offset : 0;
	}
	constructor(public el : ElementRef, public renderer:Renderer){}

	ngOnInit(){
		this.renderer.setElementClass(this.el.nativeElement,'col-md-'+this._size,true);
		if(this._offset){
			this.renderer.setElementClass(this.el.nativeElement,'col-md-offset-'+this._offset,true);
		}
	}
}


export const MY_TEXT_FIELD_CONTROL_VALUE_ACCESSOR = new Provider(NG_VALUE_ACCESSOR,{useExisting:forwardRef(() => MyTextField),multi:true});

let nextId = 0;

@Component({
	selector:'myTextField',
	template:`
	<div class="form-group" [class.has-error]="state && !state.pristine && !state.valid">
	  <label *ngIf ="tooltip"  tooltip="{{tooltip}}"  tooltipTrigger="hover" tooltipPlacement="up" class="control-label col-md-{{labelSize}} col-sm-{{labelSize}} col-xs-12" [attr.for]="labelFor">{{label}}<span *ngIf="required" class="required">*</span>
	  <span *ngIf="!!tooltip"> (?)</span></label>
	
	  <label *ngIf ="!tooltip"  class="control-label col-md-{{labelSize}} col-sm-{{labelSize}} col-xs-12" [attr.for]="labelFor">{{label}}<span *ngIf="required" class="required">*</span>
	  </label>

	  <div class="col-md-{{inputSize}} col-sm-{{inputSize}} col-xs-12">
	    <input *ngIf="type == 'input'" type="text" (blur)="onTouched()" [required]="required" name={{name}} [name]="name" [(ngModel)]="value"  class="form-control" [attr.placeholder]="placeholder">
	    <input *ngIf="type == 'number'" type="number" (blur)="onTouched()" [min]="min" [max]="max" [required]="required" name={{name}} [name]="name" [(ngModel)]="value"  class="form-control" [attr.placeholder]="placeholder">
	    <textarea *ngIf="type == 'textarea'" type="text" (blur)="onTouched()" name={{name}} [name]="name" [(ngModel)]="value"  class="form-control" [attr.placeholder]="placeholder"></textarea>
	    <span *ngIf="state && !state.pristine && !state.valid" class="help-block text-danger">
	    	<span *ngIf="state.errors.required">This field is required</span>
	    	<span *ngIf="state.errors.minlength">This field should have minimum of {{min}} characters</span>
	    	<span *ngIf="state.errors.maxlength">This field can have a maximum of {{max}} characters</span>
	    </span>
	    <ng-content select="inputSibling"></ng-content>
	  </div>
	  <div class="col-md-{{12-(inputSize+labelSize)}}">
	  	<ng-content select="siblingDiv"></ng-content>
	  </div>
	</div>
	`,
	providers:[MY_TEXT_FIELD_CONTROL_VALUE_ACCESSOR],
	directives:[TOOLTIP_DIRECTIVES],
	styles: [`
    /* Specify styling for tooltip contents */
    .tooltip.customClass .tooltip-inner {
        color: #880000;
        background-color: #ffff66;
        box-shadow: 0 6px 12px rgba(0,0,0,.175);
    }
    /* Hide arrow */
    .tooltip.customClass .tooltip-arrow {
        display: none;
    }
    input.ng-invalid,select.ng-invalid {border: 1px solid #a94442;}
    input.ng-pristine,select.ng-pristine{border: 1px solid #ccc;}
  `]
})

export class MyTextField implements ControlValueAccessor,AfterViewInit{
	@Input() required = false;
	@Input() labelSize = 3;
	@Input() inputSize = 6;
	@Input() labelFor = 'for';
	@Input() tooltip;
	@Input() placeholder = '';
	@Input() name = `my-text-field-${++nextId}`;
	@Input() type = 'input';
	@Input() min = '';
	@Input() max = '';
	@Input() label = 'label';state;
	@ViewChild(NgModel) _state:NgModel;

	@Output() change = new EventEmitter();

	private _value:any = '';
	_onTouchedCallback: () => any = () => {};
	_onChangeCallback: (value: any) => void = (value) => {};

	get value(): any { return this._value;};

	set value(v: any) {
		if (v !== this._value) {
			this._value = v;
			this._onChangeCallback(v);
			this.change.emit(this._value);
		}
	}

	ngAfterViewInit(){
		setTimeout(() => this.state = this._state);
	}

	onTouched(){
		this._onTouchedCallback();
	}

	writeValue(value: any) {
	    this._value = value;
	}

	registerOnChange(fn:any){
		this._onChangeCallback = fn;
	}

	registerOnTouched(fn:any){
		this._onTouchedCallback = fn;
	}
}

export const MY_SELECT_FIELD_CONTROL_VALUE_ACCESSOR = new Provider(NG_VALUE_ACCESSOR,{useExisting:forwardRef(() => MySelectField),multi:true});

@Component({
	selector:'mySelectField',
	template:`
	<div class="form-group" [class.has-error]="state && !state.pristine && !state.valid">

	    <label *ngIf="tooltip" tooltip="{{tooltip}}"  tooltipTrigger="hover" tooltipPlacement="up"  class="control-label col-md-{{labelSize}} col-sm-{{labelSize}} col-xs-12" [attr.for]="labelFor">{{label}}
	    <span *ngIf="required" class="required text-danger">*</span><span *ngIf="!!tooltip"> (?)</span></label>

	    <label *ngIf="!tooltip" class="control-label col-md-{{labelSize}} col-sm-{{labelSize}} col-xs-12" [attr.for]="labelFor">{{label}}
	    <span *ngIf="required" class="required text-danger">*</span></label>

	    <div class="col-md-{{inputSize}} col-sm-{{inputSize}} col-xs-12">
	        <select (blur)="onTouched()" name={{name}} [name]="name" [required]="required" [(ngModel)]="value" class="form-control" [attr.placeholder]="placeholder">
	        	<option *ngIf="placeholder" value="{{placeholderValue}}">{{placeholder}}</option>
	        	<option *ngFor="let option of options" value="{{option.value}}">{{option.label}}</option>
	        </select>
	        <span *ngIf="state && !state.pristine && !state.valid" class="help-block text-danger">
	        	<span *ngIf="state.errors.required">This field is required</span>
	        </span>
	        <ng-content select="selectSibling"></ng-content>
	    </div>
	    <div class="col-md-{{12-(inputSize+labelSize)}}">
	    	<ng-content select="siblingDiv"></ng-content>
	    </div>
	</div>
	`,
	providers:[MY_SELECT_FIELD_CONTROL_VALUE_ACCESSOR],
	directives:[TOOLTIP_DIRECTIVES],
	styles: [`
    /* Specify styling for tooltip contents */
    .tooltip.customClass .tooltip-inner {
        color: #880000;
        background-color: #ffff66;
        box-shadow: 0 6px 12px rgba(0,0,0,.175);
    }
    /* Hide arrow */
    .tooltip.customClass .tooltip-arrow {
        display: none;
    }
    input.ng-invalid,select.ng-invalid {border: 1px solid #a94442;}
    input.ng-pristine,select.ng-pristine{border: 1px solid #ccc;}
  `]
})

export class MySelectField implements ControlValueAccessor,AfterViewInit{
	@Input() labelFor = 'for';
	@Input() labelSize = 3;
	@Input() inputSize = 6;
	@Input() required = false;
	@Input() tooltip;
	@Input() placeholder = '';
	@Input() placeholderValue = '';
	@Input() options = [];
	@Input() name = `my-select-field-${++nextId}`;
	@Input() label = 'label';state;
	@ViewChild(NgModel) _state:NgModel;

	@Output() change = new EventEmitter();

	private _value:any = '';
	_onTouchedCallback: () => any = () => {};
	_onChangeCallback: (value: any) => void = (value) => {};

	get value(): any { return this._value; };

	set value(v: any) {
		if (v !== this._value) {
			this._value = v;
			this._onChangeCallback(v);
			this.change.emit(this._value);
		}
	}

	ngAfterViewInit(){
		setTimeout(() => this.state = this._state);
	}

	onTouched(){
		this._onTouchedCallback();
	}

	writeValue(value: any) {
	    this._value = value;
	}

	registerOnChange(fn:any){
		this._onChangeCallback = fn;
	}

	registerOnTouched(fn:any){
		this._onTouchedCallback = fn;
	}
}

export const MY_FORM_FIELD_DIRECTIVES = [MyTextField,MySelectField,BootstrapInputDirective,BootstrapClassDirective,BootstrapLabelDirective];