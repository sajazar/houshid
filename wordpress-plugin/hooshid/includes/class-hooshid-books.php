<?php
if (!defined('ABSPATH')) exit;
class Hooshid_Books {
  public function __construct(){
    add_action('admin_post_hooshid_save_book',[$this,'save']);
    add_action('admin_post_hooshid_delete_book',[$this,'delete']);
    add_action('admin_init',[$this,'render_admin_page']);
  }
  public function render_admin_page(){
    if (!is_admin() || !isset($_GET['page']) || $_GET['page']!=='hooshid-books') return;
    add_action('admin_notices',[$this,'notice']);
  }
  public function notice(){}
  public function save(){
    if (!current_user_can('manage_options') || !check_admin_referer('hooshid_save_book')) wp_die('دسترسی غیرمجاز');
    $attachment_id=absint($_POST['attachment_id']??0);
    $grade=absint($_POST['grade']??0);
    $subject=sanitize_text_field($_POST['subject']??'');
    $title=sanitize_text_field($_POST['title']??'');
    if(!$attachment_id || $grade<1 || $grade>6 || !$subject) wp_die('اطلاعات کتاب کامل نیست.');
    global $wpdb; $wpdb->insert($wpdb->prefix.'hooshid_books',[
      'grade'=>$grade,'subject'=>$subject,'title'=>$title ?: $subject,
      'attachment_id'=>$attachment_id,'created_at'=>current_time('mysql')
    ],['%d','%s','%s','%d','%s']);
    wp_safe_redirect(admin_url('admin.php?page=hooshid-books&saved=1')); exit;
  }
  public function delete(){
    if (!current_user_can('manage_options') || !check_admin_referer('hooshid_delete_book')) wp_die('دسترسی غیرمجاز');
    global $wpdb; $wpdb->delete($wpdb->prefix.'hooshid_books',['id'=>absint($_GET['id'])],['%d']);
    wp_safe_redirect(admin_url('admin.php?page=hooshid-books&deleted=1')); exit;
  }
}
