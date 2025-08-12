@php
    /** @var \App\Models\Announcement|null $active */
    $active = \App\Models\Announcement::query()
        ->where('is_active', true)
        ->latest('id')
        ->first();
    $message = $active?->information;
@endphp

@if ($active && $message)
    <div id="announcementBar" class="w-full bg-[#c30101] border-b-4 border-black text-white overflow-hidden">
        <div class="container mx-auto px-4 py-2">
            <div class="relative w-full overflow-hidden" aria-live="polite">
                <div class="whitespace-nowrap animate-marquee will-change-transform text-sm md:text-base font-extrabold tracking-wide drop-shadow">
                    <span class="mx-8">{{ $message }}</span>
                    <span class="mx-8">{{ $message }}</span>
                    <span class="mx-8">{{ $message }}</span>
                    <span class="mx-8">{{ $message }}</span>
                </div>
            </div>
        </div>
    </div>

    <style>
    @keyframes marquee {
      0% { transform: translateX(0); }
      100% { transform: translateX(-100%); }
    }
    .animate-marquee {
      display: inline-block;
      min-width: 200%;
      animation: marquee 55s linear infinite;
    }
    </style>
@endif
