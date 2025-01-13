<!DOCTYPE html>
<html lang="en">

@include('dashboard.partials.header')
<title>@yield('title',env('APP_NAME'))</title>

	<body class="hold-transition sidebar-mini">
		<!-- Site wrapper -->
		<div class="wrapper">
			<!-- Navbar -->
			@include('dashboard.partials.navbar')
			<!-- /.navbar -->
			
			<!-- Main Sidebar Container -->
		{{-- @include('dashboard.partials.sidebar') --}}

		<x-side/>
			<!-- Content Wrapper. Contains page content -->
			<div class="content-wrapper">
				<!-- Content Header (Page header) -->
			
				<!-- Main content -->
		@yield('content')
				<!-- /.content -->
			</div>
			<!-- /.content-wrapper -->
		@include('dashboard.partials.footer')
			
		</div>
		<!-- ./wrapper -->
		<!-- jQuery -->
	@include('dashboard.partials.scripts')
	</body>
</html>