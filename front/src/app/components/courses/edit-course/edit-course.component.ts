import { Component, OnInit } from '@angular/core';
import { AuthServiceService } from "../../../auth-service.service";
import { Router, ActivatedRoute } from '@angular/router';

@Component({
  selector: 'app-edit-course',
  templateUrl: './edit-course.component.html',
  styleUrls: ['./edit-course.component.css']
})
export class EditCourseComponent implements OnInit {

  course: any = {};
  constructor(private http: AuthServiceService,
  				private router: Router,
  				private r: ActivatedRoute) { }

  ngOnInit(): void {
  	const id = this.r.snapshot.params["id"];
  	this.http.getCourse(id).subscribe(data=>{
  		this.course = data;
  	});
  }
  save(value){
    const id = this.r.snapshot.params["id"];
    this.http.editCourse(id,value).subscribe(data=>{
        this.router.navigate(['course']);
    });
  }
}
