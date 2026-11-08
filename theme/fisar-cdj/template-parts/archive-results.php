<?php
$count = (int) $args['count'];
?>
<p class="archive-results" role="status" aria-live="polite" aria-atomic="true"><?php echo esc_html( sprintf( _n( '%s risultato', '%s risultati', $count, 'fisar-cdj' ), number_format_i18n( $count ) ) ); ?></p>
