<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta http-equiv="Content-Language" content="en" />
	<link rel="canonical" href="<?php echo site_url($this->uri->uri_string()); ?>" />
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<meta http-equiv="x-ua-compatible" content="ie=edge">
	<title><?php echo get_site_config('site_title'); ?></title>
	<link rel="icon" type="image/png" href="<?php echo base_url('/favicon.png'); ?>">
	<meta name="theme-color" content="<?php echo get_site_config('primary_color'); ?>">
	<meta name="description" content="">

	<style id="theme-options">
		:root {
			--primary-color: <?php echo get_site_config('primary_color'); ?>;
			--font-family: 'Source Sans Pro', Helvetica, Arial, sans-serif;
		}
	</style>

	<link rel="preconnect" href="https://fonts.gstatic.com">
	<link href="https://fonts.googleapis.com/css2?family=Source+Sans+Pro:wght@400;500&family=Oswald:wght@700&display=swap" rel="stylesheet">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" integrity="sha512-iBBXm8fW90+nuLcSKlbmrPcLa0OT92xO1BIsZ+ywDWZCvqsWgccV3gFoRBv0z+8dLJgyAHIhR35VZc2oM/gI1w==" crossorigin="anonymous" />
	<link rel="stylesheet" href="<?php echo base_url('/assets/css/select2.min.css'); ?>">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-datepicker@1.9.0/dist/css/bootstrap-datepicker3.min.css" rel="stylesheet">
	<link rel="stylesheet" href="<?php echo base_url('/assets/css/graindashboard.css'); ?>">

