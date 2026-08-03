@php
    $colorMap = [
        'orange'  => ['bg' => 'bg-orange-50',  'text' => 'text-orange-600',  'badgeBg' => 'bg-orange-50',  'badgeText' => 'text-orange-700'],
        'emerald' => ['bg' => 'bg-emerald-50', 'text' => 'text-emerald-600', 'badgeBg' => 'bg-emerald-50', 'badgeText' => 'text-emerald-700'],
        'purple'  => ['bg' => 'bg-purple-50',  'text' => 'text-purple-600',  'badgeBg' => 'bg-purple-50',  'badgeText' => 'text-purple-700'],
        'amber'   => ['bg' => 'bg-amber-50',   'text' => 'text-amber-600',   'badgeBg' => 'bg-amber-50',   'badgeText' => 'text-amber-700'],
    ];
    $c = $colorMap[$color] ?? $colorMap['orange'];

    $iconPaths = [
        'boxes' => 'M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10',
        'check' => 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z',
        'clock' => 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z',
        'alert' => 'M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z',
    ];
    $path = $iconPaths[$icon] ?? $iconPaths['boxes'];
@endphp

<div class="bg-white p-5 rounded-xl border border-slate-200/80 shadow-sm hover:shadow-md hover:-translate-y-0.5 transition-all duration-200">
    <div class="flex items-center justify-between mb-3">
        <span class="text-[11px] font-bold text-slate-400 tracking-wider uppercase">{{ $title }}</span>
        <div class="w-9 h-9 rounded-xl {{ $c['bg'] }} flex items-center justify-center {{ $c['text'] }}">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $path }}"></path>
            </svg>
        </div>
    </div>
    <div class="text-3xl font-extrabold text-slate-900 mb-3">{{ $value }}</div>
    <span class="inline-flex items-center gap-1 text-xs font-semibold {{ $c['badgeText'] }} {{ $c['badgeBg'] }} px-2 py-0.5 rounded-full">
        {{ $badge }}
    </span>
</div>