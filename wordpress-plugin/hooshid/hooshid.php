<?php
/**
 * Plugin Name: Hooshid
 * Description: هوشید؛ تخته آموزشی وب، مدیریت کتاب‌های PDF و اتصال امن به هوش مصنوعی.
 * Version: 1.0.0
 * Author: Hooshid
 * License: GPL-3.0-or-later
 */
if (!defined('ABSPATH')) exit;
define('HOOSHID_VERSION','1.0.0');
define('HOOSHID_FILE',__FILE__);
define('HOOSHID_DIR',plugin_dir_path(__FILE__));
define('HOOSHID_URL',plugin_dir_url(__FILE__));

require_once HOOSHID_DIR.'includes/class-hooshid.php';
require_once HOOSHID_DIR.'includes/class-hooshid-books.php';
require_once HOOSHID_DIR.'includes/class-hooshid-ai.php';
require_once HOOSHID_DIR.'includes/class-hooshid-shortcode.php';

register_activation_hook(__FILE__, ['Hooshid','activate']);
add_action('plugins_loaded', ['Hooshid','init']);
