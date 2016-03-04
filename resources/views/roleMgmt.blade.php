@extends('app')

@section('header')
    <title>Role Management</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="{{ asset('/css/techMgmt.css') }}">
@endsection

@section('brand')
    <a class="navbar-brand" href="#">
        Role Management
    </a>
@endsection

@section('logout')
    <ul class="nav navbar-nav navbar-right">
        <li class="dropdown">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button"
               aria-expanded="false">{{ $username }} <span class="caret"></span></a>
            <ul class="dropdown-menu" role="menu">
                <li><a href="#" id="logout">Logout</a></li>
            </ul>
        </li>
    </ul>
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-6 col-md-offset-3">
                <a class="show-tab" href="#role-tab">
                    <button class="btn btn-sm btn-primary">All Roles</button>
                </a>
                <a class="show-tab" href="#new-tab">
                    <button class="btn btn-sm btn-primary">New Role</button>
                </a>
            </div>
        </div>
        <div id="role-tab" class="row">
            <div class="col-md-6 col-md-offset-3">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        List of Roles
                    </div>
                    <div class="panel-body">
                        <table class="table table-striped table-bordered" cellspacing="0">
                            <thead>
                            <tr>
                                <th>Role</th>
                                <th>Description</th>
                                <th>Actions</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($roles_arr as $role)
                                @if($role['role'] == "TECHNICIAN")
                                    <tr>
                                        <td id="{{ $role['role'] }}" class="tb-rname">
                                            {{ $role['role'] }}
                                        </td>
                                        <td id="{{ $role['role'] }}" class="tb-desc">
                                            {{ $role['desc'] }}
                                        </td>
                                        <td></td>
                                    </tr>
                                @else
                                    <tr>
                                        <td id="{{ $role['role'] }}" class="tb-rname">
                                            {{ $role['role'] }}
                                        </td>
                                        <td id="{{ $role['role'] }}" class="tb-desc">
                                            {{ $role['desc'] }}
                                        </td>
                                        <td>
                                            <button id="{{ $role['role'] }}"
                                                    class="editRole btn btn-xs btn-default">
                                                <i class="fa fa-wrench"></i> Edit
                                            </button>
                                            <button id="{{ $role['role'] }}"
                                                    class="deleteRole btn btn-xs btn-danger">
                                                <i class="fa fa-times"></i> Delete
                                            </button>
                                        </td>
                                    </tr>
                                @endif
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div id="new-tab" class="row">
            <div class="col-md-6 col-md-offset-3">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        New Role Creation
                    </div>
                    <div class="panel-body">
                        <form id="newRoleForm">
                            <div class="form-group">
                                <label for="newRname">New Role Name</label>
                                <input id="newRname" name="newRname" class="form-control"
                                       value="TECHNICIAN_">
                            </div>
                            <div class="form-group">
                                <label for="newDesc">Role Description</label>
                                <textarea id="newDesc" name="newDesc" class="form-control"></textarea>
                            </div>
                            <button id="createNewRole" class="btn btn-success btn-sm pull-right" type="submit"
                                    form="newRoleForm">
                                <i class="fa fa-plus"></i> New
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- deleteRole-popup -->
    <div id="deleteRole-popup" class="popup alert-popup panel panel-default">
        <div class="panel-body">
            <div class="row">
                <div>
                    Are you sure you want to delete this role?
                </div>
            </div>
            <div class="row">
                <div>
                    <div class="pull-right">
                        <a href="#" id="deleteRole-done" class="btn btn-danger">
                            <i class="fa fa-times"></i> Yes, delete it</a>
                        <a href="#" id="deleteRole-popup-close-btn" class="btn btn-success">
                            <i class="fa fa-sign-out"></i> No, later</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- editRole-popup -->
    <div id="editRole-popup" class="popup panel panel-default">
        <div class="panel-heading">
            Edit Role
        </div>
        <div class="panel-body">
            <form id="editRoleForm">
                <div class="row">
                    <div class="col-md-10 col-md-offset-1">
                        <div class="form-group">
                            <label for="editRname">Role Name</label>
                            <input id="editRname" name="editRname" class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="editDesc">Description</label>
                            <textarea id="editDesc" name="editDesc" class="form-control"></textarea>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-10 col-md-offset-1">
                        <div class="pull-right">
                            <button id="editRole-done" class="btn btn-success" type="submit" form="editRoleForm">
                                <i class="fa fa-plus"></i> Commit
                            </button>
                            <a href="#" id="editRole-popup-close-btn" class="btn btn-danger">
                                <i class="fa fa-times"></i> Cancel</a>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="{{ asset('/js/jquery.validate.min.js') }}"></script>
    <script src="{{ asset('/js/roleMgmt.js') }}"></script>
@endsection