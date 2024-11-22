@extends('layouts.app')

@section('content')
<div class="content-wrapper">

    <div class="form-border">

        <!-- Edit Expense Head Section -->
        <div class="row">
            <div class="col-lg-12 col-xs-12">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">Edit Expense Head</h3>
                    </div>

                    <!-- Form to Edit Expense Head -->
                    <form action="{{ route('expenses-heads.update', $expenseHead->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="box-body">

                            <!-- Expense Head Name -->
                            <div class="form-group">
                                <label for="name">Name</label>
                                <input type="text" class="form-control" id="name" name="name" value="{{ old('name', $expenseHead->name) }}" required>
                            </div>

                            <!-- Expense Head Description -->
                            <div class="form-group">
                                <label for="description">Description</label>
                                <textarea class="form-control" id="description" name="description">{{ old('description', $expenseHead->description) }}</textarea>
                            </div>

                        </div>

                        <!-- Submit Button -->
                        <div class="box-footer">
                            <button type="submit" class="btn btn-primary">Save Changes</button>
                            <a href="{{ route('expenses-heads.index') }}" class="btn btn-default">Back</a>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection
