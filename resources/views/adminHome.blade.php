@extends('app')

@section('header')
    <title>Admin</title>
@endsection

@section('brand')
    <a class="navbar-brand" href="#">
        Admin Home
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
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">
                    <div class="panel-heading">Home</div>
                    <div class="panel-body">
                        <div>
                            Session id: {{ $session_id }}
                        </div>
                        <div>
                            Uuid: {{ $uuid }}
                        </div>
                        <div>
                            Username: {{ $username }}
                        </div>
                        <div>
                            Roles:
                            @foreach($roles as $role)
                                {{ $role }}
                            @endforeach
                        </div>
                        </br>
                        <div>
                            <a href="/adminHome/techMgmt">Technician Management</a>
                        </div>
                        <div>
                            <a href="/adminHome/roleMgmt">Role Management</a>
                        </div>
                        <div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="{{ asset('/js/adminHome.js') }}"></script>
@endsection
