@extends('layouts.app')

@section('content')
<div class="content-wrapper">
    <div class="form-border">
        <!-- Form Header -->
        <div class="box-header with-border">
            <h3 class="box-title custom-title">Add Currency Setting</h3>
        </div>

        <!-- Display Success Message -->
        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <!-- Display Errors -->
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Currency Setting Form -->
        <form action="{{ route('settings.store') }}" method="POST">
            @csrf

            <!-- Currency Selection -->
            <div class="form-group">
                <label for="currency">Currency <span class="text-danger">*</span></label>
                <select name="currency" id="currency" class="form-control" required>
                    <option value="">Select Currency</option>
                    @foreach ($currencies as $currencyItem)
                        <option value="{{ $currencyItem->symbol }}" 
                            @if(isset($currency) && $currency->value == $currencyItem->symbol) selected @endif>
                            {{ $currencyItem->name }} ({{ $currencyItem->symbol }})
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Submit Button -->
            <div class="text-right">
                <button type="submit" class="btn btn-success">
                    <i class="fa fa-save"></i> Save Setting
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
