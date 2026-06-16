@extends('admin.layouts.app')

@section('title','Dashboard')

@section('page-title','Dashboard')

@section('content')

<div class="grid grid-cols-4 gap-6">


<div class="bg-white rounded-xl p-6 shadow">
    <h3 class="text-slate-500 text-sm">
        Total Applications
    </h3>

    <p class="text-3xl font-bold mt-2">
        1,250
    </p>
</div>

<div class="bg-white rounded-xl p-6 shadow">
    <h3 class="text-slate-500 text-sm">
        Approved Loans
    </h3>

    <p class="text-3xl font-bold mt-2">
        950
    </p>
</div>

<div class="bg-white rounded-xl p-6 shadow">
    <h3 class="text-slate-500 text-sm">
        Pending Loans
    </h3>

    <p class="text-3xl font-bold mt-2">
        180
    </p>
</div>

<div class="bg-white rounded-xl p-6 shadow">
    <h3 class="text-slate-500 text-sm">
        Total Lenders
    </h3>

    <p class="text-3xl font-bold mt-2">
        25
    </p>
</div>


</div>

@endsection
