<div class="fi-brand flex items-center gap-2">
    @php
        $brandLogo = config('app.brand_logo_url') ?: asset('favicon.svg');
        $brandName = config('app.brand_name', config('app.name'));
    @endphp
    <img src="{{ $brandLogo }}" alt="{{ $brandName }}" class="h-8 w-8 object-contain">
    <span class="text-base font-semibold leading-none">{{ $brandName }}</span>
    
</div>
