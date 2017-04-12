<?php
	namespace app\index\controller;
	use app\common\controller\Base;
	use think\Db;

	class Xuan extends Base{
		/**
		 * 筛选数据首页
		 * @Author   黄传东
		 * @DateTime 2017-04-01T09:17:14+0800
		 * @return   [type]                   [description]
		 */
		public function index(){
			$param=$this->request->param();
			extract($param);
			isset($status) or $status=1;
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
		 * 显示信息
		 * @Author   黄传东
		 * @DateTime 2017-03-27T11:03:41+0800
		 * @return   [type]                   [description]
		 */
		public function show(){
			$param=$this->request->param();
			$itemid=$param['itemid'];
			$info=Db::name('info')->where("itemid",$itemid)->find();
			// $lists=Db::name('comment')->where('infoid',$itemid)->select();
			$lists=Db::name('comment')->field('a.*,m.username,m.truename')->alias('a')->join('__MEMBER__ m','a.userid=m.userid')->where('a.infoid',$itemid)->select();
			$this->assign("lists",$lists);
			$this->assign("info",$info);
			$this->assign("itemid",$itemid);
			$this->assign("status",$info['status']);
			$this->assign("action",$this->request->action());
			return $this->fetch();
		}
		/**
		 * 将信息添加到翻译列表
		 * @Author   黄传东
		 * @DateTime 2017-03-27T15:51:27+0800
		 */
		public function addInfo(){
			$param=$this->request->param();
			isset($param['itemid']) or $this->error("非法访问");
			$res=Db::name('info')->where("itemid",$param['itemid'])->update(["status"=>2]);
			if($res){
				$this->success("将信息添加到翻译列表成功","xuan/index");
			}else{
				$this->error("失败");
			}
		}
		/**
		 * 将信息从翻译列表中移除
		 * @Author   黄传东
		 * @DateTime 2017-03-27T15:56:41+0800
		 * @return   [type]                   [description]
		 */
		public function rmInfo(){
			$param=$this->request->param();
			isset($param['itemid']) or $this->error("非法访问");
			$res=Db::name('info')->where("itemid",$param['itemid'])->update(["status"=>1]);
			if($res){
				$this->success("将信息从翻译列表中移除成功","xuan/index");
			}else{
				$this->error("失败");
			}
		}
		/**
		 * 将信息删除
		 * @Author   黄传东
		 * @DateTime 2017-03-27T15:58:16+0800
		 * @return   [type]                   [description]
		 */
		public function delInfo(){
			$param=$this->request->param();
			isset($param['itemid']) or $this->error("非法访问");
			$res=Db::name('info')->where("itemid",$param['itemid'])->update(["status"=>0]);
			if($res){
				$this->success("删除信息成功","xuan/index");
			}else{
				$this->error("失败");
			}
		}
		/**
		 * 恢复已删除信息
		 * @Author   黄传东
		 * @DateTime 2017-03-27T15:59:13+0800
		 * @return   [type]                   [description]
		 */
		public function  ryInfo(){
			$param=$this->request->param();
			isset($param['itemid']) or $this->error("非法访问");
			$res=Db::name('info')->where("itemid",$param['itemid'])->update(["status"=>1]);
			if($res){
				$this->success("恢复信息成功","xuan/index");
			}else{
				$this->error("失败");
			}
		}
	}
?>