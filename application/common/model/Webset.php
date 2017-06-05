<?php
namespace app\common\model;
use think\Model;

class Webset extends Model{
    protected $pk='webset_id';
    protected $table='blog_webset';
    public function edit($data){
        $result  = $this->validate([
            'webset_value'=>'require',
        ],[
            'webset_value.require'=>'请输入站点的配置值',
        ])->save($data,[$this->pk=>$data['webset_id']]);
        if($result){
            return ['valid'=>1,'msg'=>'操作成功'];
        }else{
            return ['valid'=>0,'msg'=>$this->getError()];
        }
    }
}