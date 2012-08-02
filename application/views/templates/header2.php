<!doctype html>
<html lang="en" xmlns:fb="http://www.facebook.com/2008/fbml">
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
		<title><?if (!isset($pageTitle)){ echo "Cheep";}
		   else {echo $pageTitle;}?></title>
		<!-- http://ogp.me/ -->
		<meta property="og:title" content="<?if (!isset($pageTitle)){ echo "Cheep";}
		   else {echo $pageTitle;}?>">
		<!--<? if (isset($listPage)) { ?>
		<meta property="og:description" content="<? print_r($lists[0]->share); ?>">
		<? } else { ?>
		<meta property="og:description" content="Play a hilariously crude fill in the blank game with your friends">
		<? } ?>
		<meta property="og:image" content="http://rightblank.com/assets/img/m/apple-touch-icon.png">
		<meta property="og:type" content="website">
		<meta property="og:url" content="http://<? echo $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI']; ?>" />
		<meta property="og:site_name" content="rightblank" />
		<meta property="fb:admins" content="706441393" />
		-->
		<meta name="description" content="">
		<meta name="author" content="">
		<meta name="HandheldFriendly" content="True">
		<meta name="MobileOptimized" content="320">
		<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no">	

		<!--[if lt IE 9]><script src=//html5shiv.googlecode.com/svn/trunk/html5.js></script><![endif]-->

		<link rel="stylesheet" type="text/css" href="/assets/css/bootstrap.css">
		<link rel="stylesheet" type="text/css" href="/assets/css/bootstrap-responsive.css">
		
		<!--<link rel="stylesheet" href="/assets/css/friend_selector/tdfriendselector.css" />-->
<? /*		
		<link rel="stylesheet" type="text/css" href="/assets/css/jq-ui/jquery.ui.all.css">
*/ ?>
		<!--<link rel="stylesheet" type="text/css" href="/assets/css/mvp.css">-->
		<!-- Favicon and touch icons -->
		<link rel="shortcut icon" href="/assets/ico/favicon.ico">
		<link rel="apple-touch-icon-precomposed" sizes="144x144" href="/assets/ico/apple-touch-icon-144-precomposed.png">
		<link rel="apple-touch-icon-precomposed" sizes="114x114" href="/assets/ico/apple-touch-icon-114-precomposed.png">
		<link rel="apple-touch-icon-precomposed" sizes="72x72" href="/assets/ico/apple-touch-icon-72-precomposed.png">
		<link rel="apple-touch-icon-precomposed" href="/assets/ico/apple-touch-icon-57-precomposed.png">
		
		<script type="text/javascript" src="/assets/js/jquery.js"></script>
	</head>
	<body id="top">
	
		<div class="navbar navbar-fixed-top">
			<div class="navbar-inner">
				<div class="container">
					<!-- .btn-navbar is used as the toggle for collapsed navbar content -->
					<a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
					</a>
					<!-- Be sure to leave the brand out there if you want it shown -->
					<a class="brand" href="/">Cheep</a>
				
					<!-- Everything you want hidden at 940px or less, place within here -->
					<div class="nav-collapse">
					<!-- .nav, .navbar-search, .navbar-form, etc -->
						<!--<ul class="nav">
							<li><a href="/new">+ New Game</a></li>
						</ul>-->
						<ul class="nav pull-right">
							<? if (isset($user->email) && $user->email != "") { ?>
								<li><a href="/user" id="loggedInUser"><? echo $user->preferredname; ?></a></li>
							<? } ?>
							<li><a href="/about">About</a></li>
							<li><a href="http://blog.rightblank.com">Blog</a></li>
							<li><a data-toggle="modal" href="#feedbackModal" >Feedback</a></li>
							<? if (isset($user->email) && $user->email != "") { ?>
								<li><a href="/auth/logout">Log out</a></li>
							<? } else { ?>
								<li><a href="/auth/login">Log in</a></li>
							<? } ?>
							<? if(isset($user) && $user->group == 1) { ?>
								<li><a href="/admin/">Admin</a></li>
							<? } ?>
						</ul>
					</div>
				</div>
			</div>
		</div>
		<div class="container">
			<div id="content">
				<? if(($this->session->flashdata('message'))) { ?>
					<div class="alert alert-info" data-alert="alert" >
						<p><? echo $this->session->flashdata('message'); ?></p>
					</div>	
				<? } ?>