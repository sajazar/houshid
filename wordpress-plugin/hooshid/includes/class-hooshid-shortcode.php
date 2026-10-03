<?php
if (!defined('ABSPATH')) exit;
class Hooshid_Shortcode {
 public function __construct(){add_shortcode('hooshid',[$this,'render']);}
 public function render(){
  wp_enqueue_style('hooshid'); wp_enqueue_script('hooshid');
  ob_start(); ?>
  <div class="hooshid-app">
   <header class="hooshid-toolbar"><strong>هوشید</strong>
    <button data-tool="pen">قلم</button><button data-tool="eraser">پاک‌کن</button>
    <button data-tool="line">خط</button><button data-tool="rect">مستطیل</button>
    <button data-tool="circle">دایره</button><button data-action="undo">↶</button>
    <button data-action="redo">↷</button><button data-action="clear">پاک کردن</button>
   </header>
   <main class="hooshid-main"><section class="hooshid-board-wrap"><canvas class="hooshid-canvas"></canvas></section>
   <aside class="hooshid-chat"><h3>دستیار هوشید</h3><div class="hooshid-messages"></div>
   <form><textarea placeholder="مثلاً: صفحه ۱۲ ریاضی پنجم را توضیح بده"></textarea><button>ارسال</button></form></aside></main>
  </div><?php return ob_get_clean();
 }
}