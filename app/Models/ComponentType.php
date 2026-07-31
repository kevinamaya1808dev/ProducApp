<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ComponentType extends Model
{
    protected $fillable = ['name', 'color'];

    public function components()
    {
        return $this->hasMany(Component::class);
    }

    /**
     * Paleta fija: Tailwind purga clases que no ve escritas literalmente,
     * así que no podemos armar "bg-{$color}-50" dinámicamente en la vista.
     */
    public static function colorPalette(): array
    {
        return [
            'slate'   => 'bg-slate-100 text-slate-600 border-slate-200',
            'indigo'  => 'bg-indigo-50 text-indigo-600 border-indigo-200',
            'purple'  => 'bg-purple-50 text-purple-600 border-purple-200',
            'amber'   => 'bg-amber-50 text-amber-700 border-amber-200',
            'emerald' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
            'rose'    => 'bg-rose-50 text-rose-600 border-rose-200',
            'blue'    => 'bg-blue-50 text-blue-600 border-blue-200',
            'teal'    => 'bg-teal-50 text-teal-700 border-teal-200',
            'orange'  => 'bg-orange-50 text-orange-700 border-orange-200',
            'pink'    => 'bg-pink-50 text-pink-600 border-pink-200',
        ];
    }

    /** Clases Tailwind swatch sólido, para los puntitos selector de color */
    public static function swatchDot(string $color): string
    {
        $dots = [
            'slate' => 'bg-slate-400', 'indigo' => 'bg-indigo-500', 'purple' => 'bg-purple-500',
            'amber' => 'bg-amber-500', 'emerald' => 'bg-emerald-500', 'rose' => 'bg-rose-500',
            'blue' => 'bg-blue-500', 'teal' => 'bg-teal-500', 'orange' => 'bg-orange-500', 'pink' => 'bg-pink-500',
        ];
        return $dots[$color] ?? $dots['slate'];
    }

    public function badgeClasses(): string
    {
        return self::colorPalette()[$this->color] ?? self::colorPalette()['slate'];
    }
}