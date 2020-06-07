import { BrowserModule } from '@angular/platform-browser';
import { NgModule } from '@angular/core';
import { RouterModule } from "@angular/router"
import {ROUTES} from "./app.routes"
import { HttpClientModule } from "@angular/common/http";
import { FormsModule } from "@angular/forms";

import { AppComponent } from './app.component';
import { HomeComponent } from './components/home/home.component';
import { NavbarComponent } from './components/shared/navbar/navbar.component';
import { EditCourseComponent } from './components/courses/edit-course/edit-course.component';
import { AddCourseComponent } from './components/courses/add-course/add-course.component';
import { ListCourseComponent } from './components/courses/list-course/list-course.component';
import { EditStudentComponent } from './components/students/edit-student/edit-student.component';
import { AddStudentComponent } from './components/students/add-student/add-student.component';
import { ListStudentComponent } from './components/students/list-student/list-student.component';


@NgModule({
  declarations: [
    AppComponent,
    HomeComponent,
    NavbarComponent,
    EditCourseComponent,
    AddCourseComponent,
    ListCourseComponent,
    EditStudentComponent,
    AddStudentComponent,
    ListStudentComponent
  ],
  imports: [
    BrowserModule,
    RouterModule.forRoot(ROUTES, {useHash: true}),
    HttpClientModule,
    FormsModule
  ],
  providers: [],
  bootstrap: [AppComponent]
})
export class AppModule { }
