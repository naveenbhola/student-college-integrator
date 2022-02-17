import { Directive, forwardRef, Input, Attribute, OnChanges } from '@angular/core';
import { NgForm, NG_VALIDATORS, FormGroup, FormControl, Validator, ValidatorFn, FormArray } from '@angular/forms';
import { DELIVERY_METHOD, EDUCATION_TYPE } from '../Listing/CoursePosting/classes/CourseConst';

export class My_Custom_Validators {

	static ValidateCategorySeoForm(seoComponent): ValidatorFn {
		return (c: FormGroup): { [key: string]: any } => {
			if (typeof c != 'undefined') {
				let errors = {}, placeholderFinder = /\[[a-z_]{1,}\]/g, usedPlaceholders = seoComponent.usedPlaceholders;
				if (c.controls['deliveryMethods'].value == DELIVERY_METHOD.online && c.controls['courseLocationSelection'].value == 'any') {
					errors['deliveryMethods'] = ['online can be used only with All India'];
				}
				seoComponent.placeholderIdentifier.forEach(function(controlName) {
					let value = seoComponent.getExpandedValueForField(controlName), foundPlaceholders = value.match(placeholderFinder), difference;
					if (!errors[controlName]) {
						errors[controlName] = [];
					}
					if (c.controls[controlName].value && usedPlaceholders && foundPlaceholders) {
						switch (controlName) {
							case 'url':
								if (c.controls['educationTypes'].value == EDUCATION_TYPE.partTime && c.controls['deliveryMethods'].value) {
									usedPlaceholders = new Set(usedPlaceholders); usedPlaceholders.delete(seoComponent.placeholders['educationTypes']);
									usedPlaceholders = Array.from(usedPlaceholders);
									if (seoComponent.ruleId) {
										foundPlaceholders = new Set(foundPlaceholders); foundPlaceholders.delete(seoComponent.placeholders['educationTypes']);
										foundPlaceholders = Array.from(foundPlaceholders);
									}
								}
								difference = My_Custom_Validators.calculateDifferenceOfArrays(foundPlaceholders, usedPlaceholders);
								if (difference['missing']) {
									errors[controlName].push('Has missing Placeholders: ' + difference['missing'].join(','));
								}
								if (difference['extras']) {
									errors[controlName].push('Has extra Placeholders: ' + difference['extras'].join(','));
								}
								if (My_Custom_Validators.containsInValidURLCharacters(value)) {
									errors[controlName].push('Contains invalid ' + controlName + ' Characters');
								}
								break;
							case 'breadcrumb':
								foundPlaceholders = new Set(foundPlaceholders);
								foundPlaceholders.delete('[colleges]');
								difference = My_Custom_Validators.calculateDifferenceOfArrays(Array.from(foundPlaceholders), usedPlaceholders);

								if (difference['missing']) {
									errors[controlName].push('Has missing Placeholders: ' + difference['missing'].join(','));
								}
								if (difference['extras']) {
									errors[controlName].push('Has extra Placeholders: ' + difference['extras'].join(','));
								}
								if (My_Custom_Validators.containsInValidBreadCrumbCharacters(value)) {
									errors[controlName].push('Contains invalid ' + controlName + ' Characters');
								}
								break;
							default:
								if (c.controls['educationTypes'].value == EDUCATION_TYPE.partTime && c.controls['deliveryMethods'].value) {
									usedPlaceholders = new Set(usedPlaceholders); usedPlaceholders.delete(seoComponent.placeholders['educationTypes']);
									usedPlaceholders = Array.from(usedPlaceholders);
								}
								foundPlaceholders = new Set(foundPlaceholders);
								foundPlaceholders.delete('[n]');
								difference = My_Custom_Validators.calculateDifferenceOfArrays(Array.from(foundPlaceholders), usedPlaceholders);
								if (difference['extras']) {
									errors[controlName].push('Has extra Placeholders: ' + difference['extras'].join(','));
								}
								if (value.match(/[^a-z0-9_\-., \[\]|/]/gi)) {
									errors[controlName].push('Contains invalid characters');
								}
								break;
						}
					}
				});
				let returnObj = {};
				for (let controlName in errors) {
					if (errors[controlName].length) {
						returnObj[controlName] = errors[controlName].join(';;');
					}
				}
				for (let controlName in returnObj) {
					return { 'formErrors': returnObj };
				}
			}
			return null;
		}
	}

