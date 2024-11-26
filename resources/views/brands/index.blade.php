@extends('layouts.app')

@section('content')
<div class="content-wrapper">

    <div class="form-border">

        <!-- Total Summary Section -->
        <div class="row">
            @php
                $totalBrands = $totalBrands;  // Total brand records
            @endphp

            <!-- Total Brand Records Box -->
            <div class="col-lg-12 col-xs-12">
                <div class="small-box bg-grey">
                    <div class="inner">
                        <h3>{{ $totalBrands }}</h3>
                        <p>Brand Records</p>
                    </div>
                    <div class="icon" style="color:#222D32">
                        <i class="ion ion-ios-list"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Brand History Table Section -->
        <div class="box-header with-border">
            <h3 class="box-title custom-title">Brand History</h3>
            @if(session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif
        </div>

        <!-- Button to Add a New Brand -->
        <div class="text-right">
            <a href="{{ route('brand.create') }}" class="btn btn-success">
                <i class="fa fa-plus"></i> Add New Brand
            </a>
        </div>

        <!-- Brands Listings Table -->
        <table id="brand-listings" class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Description</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($brands as $brand)
                    <tr>
                        <td>{{ $brand->name }}</td>
                        <td>{{ $brand->description ?? 'N/A' }}</td>
                        <td>
                            <div class="custom-dropdown text-center">
                                <button class="custom-dropdown-toggle" type="button">
                                    Actions <i class="fa fa-caret-down"></i>
                                </button>
                                <div class="custom-dropdown-menu">
                                    <!-- Edit Brand -->
                                    <a href="{{ route('brand.edit', $brand->id) }}" class="custom-dropdown-item">
                                        <i class="fa fa-edit"></i> Edit
                                    </a>

                                    <!-- Delete Brand -->
                                    <form id="deleteForm-{{ $brand->id }}" 
                                          action="{{ route('brand.destroy', $brand->id) }}" 
                                          method="POST" 
                                          class="custom-dropdown-item delete-form">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" onclick="confirmDeleteBrand({{ $brand->id }})" class="btn btn-danger">
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
        $('#brand-listings').DataTable({
            'paging': true,
            'lengthChange': true,
            'searching': true,
            'ordering': true,
            'info': true,
            'autoWidth': true,
            'order': [[0, 'desc']] // Sorts by first column (Name) in descending order
        });
    });

    function confirmDeleteBrand(id) {
        if (confirm('Are you sure you want to delete this brand record?')) {
            document.getElementById('deleteForm-' + id).submit();
        }
    }
</script>

@endsection
