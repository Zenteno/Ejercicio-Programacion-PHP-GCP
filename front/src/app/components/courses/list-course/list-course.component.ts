import { Component, OnInit } from '@angular/core';
import { AuthServiceService } from "../../../auth-service.service";


@Component({
  selector: 'app-list-course',
  templateUrl: './list-course.component.html',
  styleUrls: ['./list-course.component.css']
})
export class ListCourseComponent implements OnInit {

  courses: any = []; 

  constructor( private auth: AuthServiceService) { }

  ngOnInit(): void {
  	  this.auth.listCourses().subscribe((courses: any)=>{
  	    this.courses = courses;
  	  });
  }


}