	static containsInValidURLCharacters(value) {
		return value.match(/[^a-z0-9_\-.\[\]\/]/gi) ? true : false;
	}

	static containsInValidBreadCrumbCharacters(value) {
		return value.match(/[^a-z0-9_\-. \[\]:>/]/gi) ? true : false;
	}

	static calculateDifferenceOfArrays(arr1, arr2) {
		let set1 = new Set(arr1), set2 = new Set(arr2),
			missing = arr2.filter((ele) => { return !set1.has(ele); }),
			extras = arr1.filter((ele) => { return !set2.has(ele); }),
			returnObj = {};
		if (missing.length) {
			returnObj['missing'] = missing;
		}
		if (extras.length) {
			returnObj['extras'] = extras;
		}
		return returnObj;
	}

	static ValidateOneOrNoneFormGroup(inputObj = {}): ValidatorFn {

		return (c: FormGroup): { [key: string]: any } => {
			if (typeof c != 'undefined' && inputObj['mymust'].length) {

				let controls = c.controls;
				let _optional = inputObj['myoptional'];
				let _must = inputObj['mymust'];


				if (Object.keys(controls).length > 0) {
					let columns = _optional.concat(_must); let proceed = false;

					if (!proceed) {
						columns.forEach((col) => {
							if (controls[col] && controls[col].value) {
								proceed = true;
							}
						});
					}

					if (proceed) {
						let errors = [];
						_must.forEach((col) => {
							if (!controls[col].value) {
								errors.push(col);
							}
						});
						if (errors.length) {
							let returnObj = {};
							for (let i of errors) {
								returnObj[i] = { valid: false };
							}

							return { 'validateOneOrNoneFormGroup': returnObj };
						}
					}
					return null;
				}
			}
		}
	}

	static ValidateOutOfInFormGroup(inputObj = {}): ValidatorFn {
		return (c: FormGroup): { [key: string]: any } => {
			if (typeof c != 'undefined') {
				let outof = inputObj['outoffieldname'] || 'outof';
				let fieldlist = inputObj['fieldlist'] || [];
				let controls = c.controls;
				if (!fieldlist.length) {
					for (let i in controls) {
						if (i != 'outof') {
							fieldlist.push(i);
						}
					}
				}
				let errors = [];
				fieldlist.forEach((val) => {
					if (controls[val] && controls[val].value) {
						if (+controls[val].value > +controls[outof].value) {
							errors.push(val);
						}
					}
				});

				let returnObj = {};
				if (errors.length) {
					for (let i of errors) {
						returnObj[i] = { 'valid': false };
					}
					return { 'validateOutOfInFormGroup': returnObj };
				}
				else {
					return null;
				}
			}
		}
	}

