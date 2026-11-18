<?php $this->load->view('email/layout_top'); ?>

<h2>Estimad@ <?php echo html_escape($paciente); ?>,</h2>

<p>Sólo queríamos avisarte que el turno agendado para el día <?php echo html_escape($fecha); ?> fue cancelado.</p>

<p>Por cualquier consulta comunicarse al teléfono <?php echo html_escape(get_site_config('telefono')); ?></p>

<?php $this->load->view('email/layout_bottom'); ?>