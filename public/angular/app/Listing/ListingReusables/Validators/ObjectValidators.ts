import { Directive,forwardRef,Input,Attribute,Output,EventEmitter } from '@angular/core';
import { NgForm,NG_VALIDATORS,FormGroup,FormControl,Validator,ValidatorFn }    from '@angular/forms';

export class My_OBJECT_VALIDATORS{
	static ValidateOneOrNoneCustomControlArray(inputObj = {}):ValidatorFn{
		return (c:FormControl) : {[key: string]: any} => {
			if(typeof c != 'undefined' && c.value && inputObj['mymust'].length){
				let _optional = inputObj['myoptional'];
				let _must = inputObj['mymust'];

				let columns = _optional.concat(_must);let proceed;let value = c.value;
				let returnObj = {};
				value.forEach((val,index) => {
					proceed = false;
					columns.forEach((col) => {
						if(val && val[col] && val[col].trim()){
							proceed = true;
						}
					});
					if(proceed){
						let errors = [];
						_must.forEach((col) => {
							if(!val[col] || !val[col].trim()){
								errors.push(col);
							}
						});
						if(errors.length){
							let errorObj = {};
							for(let i of errors){
								errorObj[i] = {valid : false};
							}
							returnObj[index] = errorObj;
						}
					}
				});
				if(Object.keys(returnObj).length){
					return {'validateOneOrNoneCustomControlArray':returnObj};
				}
				return null;
			}
		}
	}

	static ValidateMaxLength(inputObj = {}):ValidatorFn{
		return (c:FormControl) : {[key: string]: any} => {
			if(typeof c != 'undefined' && c.value && inputObj['mycolumns'].length){
				let _columns = inputObj['mycolumns'];
				let _counts = inputObj['mycounts'];

				let returnObj = {};
				c.value.forEach((val1,index1) => {
					let errors = [];
					_columns.forEach((val,index) => {
						if(val1[val] && val1[val].length > _counts[index]){
							errors.push(val);
						}
					});
					if(errors.length){
						let errorObj = {};
						for(let i of errors){
							errorObj[i] = {'maxlength' : 'should not exceed '+_counts[_columns.indexOf(i)]+' characters'};
						}
						returnObj[index1] = errorObj;
					}
				});
				
				if(Object.keys(returnObj).length){
					return {'validateMaxLength' : returnObj};
				}
			}
			return null;
		}
	}

	static validateMaxLengthObject(inputObj = {}):ValidatorFn{
		return (c:FormControl) : {[key: string]: any} => {
			if(typeof c != 'undefined' && c.value && inputObj['mycolumns'].length){
				let _columns = inputObj['mycolumns'];
				let _counts = inputObj['mycounts'];
				let returnObj = {};

				for(let i in c.value)
				{
					for(let j in c.value[i].child_facilities)
					{
						if(c.value[i].child_facilities[j].other_fields.length > 0)
						{
							let check =  c.value[i].child_facilities[j].other_fields;
							check.forEach((val1,index1) => {

							let errors = [];
							_columns.forEach((val,index) => {
								if(val1[val] && val1[val].length > _counts[index]){
									errors.push(val);
								}
							});			
							if(errors.length){
								let errorObj = {};
								for(let i of errors){
									errorObj[i] = {'maxlength' : 'should not exceed '+_counts[_columns.indexOf(i)]+' characters'};
								}
									returnObj[i+'_'+index1] = errorObj;
								}
						});

						
					}
				}
			
					
				}
				if(Object.keys(returnObj).length){
					return {'validateMaxLengthObject' : returnObj};
				}
			}
			return null;
		}
	}

