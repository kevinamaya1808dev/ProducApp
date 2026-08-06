@props(['orden' => null])

@php
    $isObject = is_object($orden);
    
    // Extraer la prioridad de forma segura según el tipo de dato
    $priority = $isObject 
        ? ($orden->priority ?? 'medium') 
        : ($orden['priority'] ?? 'medium');
    
    // Determinar la clase y la etiqueta evitando errores de tipo
    if ($isObject) {
        $badgeClass = $orden->priority_badge_class ?? match ($priority) {
            'low' => 'bg-stone-500',
            'medium' => 'bg-amber-500',
            'high' => 'bg-red-600',
            default => 'bg-amber-500',
        };
        
        $label = $orden->priority_label ?? match ($priority) {
            'low' => 'Baja',
            'medium' => 'Media',
            'high' => 'Alta',
            default => 'Media',
        };
    } else {
        $badgeClass = match ($priority) {
            'low' => 'bg-stone-500',
            'medium' => 'bg-amber-500',
            'high' => 'bg-red-600',
            default => 'bg-amber-500',
        };
        
        $label = match ($priority) {
            'low' => 'Baja',
            'medium' => 'Media',
            'high' => 'Alta',
            default => 'Media',
        };
    }
@endphp

@if($orden)
    <span class="{{ $badgeClass }} text-white text-xs font-bold px-3 py-1 rounded-full uppercase tracking-wide shadow-sm">
        Prioridad {{ $label }}
    </span>
@endif