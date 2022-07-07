<?php $this->load->view('email/layout_top'); ?>

<h2>Estimad@ <?php echo $paciente; ?>,</h2>

<p>Sólo queríamos avisarte que el turno agendado para el día <?php echo $fecha; ?> fue cancelado.</p>

<p>Por cualquier consulta comunicarse al teléfono <?php echo get_site_config('telefono'); ?></p>

<?php $this->load->view('email/layout_bottom'); ?>