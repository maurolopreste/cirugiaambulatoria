<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Email template</title>
	<style type="text/css">
		html, body {
			margin: 0;
			padding: 0;
		}
		body {
			font-family: Helvetica, 'Arial', sans-serif;
			font-size: 14px;
		}
		.btn {
			display: inline-block;
			margin: 15px 0;
			padding: .63rem 1rem;
			font-weight: 600;
			font-size: 1rem;
			line-height: 1.4;
			border-radius: .125rem;
			text-decoration: none;
			background-color: #3D968F;
			color: #fff;
		}
	</style>
</head>
<body>

	<div style="background: #f3f5fa; padding: 40px 15px">
		<div style="text-align: center">
			<a href="<?php echo site_url(); ?>">
				<img src="<?php echo base_url('/assets/img/logo-email.png'); ?>" alt="<?php echo html_escape(get_site_config('site_title')); ?>" height="50" />
			</a>
		</div>
		<br>
		<div style="width: 600px; max-width: 100%; margin: 0 auto; background: #fff; padding: 25px 15px; word-break: break-word;">