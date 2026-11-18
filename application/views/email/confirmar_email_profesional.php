<?php $this->load->view('email/layout_top'); ?>

<h2>Bienvenid@ a Cirugía Ambulatoria, <?php echo html_escape($nombre); ?>!</h2>

<p>Por favor haz click en <strong>Completar Registro</strong> para activar tu cuenta.</p>

<a href="<?php echo html_escape($url); ?>" class="btn">Completar Registro</a>

<p>Si el link de arriba no funciona, copia y pega el siguiente link en una pestaña nueva: <?php echo html_escape($url); ?></p>

<?php $this->load->view('email/layout_bottom'); ?>