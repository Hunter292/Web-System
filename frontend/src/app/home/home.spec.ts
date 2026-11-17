import { ComponentFixture, TestBed } from '@angular/core/testing';
import { provideRouter } from '@angular/router';
import { Home } from './home';
import { AuthService } from '../core/auth';
import { TokenStorageService } from '../core/token-storage';

describe('Home', () => {
  let component: Home;
  let fixture: ComponentFixture<Home>;
  let isAdminUser = true;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      imports: [Home],
      providers: [
        provideRouter([]),
        { provide: AuthService, useValue: { check_login: () => {} } },
        { provide: TokenStorageService, useValue: { getUser: () => ({ admin: isAdminUser }), clear: () => {} } },
      ],
    }).compileComponents();

    fixture = TestBed.createComponent(Home);
    component = fixture.componentInstance;
    fixture.detectChanges();
    await fixture.whenStable();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });

  it('should group admin tiles by domain color', () => {
    isAdminUser = true;
    const f = TestBed.createComponent(Home);
    f.detectChanges();
    const el: HTMLElement = f.nativeElement;

    expect(el.querySelectorAll('.tile-btn--mint').length).toBe(3);
    expect(el.querySelectorAll('.tile-btn--primary').length).toBe(2);
    expect(el.querySelectorAll('.tile-btn--amber').length).toBe(2);
    expect(el.querySelectorAll('.tile-btn--muted').length).toBe(1);
    expect(el.querySelectorAll('.tile-btn--neutral').length).toBe(1);
  });

  it('should group non-admin tiles into daily work and account', () => {
    isAdminUser = false;
    const f = TestBed.createComponent(Home);
    f.detectChanges();
    const el: HTMLElement = f.nativeElement;

    expect(el.querySelectorAll('.tile-btn--mint').length).toBe(3);
    expect(el.querySelectorAll('.tile-btn--primary').length).toBe(0);
    expect(el.querySelectorAll('.tile-btn--amber').length).toBe(0);
    expect(el.querySelectorAll('.tile-btn--muted').length).toBe(1);
    expect(el.querySelectorAll('.tile-btn--neutral').length).toBe(1);
  });
});