	static ValidateMinLength(inputObj = {}):ValidatorFn{
		return (c:FormControl) : {[key: string]: any} => {
			if(typeof c != 'undefined' && c.value && inputObj['mycolumns'].length){
				let _columns = inputObj['mycolumns'];
				let _counts = inputObj['mycounts'];

				let returnObj = {};
				c.value.forEach((val1,index1) => {
					let errors = [];
					_columns.forEach((val,index) => {
						if(val1[val] && val1[val].length < _counts[index]){
							errors.push(val);
						}
					});
					if(errors.length){
						let errorObj = {};
						for(let i of errors){
							errorObj[i] = {'minlength' : 'should have minimum of '+_counts[_columns.indexOf(i)]+' characters'};
						}
						returnObj[index1] = errorObj;
					}
				});
				
				if(Object.keys(returnObj).length){
					return {'validateMinLength' : returnObj};
				}
			}
			return null;
		}
	}
}

@Directive({
    selector: '[validateOneOrNoneCustomControlArray][ngModel]',
    providers: [
        {provide : NG_VALIDATORS, useExisting: forwardRef(() => ValidateOneOrNoneCustomControlArray), multi: true}
    ]
})

export class ValidateOneOrNoneCustomControlArray implements Validator{
	private _optional = [];
	private _must = [];
	private _validator: ValidatorFn;

	constructor(@Attribute('myoptional') myoptional:string, @Attribute('mymust') mymust:string){
		this._optional = myoptional.split(',');
		this._must 	   = mymust.split(',');
	}

    validate(c:FormControl){
    	if(typeof c != 'undefined'){
    		this._validator = My_OBJECT_VALIDATORS.ValidateOneOrNoneCustomControlArray({'myoptional':this._optional,'mymust':this._must});
    		return this._validator(c);
    	}
    }
}

@Directive({
    selector: '[validateMaxLength][ngModel]',
    providers: [
        {provide : NG_VALIDATORS, useExisting: forwardRef(() => ValidateMaxLength), multi: true}
    ]
})

export class ValidateMaxLength implements Validator{
	private _columns = [];
	private _count = [];
	private _validator: ValidatorFn;

	constructor(@Attribute('mymaxcol') cols:string, @Attribute('mymaxcount') counts:string){
		this._columns = cols.split(',');
		this._count 	   = counts.split(',');
	}

	validate(c:FormControl){
		if(typeof c != 'undefined'){
			this._validator = My_OBJECT_VALIDATORS.ValidateMaxLength({'mycolumns':this._columns,'mycounts':this._count});
			return this._validator(c);
		}
	}
}

@Directive({
    selector: '[validateMinLength][ngModel]',
    providers: [
        {provide : NG_VALIDATORS, useExisting: forwardRef(() => ValidateMinLength), multi: true}
    ]
})
export class ValidateMinLength implements Validator{
	private _columns = [];
	private _count = [];
	private _validator: ValidatorFn;

	constructor(@Attribute('mymincol') cols:string, @Attribute('mymincount') counts:string){
		this._columns = cols.split(',');
		this._count 	   = counts.split(',');
	}

	validate(c:FormControl){
		if(typeof c != 'undefined'){
			this._validator = My_OBJECT_VALIDATORS.ValidateMinLength({'mycolumns':this._columns,'mycounts':this._count});
			return this._validator(c);
		}
	}
}


@Directive({
    selector: '[validateMaxLengthObject][ngModel]',
    providers: [
        {provide : NG_VALIDATORS, useExisting: forwardRef(() => validateMaxLengthObject), multi: true}
    ]
})
export class validateMaxLengthObject implements Validator{
	private _columns = [];
	private _count = [];
	private _validator: ValidatorFn;

	constructor(@Attribute('mymaxcol') cols:string, @Attribute('mymaxcount') counts:string){
		this._columns = cols.split(',');
		this._count 	   = counts.split(',');
	}

	validate(c:FormControl){
		if(typeof c != 'undefined'){
			this._validator = My_OBJECT_VALIDATORS.validateMaxLengthObject({'mycolumns':this._columns,'mycounts':this._count});
			return this._validator(c);
		}
	}
}



export const MY_OBJ_VALIDATORS = [ValidateOneOrNoneCustomControlArray,ValidateMaxLength,ValidateMinLength,validateMaxLengthObject];
