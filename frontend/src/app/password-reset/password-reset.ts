import { Component } from '@angular/core';
import { FormsModule, ReactiveFormsModule,FormBuilder, FormGroup, Validators } from '@angular/forms';
import { Injectable, inject } from '@angular/core';
import { HttpClient } from '@angular/common/http';
import { ChangeDetectorRef } from '@angular/core';
import { Router, ActivatedRoute } from '@angular/router';
import { switchMap } from 'rxjs';

@Component({
  selector: 'app-password-reset',
  imports: [
     FormsModule,
    ReactiveFormsModule
  ],
  templateUrl: './password-reset.html',
  styleUrl: './password-reset.css',
})
export class PasswordReset {
  private http=inject(HttpClient);
   private readonly headers = {
    Accept: 'application/json',
  };
  inputForm: FormGroup;
  error_message: string | null = null;
  success=false;
  loading = false;
   constructor(
      private fb: FormBuilder,
      private router: Router,
      private route:ActivatedRoute,
      private changeDetectorRef: ChangeDetectorRef,
    ){
      this.inputForm = this.fb.group({
        email: ['', [Validators.required,Validators.email]],
        password: ['', [Validators.required,Validators.minLength(6)]],
        password2: ['',Validators.required],
      });
    }
    reset_password(){
    if (this.inputForm.invalid){
      if(this.inputForm.get('email')?.hasError('email') || this.inputForm.get('email')?.hasError('required')) this.error_message="Invalid email";
      if(this.inputForm.get('password')?.hasError('minlength')|| this.inputForm.get('password')?.hasError('required')) this.error_message="Password is too short";
      this.changeDetectorRef.detectChanges();
      return;
    }
    this.loading = true;
    const { email, password,password2} = this.inputForm.value;
    if(password!=password2){
      this.loading=false;
      this.error_message="Hasła muszą się zgadzać";
      return;
    }
    let token:string|null="";
    this.route.paramMap.subscribe((obs) => {
      token=obs.get('token');
    });
    
    this.http.post<any>(`${sessionStorage.getItem("apiURL")}/reset-password`,{email:email,password:password, password_confirmation:password2,token:token}
      ).subscribe({
        next: data => {
            this.loading=false;
            this.error_message="";
            this.success=true;
            this.changeDetectorRef.detectChanges();
        },
        error: (error) => {
            this.error_message = error.error.detail;
            console.error('There was an error!', error);
            this.loading=false;
            return;
        }
    });
  }
  to_login(){
    this.router.navigate(['/login']);
  }
}
