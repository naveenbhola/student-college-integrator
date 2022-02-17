import { Component, OnInit, OnChanges, OnDestroy, Input,AfterViewInit } from '@angular/core';
import { REACTIVE_FORM_DIRECTIVES, FormGroup, FormControl, Validators, FormArray } from '@angular/forms';
import { Posting } from '../Common/Classes/Posting';
import { My_Custom_Validators } from './Validators';

@Component({
	selector: 'addColumnTableComponent',
	templateUrl: '/public/angular/app/reusables/addColumnTableComponent.template.html',
	directives: [REACTIVE_FORM_DIRECTIVES],
	styles: [`
		.table tbody>tr>td{
		    vertical-align: middle;
		    text-align: center;
		}
	`]
})
export class AddColumnTableComponent extends Posting implements OnChanges,OnInit,AfterViewInit {

	@Input() group: FormGroup;
	@Input() default = [];
	@Input() addmore = [];
	@Input() tableHeading;
	@Input() colHeading = 'Yearly';
	@Input() showOutOf = false;
	@Input() editData;
	@Input() validateOutOf = true;
	@Input() numberValidation = 'decimal';

	colsToShow = [];
	_addmore;
	initialized: boolean = false;

	ngOnInit(){
		// console.log('in descendant init');
		this.colsToShow = this.default.filter(() => { return true; });
		setTimeout(() => {

			this.colsToShow.forEach((val) => {
				this.group.addControl(val['value'], new FormControl('', My_Custom_Validators.validateNumber(0)));
			});

			if (this.validateOutOf) {
				let columns = [];
				this.colsToShow.forEach((val) => {
					columns.push(val['value']);
				});

				this.group.setValidators([My_Custom_Validators.ValidateOutOfInFormGroup({ 'fieldlist': columns })]);
			}

			this.initialized = true;
		}, 0);
	}

	ngOnChanges(changes) {
		for (let propName in changes) {
			if(propName == 'addmore' && !this._addmore && changes[propName]['currentValue']){
				this._addmore = this.addmore.filter(() => { return true; });
			}
			// console.log('in descendant changes '+propName);
			if (propName == 'editData' && changes[propName]['currentValue']) {
				// console.log('in descendant changes');console.log(this._addmore);
				this.fillFormGroupData();
			}
			if (propName == 'numberValidation') {
				setTimeout(() => { this.updateNumberValidation(changes[propName]['currentValue']) }, 0);
			}
		}
	}

	ngAfterViewInit(){
		// console.log('in descendant view init');
	}

	addColumn(item) {
		// console.log(item);
		if (item) {
			let removeIndex: number;
			this._addmore.forEach((val, index) => {
				if (val['value'] == item) {
					this.colsToShow.push(val);
					this.group.addControl(val['value'], new FormControl('', My_Custom_Validators.validateWholeNumber(0)));
					removeIndex = index;
				}
			});

			if (this.validateOutOf) {
				let columns = [];
				this.colsToShow.forEach((val) => {
					columns.push(val['value']);
				});

				this.group.setValidators([My_Custom_Validators.ValidateOutOfInFormGroup({ 'fieldlist': columns })]);
			}
			if(typeof removeIndex != 'undefined'){
				this._addmore.splice(removeIndex, 1);
			}
		}
	}

	updateNumberValidation(type) {
		for (let control in this.group.controls) {
			if (control !== 'outof') {
				if (type == 'decimal') {
					this.group.controls[control].setValidators(My_Custom_Validators.validateNumber(0));
				}
				else if (type == 'integer') {
					this.group.controls[control].setValidators(My_Custom_Validators.validateWholeNumber(0));
				}
			}
		}
	}

	fillFormGroupData() {
		// console.log(this._addmore);console.log(this.editData);
		for (let key in this.editData) {
			for (let val of this._addmore) {
				if (val['value'] == key) {
					setTimeout(() => { this.addColumn(key); }, 0);
				}
			}
		}
		super.fillFormGroup(this.editData, this.group);
	}
}