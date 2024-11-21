@extends('layouts.app')

@section('content')
<div class="content-wrapper">

    <div class="form-border">

        <!-- Total Summary Section -->
        <div class="row">
            @php
                $totalCurrencies = $currencies->count();
            @endphp

            <div class="col-lg-12 col-xs-12">
                <div class="small-box bg-grey">
                    <div class="inner">
                        <h3>{{ $totalCurrencies }}</h3>
                        <p>Total Currencies</p>
                    </div>
                    <div class="icon" style="color:#222D32">
                        <i class="ion ion-social-usd"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Currency Table Section -->
        <div class="box-header with-border">
            <h3 class="box-title custom-title">Currency List</h3>
            @if(session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif
        </div>

        <!-- Button to Add a New Currency -->
        <div class="text-right">
            <a href="{{ route('currencies.create') }}" class="btn btn-success">
                <i class="fa fa-plus"></i> Add Currency
            </a>
        </div>

        <!-- Currencies Listings Table -->
        <table id="currencies-listings" class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Code</th>
                    <th>Symbol</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($currencies as $currency)
                    <tr>
                        <td>{{ $currency->id }}</td>
                        <td>{{ $currency->name }}</td>
                        <td>{{ $currency->code }}</td>
                        <td>{{ $currency->symbol }}</td>
                        <td>
                            <div class="custom-dropdown text-center">
                                <button class="custom-dropdown-toggle" type="button">
                                    Actions <i class="fa fa-caret-down"></i>
                                </button>
                                <div class="custom-dropdown-menu">
                                    <!-- Edit Currency -->
                                    <a href="{{ route('currencies.edit', $currency->id) }}" class="custom-dropdown-item">
                                        <i class="fa fa-edit"></i> Edit
                                    </a>

                                    <!-- Delete Currency -->
                                    <form id="deleteForm-{{ $currency->id }}" 
                                          action="{{ route('currencies.destroy', $currency->id) }}" 
                                          method="POST" 
                                          class="custom-dropdown-item delete-form">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" onclick="confirmDeleteCurrency({{ $currency->id }})" class="btn btn-danger">
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
    function confirmDeleteCurrency(id) {
        if (confirm('Are you sure you want to delete this currency?')) {
            document.getElementById('deleteForm-' + id).submit();
        }
    }
</script>

@endsection
