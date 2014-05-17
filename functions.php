<?php

define('KOPA_THEME_NAME', 'Forceful');
define('KOPA_DOMAIN', 'forceful');
define('KOPA_CPANEL_IMAGE_DIR', get_template_directory_uri() . '/library/images/layout/');
define('KOPA_UPDATE_TIMEOUT', 21600);
define('KOPA_UPDATE_URL', 'http://kopatheme.com/notifier/forceful.xml');

require trailingslashit(get_template_directory()) . '/library/kopa.php';
require trailingslashit(get_template_directory()) . '/library/ini.php';

require trailingslashit(get_template_directory()) . '/library/includes/ajax.php';
require trailingslashit(get_template_directory()) . '/library/includes/metabox/post.php';
require trailingslashit(get_template_directory()) . '/library/includes/metabox/category.php';
require trailingslashit(get_template_directory()) . '/library/includes/metabox/page.php';
require trailingslashit(get_template_directory()) . '/library/front.php';

// post rating metabox
require trailingslashit(get_template_directory()) . '/library/includes/options_post_rating.php';

/*
 * Custom Header
 */
require get_template_directory().'/library/custom-header.php';