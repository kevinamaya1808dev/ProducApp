@extends('layouts.app')

@section('content')
<div class="p-6 max-w-[1400px] mx-auto">

    @include('admin.permissions.components.header')

    @if(session('success'))
        <div class="mb-4 px-4 py-3 bg-emerald-50 dark:bg-emerald-950/50 border border-emerald-200 dark:border-emerald-900/50 text-emerald-700 dark:text-emerald-400 rounded-xl text-sm">
            {{ session('success') }}
        </div>
    @endif

    @include('admin.permissions.components.modules-table')

    @include('admin.permissions.components.special-list')

    @include('admin.permissions.components.modals.create-modal')
    @include('admin.permissions.components.modals.edit-modal')
    @include('admin.permissions.components.modals.delete-modal')

</div>

@include('admin.permissions.components.index-scripts')

@endsection