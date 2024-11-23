@extends('layouts.app')

@section('content')
<div class="content-wrapper">

    <div class="form-border">

        <!-- Edit Income Head Section -->
        <div class="row">
            <div class="col-lg-12 col-xs-12">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">Edit Income Head</h3>
                    </div>

                    <!-- Form to Edit Income Head -->
                    <form action="{{ route('income-heads.update-head', $incomeHead->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="box-body">

                            <!-- Income Head Name -->
                            <div class="form-group">
                                <label for="name">Name</label>
                                <input type="text" class="form-control" id="name" name="name" value="{{ old('name', $incomeHead->name) }}" required>
                            </div>

                            <!-- Income Head Description -->
                            <div class="form-group">
                                <label for="description">Description</label>
                                <textarea class="form-control" id="description" name="description">{{ old('description', $incomeHead->description) }}</textarea>
                            </div>

                        </div>

                        <!-- Submit Button -->
                        <div class="box-footer">
                            <button type="submit" class="btn btn-primary">Save Changes</button>
                            
                            <a href="{{ route('income-heads.index-head') }}" class="btn btn-success">
                                <i class="fa fa-eye"></i> View Heads
                            </a>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection
