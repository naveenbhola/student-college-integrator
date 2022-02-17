import { Component, OnInit } from '@angular/core';
import { Router, ActivatedRoute, ROUTER_DIRECTIVES } from '@angular/router';
import { getHtmlVersion } from '../../../Common/utils/htmlVersioning';


@Component({
    selector: 'coursePostingCreateTemp',
    templateUrl: '/public/angular/app/Listing/CoursePosting/views/'+getHtmlVersion('coursePostingCreateTemp.component.html'),
    directives: [ROUTER_DIRECTIVES]
})

export class CoursePostingCreateTempComponent implements OnInit {

	constructor(
        public route: ActivatedRoute,
        public router: Router
	) {

	}
	ngOnInit() {
		this.router.navigate(['/nationalCourse/CoursePosting/create']);
	}
}