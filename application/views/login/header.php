<!DOCTYPE html>
<html lang="en" xmlns="https://www.w3.org/1999/xhtml" xmlns:fb="https://www.facebook.com/2008/fbml" xmlns:og="https://www.facebook.com/2008/fbml" xmlns:og="https://opengraphprotocol.org/schema/">
<head>
	<meta charset="utf-8">
	<meta http-equiv="Content-Language" content="en" />
	<link rel="canonical" href="<?php echo html_escape(site_url($this->uri->uri_string())); ?>" />
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=0">
	<title><?php echo html_escape(get_site_config('site_title')); ?></title>
	<link rel="icon" type="image/png" href="<?php echo base_url('/favicon.png'); ?>">
	<meta name="theme-color" content="<?php echo html_escape(get_site_config('primary_color')); ?>">
	<meta name="description" content="">

	<style id="theme-options">
		:root {
			--primary-color: <?php echo html_escape(get_site_config('primary_color')); ?>;
			--font-family: 'Source Sans Pro', Helvetica, Arial, sans-serif;
		}
	</style>

	<link rel="preconnect" href="https://fonts.gstatic.com">
	<link href="https://fonts.googleapis.com/css2?family=Source+Sans+Pro:wght@400;500&display=swap" rel="stylesheet">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" integrity="sha512-iBBXm8fW90+nuLcSKlbmrPcLa0OT92xO1BIsZ+ywDWZCvqsWgccV3gFoRBv0z+8dLJgyAHIhR35VZc2oM/gI1w==" crossorigin="anonymous" />
	<link rel="stylesheet" href="<?php echo base_url('/assets/css/graindashboard.css'); ?>">
	<meta name="csrf-token" content="<?php echo html_escape($this->security->get_csrf_hash()); ?>" data-name="<?php echo html_escape($this->security->get_csrf_token_name()); ?>">
</head>
<body>

	<!-- <a href="https://api.whatsapp.com/send/?phone=5492612655517&text=" class="float_wa">
		<i class="fab fa-whatsapp"></i>
	</a> -->

	<main class="main">
		<div class="content">
			<div class="container-fluid pb-5">
				<div class="row justify-content-md-center">
					<div class="card-wrapper col-12 col-md-<?php echo isset($large) ? '6' : '4'; ?> pt-5">
						<div class="brand text-center mb-5">
							<a href="<?php echo site_url(); ?>"><img src="<?php echo html_escape(base_url('uploads/'.get_site_config('logo'))); ?>" alt="<?php echo html_escape(get_site_config('site_title')); ?>" height="50"></a>
						</div>

						<?php $this->load->view('alert'); ?>
						