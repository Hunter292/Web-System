import { Component } from '@angular/core';
import { Router, ActivatedRoute } from '@angular/router';
import { TokenStorageService } from '../core/token-storage';
import { inject } from '@angular/core';
import { HttpClient } from '@angular/common/http';
import { Input, input } from '@angular/core';
@Component({
  selector: 'app-header',
  imports: [],
  templateUrl: './header.html',
  styleUrl: './header.css',
})
export class Header {
  constructor(
    private router: Router,
    private tokenService: TokenStorageService,
    private activatedRoute: ActivatedRoute,
    
  ){}
  private http = inject(HttpClient);
  is_admin=false;
  count:number=0;
  active=input<boolean>(false);
  ngOnInit(){
    if(!this.active) return;
    this.is_admin=this.tokenService.getUser()?.admin;
    this.http.get<any>(`${sessionStorage.getItem("apiURL")}/task/new`).subscribe({
      next: data => {
          this.count=data;
      },
      error: (error) => {
          console.error('There was an error!', error);
          return;
      }
    })
  }
  to_task(){
    this.router.navigate(['/zadania']);
  }
  to_timer(){
    this.router.navigate(['/timer']);
  }
  to_mailer(){
    this.router.navigate(['/wyliczenia']);
  }
  to_stats(){
    this.router.navigate(['/oplacalnosc']);
  }
  log_out(){
    this.tokenService.clear();
    this.router.navigate(['/login'])
  }
  to_workers(){
    this.router.navigate(['/pracownicy']);
  }
  to_income(){
    this.router.navigate(['/faktury']);
  }
  to_hr(){
    this.router.navigate(['/kadry']);
  }
  to_profile(){
    this.router.navigate(['/profil']);
  }
}
