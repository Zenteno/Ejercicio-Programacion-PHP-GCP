import { Component, OnInit } from '@angular/core';
import { ApiService } from "../../../service/api.service";

@Component({
  selector: 'app-navbar',
  templateUrl: './navbar.component.html',
  styleUrls: ['./navbar.component.css']
})
export class NavbarComponent implements OnInit {

  constructor(private api: ApiService) { }

  ngOnInit(): void {
  }
  tokenize(): void {
  	this.api.tokenizer();
  }
}