</head>
<body class="has-sidebar has-fixed-sidebar-and-header">

	<div class="toast alert-success fade" role="alert" aria-live="assertive" aria-atomic="true" data-delay="3500">
		<div class="toast-body text-center"></div>
	</div>

	<!-- <a href="https://api.whatsapp.com/send/?phone=5492612655517&text=" target="_blank" class="float_wa">
		<i class="fab fa-whatsapp"></i>
	</a> -->

	<!-- Header -->
	<header class="header bg-body">
		<nav class="navbar flex-nowrap p-0">
			<div class="navbar-brand-wrapper d-flex align-items-center col-auto">
				<!-- Logo For Mobile View -->
				<a class="navbar-brand navbar-brand-mobile" href="<?php echo site_url(); ?>">
					<img class="img-fluid w-100" src="<?php echo base_url('uploads/'.get_site_config('logo')); ?>" height="40" alt="<?php echo get_site_config('site_title'); ?>">
				</a>

				<!-- Logo For Desktop View -->
				<a class="navbar-brand navbar-brand-desktop" href="<?php echo site_url(); ?>">
					<img class="side-nav-show-on-closed" src="<?php echo base_url('assets/img/logo-mini.png'); ?>" alt="<?php echo get_site_config('site_title'); ?>">
					<img class="side-nav-hide-on-closed" src="<?php echo base_url('uploads/'.get_site_config('logo')); ?>" height="50" alt="<?php echo get_site_config('site_title'); ?>">
				</a>
			</div>

			<div class="header-content col px-md-3">
				<div class="d-flex align-items-center">

					<!-- Side Nav Toggle -->
					<a  class="js-side-nav header-invoker d-flex mr-md-2" href="#"
						data-close-invoker="#sidebarClose"
						data-target="#sidebar"
						data-target-wrapper="body">
						<i class="gd-align-left"></i>
					</a>

					<?php /*<!-- User Notifications -->
					<div class="dropdown ml-auto">
						<a id="notificationsInvoker" class="header-invoker" href="#" aria-controls="notifications" aria-haspopup="true" aria-expanded="false" data-unfold-event="click" data-unfold-target="#notifications" data-unfold-type="css-animation" data-unfold-duration="300" data-unfold-animation-in="fadeIn" data-unfold-animation-out="fadeOut">
							<span class="indicator indicator-bordered indicator-top-right indicator-primary rounded-circle"></span>
							<i class="gd-bell"></i>
						</a>

						<div id="notifications" class="dropdown-menu dropdown-menu-center py-0 mt-4 w-18_75rem w-md-22_5rem unfold-css-animation unfold-hidden fadeOut" aria-labelledby="notificationsInvoker" style="animation-duration: 300ms;">
							<div class="card">
								<div class="card-header d-flex align-items-center border-bottom py-3">
									<h5 class="mb-0">Notificaciones</h5>
									<a class="link small ml-auto" href="#">Eliminar notificaciones</a>
								</div>
								<div class="card-body p-0">
									<div class="list-group list-group-flush">
										<div class="list-group-item list-group-item-action">
											<div class="d-flex align-items-center text-nowrap mb-2">
												<i class="gd-info-alt icon-text text-primary mr-2"></i>
												<h6 class="font-weight-semi-bold mb-0">Nueva venta</h6>
												<span class="list-group-item-date text-muted ml-auto">ahora</span>
											</div>
											<p class="mb-0">
												La orden <strong>#10000</strong> fue creada
											</p>
											<a class="list-group-item-closer text-muted" href="#"><i class="gd-close"></i></a>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
					<!-- End User Notifications -->*/ ?>

					<!-- User Avatar -->
					<div class="dropdown mx-3 dropdown ml-auto">
						<a id="profileMenuInvoker" class="header-complex-invoker" href="#" aria-controls="profileMenu" aria-haspopup="true" aria-expanded="false" data-unfold-event="click" data-unfold-target="#profileMenu" data-unfold-type="css-animation" data-unfold-duration="300" data-unfold-animation-in="fadeIn" data-unfold-animation-out="fadeOut">
							<span class="mr-md-2 avatar-placeholder"><?php echo substr($this->login->first_name, 0, 1); ?></span>
							<span class="d-none d-md-block">
								<?php echo $this->login->first_name; ?>
							</span>
							<i class="gd-angle-down d-none d-md-block ml-2"></i>
						</a>
						<ul id="profileMenu" class="unfold unfold-user unfold-light unfold-top unfold-centered position-absolute pt-2 pb-1 mt-4 unfold-css-animation unfold-hidden fadeOut" aria-labelledby="profileMenuInvoker" style="animation-duration: 300ms;">
							<li class="unfold-item">
								<a class="unfold-link d-flex align-items-center text-nowrap" href="<?php echo site_url('/perfil'); ?>">
									<span class="unfold-item-icon mr-3">
										<i class="gd-user"></i>
									</span>
									<?php echo lang('Mi perfil'); ?>
								</a>
							</li>
							<li class="unfold-item">
								<a class="unfold-link d-flex align-items-center text-nowrap" href="<?php echo site_url('/logout'); ?>">
									<span class="unfold-item-icon mr-3">
										<i class="gd-power-off"></i>
									</span>
									<?php echo lang('Salir'); ?>
								</a>
							</li>
						</ul>
					</div>
				</div>
			</div>
		</nav>
	</header>
	<!-- End Header -->

	<main class="main">
		<!-- Sidebar Nav -->
		<aside id="sidebar" class="js-custom-scroll side-nav">
			<ul id="sideNav" class="side-nav-menu side-nav-menu-top-level mb-0">
				<?php if(!isset($this->uri->segments[1])) $this->uri->segments[1] = FALSE; ?>
				<?php if(!isset($this->uri->segments[2])) $this->uri->segments[2] = FALSE; ?>

				<?php if($this->is_paciente): ?>
					<li class="sidebar-heading h6">Portal Paciente</li>

					<li class="side-nav-menu-item<?php if('dashboard'==$this->uri->segments[1]) echo ' active'; ?>">
						<a class="side-nav-menu-link media align-items-center" href="<?php echo site_url('dashboard'); ?>">
							<span class="side-nav-menu-icon d-flex mr-3">
								<i class="gd-home"></i>
							</span>
							<span class="side-nav-fadeout-on-closed media-body"><?php echo lang('Inicio'); ?></span>
						</a>
					</li>

					<li class="side-nav-menu-item<?php if('prescripcion'==$this->uri->segments[1]) echo ' active'; ?>">
						<a class="side-nav-menu-link media align-items-center" href="<?php echo site_url('prescripcion'); ?>">
							<span class="side-nav-menu-icon d-flex mr-3">
								<i class="gd-pencil-alt"></i>
							</span>
							<span class="side-nav-fadeout-on-closed media-body"><?php echo lang('Prescripciones'); ?></span>
						</a>
					</li>

					<li class="side-nav-menu-item<?php if('cita'==$this->uri->segments[1]) echo ' active'; ?>">
						<a class="side-nav-menu-link media align-items-center" href="<?php echo site_url('cita'); ?>">
							<span class="side-nav-menu-icon d-flex mr-3">
								<i class="gd-calendar"></i>
							</span>
							<span class="side-nav-fadeout-on-closed media-body"><?php echo lang('Citas Agendadas'); ?></span>
						</a>
					</li>

					<li class="side-nav-menu-item<?php if('perfil'==$this->uri->segments[1]) echo ' active'; ?>">
						<a class="side-nav-menu-link media align-items-center" href="<?php echo site_url('perfil'); ?>">
							<span class="side-nav-menu-icon d-flex mr-3">
								<i class="gd-user"></i>
							</span>
							<span class="side-nav-fadeout-on-closed media-body"><?php echo lang('Mi Perfil'); ?></span>
						</a>
					</li>
				<?php endif; ?>

				<?php if($this->is_profesional): ?>
					<li class="sidebar-heading h6">Administración Profesional</li>

					<li class="side-nav-menu-item<?php if('dashboard'==$this->uri->segments[1]) echo ' active'; ?>">
						<a class="side-nav-menu-link media align-items-center" href="<?php echo site_url('dashboard'); ?>">
							<span class="side-nav-menu-icon d-flex mr-3">
								<i class="gd-home"></i>
							</span>
							<span class="side-nav-fadeout-on-closed media-body"><?php echo lang('Inicio'); ?></span>
						</a>
					</li>

					<li class="side-nav-menu-item<?php if('agenda'==$this->uri->segments[1]) echo ' active'; ?>">
						<a class="side-nav-menu-link media align-items-center" href="<?php echo site_url('agenda'); ?>">
							<span class="side-nav-menu-icon d-flex mr-3">
								<i class="gd-calendar"></i>
							</span>
							<span class="side-nav-fadeout-on-closed media-body"><?php echo lang('Agenda'); ?></span>
						</a>
					</li>

					<li class="side-nav-menu-item<?php if('paciente'==$this->uri->segments[1]) echo ' active'; ?>">
						<a class="side-nav-menu-link media align-items-center" href="<?php echo site_url('paciente'); ?>">
							<span class="side-nav-menu-icon d-flex mr-3">
								<i class="gd-user"></i>
							</span>
							<span class="side-nav-fadeout-on-closed media-body"><?php echo lang('Pacientes'); ?></span>
						</a>
					</li>
				<?php endif; ?>

				<?php if($this->is_admin): ?>
					<li class="sidebar-heading h6">Administración</li>

					<li class="side-nav-menu-item<?php if('dashboard'==$this->uri->segments[1]) echo ' active'; ?>">
						<a class="side-nav-menu-link media align-items-center" href="<?php echo site_url('dashboard'); ?>">
							<span class="side-nav-menu-icon d-flex mr-3">
								<i class="gd-home"></i>
							</span>
							<span class="side-nav-fadeout-on-closed media-body"><?php echo lang('Inicio'); ?></span>
						</a>
					</li>

					<li class="side-nav-menu-item<?php if('agenda'==$this->uri->segments[1]) echo ' active'; ?>">
						<a class="side-nav-menu-link media align-items-center" href="<?php echo site_url('agenda'); ?>">
							<span class="side-nav-menu-icon d-flex mr-3">
								<i class="gd-calendar"></i>
							</span>
							<span class="side-nav-fadeout-on-closed media-body"><?php echo lang('Agenda'); ?></span>
						</a>
					</li>

					<!-- <li class="side-nav-menu-item<?php if('pagos'==$this->uri->segments[1]) echo ' active'; ?>">
						<a class="side-nav-menu-link media align-items-center" href="<?php echo site_url('pagos'); ?>">
							<span class="side-nav-menu-icon d-flex mr-3">
								<i class="gd-money"></i>
							</span>
							<span class="side-nav-fadeout-on-closed media-body"><?php echo lang('Pagos'); ?></span>
						</a>
					</li> -->

					<li class="side-nav-menu-item<?php if('paciente'==$this->uri->segments[1]) echo ' active'; ?>">
						<a class="side-nav-menu-link media align-items-center" href="<?php echo site_url('paciente'); ?>">
							<span class="side-nav-menu-icon d-flex mr-3">
								<i class="gd-user"></i>
							</span>
							<span class="side-nav-fadeout-on-closed media-body"><?php echo lang('Pacientes'); ?></span>
						</a>
					</li>
					
					<li class="side-nav-menu-item<?php if('profesional'==$this->uri->segments[1]) echo ' active'; ?>">
						<a class="side-nav-menu-link media align-items-center" href="<?php echo site_url('profesional'); ?>">
							<span class="side-nav-menu-icon d-flex mr-3">
								<i class="gd-briefcase"></i>
							</span>
							<span class="side-nav-fadeout-on-closed media-body"><?php echo lang('Profesionales'); ?></span>
						</a>
					</li>

					<li class="side-nav-menu-item side-nav-has-menu<?php if('config'==$this->uri->segments[1]) echo ' side-nav-opened active'; ?>">
					<a class="side-nav-menu-link media align-items-center" href="#" data-target="#dropdownConfig">
						<span class="side-nav-menu-icon d-flex mr-3">
							<i class="gd-settings"></i>
						</span>
						<span class="side-nav-fadeout-on-closed media-body">Parámetros</span>
						<span class="side-nav-control-icon d-flex">
							<i class="gd-angle-right side-nav-fadeout-on-closed"></i>
						</span>
						<span class="side-nav__indicator side-nav-fadeout-on-closed"></span>
					</a>
					<ul id="dropdownConfig" class="side-nav-menu side-nav-menu-second-level mb-0" style="<?php if('config'==$this->uri->segments[1]) echo ' display: block'; ?>">
						<li class="side-nav-menu-item">
							<a class="side-nav-menu-link<?php if(strpos($this->uri->uri_string(), 'config/obra_social')!==false) echo ' active'; ?>" href="<?php echo site_url('config/obra_social'); ?>">Obras Sociales</a>
						</li>
						<li class="side-nav-menu-item">
							<a class="side-nav-menu-link<?php if(strpos($this->uri->uri_string(), 'config/art')!==false) echo ' active'; ?>" href="<?php echo site_url('config/art'); ?>">ART</a>
						</li>
						<!-- <li class="side-nav-menu-item">
							<a class="side-nav-menu-link<?php if(strpos($this->uri->uri_string(), 'config/medicamento')!==false) echo ' active'; ?>" href="<?php echo site_url('config/medicamento'); ?>">Medicamentos</a>
						</li> -->
					</ul>

					<li class="side-nav-menu-item<?php if('user'==$this->uri->segments[1]) echo ' active'; ?>">
						<a class="side-nav-menu-link media align-items-center" href="<?php echo site_url('user'); ?>">
							<span class="side-nav-menu-icon d-flex mr-3">
								<i class="gd-user"></i>
							</span>
							<span class="side-nav-fadeout-on-closed media-body"><?php echo lang('Usuarios Sistema'); ?></span>
						</a>
					</li>
				</li>
					
				<?php endif; ?>
			</ul>
		</aside>
		<!-- End Sidebar Nav -->

		<div class="content">
			