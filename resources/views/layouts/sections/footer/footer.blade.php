@php
$containerFooter = ($configData['contentLayout'] === 'compact') ? 'container-xxl' : 'container-fluid';
@endphp

<!-- Footer -->
<footer class="content-footer footer bg-footer-theme">
  <div class="{{ $containerFooter }}">
    <div class="footer-container d-flex align-items-center justify-content-between py-4 flex-md-row flex-column">
      <div class="text-body mb-2 mb-md-0">
        © <script>document.write(new Date().getFullYear())</script>,
        {{ config('variables.templateName') }} &middot; Aplikasi pemesanan tiket bus online.
      </div>
      <div class="d-none d-lg-inline-block">
        <a href="{{ route('tickets.index') }}" class="footer-link me-4">Cari Tiket</a>
        @if (config('variables.socialInstagram'))
          <a href="{{ config('variables.socialInstagram') }}" target="_blank" class="footer-link me-4">Instagram</a>
        @endif
        @if (config('variables.contactEmail'))
          <a href="mailto:{{ config('variables.contactEmail') }}" class="footer-link">{{ config('variables.contactEmail') }}</a>
        @endif
      </div>
    </div>
  </div>
</footer>
<!--/ Footer -->