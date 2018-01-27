<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! is_array( $results ) ) {
	echo "<!-- no results -->\n";

	return;
}

echo '<ol>';
foreach ( $results as $item ) {
	echo sprintf( '<li><a href="%s">%s</a></li>',
		esc_url( $item->link ),
		esc_html( $item->friendly_path )
	);
}
echo '</ol>';
echo $more_text;