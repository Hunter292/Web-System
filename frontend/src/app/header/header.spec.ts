import { ComponentFixture, TestBed } from '@angular/core/testing';
import { provideHttpClient } from '@angular/common/http';
import { provideRouter, Router } from '@angular/router';
import { Header } from './header';
import { TokenStorageService } from '../core/token-storage';

describe('Header', () => {
  let component: Header;
  let fixture: ComponentFixture<Header>;
  let router: Router;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      imports: [Header],
      providers: [
        provideHttpClient(),
        provideRouter([]),
        { provide: TokenStorageService, useValue: { getUser: () => null, clear: () => {} } },
      ],
    }).compileComponents();

    router = TestBed.inject(Router);
    fixture = TestBed.createComponent(Header);
    component = fixture.componentInstance;
    fixture.detectChanges();
    await fixture.whenStable();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });

  it('should navigate to /home when clicking the rose logo', () => {
    const navigateSpy = vi.spyOn(router, 'navigate');
    const logoBtn: HTMLButtonElement | null = fixture.nativeElement.querySelector('.top-banner button');
    expect(logoBtn).toBeTruthy();
    logoBtn!.click();
    expect(navigateSpy).toHaveBeenCalledWith(['/home']);
  });
});
