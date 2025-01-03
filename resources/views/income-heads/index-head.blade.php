@extends('layouts.app')

@section('content')
<div class="content-wrapper">

    <div class="form-border">

        <!-- Total Summary Section -->
        <div class="row">
            @php
                $totalIncomeHeads = $incomeHeads->count();
            @endphp

            <div class="col-lg-12 col-xs-12">
                <div class="small-box bg-grey">
                    <div class="inner">
                        <h3>{{ $totalIncomeHeads }}</h3>
                        <p>Total Income Heads</p>
                    </div>
                    <div class="icon" style="color:#222D32">
                        <i class="ion ion-ios-list"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Income Head Table Section -->
        <div class="box-header with-border">
            <h3 class="box-title custom-title">Income Categories List</h3>
            @if(session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif
        </div>

        <!-- Button to Add a New Income Head -->
        <div class="text-right">
            <a href="{{ route('income-heads.create-head') }}" class="btn btn-success">
                <i class="fa fa-plus"></i> Add Income Categories
            </a>
        </div>

        <!-- Income Heads Listings Table -->
        <table id="income-heads-listings" class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Description</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($incomeHeads as $incomeHead)
                    <tr>
                        <td>{{ $incomeHead->name }}</td>
                        <td>{{ $incomeHead->description }}</td>
                        <td>
                            <div class="custom-dropdown text-center">
                                <button class="custom-dropdown-toggle" type="button">
                                    Actions <i class="fa fa-caret-down"></i>
                                </button>
                                <div class="custom-dropdown-menu">
                                    <!-- Edit Income Head -->
                                    <a href="{{ route('income-heads.edit-head', $incomeHead->id) }}" class="custom-dropdown-item">
                                        <i class="fa fa-edit"></i> Edit
                                    </a>

                                    <!-- Delete Income Head -->
                                    <form id="deleteForm-{{ $incomeHead->id }}" 
                                          action="{{ route('income-heads.destroy-head', $incomeHead->id) }}" 
                                          method="POST" 
                                          class="custom-dropdown-item delete-form">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" onclick="confirmDeleteIncomeHead({{ $incomeHead->id }})" class="btn btn-danger">
                                            <i class="fa fa-trash"></i> Delete
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<script>
    function confirmDeleteIncomeHead(id) {
        if (confirm('Are you sure you want to delete this income head?')) {
            document.getElementById('deleteForm-' + id).submit();
        }
    }
</script>

@endsection
