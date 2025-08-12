<!-- Frontend Footer Component -->
@php
    /** @var \App\Models\Footer|null $footer */
    $footer = \App\Models\Footer::query()
        ->where('is_active', true)
        ->latest('id')
        ->first();
@endphp

@if ($footer)
    <footer class="bg-white border-t border-black py-8">
        <div class="container mx-auto px-4">
            <div class="text-center text-black prose max-w-none">
                {!! $footer->content !!}
            </div>
        </div>
    </footer>
@endif