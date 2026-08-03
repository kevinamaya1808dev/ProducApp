<div>
    <label class="block text-sm font-semibold text-slate-700 mb-2">Color</label>
    <div class="flex flex-wrap gap-2">
        @foreach(\App\Models\ComponentType::colorPalette() as $key => $classes)
            <label class="cursor-pointer">
                <input type="radio" name="color" value="{{ $key }}" class="peer sr-only" @checked($selectedColor === $key)>
                <span class="block w-8 h-8 rounded-full {{ \App\Models\ComponentType::swatchDot($key) }} ring-offset-2 peer-checked:ring-2 ring-slate-900"></span>
            </label>
        @endforeach
    </div>
</div>