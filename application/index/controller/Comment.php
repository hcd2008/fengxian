<?php
	namespace app\index\controller;
	use think\Db;
	use think\Session;
	use app\common\controller\Base;

	class Comment extends Base{
		/**
		 * 添加评论
		 * @Author   黄传东
		 * @DateTime 2017-04-11T09:11:45+0800
		 */
		public function add(){
			Session::has('uid') or $this->error('请登录','login/index');
			$userid=Session::get('uid');
			$param=$this->request->param();
			trim($param['content'])!='' or $this->error('请输入评论内容');
			$param['userid']=$userid;
			$param['addtime']=time();
			$res=Db::name('comment')->insert($param);
			if($res){
				$this->success('添加评论成功');
			}else{
				$this->error('添加评论失败');
			}
		}
		/**
		 * 留言列表
		 * @Author   黄传东
		 * @DateTime 2017-04-11T10:14:40+0800
		 * @return   [type]                   [description]
		 */
		public  function lists($itemid){
			$res=Db::name('comment')->where('infoid',$itemid)->select();
			return $res;
		}
	}
?>