@extends('app')

@section('header')
    <title>Technician Management</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="{{ asset('/css/techMgmt.css') }}">
@endsection

@section('brand')
    <a class="navbar-brand" href="#">
        Technician Management
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
                <a class="show-tab" href="#all-tab">
                    <button class="btn btn-sm btn-primary">All Technicians</button>
                </a>
                <a class="show-tab" href="#new-tab">
                    <button class="btn btn-sm btn-primary">New Technician</button>
                </a>
                <a class="show-tab" href="#batch-tab">
                    <button class="btn btn-sm btn-primary">Batch Technician Account Creation</button>
                </a>
            </div>
        </div>
        <div id="batch-tab" class="row">
            <div class="col-md-6 col-md-offset-3">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        Batch Technician Account Creation
                    </div>
                    <div class="panel-body">
                        <form>
                            <div class="form-group">
                                <textarea id="batch-content" class="form-control"></textarea>
                            </div>
                            <a href="#" class="btn btn-success pull-right" type="submit">
                                <i class="fa fa-upload"></i> Upload</a>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <div id="all-tab" class="row">
            <div class="col-md-6 col-md-offset-3">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        List of Technicians
                    </div>
                    <div class="panel-body">
                        <table class="table table-striped table-bordered" cellspacing="0">
                            <thead>
                            <tr>
                                <th>Technician</th>
                                <th>Username</th>
                                <th>Roles</th>
                                <th>Actions</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($users_arr as $user)
                                <tr>
                                    <td><span id="{{ $user->uuid }}" class="tb-fname">{{ $user->fname }}</span>
                                        <span id="{{ $user->uuid }}" class="tb-lname"> {{ $user->lname }}</span>
                                    </td>
                                    <td id="{{ $user->uuid }}" class="tb-username">{{ $user->username }}</td>
                                    <td id="{{ $user->uuid }}" class="tb-roles">
                                        @foreach($user->roles as $role)
                                            {{ $role }}<br>
                                        @endforeach
                                    </td>
                                    <td>
                                        <button id="{{ $user->uuid }}"
                                                class="editUser btn btn-xs btn-default">
                                            <i class="fa fa-wrench"></i> Edit
                                        </button>
                                        <button id="{{ $user->uuid }}"
                                                class="deleteUser btn btn-xs btn-danger">
                                            <i class="fa fa-times"></i> Delete
                                        </button>
                                    </td>
                                </tr>
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
                        New Technician Creation
                    </div>
                    <div class="panel-body">
                        <form id="newUserForm">
                            <div class="form-group">
                                <label for="newUsername">New Username</label>
                                <input id="newUsername" name="newUsername" class="form-control" type="text">
                            </div>
                            <div class="form-group">
                                <label for="newPassword">New Password</label>
                                <input id="newPassword" name="newPassword" class="form-control" type="password">
                            </div>
                            <div class="form-group">
                                <label for="retypePassword">Retype Password</label>
                                <input id="retypePassword" name="retypePassword" class="form-control" type="password">
                            </div>
                            <div class="form-group">
                                <label for="newFname">First Name</label>
                                <input id="newFname" name="newFname" class="form-control" type="text">
                            </div>
                            <div class="form-group">
                                <label for="newLname">Last Name</label>
                                <input id="newLname" name="newLname" class="form-control" type="text">
                            </div>
                            <div class="form-group">
                                <label for="newRole">New role</label>
                                <br>
                                <input type="checkbox" checked disabled> TECHINICIAN<br>
                                @foreach($roles_arr as $role)
                                    <input class="newRole" type="checkbox"
                                           value={{ $role }}> {{ $role }}<br>
                                @endforeach
                            </div>
                            <button id="createNewUser" class="btn btn-success pull-right" type="submit"
                                    form="newUserForm">
                                <i class="fa fa-plus"></i> Done
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- deleteUser-popup -->
    <div id="deleteUser-popup" class="popup alert-popup panel panel-default">
        <div class="panel-body">
            <div class="row">
                <div>
                    Are you sure you want to delete this technician?
                </div>
            </div>
            <div class="row">
                <div>
                    <div class="pull-right">
                        <a href="#" id="deleteUser-done" class="btn btn-danger">
                            <i class="fa fa-times"></i> Yes, do it</a>
                        <a href="#" id="deleteUser-popup-close-btn" class="btn btn-success">
                            <i class="fa fa-sign-out"></i> No, later</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- editUser-popup -->
    <div id="editUser-popup" class="popup panel panel-default">
        <div class="panel-heading">
            Edit User
        </div>
        <div class="panel-body">
            <form id="editUserForm">
                <div class="row">
                    <div class="col-md-10 col-md-offset-1">
                        <div class="form-group">
                            <label for="editFname">First Name</label>
                            <input id="editFname" name="editFname" class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="editLname">Last Name</label>
                            <input id="editLname" name="editLname" class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="editUsername">Username</label>
                            <input id="editUsername" name="editUsername" class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="editRoles">Roles</label>
                            <br>
                            <input type="checkbox" checked disabled> TECHINICIAN<br>
                            @foreach($roles_arr as $role)
                                <input id={{ $role }} class="editRoles"
                                       type="checkbox"
                                       value={{ $role }}> {{ $role }}<br>
                            @endforeach
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-10 col-md-offset-1">
                        <div class="pull-right">
                            <button href="#" id="editUser-done" class="btn btn-success" type="submit"
                                    form="editUserForm">
                                <i class="fa fa-plus"></i> Commit
                            </button>
                            <a href="#" id="editUser-popup-close-btn" class="btn btn-danger">
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
    <script src="{{ asset('/js/techMgmt.js') }}"></script>
@endsection
