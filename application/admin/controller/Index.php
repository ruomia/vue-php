<?php
namespace app\admin\controller;

use app\common\controller\Backend;
use think\Validate;
use think\Request;
use app\facade\Admin;
use app\admin\model\Admin as AdminModel;
use app\admin\model\AuthRoleAdmin;
use app\admin\model\AuthRule;
use app\admin\model\AuthRuleRole;
/**
 * 后台首页
 * @internal
 */

 class Index extends Backend
 {
    protected $middleware = [
        'CORS',
        'Check' => ['except' => ['login','upload']],
    ];
    // protected $noNeedLogin = ['login'];
    /**
     * 后台首页
     */
    public function index()
    {

    }

    /**
     * 获取用户信息和权限列表
     */
    public function userinfo()
    {
        // 权限信息
        $admin_id = $this->requset->admin_id;
        $roles = AuthRoleAdmin::getRoleId($admin_id);
        // 判断是否有超级管理员权限
        if(!in_array( 1, $roles)) {
            $rules = AuthRuleRole::getRuleIds($roles);

            $authRules = AuthRule::getAuthRules($rules);
        } else {
            $authRules = ['admin'];
        }
        $admin = Admin::where('id', $admin_id)
                    ->field('id,username,avatar')
                    ->find();
        return success([
            'username'=> $admin->username,
            'avatar'=> $admin->avatar,
            'authRules'=>$authRules
        ]);
    }

    /**
     * 管理员登录
     */
    public function login(Request $req)
    {
        // $url = Request::get('url');
        // echo $url;
        if ($req->isPost()) {
            $username = $req->post('username');
            $password = $req->post('password');
            // $captcha = Request::post('captcha');
            $validate = Validate::make([
                'username|用户名' => 'require|length:3,30',
                'password|密码' => 'require|length:3,30',
                // 'captcha|验证码'  => 'require|captcha', 
            ]);
            $data = [
                'username' => $username,
                'password' => $password,
                // 'captcha'  => $captcha,
            ];
            $result = $validate->check($data);
            if (!$result) {
                $this->error($validate->getError());
            }
            $result = Admin::login($username, $password);
            if ($result) {
              
                // 发给前端
                return success([
                    'token' => $result
                ]);
            } 
            else 
            {
                $msg = Admin::getError();
                $msg = $msg ? $msg : ('Username or password is incorrect');
                // $this->error($msg);
                return error($msg);
            }

        }
    }

    /**
     * 注销登录 
     */
    public function logout()
    {

    }

    public function upload()
    {
        $file = Request::file('file');
        // 移动到框架应用根目录/uploads/目录下
        $info = $file->move('uploads');
        if($info){
            // 成功上传后，获取上传信息
            $result =  [
                'code' => 0,
                'msg'  => '',
                'data' => [
                    'src' => '/uploads/' . $info->getSaveName()
                ]
            ];
            return json($result);
        }else{
            $result =  [
                'code' => 1,
                'msg'  => $file->getError(),
                'data' => ''
            ];
            return json($result);
        }
    }
 }
