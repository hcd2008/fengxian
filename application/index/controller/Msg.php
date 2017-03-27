<?php
	namespace app\index\controller;
	use app\common\controller\Base;
	use think\Db;
	use think\Session;

	class Msg extends Base{
		/**
		 * 信息首页
		 * @Author    黄传东
		 * @DateTime  2017-03-22T14:11:46+0800
		 * @copyright 风险评估中心信息平台
		 * @return    [type]                   [description]
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
			$this->assign("controller",$this->request->controller());
			$this->assign('laiyuan',$laiyuan);
			$this->assign('keyword',$keyword);
			$this->assign("status",$status);
			$this->assign("lists",$res);
			return $this->fetch();
		}
		/**
		 * 上传信息
		 * @Author    黄传东
		 * @DateTime  2017-03-22T14:12:11+0800
		 * @copyright 风险评估中心信息平台
		 * @return    [type]                   [description]
		 */
		public function upload(){
			if($this->request->isPost()){
				$param=$this->request->param();
				$param['title_cn']!='' or $this->error('请填写中文标题');
				$file = $this->request->file('fujian');
				$file!='' or $this->error("请上传附件");
			    // 移动到框架应用根目录/public/uploads/ 目录下
			    $info = $file->move(ROOT_PATH . 'public' . DS . 'uploads');
			    if($info){
			        $file= $info->getSaveName();
			        $param['fujian']=$file;  
			        $param['addtime']=time();
			        $param['userid']=Session::get('uid');
			       	$res=Db::name('info')->insert($param);
			       	if($res){
			       		$this->success("添加信息成功","msg/upload");
			       	}else{
			       		$this->error("添加失败");
			       	}
			    }else{
			        // 上传失败获取错误信息
			        echo $file->getError();
			    }
			}else{
				$res=Db::name('info')->where("status",3)->field('itemid,title_cn,addtime')->order('itemid',"desc")->limit(7)->select();
				$this->assign("lists",$res);
				return $this->fetch();
			}
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
			$this->assign("info",$info);
			return $this->fetch();
		}
	}
?>