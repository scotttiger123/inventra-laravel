@extends('layouts.app')

@section('content')
<div class="content-wrapper">

    <div class="form-border">

        <!-- Total Summary Section -->
        <div class="row">
            @php
                $totalExpenseHeads = $expenseHeads->count();
            @endphp

            <div class="col-lg-12 col-xs-12">
                <div class="small-box bg-grey">
                    <div class="inner">
                        <h3>{{ $totalExpenseHeads }}</h3>
                        <p>Total Expense Heads</p>
                    </div>
                    <div class="icon" style="color:#222D32">
                        <i class="ion ion-ios-list"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Expense Head Table Section -->
        <div class="box-header with-border">
            <h3 class="box-title custom-title">Expense Categories List</h3>
            @if(session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif
        </div>

        <!-- Button to Add a New Expense Head -->
        <div class="text-right">
            <a href="{{ route('expenses-heads.create-head') }}" class="btn btn-success">
                <i class="fa fa-plus"></i> Add Expense Head
            </a>
        </div>

        <!-- Expense Heads Listings Table -->
        <table id="expense-heads-listings" class="table table-bordered table-striped">
            <thead>
                <tr>
                    
                    <th>Name</th>
                    <th>Description</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($expenseHeads as $expenseHead)
                    <tr>
                        
                        <td>{{ $expenseHead->name }}</td>
                        <td>{{ $expenseHead->description }}</td>
                        <td>
                            <div class="custom-dropdown text-center">
                                <button class="custom-dropdown-toggle" type="button">
                                    Actions <i class="fa fa-caret-down"></i>
                                </button>
                                <div class="custom-dropdown-menu">
                                    <!-- Edit Expense Head -->
                                    <a href="{{ route('expenses-heads.edit-head', $expenseHead->id) }}" class="custom-dropdown-item">
                                        <i class="fa fa-edit"></i> Edit
                                    </a>

                                    <!-- Delete Expense Head -->
                                    <form id="deleteForm-{{ $expenseHead->id }}" 
                                          action="{{ route('expenses-heads.destroy-head', $expenseHead->id) }}" 
                                          method="POST" 
                                          class="custom-dropdown-item delete-form">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" onclick="confirmDeleteExpenseHead({{ $expenseHead->id }})" class="btn btn-danger">
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
    function confirmDeleteExpenseHead(id) {
        if (confirm('Are you sure you want to delete this expense head?')) {
            document.getElementById('deleteForm-' + id).submit();
        }
    }
</script>

@endsection
