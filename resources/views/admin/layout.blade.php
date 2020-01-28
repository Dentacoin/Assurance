<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <!-- Meta, title, CSS, favicons, etc. -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <meta name="csrf-token" content="{{ csrf_token() }}"/>

    <title>Admin</title>
    <link rel="stylesheet" href="/dist/css/admin-libs-style.css?v=1.0.2">

    <link href="/assets/css/admin.css?v=1.0.2" rel="stylesheet">
    <script type="text/javascript">
        var SITE_URL = "{{ route('admin-access') }}";
    </script>
</head>
<body class="hold-transition skin-blue sidebar-mini @if(!empty(Route::current())) {{Route::current()->getName()}} @endif">
<!-- Site wrapper -->
<div class="wrapper">
    <header class="main-header">
        <figure class="logo">
            <a href="{{route('home')}}" target="_blank">
                <img src="{{URL::asset('assets/images/logo.svg') }}" alt="Dentacoin logo"/>
            </a>
        </figure>
        <!-- Header Navbar: style can be found in header.less -->
        <nav class="navbar navbar-static-top">
            <!-- Sidebar toggle button-->
            <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
                <span class="sr-only">Toggle navigation</span>
            </a>
            <div class="navbar-custom-menu">
                <ul class="nav navbar-nav">
                    <!-- User Account: style can be found in dropdown.less -->
                    <li class="dropdown user user-menu">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                            <span>Admin</span>
                        </a>
                        <ul class="dropdown-menu" role="menu">
                            <!-- Menu Footer-->
                            <li class="user-footer">
                                <div class="pull-right">
                                    <a href="{{ route('logout') }}" class="btn btn-default btn-flat logout">Logout</a>
                                </div>
                            </li>
                        </ul>
                    </li>
                </ul>
            </div>
        </nav>
    </header>
    <!-- =============================================== -->
    <!-- Left side column. contains the sidebar -->
    <aside class="main-sidebar">
        <!-- sidebar: style can be found in sidebar.less -->
        <section class="sidebar">
            <!-- sidebar menu: : style can be found in sidebar.less -->
            <ul class="sidebar-menu">
                <li @if(!empty(Route::current()->getName()) && Route::current()->getName() == 'guide') class="active" @endif >
                    <a href="{{ route('guide') }}"><i class="fa fa-list-alt" aria-hidden="true"></i> Guide</a>
                </li>
                <li @if(!empty(Route::current()->getName()) && Route::current()->getName() == 'media') class="active" @endif >
                    <a href="{{ route('media') }}"><i class="fa fa-picture-o"></i> Media</a>
                </li>
                <li @if(!empty(Route::current()->getName()) && (Route::current()->getName() == 'all-pages' || Route::current()->getName() == 'edit-page')) class="active" @endif>
                    <a href="{{route('all-pages')}}"><i class="fa fa-file-text-o" aria-hidden="true"></i>All pages</a>
                </li>
                <li class="treeview menu-open @if(!empty(Route::current()->getName()) && (Route::current()->getName() == 'all-menus' || Route::current()->getName() == 'edit-menu' || Route::current()->getName() == 'add-menu-element' || Route::current()->getName() == 'edit-menu-element')) active @endif">
                    <a href="#">
                        <i class="fa fa-folder" aria-hidden="true"></i> <span>Menus</span>
                        <span class="pull-right">
                          <i class="fa fa-angle-left pull-right"></i>
                        </span>
                    </a>
                    <ul class="treeview-menu">
                        <li @if(!empty(Route::current()->getName()) && (Route::current()->getName() == 'all-menus' || Route::current()->getName() == 'edit-menu')) class="active" @endif><a href="{{route('all-menus')}}"><i class="fa fa-list-ol" aria-hidden="true"></i>All menus</a></li>
                        <li @if(!empty(Route::current()->getName()) && Route::current()->getName() == 'add-menu-element') class="active" @endif><a href="{{route('add-menu-element')}}"><i class="fa fa-plus" aria-hidden="true"></i>Add menu element</a></li>
                    </ul>
                </li>
                <li class="treeview menu-open @if(!empty(Route::current()->getName()) && (Route::current()->getName() == 'calculator-parameters' || Route::current()->getName() == 'add-calculator-parameter')) active @endif">
                    <a href="#">
                        <i class="fa fa-folder" aria-hidden="true"></i> <span>Calculator parameters</span>
                        <span class="pull-right">
                          <i class="fa fa-angle-left pull-right"></i>
                        </span>
                    </a>
                    <ul class="treeview-menu">
                        <li @if(!empty(Route::current()->getName()) && (Route::current()->getName() == 'calculator-parameters')) class="active" @endif><a href="{{route('calculator-parameters')}}"><i class="fa fa-list-ol" aria-hidden="true"></i>All parameters</a></li>
                        <li @if(!empty(Route::current()->getName()) && Route::current()->getName() == 'add-calculator-parameter') class="active" @endif><a href="{{route('add-calculator-parameter')}}"><i class="fa fa-plus" aria-hidden="true"></i>Add parameter</a></li>
                    </ul>
                </li>
                <li class="treeview menu-open @if(!empty(Route::current()->getName()) && (Route::current()->getName() == 'all-support-guides' || Route::current()->getName() == 'add-support-guide' || Route::current()->getName() == 'edit-support-guide')) active @endif">
                    <a href="#">
                        <i class="fa fa-folder" aria-hidden="true"></i> <span>Support Guide</span>
                        <span class="pull-right">
                          <i class="fa fa-angle-left pull-right"></i>
                        </span>
                    </a>
                    <ul class="treeview-menu">
                        <li @if(!empty(Route::current()->getName()) && (Route::current()->getName() == 'all-support-guides' || Route::current()->getName() == 'edit-support-guide')) class="active" @endif><a href="{{route('all-support-guides')}}"><i class="fa fa-list-ol" aria-hidden="true"></i>All support guides</a></li>
                        <li @if(!empty(Route::current()->getName()) && Route::current()->getName() == 'add-support-guide') class="active" @endif><a href="{{route('add-support-guide')}}"><i class="fa fa-plus" aria-hidden="true"></i>Add support guide</a></li>
                    </ul>
                </li>
            </ul>
        </section>
        <!-- /.sidebar -->
    </aside>
    <!-- =============================================== -->
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        @include('admin.partials.error')
        @include('admin.partials.success')
        <section>@yield("content")</section>
    </div>
</div>
<script>
    var CKEDITOR_BASEPATH = '/assets/libs/ckeditor-full/';
</script>
<script src="/dist/js/admin-libs-script.js?v=1.0.4"></script>
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBd5xOHXvqHKf8ulbL8hEhFA4kb7H6u6D4" type="text/javascript"></script>
<script src="https://dentacoin.com/assets/js/basic.js?v=1.0.4"></script>
<script src="/assets/js/admin/index.js?v=1.0.4"></script>
</body>
</html>