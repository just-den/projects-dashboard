<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<META HTTP-EQUIV="Pragma" CONTENT="no-cache">
    <META HTTP-EQUIV="Expires" CONTENT="-1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Demo</title>
	<link href="https://fonts.googleapis.com/css?family=Open+Sans|Montserrat|Poiret+One&display=swap" rel="stylesheet">
	<link rel="stylesheet" href="/css/app.css">
</head>

<body>
	<div id="app">

			<a 
				class="main_nav_toggle"
				href="#"
				@click.prevent="mobileNav()"
			>
				<div>
					<span></span>
					<span></span>
					<span></span>
				</div>
			</a>

			@include('layouts.aside-nav')

		
		<transition mode="out-in">
			<router-view				
				:csrf="CSRF"
				:main_block_styles="mainBlockStyles"
				:currencies="currencies"
			></router-view>
		</transition>

		<a 
			class="info_toggle"
			href="#"
			@click.prevent="mobileInfo()"
		>
			<div>
				<span></span>
				<span></span>
				<span></span>
			</div>
		</a>

		@include('layouts.aside-info')
		

	</div>
	<script src="/js/app.js"></script>
</body>
</html>
