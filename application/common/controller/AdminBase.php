<?php
	namespace app\common\controller;
	use think\Controller;
	use think\Session;
	use think\Validate;

	class AdminBase extends Controller{
		/**
		 * 基本类判断用户登录等
		 * @Author    黄传东
		 * @DateTime  2017-03-15T14:30:03+0800
		 * @copyright 风险评估中心信息平台
		 */
		public function __construct(){
			// $this->isLogin();
			parent::__construct();
			
		}
		public function _initialize(){
			$this->isLogin();
			$this->assign('controller',$this->request->controller());
		}
		/**
		 * 判断是否已登录
		 * @Author    黄传东
		 * @DateTime  2017-03-15T14:22:06+0800
		 * @copyright 风险评估中心信息平台
		 * @return    boolean                  [description]
		 */
		public function isLogin(){
			if(!Session::has('uid')){
				$this->redirect('login/index');
			}
		}
	}
?>