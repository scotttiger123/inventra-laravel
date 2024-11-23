@extends('layouts.app')

@section('content')
<div class="content-wrapper">

    <div class="form-border">

        <!-- Total Summary Section -->
        <div class="row">
            @php
                $totalTaxes = $totalTaxes;  // Total tax records
                
            @endphp

            <!-- Total Tax Records Box -->
            <div class="col-lg-12 col-xs-12">
                <div class="small-box bg-grey">
                    <div class="inner">
                        <h3>{{ $totalTaxes }}</h3>
                        <p>Tax Records</p>
                    </div>
                    <div class="icon" style="color:#222D32">
                        <i class="ion ion-ios-list"></i>
                    </div>
                </div>
            </div>

            
            
        </div>

        <!-- Tax History Table Section -->
        <div class="box-header with-border">
            <h3 class="box-title custom-title">Tax History</h3>
            @if(session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif
        </div>

        <!-- Button to Add a New Tax -->
        <div class="text-right">
            <a href="{{ route('tax.create') }}" class="btn btn-success">
                <i class="fa fa-plus"></i> Add New Tax
            </a>
        </div>

        <!-- Taxes Listings Table -->
        <table id="tax-listings" class="table table-bordered table-striped">
            <thead>
                <tr>
                    
                    
                    <th>Name</th>
                    <th>Rate(%)</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($taxes as $tax)
                    <tr>
                    <td>{{ $tax->name }}</td>    
                    <td>{{ number_format($tax->rate, 2) }}</td> <!-- Format amount -->
                        
                        <td>
                            <div class="custom-dropdown text-center">
                                <button class="custom-dropdown-toggle" type="button">
                                    Actions <i class="fa fa-caret-down"></i>
                                </button>
                                <div class="custom-dropdown-menu">
                                    <!-- Edit tax -->
                                    <a href="{{ route('tax.edit', $tax->id) }}" class="custom-dropdown-item">
                                        <i class="fa fa-edit"></i> Edit
                                    </a>

                                    <!-- Delete tax -->
                                    <form id="deleteForm-{{ $tax->id }}" 
                                          action="{{ route('tax.destroy', $tax->id) }}" 
                                          method="POST" 
                                          class="custom-dropdown-item delete-form">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" onclick="confirmDeleteTax({{ $tax->id }})" class="btn btn-danger">
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
    $(function () {
        $('#tax-listings').DataTable({
            'paging': true,
            'lengthChange': true,
            'searching': true,
            'ordering': true,
            'info': true,
            'autoWidth': true,
            'order': [[0, 'desc']] // Sorts by first column (Tax Date) in descending order
        });
    });

    function confirmDeleteTax(id) {
        if (confirm('Are you sure you want to delete this tax record?')) {
            document.getElementById('deleteForm-' + id).submit();
        }
    }
</script>

@endsection
