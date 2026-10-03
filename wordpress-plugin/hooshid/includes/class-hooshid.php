<?php
if (!defined('ABSPATH')) exit;
class Hooshid {
  public static function init() {
    new Hooshid_Books();
    new Hooshid_AI();
    new Hooshid_Shortcode();
    add_action('admin_menu',[__CLASS__,'admin_menu']);
    add_action('admin_init',[__CLASS__,'settings']);
    add_action('wp_enqueue_scripts',[__CLASS__,'assets']);
  }
  public static function activate() {
    global $wpdb;
    require_once ABSPATH.'wp-admin/includes/upgrade.php';
    $table=$wpdb->prefix.'hooshid_books';
    $charset=$wpdb->get_charset_collate();
    dbDelta("CREATE TABLE $table (
      id bigint(20) unsigned NOT NULL AUTO_INCREMENT,
      grade tinyint(3) unsigned NOT NULL,
      subject varchar(100) NOT NULL,
      title varchar(255) NOT NULL,
      attachment_id bigint(20) unsigned NOT NULL,
      pages longtext NULL,
      created_at datetime NOT NULL,
      PRIMARY KEY (id), KEY grade (grade), KEY subject (subject)
    ) $charset;");
  }
  public static function admin_menu() {
    add_menu_page('هوشید','هوشید','manage_options','hooshid','Hooshid::dashboard','dashicons-welcome-learn-more',30);
    add_submenu_page('hooshid','تنظیمات','تنظیمات','manage_options','hooshid-settings','Hooshid::settings_page');
    add_submenu_page('hooshid','درس‌ها','درس‌ها','manage_options','hooshid-books','Hooshid::books_page');
  }
  public static function settings() {
    register_setting('hooshid_settings','hooshid_openai_key',['sanitize_callback'=>'sanitize_text_field']);
    register_setting('hooshid_settings','hooshid_openai_model',['sanitize_callback'=>'sanitize_text_field']);
  }
  public static function assets() {
    wp_register_style('hooshid',HOOSHID_URL.'assets/css/hooshid.css',[],HOOSHID_VERSION);
    wp_register_script('hooshid',HOOSHID_URL.'assets/js/hooshid-board.js',[],HOOSHID_VERSION,true);
  }
  public static function dashboard(){ echo '<div class="wrap"><h1>هوشید</h1><p>تخته آموزشی، کتاب‌های درسی و هوش مصنوعی.</p><p>شورتکد: <code>[hooshid]</code></p></div>'; }
  public static function settings_page(){ ?>
    <div class="wrap"><h1>تنظیمات هوشید</h1>
    <form method="post" action="options.php"><?php settings_fields('hooshid_settings'); ?>
      <table class="form-table">
        <tr><th>OpenAI API Key</th><td><input type="password" class="regular-text" name="hooshid_openai_key" value="<?php echo esc_attr(get_option('hooshid_openai_key','')); ?>"></td></tr>
        <tr><th>مدل</th><td><input class="regular-text" name="hooshid_openai_model" value="<?php echo esc_attr(get_option('hooshid_openai_model','gpt-4o-mini')); ?>"></td></tr>
      </table><?php submit_button('ذخیره تنظیمات'); ?></form></div><?php
  }
  public static function books_page(){ echo '<div class="wrap"><h1>درس‌ها و کتاب‌ها</h1><p>از زیرمنوی «درس‌ها» کتاب‌های PDF را برای هر پایه و درس مدیریت کنید.</p></div>'; }
}
