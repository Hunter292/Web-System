import { Component } from '@angular/core';
import { NgIf } from '@angular/common';
import { FormsModule,FormBuilder, FormGroup, Validators, ReactiveFormsModule } from '@angular/forms';
import { Router, ActivatedRoute } from '@angular/router';
import { AuthService } from '../core/auth';
import { Injectable, inject } from '@angular/core';
import { HttpClient } from '@angular/common/http';
import { ChangeDetectorRef } from '@angular/core';
import { TokenStorageService } from './../core/token-storage';

@Component({
  selector: 'app-login',
  imports: [
    NgIf,
    FormsModule,
    ReactiveFormsModule
  ],
  templateUrl: './login.html',
  styleUrl: './login.css',
  
})
@Injectable({providedIn: 'root'})
export class Login {
  loginForm: FormGroup;
  errorMessage: string | null = null;
  returnUrl: string = '';
  loading = false;
  reset=false;
  message="";
  auth2F=false;
  private http = inject(HttpClient);
   constructor(
    private changeDetectorRef: ChangeDetectorRef,
    private fb: FormBuilder,
    private authService: AuthService,
    private router: Router,
    private route: ActivatedRoute,
    private tokenStorage: TokenStorageService,
    
  ) {
    this.loginForm = this.fb.group({
      email: ['', [Validators.required, Validators.email]],
      password: ['', Validators.minLength(4)],
    });


    this.returnUrl = this.route.snapshot.queryParams['returnUrl'] || '/home';
  }
  log_in(){
    if (this.loginForm.invalid){
      if(this.loginForm.get('email')?.hasError('email') || this.loginForm.get('email')?.hasError('required')) this.errorMessage="Invalid email";
      if(this.loginForm.get('password')?.hasError('minlength')|| this.loginForm.get('password')?.hasError('required')) this.errorMessage="Password is too short";
      this.changeDetectorRef.detectChanges();
      return;
    }
    this.loading = true;

    const { email, password} = this.loginForm.value;
    this.authService.login(email, password).subscribe({
      next: (user) => {
        this.tokenStorage.saveUser(user);
        sessionStorage.setItem("logged","yes");
        this.router.navigateByUrl(this.returnUrl);
        // this.loading = false;
        // this.errorMessage = '';
        // this.auth2F=true;
        // this.changeDetectorRef.detectChanges();
      },
      error: (error) => {
        this.errorMessage = 'Nieprawidłowy login lub hasło';
        this.loginForm.get('password')?.reset();
        this.loading = false;
        this.changeDetectorRef.detectChanges();
      }
    });
    
  }
  log_in2F(){
    this.loading = true;
    const { email, password} = this.loginForm.value;
    let input=document.getElementById('auth_code') as HTMLInputElement;
    let code=input.value;
    this.http.post<any>(`${sessionStorage.getItem("apiURL")}/login/2F`,{email:email,password:password,otp:code}).subscribe({
        next: user => {
          this.tokenStorage.saveUser(user);
          sessionStorage.setItem("logged","yes");
          this.router.navigateByUrl(this.returnUrl);
        },
        error: (error) => {
            this.errorMessage = error.error.detail;
            console.error('There was an error!', error);
            this.loading=false;
            return;
        }
    })
  }
  send_reset(){
    let input=document.getElementById('reset_email') as HTMLInputElement;
    let email=input.value;
     this.http.post<any>(`${sessionStorage.getItem("apiURL")}/forgot-password`,{email:email}).subscribe({
        next: data => {
            this.loading=false;
            this.errorMessage="";
            this.message="Mail został wysłany";
            input.value="";
            this.changeDetectorRef.detectChanges();
        },
        error: (error) => {
            this.errorMessage = error.error.detail;
            console.error('There was an error!', error);
            this.loading=false;
            return;
        }
    })
  }
  reset_password(){
    this.reset=true;
    console.log(this.reset);
    this.changeDetectorRef.detectChanges();
  }
}
