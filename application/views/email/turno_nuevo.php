<?php $this->load->view('email/layout_top'); ?>

<h2>Hola <?php echo html_escape($paciente); ?>,</h2>

<p><strong>Cirugía Ambulatoria</strong> te ha asignado un turno con <strong><?php echo html_escape($profesional); ?></strong>, el día <strong><?php echo html_escape($fecha); ?>.</strong></p>

<p>Domicilio de atención: Av. J.B. Justo y Paso de los Andes - Ciudad - Mendoza.</p>

<p>Te lo recordaremos un día antes del turno.<br>
Por cualquier consulta, llamanos al 2615716832.</p>

<?php $this->load->view('email/layout_bottom'); ?>