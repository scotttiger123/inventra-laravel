@extends('layouts.app')

@section('content')
<div class="content-wrapper">
    <div class="form-border">
        <div class="box-header with-border">
            <h3 class="box-title custom-title">User Listings</h3>
            @if(session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif
        </div>

        <!-- Button to Add a New User -->
        <div class="text-right">
            <a href="{{ route('users.create') }}" class="btn btn-success">
                <i class="fa fa-plus"></i> Add User
            </a>
        </div>
        
        <!-- User Listings Table -->
        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Email</th>
                    <th>status</th>
                    <th>Role</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($users as $user)
                <tr id="userRow-{{ $user->id }}">
                    <td>{{ strtoupper($user->name) }}</td>
                    <td>{{ $user->email }}</td>
                    <td>
                        <div class="{{ $user->status === 'active' ? 'badge-badge-success' : 'badge-badge-danger' }}">
                            {{ ucfirst($user->status) }}
                        </div>
                    </td>

                    <td>{{ ucwords(str_replace('_', ' ', $user->role->name)) }}</td>


                    <td>
                        <div class="custom-dropdown text-center">
                            <button class="custom-dropdown-toggle" type="button">
                                Actions <i class="fa fa-caret-down"></i>
                            </button>
                            <div class="custom-dropdown-menu">
                                <!-- View User Option -->
                                <button 
                                    class="custom-dropdown-item" 
                                    type="button"
                                    data-toggle="modal" 
                                    data-target="#viewUserModal"
                                    data-id="{{ $user->id }}"
                                    data-name="{{ $user->name }}"
                                    data-email="{{ $user->email }}"
                                    data-phone="{{ $user->phone }}"
                                    data-address="{{ $user->address }}"
                                    data-city="{{ $user->city }}"
                                    data-zip-code="{{ $user->zip_code }}"
                                    data-join-date="{{ $user->created_at }}"
                                    data-role="{{ $user->role }}"
                                    data-status="{{ $user->status }}"
                                >
                                    <i class="fa fa-eye"></i> View
                                </button>
                              
                                
                                    @can('edit_users')
                                        <a href="{{ route('users.edit', $user->id) }}" class="custom-dropdown-item">
                                            <i class="fa fa-edit"></i> Edit
                                        </a>
                                    @endcan

                                    
                                    @can('delete_users')
                                        <form id="deleteForm-{{ $user->id }}" action="{{ route('users.destroy', $user->id) }}" method="POST" class="custom-dropdown-item delete-form">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button" onclick="confirmDeleteUser({{ $user->id }})" class="delete-btn btn btn-danger">
                                                <i class="fa fa-trash"></i> Delete
                                            </button>
                                        </form>
                                    @endcan
                            </div>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<!-- Modal for Viewing User Details -->
<div class="modal fade" id="viewUserModal" tabindex="-1" role="dialog" aria-labelledby="viewUserModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="viewUserModalLabel">User Details</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p><strong>Name:</strong> <span id="user-name"></span></p>
                <p><strong>Email:</strong> <span id="user-email"></span></p>
                <p><strong>Phone:</strong> <span id="user-phone"></span></p>
                <p><strong>Address:</strong> <span id="user-address"></span></p>
                <p><strong>City:</strong> <span id="user-city"></span></p>
                <p><strong>Zip Code:</strong> <span id="user-zip-code"></span></p>
                <p><strong>Join Date:</strong> <span id="user-join-date"></span></p>
                <p><strong>Role:</strong> <span id="user-role"></span></p>
                <p><strong>Status:</strong> <span id="user-status"></span></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

@endsection