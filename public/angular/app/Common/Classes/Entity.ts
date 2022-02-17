export class Entity{

	fillData(options){
		for(let i in options){
			this[i] = options[i];
		}
	}
}