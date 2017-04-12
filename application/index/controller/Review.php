<?php
	namespace app\index\controller;
	use app\common\controller\Base;
	use think\Db;

	class Review extends Base{
		/**
		 * 翻译列表
		 * @Author   黄传东
		 * @DateTime 2017-04-01T09:37:33+0800
		 * @return   [type]                   [description]
		 */
		public function index(){
			$param=$this->request->param();
			extract($param);
			isset($status) or $status=4;
			isset($keyword) or $keyword=0;
			isset($laiyuan) or $laiyuan=0;
			$map['status']=$status;
			if($keyword){
				$map['title_cn']=['like','%'.$keyword.'%'];
				
			}
			if($laiyuan){
				$map['laiyuan']=['like','%'.$laiyuan.'%'];

			}
			$res=Db::name('info')->where($map)->order('itemid',"desc")->paginate(10);
			$this->assign("action",$this->request->action());
			$this->assign('laiyuan',$laiyuan);
			$this->assign('keyword',$keyword);
			$this->assign("status",$status);
			$this->assign("lists",$res);
			return $this->fetch();
		}
		/**
		 * 信息详情
		 * @Author   黄传东
		 * @DateTime 2017-04-10T11:13:26+0800
		 * @param    [type]                   $itemid [description]
		 * @return   [type]                           [description]
		 */
		public function show($itemid){
			$info=Db::name('info')->where('itemid',$itemid)->find();
			$this->assign('info',$info);
			$this->assign("itemid",$itemid);
			$lists=Db::name('comment')->field('a.*,m.username,m.truename')->alias('a')->join('__MEMBER__ m','a.userid=m.userid')->where('a.infoid',$itemid)->select();
			$this->assign("lists",$lists);
			return $this->fetch();
		}
		/**
		 * 审核通过
		 * @Author   黄传东
		 * @DateTime 2017-04-10T11:22:43+0800
		 * @param    [type]                   $itemid [description]
		 * @return   [type]                           [description]
		 */
		public function yes($itemid){
			$res=Db::name('info')->where('itemid',$itemid)->update(['status'=>5]);
			if($res){
				$this->success('审核成功','review/index');
			}else{
				$this->erroc('审核失败');
			}
		}
		/**
		 * 拒绝通过
		 * @Author   黄传东
		 * @DateTime 2017-04-11T13:31:53+0800
		 * @param    [type]                   $itemid [description]
		 * @return   [type]                           [description]
		 */
		public function no($itemid){
			$res=Db::name('info')->where('itemid',$itemid)->update(['status'=>6]);
			if($res){
				$this->success('审核成功','review/index');
			}else{
				$this->erroc('审核失败');
			}
		}
		
	}
?>