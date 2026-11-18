<?php $this->load->view('email/layout_top'); ?>

<p>Estimado/a <?php echo html_escape($nombre); ?></p>

<p>A continuación adjuntamos la historia clínica de <strong><?php echo html_escape($paciente->last_name); ?>, <?php echo html_escape($paciente->first_name); ?></strong></p>

<p>Por cualquier consulta comunicarse al teléfono 261 571-6832 o por email info@cirugiaambulatoria.com.ar</p>

<?php $this->load->view('email/layout_bottom'); ?>