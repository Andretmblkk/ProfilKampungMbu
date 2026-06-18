<div class="stat-card {{ $tone ?? '' }}">
    <div class="stat-icon"><i class="fa-solid {{ $icon }}"></i></div>
    <span>{{ $label }}</span>
    <strong>{{ $value }}</strong>
    @isset($meta)<small>{{ $meta }}</small>@endisset
</div>
