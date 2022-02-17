import { Entity } from '../../../Common/Classes/Entity';

export class EducationType extends Entity{
	type:number             = 0;
	schedule:any            = [];
	delivery_method:number  = 0;
	time_of_learning:number = 0;
}