<?php
namespace app\admin\validate;
use think\validate;
//消息模板验证器
class UserMsgTpl extends validate
{
    protected $rule=[
        'mmt_code'                     => 'require',
        'mmt_name'                     => 'require',
        'mmt_message_content'          => 'require',
        'mmt_short_content'            => 'require',
        'mmt_mail_subject'             => 'require',
        'mmt_mail_content'             => 'require',
    ];
    protected $message = [
        'mmt_code.require'             => '模板编号必填',
        'mmt_name.require'             => '模板名称必填',
        'mmt_message_content.require'  => '站内信消息内容必填',
        'mmt_short_content.require'    => '短信接收内容必填',
        'mmt_mail_subject.require'     => '邮件标题必填',
        'mmt_mail_content.require'     => '邮件内容必填',
    ];
    protected $scene = [
        'add'  => ['mmt_code','mmt_name','mmt_message_content','mmt_short_content','mmt_mail_subject','mmt_mail_subject'],
        'edit' => ['mmt_name','mmt_message_content','mmt_short_content','mmt_mail_subject','mmt_mail_subject'],
        'del'  => ['mmt_code'],
    ];

}