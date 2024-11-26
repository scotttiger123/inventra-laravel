@extends('layouts.app')

@section('content')
<div class="content-wrapper">

    <div class="form-border">

        <!-- Total Summary Section -->
        <div class="row">
            @php
                $totalCategories = $categories->count();  // Total category records
            @endphp

            <!-- Total Category Records Box -->
            <div class="col-lg-12 col-xs-12">
                <div class="small-box bg-grey">
                    <div class="inner">
                        <h3>{{ $totalCategories }}</h3>
                        <p>Category Records</p>
                    </div>
                    <div class="icon" style="color:#222D32">
                        <i class="ion ion-ios-list"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Category Listings Table -->
        <div class="box-header with-border">
            <h3 class="box-title custom-title">Category Listings</h3>
            @if(session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif
        </div>

        <!-- Button to Add a New Category -->
        <div class="text-right">
            <a href="{{ route('category.create') }}" class="btn btn-success">
                <i class="fa fa-plus"></i> Add New Category
            </a>
        </div>

        <!-- Categories Listings Table -->
        <table id="category-listings" class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Description</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($categories as $category)
                    <tr>
                        <td>{{ $category->name }}</td>
                        <td>{{ $category->description }}</td>
                        <td>
                            <div class="custom-dropdown text-center">
                                <button class="custom-dropdown-toggle" type="button">
                                    Actions <i class="fa fa-caret-down"></i>
                                </button>
                                <div class="custom-dropdown-menu">
                                    <!-- Edit category -->
                                    <a href="{{ route('category.edit', $category->id) }}" class="custom-dropdown-item">
                                        <i class="fa fa-edit"></i> Edit
                                    </a>

                                    <!-- Delete category -->
                                    <form id="deleteForm-{{ $category->id }}" 
                                          action="{{ route('category.destroy', $category->id) }}" 
                                          method="POST" 
                                          class="custom-dropdown-item delete-form">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" onclick="confirmDeleteCategory({{ $category->id }})" class="btn btn-danger">
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
        $('#category-listings').DataTable({
            'paging': true,
            'lengthChange': true,
            'searching': true,
            'ordering': true,
            'info': true,
            'autoWidth': true,
            'order': [[0, 'desc']] // Sorts by first column (Category Name)
        });
    });

    function confirmDeleteCategory(id) {
        if (confirm('Are you sure you want to delete this category?')) {
            document.getElementById('deleteForm-' + id).submit();
        }
    }
</script>

@endsection
