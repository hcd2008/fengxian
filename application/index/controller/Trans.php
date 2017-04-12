<?php
	namespace app\index\controller;
	use app\common\controller\Base;
	use think\Db;

	class Trans extends Base{
		/**
		 * 翻译列表
		 * @Author   黄传东
		 * @DateTime 2017-04-01T09:37:33+0800
		 * @return   [type]                   [description]
		 */
		public function index(){
			$param=$this->request->param();
			extract($param);
			isset($status) or $status=2;
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
		 * 显示信息并进行翻译操作
		 * @Author   黄传东
		 * @DateTime 2017-04-06T17:04:52+0800
		 * @return   [type]                   [description]
		 */
		public function fanyi($itemid){
			if($this->request->isPost()){
				$param=$this->request->param();
				$submit=$submit1='';
				if(isset($param['submit'])){
					$submit=$param['submit'];
					unset($param['submit']);
				}
				if(isset($param['submit1'])){
					$submit1=$param['submit1'];
					unset($param['submit1']);
				}

				$file = $this->request->file('fy_fujian');
				if($file!=''){
					$info = $file->move(ROOT_PATH . 'public' . DS . 'uploads');
				    if($info){
				        $file= $info->getSaveName();
				        $param['fy_fujian']=$file;  
				    }
				}
				$param['status']=3;
				
				// print_r($param);exit;
				$detail=Db::name('info')->where('itemid',$itemid)->find();
				//如果是拒绝通过的
				if($detail['status']==6){
					$detail['parentid']=$detail['itemid'];
					unset($detail['itemid']);
					$detail['addtime']=time();
					Db::name('record')->insert($detail);
					//状态仍设置为拒绝通过
					$param['status']=6;
				}
				if($submit1!=''){
					$param['status']=4;
				}
				$res=Db::name('info')->update($param);
				if($res){
					if($submit1!=''){
						$this->success("提交翻译信息审核成功",'trans/index');
					}else{
						$this->success("翻译信息成功",'trans/index');
					}
					
				}else{
					$this->error("翻译信息失败");
				}
			}else{
				$res=Db::name('info')->where("itemid",$itemid)->find();
				if($res['status']==6){
					$history_lists=Db::name('record')->where('parentid',$itemid)->select();
					$this->assign('history_lists',$history_lists);
				}
				// print_r($res);
				$this->assign("action",$this->request->action());
				$this->assign("info",$res);
				$this->assign("itemid",$itemid);
				$lists=Db::name('comment')->field('a.*,m.username,m.truename')->alias('a')->join('__MEMBER__ m','a.userid=m.userid')->where('a.infoid',$itemid)->select();
				$this->assign("lists",$lists);
				return $this->fetch();
			}
		}
		/**
		 * 提交审核
		 * @Author   黄传东
		 * @DateTime 2017-04-10T10:31:15+0800
		 * @return   [type]                   [description]
		 */
		public function shen($itemid){
			$res=Db::name('info')->where('itemid',$itemid)->update(['status'=>4]);
			if($res){
				$this->success('提交审核成功');
			}else{
				$this->error('提交审核失败');
			}
		}
		/**
		 * 撤回审核
		 * @Author   黄传东
		 * @DateTime 2017-04-10T10:31:15+0800
		 * @return   [type]                   [description]
		 */
		public function cheshen($itemid){
			$res=Db::name('info')->where('itemid',$itemid)->update(['status'=>3]);
			if($res){
				$this->success('撤回成功');
			}else{
				$this->error('撤回失败');
			}
		}
		/**
		 * 信息详情
		 * @Author   黄传东
		 * @DateTime 2017-04-10T10:41:15+0800
		 * @param    [type]                   $itemid [description]
		 * @return   [type]                           [description]
		 */
		public function show($itemid){
			$info=Db::name('info')->where('itemid',$itemid)->find();
			$this->assign('info',$info);
			return $this->fetch();
		}
		/**
		 * 历史翻译信息
		 * @Author   黄传东
		 * @DateTime 2017-04-11T16:06:30+0800
		 * @param    [type]                   $itemid [description]
		 * @return   [type]                           [description]
		 */
		public function history($itemid){
			$info=Db::name('record')->where('itemid',$itemid)->find();
			$this->assign('info',$info);
			$this->assign('itemid',$itemid);
			return $this->fetch();
		}
	}
?>