	static ValidateMinMaxInFormGroup(inputObj = {}): ValidatorFn {
		return (c: FormGroup): { [key: string]: any } => {
			if (typeof c != 'undefined') {
				let type = inputObj['type'] || 'default';
				let allowequal = inputObj['allowequal'] || false;
				if (type == 'default') {
					let mincontrol = c.controls[inputObj['min_control']];
					let maxcontrol = c.controls[inputObj['max_control']];
					if (mincontrol.value && mincontrol.value.trim() && maxcontrol.value && maxcontrol.value.trim()) {
						if ((!allowequal && (+mincontrol.value.trim() >= +maxcontrol.value.trim())) || (allowequal && (+mincontrol.value.trim() > +maxcontrol.value.trim()))) {
							let returnObj = {};
							let str = allowequal ? 'or equal to ' : '';
							returnObj[inputObj['min_control']] = inputObj['min_control'] + ' should be less than ' + str + inputObj['max_control'];
							return returnObj;
						}
					}
				}
				else if (type == 'date') {
					let mingroup: FormGroup = <FormGroup>c.controls[inputObj['mingroup']];
					let maxgroup: FormGroup = <FormGroup>c.controls[inputObj['maxgroup']];
					let error = false;
					let minyear = +mingroup.controls['year'].value; let minmonth = +mingroup.controls['month'].value; let mindate = +mingroup.controls['date'].value;
					let maxyear = +maxgroup.controls['year'].value; let maxmonth = +maxgroup.controls['month'].value; let maxdate = +maxgroup.controls['date'].value;

					if (minyear) {
						if (maxyear) {
							if (maxyear < minyear) {
								error = true;
							}
							else {
								error = false;
								if (minmonth) {
									if (maxmonth) {
										if (maxyear <= minyear && maxmonth < minmonth) {
											error = true;
										}
										else {
											error = false;
											if (mindate) {
												if (maxdate) {
													if (maxyear <= minyear && maxmonth <= minmonth && maxdate < mindate) {
														error = true;
													}
												}
											}
										}
									}
								}
							}
						}
					}
					if (error) {
						return { 'invalidMax': true };
					}
				}
				return null;
			}
		}
	}

	static validateCourseExamTable(index, arrayKeyName = null): ValidatorFn {
		return (c: FormControl): { [key: string]: any } => {
			let maxValue;
			if (arrayKeyName != 'course12thCutOff') {
				if ((<FormGroup>c.root)) {
					if ((<FormGroup>c.root).controls) {
						if ((<FormArray>(<FormGroup>c.root).controls['courseExamCutOff']).controls) {
							if ((<FormGroup>(<FormArray>(<FormGroup>c.root).controls['courseExamCutOff']).controls[index]).value['exam_unit'] == 'rank' || !(<FormGroup>(<FormArray>(<FormGroup>c.root).controls['courseExamCutOff']).controls[index]).value['exam_unit']) {
								return null;
							}
							maxValue = (<FormGroup>(<FormArray>(<FormGroup>c.root).controls['courseExamCutOff']).controls[index]).value['exam_out_of'];
						}
					}
				}
			}
			else {
				maxValue = 100;
			}


			if (this.isFilled(c.value)) {
				if (+c.value <= 0) {
					return { msg: "Value should be positive non zero number" };
				}
				if (+c.value > +maxValue) {
					return { msg: "Value should be less than out of value " + maxValue };
				}
			}
			return null;
		};
	}

	static isFilled(val) {
		return val != '' && val;
	}

	static validatePartnerForm(basicInfoObj, coursePartnerForm): ValidatorFn {
		return (c: FormGroup): { [key: string]: any } => {
			let totalCalculatedDuration = 0, courseDuration = 0;
			if (basicInfoObj.contains('duration_value')) {
				courseDuration = this.returnCalculatedDuration(
					Number(basicInfoObj.controls['duration_value'].value),
					basicInfoObj.controls['duration_unit'].value
				);
			}

			if (coursePartnerForm) {
				if (coursePartnerForm.contains('course_partner_institute_flag')) {
					if (+coursePartnerForm.controls['course_partner_institute_flag'].value == 0) {
						return null;
					}
				}
			}
			if (coursePartnerForm) {
				if (coursePartnerForm.contains('partnerInstituteFormArrExit')) {
					if (coursePartnerForm.controls['partnerInstituteFormArrExit'].controls.length == 0) {
						return { msg: 'Data for Exit partner institute cannot be empty' };
					}
					for (let item of coursePartnerForm.controls['partnerInstituteFormArrExit'].controls) {
						if (item['controls']['duration_value'].value) {
							totalCalculatedDuration += My_Custom_Validators.returnCalculatedDuration(Number(item['controls']['duration_value'].value), item['controls']['duration_unit'].value);
						}
					}
				}
				if (coursePartnerForm.contains('partnerInstituteFormArr')) {
					for (let item of coursePartnerForm.controls['partnerInstituteFormArr'].controls) {
						if (Number(item['controls']['duration_value'].value) !== item['controls']['duration_value'].value) {
						}
						if (Number(item['controls']['duration_value'].value) <= 0) {
						}
						if (item['controls']['duration_value'].value) {
							totalCalculatedDuration += My_Custom_Validators.returnCalculatedDuration(Number(item['controls']['duration_value'].value), item['controls']['duration_unit'].value);
						}
					}
				}
			}
			if (courseDuration < totalCalculatedDuration) {
				return { msg: 'Sum of course partner duration should be less than course duration' };
			}
			return null;
		};
	}

