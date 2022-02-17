import { Component, Pipe, PipeTransform, Injectable } from '@angular/core';
import { InstituteLocation } from '../Listing/InstitutePosting/Classes/InstituteEntities';
@Pipe({
	name: 'arraysearch',
	pure: false
})
@Injectable()
export class ArraySearchPipe implements PipeTransform {
	transform(items: any[], keyword: any, searchColumns: any): any {
		if (!keyword || !items) {
			return items;
		}

		keyword = new RegExp(keyword.replace(/([.?*+^$[\]\\(){}|-])/g, "\\$1"), 'i');
		searchColumns = searchColumns.split(',');
		for (let i in items) {
			if (typeof items[i] != "object") {
				return items.filter(item => { return item.search(keyword) > -1; });
			}
			else {
				return items.filter(item => {
					for (let key in item) {
						if (searchColumns.length) {
							if ((searchColumns.indexOf(key) > -1) && item[key] && (item[key].search(keyword) > -1)) { return true; }
						}
						else if (item) {
							return item.search(keyword) > -1;
						}
					}
				});
			}
		}
	}
}

@Pipe({
	name: 'arrayset',
	pure: false
})
@Injectable()
export class ArraySetPipe implements PipeTransform {
	transform(items: any[], column: any): any {
		var set = new Set();
		if (!items) {
			return items;
		}

		items.forEach((item) => {
			if (typeof item == 'object' && item[column]) {
				set.add(item[column]);
			}
		});

		return Array.from(set);
	}
}

@Pipe({
	name: 'limitarr',
	pure: false
})
@Injectable()
export class LimitArrayPipe implements PipeTransform {
	transform(items: any[], length: number): any {
		if (!length || !items) {
			return items;
		}
		else {
			return items.slice(0, length);
		}
	}
}

@Pipe({
	name: 'sortArrByColumn',
	pure: false
})
@Injectable()
export class SortArrayPipe implements PipeTransform {
	transform(items: any[], order: string = 'asc', sortColumn: string): any {
		if (!items) {
			return items;
		}
		if (typeof items[0] != "object") {
			if (!isNaN(parseInt(items[0]))) {
				items.sort(function(a, b) { return parseInt(a) - parseInt(b); });
			}
			else {
				items.sort();
			}
		}
		else {
			var sortColumnArr = sortColumn.split('.'), val1, val2, sortColumnArrLength = sortColumnArr.length, val1String: string, val2String: string;
			items.sort(function(a, b) {
				if (!isNaN(parseInt(a[sortColumn]))) {
					return parseInt(a[sortColumn]) - parseInt(b[sortColumn]);
				}
				else {

					if (sortColumnArr.length > 1) {
						val1String = "a", val2String = "b";
						for (var i = 0; i < sortColumnArrLength; i++) {
							val1String += "['" + sortColumnArr[i] + "']";
							val2String += "['" + sortColumnArr[i] + "']";
						}
						val1 = eval(val1String);
						val2 = eval(val2String);
					}
					else {
						var val1 = a[sortColumn];
						var val2 = b[sortColumn];
					}
					if (val1.toLowerCase() == val2.toLowerCase()) {
						return 0;
					}
					return val1.toLowerCase() < val2.toLowerCase() ? -1 : 1;
				}
			});
		}
		if (order == 'asc') {
			return items;
		}
		return items.reverse();
	}
}

@Pipe({
	name: 'rangearray',
	pure: false
})
@Injectable()
export class RangeArrayPipe implements PipeTransform {
	transform(size: number, start: number = 0, order: string = 'desc'): any {
		let returnArr = [];
		if (!size) {
			return returnArr;
		}
		if (order == 'desc') {
			let i = start + size;
			while (i > start) {
				returnArr.push(i);
				i--;
			}
			return returnArr;
		}
		else {
			return new Array(size).fill(0).map((val, index) => { return index + start });
		}
	}
}



@Pipe({
	name: 'mapToIterable'
})
export class MapToIterable {
	transform(dict: Object): any {
		var a = [];
		for (var key in dict) {
			if (dict.hasOwnProperty(key)) {
				a.push({ key: key, val: dict[key] });
			}
		}
		return a;
	}
}

@Pipe({
	name: 'slicearray',
	pure: false
})
@Injectable()
export class SliceArrayPipe implements PipeTransform {
	transform(items: any[], start: number, end: number): any {
		if (typeof end == 'undefined') {
			return items.slice(start);
		} else {
			//console.log(start);
			//return items.slice(start,end);
		}

	}
}


@Pipe({
	name: 'locationObjectHierarchy'
})
export class LocationObjectHierarchy {
	transform(dict: Object): any {
		var a = [], b = {}, key;
		for (var arrkey in dict) {
			key = dict[arrkey].city_id;
			dict[arrkey]['localities'] = [];

			if (typeof b[key] === 'undefined' && dict[arrkey].locality_id !== 0) {
				var tempLoc = new InstituteLocation();
				tempLoc = JSON.parse(JSON.stringify(dict[arrkey]));
				b[key] = tempLoc;
			}
			else if (typeof b[key] === 'undefined') {
				b[key] = dict[arrkey];
			}

			if (dict[arrkey].locality_id !== 0)
				b[key]['localities'].push(dict[arrkey]);
		}

		for (let key in b) {
			if (b.hasOwnProperty(key)) {
				a.push({ key: key, val: b[key] });
			}
		}
		return a;
	}
}