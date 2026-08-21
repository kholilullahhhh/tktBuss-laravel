@php
$configData = Helper::appClasses();
$isFront = true;
@endphp

@section('layoutContent')

@extends('layouts/commonMaster')

@php
use Illuminate\Support\Facades\Auth;
$currentRouteName = request()->route()?->getName();
@endphp

<!-- Public Navbar: Start -->
<nav class="layout-navbar container shadow-none py-0">
  <div class="navbar navbar-expand-lg landing-navbar border-top-0 px-4 px-md-8">
    <div class="navbar-brand app-brand demo d-flex py-0 py-lg-2 me-6">
      <button class="navbar-toggler border-0 px-0 me-2" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <i class="tf-icons ri-menu-fill ri-24px align-middle"></i>
      </button>
      <a href="{{ url('/') }}" class="app-brand-link">
        <span class="app-brand-logo demo">
          @if (config('variables.templateLogo'))
            <img src="{{ asset('storage/' . config('variables.templateLogo')) }}" alt="Logo" height="30">
          @else
            @include('_partials.macros', ['width' => 25, 'withbg' => 'var(--bs-primary)'])
          @endif
        </span>
        <span class="app-brand-text demo menu-text fw-semibold ms-2 ps-1">{{ config('variables.templateName') }}</span>
      </a>
    </div>
    <div class="collapse navbar-collapse landing-nav-menu" id="navbarSupportedContent">
      <button class="navbar-toggler border-0 text-heading position-absolute end-0 top-0 scaleX-n1-rtl" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <i class="tf-icons ri-close-fill"></i>
      </button>
      <ul class="navbar-nav me-auto p-4 p-lg-0">
        <li class="nav-item">
          <a class="nav-link fw-medium {{ $currentRouteName === 'tickets.index' || $currentRouteName === 'tickets.search' ? 'active' : '' }}" href="{{ route('tickets.index') }}">Cari Tiket</a>
        </li>
        @auth
          <li class="nav-item">
            <a class="nav-link fw-medium {{ $currentRouteName === 'customer.bookings' ? 'active' : '' }}" href="{{ route('customer.bookings') }}">Booking Saya</a>
          </li>
          <li class="nav-item">
            <a class="nav-link fw-medium {{ $currentRouteName === 'customer.tickets' ? 'active' : '' }}" href="{{ route('customer.tickets') }}">Tiket Saya</a>
          </li>
        @endauth
      </ul>
    </div>
    <div class="landing-menu-overlay d-lg-none"></div>
    <ul class="navbar-nav flex-row align-items-center ms-auto">
      @auth
        <li class="nav-item dropdown">
          <a class="nav-link fw-medium d-flex align-items-center" href="javascript:void(0);" data-bs-toggle="dropdown">
            <span class="avatar avatar-xs me-2 flex-shrink-0">
              @if (auth()->user()->avatar)
                <img src="{{ asset('storage/' . auth()->user()->avatar) }}" alt="Avatar" class="rounded-circle">
              @else
                <span class="avatar-initial rounded-circle bg-primary">{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</span>
              @endif
            </span>
            <span class="d-none d-md-inline-block">{{ auth()->user()->name }}</span>
          </a>
          <ul class="dropdown-menu dropdown-menu-end">
            <li>
              <a class="dropdown-item" href="{{ route('customer.dashboard') }}"><i class="ri-dashboard-line me-2"></i>Dashboard</a>
            </li>
            <li>
              <a class="dropdown-item" href="{{ route('profile.index') }}"><i class="ri-user-3-line me-2"></i>Profil</a>
            </li>
            <li>
              <hr class="dropdown-divider">
            </li>
            <li>
              <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="dropdown-item"><i class="ri-logout-box-line me-2"></i>Keluar</button>
              </form>
            </li>
          </ul>
        </li>
      @else
        <li>
          <a href="{{ route('login') }}" class="btn btn-outline-primary px-3 me-2"><span class="tf-icons ri-login-circle-line me-md-1"></span><span class="d-none d-md-inline-block">Masuk</span></a>
        </li>
        <li>
          <a href="{{ route('register') }}" class="btn btn-primary px-3"><span class="tf-icons ri-user-add-line me-md-1"></span><span class="d-none d-md-inline-block">Daftar</span></a>
        </li>
      @endauth
    </ul>
  </div>
</nav>
<!-- Public Navbar: End -->

<!-- Sections:Start -->
@yield('content')
<!-- / Sections:End -->

<!-- Public Footer: Start -->
<footer class="footer front-footer bg-body text-muted">
  <div class="container py-4">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-center gap-2">
      <div>
        <span class="fw-bold text-heading">{{ config('variables.templateName') }}</span> &mdash; Aplikasi pemesanan tiket bus online.
      </div>
      <div class="d-flex gap-4">
        <a href="{{ route('tickets.index') }}" class="footer-link">Cari Tiket</a>
        @if (config('variables.socialInstagram'))
          <a href="{{ config('variables.socialInstagram') }}" target="_blank" rel="noopener" class="footer-link"><i class="ri-instagram-line me-1"></i>Instagram</a>
        @endif
        @if (config('variables.contactEmail'))
          <a href="mailto:{{ config('variables.contactEmail') }}" class="footer-link"><i class="ri-mail-line me-1"></i>{{ config('variables.contactEmail') }}</a>
        @endif
      </div>
    </div>
    <div class="text-center mt-3 small">
      &copy; {{ date('Y') }} {{ config('variables.templateName') }}. Hak cipta dilindungi.
    </div>
  </div>
</footer>
<!-- Public Footer: End -->
@endsection