	static validatePartnerFormDuration(c: FormControl): { [key: string]: any } {
		if (c.value == '' || c.value == null) {
			return { msg: 'Input duration is empty' };
		}
		if (Number(c.value) !== c.value) {
			// console.log("inside first");
			return { msg: 'Input duration is invalid' };
		}
		if (Number(c.value) <= 0) {
			// console.log("inside second");
			return { msg: 'Input duration should be a positive number' };
		}
		return null;
		// };
	}

	static returnCalculatedDuration(durationValue, durationUnit) {
		let smallestTimeUnit;
		switch (durationUnit) {
			case 'years': smallestTimeUnit = durationValue * 365 * 24;
				break;
			case 'months': smallestTimeUnit = durationValue * 30 * 24;
				break;
			case 'weeks': smallestTimeUnit = durationValue * 7 * 24;
				break;
			case 'days': smallestTimeUnit = durationValue * 24;
				break;
			case 'hours': smallestTimeUnit = durationValue;
				break;
		}
		return smallestTimeUnit;
	}

	static validateCourseFeesForm(courseFeesForm): ValidatorFn {
		return (c: FormGroup): { [key: string]: any } => {
			if (typeof c != 'undefined') {
				if (c.contains('total_fees')) {
					let categoriesArr = [];
					for (let key in c.value['total_fees_includes']) {
						if (c.value['total_fees_includes'][key] != null && c.value['total_fees_includes'][key] != '' && c.value['batch'] == '') {
							return { batchError: true };
						}
					}
					if (c.value['other_info'] != null && c.value['other_info'] != '' && c.value['batch'] == '') {
						return { batchError: true };
					}
					for (let item of (<FormArray>c.controls['total_fees']).controls) {
						for (let key in (<FormGroup>item).controls) {
							categoriesArr.push(key);
						}
						break;
					}

					for (let categoryName of categoriesArr) {
						let categoryCount = 0, categorySum = 0;

						for (let item of (<FormArray>c.controls['total_fees']).controls) {
							if ((<FormGroup>item).contains(categoryName) && (<FormGroup>c.controls['total_fees_total']).contains(categoryName)) {
								if ((<FormGroup>item).controls[categoryName].value != '' && (<FormGroup>item).controls[categoryName].value != null) {
									categoryCount++;
									categorySum += (<FormGroup>item).controls[categoryName].value;
								}
							}
						}

						if (categoryCount > 0 && c.value['batch'] == '') {
							return { batchError: true };
						}

						if (c.value['total_fees_total'] && c.value['total_fees_total'][categoryName] != '' && c.value['total_fees_total'][categoryName] != null && c.value['batch'] == '') {
							return { batchError: true };
						}
						if (c.value['total_fees_one_time_payment'] && c.value['total_fees_one_time_payment'][categoryName] != '' && c.value['total_fees_one_time_payment'][categoryName] != null && c.value['batch'] == '') {
							return { batchError: true };
						}
						if (c.value['hostel_fees'] && c.value['hostel_fees'][categoryName] != '' && c.value['hostel_fees'][categoryName] != null && c.value['batch'] == '') {
							return { batchError: true };
						}

						else if (categoryCount > 0) {
							if ((<FormGroup>c.controls['total_fees_total']).controls[categoryName].value == '' || (<FormGroup>c.controls['total_fees_total']).controls[categoryName].value == null) {
								return { msg: 'Total fees of  category ' + categoryName.toUpperCase() + ' should be filled' };
							}

							if ((<FormGroup>c.controls['total_fees_total']).controls[categoryName].value < categorySum) {
								return { msg: 'Total fees of  category ' + categoryName.toUpperCase() + ' should be greater' };
							}
						}
						if (c.controls['total_fees_total'] && c.controls['total_fees_one_time_payment'] && (<FormGroup>c.controls['total_fees_total']).controls[categoryName] && (<FormGroup>c.controls['total_fees_one_time_payment']).controls[categoryName]) {

							if (parseFloat((<FormGroup>c.controls['total_fees_total']).controls[categoryName].value) < parseFloat((<FormGroup>c.controls['total_fees_one_time_payment']).controls[categoryName].value)) {
								return { msg: 'One Time Payment fees of  category ' + categoryName.toUpperCase() + ' should be less than total ' };
							}
						}
					}
				}
			}

			return null;
		}
	}

