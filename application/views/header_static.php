<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta http-equiv="Content-Language" content="en" />
	<link rel="canonical" href="<?php echo site_url($this->uri->uri_string()); ?>" />
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<meta http-equiv="x-ua-compatible" content="ie=edge">
	<title><?php echo !empty($page_title) ? $page_title : get_site_config('site_title'); ?></title>
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
    <link href="https://unpkg.com/bootstrap-datepicker@1.9.0/dist/css/bootstrap-datepicker3.min.css" rel="stylesheet">
	<link rel="stylesheet" href="<?php echo base_url('/assets/css/graindashboard.css'); ?>">
	<link rel="stylesheet" href="<?php echo base_url('/assets/css/bootstrap-print.min.css'); ?>" media="print">
</head>
<body>

	<main class="main">
		<div class="content">
			