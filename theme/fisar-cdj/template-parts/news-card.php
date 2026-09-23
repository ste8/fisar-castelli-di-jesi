<?php
/**
 * Card news; usa il post globale.
 */
?>
<article <?php post_class( 'news-card' ); ?>>
	<a class="news-card__media" href="<?php the_permalink(); ?>" tabindex="-1" aria-hidden="true">
		<?php fisar_cdj_theme_post_image( get_the_ID(), 'news-card__image' ); ?>
	</a>
	<div class="news-card__body">
		<p class="news-card__date"><time datetime="<?php echo esc_attr( get_the_date( DATE_W3C ) ); ?>"><?php echo esc_html( get_the_date( 'j F Y' ) ); ?></time></p>
		<h3 class="news-card__title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
		<p><?php echo esc_html( get_the_excerpt() ); ?></p>
		<a class="text-link" href="<?php the_permalink(); ?>">Leggi l’articolo<span class="screen-reader-text">: <?php echo esc_html( get_the_title() ); ?></span> <span aria-hidden="true">→</span></a>
	</div>
</article>