	static validateMaxLength(_maxlength): ValidatorFn {
		return (c: FormControl): { [key: string]: any } => {
			let value = c ? c.value : null; let maxlength = _maxlength;
			if (value && maxlength) {
				return value.length > maxlength ? { 'maxlength': 'should not exceed character limit of ' + maxlength } : null;
			}
			return null;
		}
	}

	static validateMinLength(_minlength): ValidatorFn {
		return (c: FormControl): { [key: string]: any } => {
			let value = c ? c.value : null; let minlength = _minlength;
			if (value && minlength) {
				return value.length < minlength ? { 'minlength': 'should have minimum of ' + minlength + ' characters' } : null;
			}
			return null;
		}
	}

	static validatePattern(patternregex): ValidatorFn {
		return (c: FormControl): { [key: string]: any } => {
			let value = c ? c.value : null;
			if (value && patternregex) {
				let pattern = new RegExp(patternregex);
				return pattern.test(value) ? null : { 'pattern': 'Does not match the required format ' };
			}
			return null;
		}
	}

	static validateWholeNumber(_mymin, _mymax = null): ValidatorFn {
		return (c: FormControl): { [key: string]: any } => {
			let value = c ? c.value : null;
			let mymin = _mymin; let mymax = _mymax;
			if (value) {
				let pattern = /^[0-9]+$/;
				if (pattern.test(value)) {
					let valid = false; let msg;
					value = +value;
					if (mymin === null && mymax === null) {
						return null;
					}
					else if (mymin !== null && mymax !== null) {
						valid = (value >= mymin && value <= mymax) ? true : false;
						msg = { 'number': 'Range must be between ' + mymin + ' and ' + mymax };
					}
					else if (mymin !== null) {
						valid = (value > mymin) ? true : false;
						msg = { 'number': 'must be greater than ' + mymin };
					}
					else if (mymax !== null) {
						valid = (value < mymax) ? true : false;
						msg = { 'number': 'must be less than ' + mymax };
					}
					return valid ? null : msg;
				}
				return { 'number': 'Not a valid number ' };
			}
			return null;
		}
	}

	static validateNumber(_mymin, _mymax = null): ValidatorFn {
		return (c: FormControl): { [key: string]: any } => {
			let value = c ? c.value : null;
			let mymin = _mymin; let mymax = _mymax;
			if (value) {
				let pattern = /^[1-9]\d*(\.\d+)?$/;

				if (pattern.test(value)) {
					let valid = false; let msg;
					value = +value;
					if (mymin === null && mymax === null) {
						return null;
					}
					else if (mymin !== null && mymax !== null) {
						valid = (value >= mymin && value <= mymax) ? true : false;
						msg = { 'number': 'Range must be between ' + mymin + ' and ' + mymax };
					}
					else if (mymin !== null) {
						valid = (value > mymin) ? true : false;
						msg = { 'number': 'must be greater than ' + mymin };
					}
					else if (mymax !== null) {
						valid = (value < mymax) ? true : false;
						msg = { 'number': 'must be less than ' + mymax };
					}
					return valid ? null : msg;
				}
				return { 'number': 'Not a valid number ' };
			}
			return null;
		}
	}

