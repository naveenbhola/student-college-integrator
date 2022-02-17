import { Injectable } from '@angular/core';
import { Subject }    from 'rxjs/Subject';
import { BehaviorSubject }    from 'rxjs/BehaviorSubject';

@Injectable()
export class CourseDependencyService {
	private courseLevelChange = new Subject();
	public cloneSource = new BehaviorSubject({});

	courseLevelObservable = this.courseLevelChange.asObservable();

	updateCourseLevel(val) {
		this.courseLevelChange.next(val);
	}

	cloneSections(val,courseId){
		let data = {'courseId':courseId,'sections':val};
		this.cloneSource.next(data);
	}
}