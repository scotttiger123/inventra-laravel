@extends('layouts.app')

@section('content')
<div class="content-wrapper">
    <div class="form-border">

        <div class="box-header with-border">
            <h3 class="box-title custom-title">Settings</h3>
            @if(session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif
        </div>

        <div class="text-right">
            <a href="{{ route('settings.create') }}" class="btn btn-dark">
                <i class="fa fa-plus"></i> Add New Setting
            </a>
        </div>

        <table id="settings-listings" class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>Key</th>
                    <th>Value</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($settings as $setting)
                    <tr>
                        <td>{{ $setting->key }}</td>
                        <td>{{ $setting->value }}</td>
                        <td>
                            <a href="{{ route('settings.edit', $setting->id) }}" class="btn btn-primary">
                                <i class="fa fa-edit"></i> Edit
                            </a>
                            <form action="{{ route('settings.destroy', $setting->id) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger">
                                    <i class="fa fa-trash"></i> Delete
                                </button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
