@extends('layouts.app')

@section('content')
<div class="p-6 max-w-[1400px] mx-auto">

    @include('admin.permissions.components.header')

    @include('admin.permissions.components.modules-table')

    @include('admin.permissions.components.special-list')

</div>
@endsection