import { Entity } from './Entity';
export class Location extends Entity{
	state_id:number = 0;
	state_name:string = '';
	city_id:number = 0;
	city_name:string = '';
	locality_id:number = 0;
	locality_name:string = '';

	constructor(options?:any){
		super();
		if(typeof options != 'undefined'){
			this.fillData(options);
		}
	}
}