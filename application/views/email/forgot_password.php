<?php $this->load->view('email/layout_top'); ?>

<h2>¡Hola!</h2>

<p>Al parecer has olvidado tu contraseña.<br>

<p>Pero no te preocupes, puedes crear una nueva clave desde el siguiente link:</p>

<a href="<?php echo $url; ?>" class="btn">Cambiar contraseña</a>

<p>Si el link de arriba no funciona, copia y pega el siguiente link en una pestaña nueva: <?php echo $url; ?></p>

<?php $this->load->view('email/layout_bottom'); ?>