	static ValidateString(type): ValidatorFn {
		return (c: FormControl): { [key: string]: any } => {
			let value = c ? c.value : null;
			if (value && type) {
				var isValid = true;
				var errorMsg = {};
				var pattern = /[\s\S]*/;
				switch (type) {
					case 'characterstring':
						break;
					case 'alphanumeric':
						break;
					case 'namestring':
						break;
					case 'email':
						let pattern1 = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
						if (pattern1.test(value)) {
							isValid = true;
							return null;
						}
						else {
							isValid = false;
							errorMsg = { 'email': 'Not a valid email-id ' };
						}
						break;
					case 'link':
						let pattern2 = /^((http|https|ftp):\/\/[^\s]+)?((www\.)?[-a-zA-Z0-9@:%._\+~#=]{2,256}(\.[a-z]{2,6}|:[0-9]{3,4})\b([-a-zA-Z0-9@:%_\+.~#?&\/\/=]*)?$)/i;

						if (pattern2.test(value)) {
							isValid = true;
						}
						else {
							isValid = false;
							errorMsg = { 'link': 'Not a valid link ' };
						}
						break;
					case 'shiksha_link':
						let shikshaPattern = /^((http|https):\/\/[^\s]+)((www\.)?[-a-zA-Z0-9@:%._\+~#=]{2,256}(\.[a-z]{2,6}|:[0-9]{3,4})\b([-a-zA-Z0-9@:%_\+.~#?&\/\/=]*)?$)/i;

						let pos = value.indexOf('shiksha');
						if (shikshaPattern.test(value) && pos != -1) {
							isValid = true;
						}
						else {
							isValid = false;
							errorMsg = { 'shiksha_link': 'Not a valid shiksha url ' };
						}
						break;
					case 'geo_coordinates':
						let pattern3 = /^\d{1,4}[.]?\d{0,10}$/;
						if (pattern3.test(value)) {
							isValid = true;
						}
						else {
							isValid = false;
							errorMsg = { 'geo_coordinates': 'Not a valid Coordinate ' };
						}
						break;
					case 'phone_number':
						let pattern4 = /^[\d\-\+]{1,20}$/;
						if (pattern4.test(value)) {
							isValid = true;
						}
						else {
							isValid = false;
							errorMsg = { 'phone_number': 'Not a valid Contact Number ' };
						}
						break;
				}
				if (isValid === false)
					return errorMsg;
				else
					return null;
			}
			return null;
		}
	}
}



@Directive({
	selector: '[validateOneOrNoneFormGroup][ngModelGroup]',
	providers: [
		{ provide: NG_VALIDATORS, useExisting: forwardRef(() => ValidateOneOrNoneFormGroup), multi: true }
	]
})

export class ValidateOneOrNoneFormGroup implements Validator {
	private _optional = [];
	private _must = [];
	private _validator: ValidatorFn;

	@Input() set myoptional(columns) {
		this._optional = columns.split(',');
	}
	@Input() set mymust(columns) {
		this._must = columns.split(',');
	}

	constructor( @Attribute('myoptional') myoptional: string, @Attribute('mymust') mymust: string) {
		if (myoptional && mymust) {
			this._optional = myoptional.split(',');
			this._must = mymust.split(',');
		}
	}

	validate(c: FormGroup) {
		if (typeof c != 'undefined') {
			this._validator = My_Custom_Validators.ValidateOneOrNoneFormGroup({ 'myoptional': this._optional, 'mymust': this._must });
			return this._validator(c);
		}
	}
}

@Directive({
	selector: '[mymaxlength][ngModel],[mymaxlength][formControl]',
	providers: [
		{ provide: NG_VALIDATORS, useExisting: forwardRef(() => ValidateMaxLength), multi: true }
	]
})

export class ValidateMaxLength implements Validator, OnChanges {

	private _validator: ValidatorFn;
	@Input() mymaxlength;

	ngOnChanges(changes) {
		if (changes['mymaxlength']) {
			this._createValidator();
		}
	}

	_createValidator() {
		this._validator = My_Custom_Validators.validateMaxLength(+this.mymaxlength);
	}

	validate(c: FormControl) {
		if (typeof c != 'undefined') {
			return this.mymaxlength ? this._validator(c) : null;
		}
	}
}

@Directive({
	selector: '[myminlength][ngModel],[myminlength][formControl]',
	providers: [
		{ provide: NG_VALIDATORS, useExisting: forwardRef(() => ValidateMinLength), multi: true }
	]
})

export class ValidateMinLength {

	private _validator: ValidatorFn;
	@Input() myminlength;

	ngOnChanges(changes) {
		if (changes['myminlength']) {
			this._createValidator();
		}
	}

	_createValidator() {
		this._validator = My_Custom_Validators.validateMinLength(+this.myminlength);
	}

	validate(c: FormControl) {
		if (typeof c != 'undefined') {
			return this.myminlength ? this._validator(c) : null;
		}
	}
}

@Directive({
	selector: '[myrequired][ngModel],[myrequired][formControl]',
	providers: [
		{ provide: NG_VALIDATORS, useExisting: forwardRef(() => ValidateRequired), multi: true }
	]
})

export class ValidateRequired {
	private _required = null;
	@Input() set myrequired(required) {
		this._required = !!required;
	}
	validate(c: FormControl) {
		if (typeof c != 'undefined') {
			return this.validator(c);
		}
	}

	validator(c) {
		let value = c ? c.value : null;
		if (!value && this._required || +value == 0 && this._required) {
			return { 'required': 'Field is mandatory ' };
		}
		return null;
	}
}

@Directive({
	selector: '[mypattern][ngModel],[mypattern][formControl]',
	providers: [
		{ provide: NG_VALIDATORS, useExisting: forwardRef(() => ValidatePattern), multi: true }
	]
})

export class ValidatePattern {

	private _validator: ValidatorFn;
	@Input() mypattern;

	ngOnChanges(changes) {
		if (changes['mypattern']) {
			this._createValidator();
		}
	}

	_createValidator() {
		this._validator = My_Custom_Validators.validatePattern(this.mypattern);
	}

	validate(c: FormControl) {
		if (typeof c != 'undefined') {
			return this.mypattern ? this._validator(c) : null;
		}
	}
}

@Directive({
	selector: '[mywholenumber][ngModel],[mywholenumber][formControl]',
	providers: [
		{ provide: NG_VALIDATORS, useExisting: forwardRef(() => ValidateWholeNumber), multi: true }
	]
})

export class ValidateWholeNumber {
	private _validator: ValidatorFn;
	@Input() mywholenumber;
	@Input() mymin;
	@Input() mymax;

	ngOnChanges(changes) {
		if (changes['mymin'] || changes['mymax']) {
			this._createValidator();
		}
	}

	_createValidator() {
		this._validator = My_Custom_Validators.validateWholeNumber(+this.mymin, +this.mymax);
	}

	validate(c: FormControl) {
		if (typeof c != 'undefined' && this._validator) {
			return this._validator(c);
		}
	}
}

@Directive({
	selector: '[mystring][ngModel],[mystring][formControl]',
	providers: [
		{ provide: NG_VALIDATORS, useExisting: forwardRef(() => ValidateString), multi: true }
	]
})

export class ValidateString {

	private _validator: ValidatorFn;
	@Input() mystring = 'characterstring';

	ngOnChanges(changes) {
		if (changes['mystring']) {
			this._createValidator();
		}
	}

	_createValidator() {
		this._validator = My_Custom_Validators.ValidateString(this.mystring);
	}

	validate(c: FormControl) {
		if (typeof c != 'undefined') {
			return this.mystring ? this._validator(c) : null;
		}
	}
}

export const MY_VALIDATORS = [ValidateRequired, ValidateString, ValidateWholeNumber, ValidatePattern, ValidateMaxLength, ValidateMinLength, ValidateOneOrNoneFormGroup];

