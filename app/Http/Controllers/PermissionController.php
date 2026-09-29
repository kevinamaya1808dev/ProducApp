<?php

namespace App\Http\Controllers;

use App\Models\Permission;
use Illuminate\View\View;

class PermissionController extends Controller
{
    private const ACTIONS = [
        'view'   => 'Ver',
        'create' => 'Crear',
        'edit'   => 'Editar',
        'delete' => 'Borrar',
        'manage' => 'Gestionar',
    ];

    public function index(): View
    {
        $permissions = Permission::orderBy('module')->orderBy('action')->get();

        return view('admin.permissions.index', [
            'modules' => $permissions->where('is_special', false)->groupBy('module'),
            'special' => $permissions->where('is_special', true),
            'actions' => self::ACTIONS,
        ]);
    }
}