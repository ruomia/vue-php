<?php
namespace app\admin\controller\auth;

use app\common\controller\Backend;
use app\admin\model\Admin as AdminModel;
use think\facade\Request;
use app\common\vo\ResultVo;
use app\common\enums\ErrorCode;
use think\facade\Validate;
use app\admin\model\AuthRoleAdmin;
class Admin extends Backend
{

    public function index()
    {

        $where = [];
        $order = 'id DESC';
        $status = Request::get('status', '');
        if ($status !== ''){
            $where[] = ['status','=',intval($status)];
            $order = '';
        }
        $username = Request::get('username', '');
        if (!empty($username)){
            $where[] = ['username','like',$username . '%'];  
            $order = '';
        }
        $role_id = Request::get('role_id/id', '');
        if ($role_id !== ''){
            $admin_ids = AuthRoleAdmin::where('role_id', $role_id)->column('admin_id');
            $where[] = ['id', 'in', $admin_ids];
            $order = '';
        }
        // $limit = Request::get('limit/d', 20);
        $admin = new AdminModel;
        $result = $admin->getList($where, $order);
        return ResultVo::success($result);    

    }

    /**
     * 添加
     */
    public function save()
    {
        $params = Request::post();
        $validate = Validate::make([
            'username' => 'require|min:3|max:20|unique:Admin,username',
            'password' => 'require|min:6|max:30'
        ]);
        if(!$validate->check($params)){
            return ResultVo::error(ErrorCode::DATA_REPEAT, $validate->getError());
        }
        $params['password'] = md5($params['password']);
        $params['avatar'] = '/assets/img/avatar.png';
        $params['nickname'] = '管理员';
        $params['email'] = '123@163.com';
        $result = AdminModel::create($params);
        if (!$result){
            return ResultVo::error(ErrorCode::NOT_NETWORK);
        }

        $result->roles()->saveAll($params['roles']);
        return ResultVo::success($result);
  

    }

    /**
     * 编辑
     */
    public function edit()
    {
        $params = Request::post();
        if (empty($params['id']) || empty($params['username'])){
            return ResultVo::error(ErrorCode::DATA_VALIDATE_FAIL);
        }
        $id = $params['id'];
        $username = strip_tags($params['username']);
        // 模型
        $row = AdminModel::where('id',$id)
            ->field('id,username')
            ->find();
        if (!$row){
            return ResultVo::error(ErrorCode::DATA_NOT, "管理员不存在");
        }
        // 如果是超级管理员，判断当前登录用户是否匹配
        if ($row->id === 1 && $row->id != $this->request->admin_id){
            return ResultVo::error(ErrorCode::DATA_NOT, "最高权限用户，无权修改");
        }
        
        $validate = Validate::make([
            'username' => 'require|max:50|unique:admin,username,' . $row->id,
            'status'   => 'require'
        ]);
        if(!$validate->check($params)){
            return ResultVo::error(ErrorCode::DATA_REPEAT, $validate->getError());
        }
        $row->username = $username;
        // if ($params['password']){
        //     $row->password = md5($params['password']);
        // }
        $row->status = $params['status'];
        $row->save();


        $row->roles()->detach();
        $row->roles()->saveAll($params['roles']);

        return ResultVo::success();
    }

    /**
     * 删除
     */
    public function delete()
    {   
        $id = Request::post('id/d');
        if (empty($id) || $id === 1){
            return ResultVo::error(ErrorCode::DATA_VALIDATE_FAIL);
        }

        $row = AdminModel::get($id);
        // 先删除中间表shuju
        $row->roles()->detach();
        $row->delete();

        return ResultVo::success();
    }
}