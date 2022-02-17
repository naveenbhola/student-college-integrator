import { Entity } from '../../../Common/Classes/Entity';
type entityTypes = "institute"|"course"|"university"|"group";
import { EducationType } from './CourseEntities';


export class Course extends Entity{
	parent_entity_id:number;
	parent_entity_type:entityTypes;
	parent_entity_name:string = '';
	primary_institute_id:number;
	education_type:EducationType;
	
	addEducationType(){
		this.education_type = new EducationType();
	}
}
