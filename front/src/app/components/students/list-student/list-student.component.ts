import { Component, OnInit } from '@angular/core';
import { AuthServiceService } from "../../../auth-service.service";

@Component({
  selector: 'app-list-student',
  templateUrl: './list-student.component.html',
  styleUrls: ['./list-student.component.css']
})
export class ListStudentComponent implements OnInit {

  constructor(private auth: AuthServiceService) { }

  students: any = [];

  ngOnInit(): void {
  	this.auth.listStudents().subscribe((students: any)=>{
  	 	this.students = students;
  	 });
  }

}
