import { Location } from '../../../Common/Classes/Location';
import { Entity } from '../../../Common/Classes/Entity';

export class AcademicStaff extends Entity{
	type = "167";
	name:string;
	current_designation:string;
	education_background:string;
	highlights:string;
	position:number;

	constructor(options?:any){
		super();
		if(typeof options != 'undefined'){
			this.fillData(options);
		}
	}
}

export class ContactDetails extends Entity{
	address:string;
	website_url:string;
	coordinates:string;
	latitude:string='';
	longitude:string='';
	admission_contact_number:number;
	admission_email:string;
	generic_contact_number:number;
	generic_email:string;
	copy_toall_flag:boolean;
	google_url:string;

	constructor(options?:any){
		super();
		if(typeof options != 'undefined'){
			this.fillData(options);
		}
	}
}

export class InstituteLocation extends Location{
	is_main_location:boolean = false;
	institute_location_id:number;
	contact_details:ContactDetails = new ContactDetails;

	constructor(options?:any){
		super(options);
		if(typeof options != 'undefined'){
			for(let i in options){
				if(typeof this[i] != 'undefined'){
					switch(i){
						case 'contact_details':
							this.contact_details = new ContactDetails(options['contact_details']);
							break;
						default:
							this[i] = options[i];
					}
				}
			}
		}
	}

	addContact(obj){
		this.contact_details = new ContactDetails(obj);
	}
}

export class InstituteEvent extends Entity{
	id:any = '';
	type:any = '';
	name:string;
	description:string;
	position:number;
	randomIdentifier = "event_"+(new Date()).getTime()+"_"+(Math.floor(Math.random() * 20));

	constructor(options?:any){
		super();
		if(typeof options != 'undefined'){
			if(typeof options.id != 'undefined')
				this.randomIdentifier = "event_"+options.id;
			else
				this.randomIdentifier = "event_"+options.position;
			this.fillData(options);
		}
	}
}

export class InstituteScholarship extends Entity{
	type:any = '';
	description:string;

	constructor(options?:any){
		super();
		if(typeof options != 'undefined'){
			this.fillData(options);
		}
	}
}

export class InstituteMedia extends Entity{
	locations:string[];
	tags:string[];
	title:string;
	media_id:string;
	media_url:string;
	media_thumb_url:string;
	position:number=1;
	type:string;
	all_locations_flag:boolean = true;

	constructor(options?:any){
		super();
		if(typeof options != 'undefined'){
			this.fillData(options);
		}
	}
}

export class InstituteCompany extends Entity{
	company_id:number;
	company_name:string;
	position:number = 1;

	constructor(options?:any){
		super();
		if(typeof options != 'undefined'){
			this.fillData(options);
		}
	}
}

export class InstituteFacility{
	id:number;
	name:string = '';
	is_present:string;
	description:any;
	display_type;
	child_facilities:any = {};

	constructor(options?:any){
		if(typeof options != 'undefined'){
			this.fillData(options);
		}
	}

	fillData(options){
		for(let i in options){
			switch(i){
				case 'id':
				case 'name':
				case 'is_present':
				case 'display_type':
				case 'description':this[i] = options[i];break;
				case 'child_facilities':
					for(let j in options[i]){
						let temp = new InstituteChildFacility(options[i][j]);
						this.child_facilities[temp['id']] = temp;
					}
					break;
			}
		}
	}
}

export class InstituteChildFacility{
	id:number;
	name:any;
	is_present:string;
	display_type;
	other_fields:any = [];
	custom_fields:any = [];
	values:any = [];

	constructor(options?:any){
		if(typeof options != 'undefined'){
			this.fillData(options);
		}
	}

	addOthers(str:string = ''){
		this.other_fields.push({'value':str});
	}

	removeOthers(index){
		this.other_fields.splice(index,1);
	}

	fillData(options){
		for(let i in options){
			switch(i){
				case 'id':
				case 'name':
				case 'is_present':
				case 'display_type':
				case 'values': this[i] = options[i];break;
				case 'custom_fields':
					for(let j in options[i]){
						this.custom_fields.push({'name': options[i][j]['name'],'value':options[i][j]['value'],'id':options[i][j]['id']});
					}
					break;
				case 'other_fields':
					for(let j in options[i]){
						this.other_fields.push({'value':options[i][j]['value']});
					}
					break;
			}
		}
	}
}