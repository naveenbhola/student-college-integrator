import { Component,OnInit,Output, EventEmitter,Input,Injectable } from '@angular/core';

@Component({
	selector:'populargroup',
})

@Injectable()
export class PopularGroup{
	name:string = '';
	alias:string = '';
	synonym:string = '';
	hierarchyMapping = [];
}