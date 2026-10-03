<?php
if(!defined('ABSPATH'))exit;
class Hooshid_Books{
 public function __construct(){add_action('admin_post_hooshid_save_book',[$this,'save']);add_action('admin_post_hooshid_delete_book',[$this,'delete']);}
 public function save(){
  if(!current_user_can('manage_options')||!check_admin_referer('hooshid_save_book'))wp_die('دسترسی غیرمجاز');
  $id=absint($_POST['book_id']??0);$grade=absint($_POST['grade']??0);$subject=sanitize_text_field($_POST['subject']??'');$title=sanitize_text_field($_POST['title']??'');$attachment=absint($_POST['attachment_id']??0);
  if($grade<1||$grade>6||!$subject||!$attachment)wp_die('پایه، درس و فایل PDF الزامی است.');
  if(get_post_mime_type($attachment)!=='application/pdf')wp_die('فایل انتخاب‌شده باید PDF باشد.');
  global $wpdb;$t=$wpdb->prefix.'hooshid_books';$data=['grade'=>$grade,'subject'=>$subject,'title'=>$title?:$subject,'attachment_id'=>$attachment,'created_at'=>current_time('mysql')];$fmt=['%d','%s','%s','%d','%s'];
  if($id)$wpdb->update($t,$data,['id'=>$id],$fmt,['%d']);else $wpdb->insert($t,$data,$fmt);
  wp_safe_redirect(admin_url('admin.php?page=hooshid-books&saved=1'));exit;
 }
 public function delete(){if(!current_user_can('manage_options')||!check_admin_referer('hooshid_delete_book'))wp_die('دسترسی غیرمجاز');global $wpdb;$wpdb->delete($wpdb->prefix.'hooshid_books',['id'=>absint($_GET['id'])],['%d']);wp_safe_redirect(admin_url('admin.php?page=hooshid-books&deleted=1'));exit;}
 public static function page($id,$page=1){global $wpdb;return $wpdb->get_row($wpdb->prepare("SELECT b.*,p.page_text FROM {$wpdb->prefix}hooshid_books b LEFT JOIN {$wpdb->prefix}hooshid_book_pages p ON p.book_id=b.id AND p.page_number=%d WHERE b.id=%d",$page,$id));}
 public static function all(){global $wpdb;return $wpdb->get_results("SELECT b.*,COUNT(p.id) page_count FROM {$wpdb->prefix}hooshid_books b LEFT JOIN {$wpdb->prefix}hooshid_book_pages p ON p.book_id=b.id GROUP BY b.id ORDER BY b.grade,b.subject,b.id DESC");}
}