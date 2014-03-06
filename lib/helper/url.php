<?php

/**
 * Generate a rewritten url.
 *
 * TODO: Determine route automatically.
 *
 * @param string uri
 */
function gen_url($uri) {
	return Routing::generate($uri, '/:module/:action/*');
}

?>
