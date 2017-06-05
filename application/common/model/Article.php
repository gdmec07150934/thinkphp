<?php
namespace app\common\model;

use think\Model;

class Article extends Model{
    protected $pk='arc_id';
    protected $table='blog_article';
    protected $auto =['admin_id'];
    protected $insert=['sendtime'];
    protected $update = ['updatetime'];
    protected function setAdminIdAttr($value)
    {
        return session('admin.admin_id');
    }
    protected function setSendTimeAttr($value)
    {
        return time();
    }
    protected function setUpdateTimeAttr($value)
    {
        return time();
    }
    public function getAll($is_recycle){
         return db('article')->alias('a')
            ->join('__CATE__ c','a.cate_id=c.cate_id')
            ->where('a.is_recycle',$is_recycle)
            ->field('a.arc_id,a.arc_title,a.arc_author,a.sendtime,c.cate_name,a.arc_sort')
             ->order('a.arc_sort desc,a.sendtime desc,a.arc_id desc')
            ->paginate(2);

    }
    public function store($data){
        if(!isset($data['tag'])){
            return ['valid'=>0,'msg'=>'请选择标签'];
        }
       $result= $this->validate(true)->allowField(true)->save($data);
       if($result){
           foreach ($data['tag'] as $v){
               $arcTagData = [
                   'arc_id'=>$this->arc_id,
                   'tag_id'=>$v,
               ];
               (new ArcTag())->save($arcTagData);
           }
            return ['valid'=>1,'msg'=>'文章添加成功'];
       }else{
           return ['valid'=>0,'msg'=>$this->getError()];
       }
    }
    public function changeSort($data){
        $result=$this->validate([
            'arc_sort'=>'require|between:1,9999',
        ],[
            'arc_sort.require'=>'请输入排序',
            'arc_sort.between'=>'排序需要在1—9999之间',
        ])->save($data,[$this->pk=>$data['arc_id']]);
        if($result){
            return ['valid'=>1,'msg'=>'操作成功'];
        }else{
            return ['valid'=>0,'msg'=>$this->getError()];
        }
    }
    public function edit($data){
        $result =$this->validate(true)->allowField(true)->save($data,[$this->pk=>$data['arc_id']]);
        if($result){
            (new ArcTag())->where('arc_id',$data['arc_id'])->delete();
            foreach ($data['tag'] as $v){
                $arcTagData = [
                    'arc_id'=>$this->arc_id,
                    'tag_id'=>$v,
                ];
                (new ArcTag())->save($arcTagData);
            }
            return ['valid'=>1,'msg'=>'操作成功'];
        }else{
            return ['valid'=>0,'msg'=>$this->getError()];
        }
    }
    public function del($arc_id){
       if(Article::destroy($arc_id)){
           (new ArcTag())->where('arc_id',$arc_id)->delete();
           return ['valid'=>1,'msg'=>'删除成功'];
       }else{
           return ['valid'=>0,'msg'=>'删除失败'];
       }
    }
}