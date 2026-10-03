<?php
if(!defined('ABSPATH'))exit;
class Hooshid_AI{
 public function __construct(){add_action('rest_api_init',[$this,'routes']);}
 public function routes(){register_rest_route('hooshid/v1','/chat',['methods'=>'POST','callback'=>[$this,'chat'],'permission_callback'=>function(){return is_user_logged_in();}]);}
 public function chat(WP_REST_Request $r){
  $key=get_option('hooshid_openai_key','');$model=get_option('hooshid_openai_model','gpt-6-luna');
  if(!$key)return new WP_Error('no_api_key','کلید OpenAI تنظیم نشده است',['status'=>400]);
  $message=sanitize_textarea_field($r->get_param('message'));if(!$message)return new WP_Error('empty_message','پیام خالی است',['status'=>400]);
  $res=wp_remote_post('https://api.openai.com/v1/responses',['timeout'=>90,'headers'=>['Authorization'=>'Bearer '.$key,'Content-Type'=>'application/json'],'body'=>wp_json_encode(['model'=>$model,'instructions'=>'تو دستیار آموزشی هوشید هستی. پاسخ‌ها را روشن، دقیق، آموزشی و مناسب دانش‌آموزان ارائه کن. اگر کاربر درباره یک صفحه کتاب سؤال کرد، فقط بر اساس متن صفحه‌ای که در پیام داده شده پاسخ بده.','input'=>$message])]);
  if(is_wp_error($res))return $res;$code=wp_remote_retrieve_response_code($res);$body=json_decode(wp_remote_retrieve_body($res),true);
  if($code<200||$code>=300)return new WP_Error('openai_error',$body['error']['message']??'خطا در ارتباط با OpenAI',['status'=>502]);
  $text=$body['output_text']??'';
  if(!$text&&isset($body['output'])&&is_array($body['output']))foreach($body['output'] as $item)foreach(($item['content']??[]) as $part)if(isset($part['text']))$text.=$part['text'];
  return $text?['reply'=>$text]:new WP_Error('ai_error','پاسخ معتبر از OpenAI دریافت نشد.',['status'=>502]);
 }
}