<?php
/**
 * Card news; usa il post globale.
 */
$excerpt = get_the_excerpt();
?>
<article <?php post_class( 'news-card' ); ?>>
	<?php if ( has_post_thumbnail() ) : ?>
		<a class="news-card__media" href="<?php the_permalink(); ?>" tabindex="-1" aria-hidden="true">
			<?php fisar_cdj_theme_post_image( get_the_ID(), 'news-card__image' ); ?>
		</a>
	<?php endif; ?>
	<div class="news-card__body">
		<h3 class="news-card__title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
		<p class="news-card__date"><?php echo fisar_cdj_theme_icon( 'calendar' ); ?><time datetime="<?php echo esc_attr( get_the_date( DATE_W3C ) ); ?>"><?php echo esc_html( get_the_date( 'j F Y' ) ); ?></time></p>
		<?php if ( '' !== trim( $excerpt ) ) : ?><p><?php echo esc_html( $excerpt ); ?></p><?php endif; ?>
		<a class="text-link" href="<?php the_permalink(); ?>">Leggi l’articolo<span class="screen-reader-text">: <?php echo esc_html( get_the_title() ); ?></span> <span aria-hidden="true">→</span></a>
	</div>
</article>
