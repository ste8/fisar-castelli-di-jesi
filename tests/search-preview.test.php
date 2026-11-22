<?php
/** Local WordPress test: contextual excerpts and safe, accent-insensitive highlighting. */
if ( ! defined( 'WP_CLI' ) || ! WP_CLI || 'local' !== wp_get_environment_type() ) {
	throw new RuntimeException( 'Run only in local WordPress with WP-CLI.' );
}
$check = static function ( bool $condition, string $message ): void {
	if ( ! $condition ) { throw new RuntimeException( $message ); }
};
$query = static function ( array $terms ): WP_Query {
	$q = new WP_Query();
	$q->set( 'search_terms', $terms );
	return $q;
};
$late = '<p>' . str_repeat( 'Introduzione lontana. ', 80 ) . '</p><p>La visita alla cantina Sèlce è stata un incontro speciale con i produttori.</p><p>' . str_repeat( 'Conclusione lunga. ', 80 ) . '</p>';
$excerpt = Fisar_CDJ_Search_Preview::excerpt( $late, $query( array( 'selce' ) ) );
$check( str_contains( $excerpt, '<mark>Sèlce</mark>' ), 'Find the matching passage beyond the opening, preserving the original accent.' );
$check( str_starts_with( $excerpt, '… ' ) && str_ends_with( $excerpt, ' …' ), 'Ellipses indicate omitted text at both ends.' );
$check( ! str_contains( $excerpt, 'Introduzione lontana. Introduzione lontana. Introduzione lontana. Introduzione lontana. Introduzione lontana. Introduzione lontana.' ), 'The excerpt is a short passage, not the whole document.' );
$check( '<mark>CITTÀ</mark> e <mark>città</mark>' === Fisar_CDJ_Search_Preview::highlight( 'CITTÀ e città', $query( array( 'citta' ) ) ), 'Accents and case match consistently.' );
$check( '<mark>Villa Locale</mark> e Villa' === Fisar_CDJ_Search_Preview::highlight( 'Villa Locale e Villa', $query( array( 'Villa Locale' ) ) ), 'Quoted phrases remain phrases.' );
$check( '<mark>Villa Locale</mark> e <mark>Villa</mark>' === Fisar_CDJ_Search_Preview::highlight( 'Villa Locale e Villa', $query( array( 'Villa', 'Villa Locale' ) ) ), 'Overlapping terms do not create nested marks.' );
$check( '<mark>vino</mark> e birra' === Fisar_CDJ_Search_Preview::highlight( 'vino e birra', $query( array( 'vino', '-birra' ) ) ), 'Excluded terms are never highlighted.' );
$check( '<mark>Æ</mark>' === Fisar_CDJ_Search_Preview::highlight( 'Æ', $query( array( 'a', 'e' ) ) ), 'Expanded normalization offsets cannot duplicate characters or overlap marks.' );
$safe = Fisar_CDJ_Search_Preview::highlight( '<img src=x onerror=alert(1)> vino & amici', $query( array( 'vino', '<img' ) ) );
$check( ! str_contains( $safe, '<img' ) && str_contains( $safe, '&lt;img' ) && str_contains( $safe, '<mark>vino</mark> &amp; amici' ), 'Only generated marks are HTML; source text and query cannot inject tags.' );
$clean = Fisar_CDJ_Search_Preview::excerpt( '<p>Vino &amp; persone</p><p>Una <strong>città</strong><br>accogliente.</p><script>secretScript</script>', $query( array( 'citta' ) ) );
$check( str_contains( $clean, 'Vino &amp; persone Una <mark>città</mark> accogliente.' ) && ! str_contains( $clean, 'secretScript' ), 'Blocks, entities and markup become readable text, without scripts.' );
$fallback = Fisar_CDJ_Search_Preview::excerpt( '<p>' . str_repeat( 'Una parola ', 50 ) . '</p>', $query( array( 'Palazzo' ) ) );
$check( ! str_contains( $fallback, '<mark>' ) && str_ends_with( $fallback, '…' ), 'Title/location-only matches keep the short introductory fallback.' );
$check( '' === Fisar_CDJ_Search_Preview::excerpt( '', $query( array( 'vino' ) ) ), 'Empty bodies remain empty.' );
$contacts = get_page_by_path( 'contatti' );
$delegate = fisar_cdj_get_delegation_details()['delegate_name'];
$generated = Fisar_CDJ_Search_Preview::excerpt( Fisar_CDJ_Global_Search::page_text( $contacts ), $query( array( $delegate ) ) );
$check( str_contains( $generated, '<mark>' . esc_html( $delegate ) . '</mark>' ), 'Generated public Contacts data appears in the contextual excerpt.' );
WP_CLI::success( 'Search previews: context, phrases, exclusions, Unicode offsets, safe HTML, fallback and Contacts passed.' );
