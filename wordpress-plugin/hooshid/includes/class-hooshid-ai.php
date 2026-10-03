<?php
if (!defined('ABSPATH')) exit;
class Hooshid_AI {
  public function __construct(){ add_action('rest_api_init',[$this,'routes']); }
  public function routes(){ register_rest_route('hooshid/v1','/chat',[
    'methods'=>'POST','callback'=>[$this,'chat'],'permission_callback'=>function(){ return is_user_logged_in(); }
  ]); }
  public function chat(WP_REST_Request $r){
    $key=get_option('hooshid_openai_key',''); $model=get_option('hooshid_openai_model','gpt-4o-mini');
    if(!$key) return new WP_Error('no_api_key','کلید OpenAI تنظیم نشده است',['status'=>400]);
    $message=sanitize_textarea_field($r->get_param('message'));
    if(!$message) return new WP_Error('empty_message','پیام خالی است',['status'=>400]);
    $res=wp_remote_post('https://api.openai.com/v1/chat/completions',[
      'timeout'=>60,'headers'=>['Authorization'=>'Bearer '.$key,'Content-Type'=>'application/json'],
      'body'=>wp_json_encode(['model'=>$model,'messages'=>[
        ['role'=>'system','content'=>'تو دستیار آموزشی هوشید هستی. پاسخ‌ها را روشن، آموزشی و مناسب دانش‌آموزان ارائه کن.'],
        ['role'=>'user','content'=>$message]
      ]])
    ]);
    if(is_wp_error($res)) return $res;
    $body=json_decode(wp_remote_retrieve_body($res),true);
    $text=$body['choices'][0]['message']['content']??'';
    if(!$text) return new WP_Error('ai_error','پاسخ معتبر از OpenAI دریافت نشد.',['status'=>502]);
    return ['reply'=>$text];
  }
}
