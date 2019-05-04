<?php
namespace app\admin\controller\auth;

use app\admin\model\AuthRule;
use app\common\controller\Backend;
use think\facade\Request;
use think\facade\Validate;
use app\common\vo\ResultVo;
use app\common\enums\ErrorCode;
use app\admin\model\AuthRole;
use app\admin\model\AuthRuleRole;
/**
 * 角色组
 * 
 * @icon fa fa-group
 * @remark 角色组可以有多个，角色有上下级层级关系，如果子角色有角色组和管理员的权限则可以派生属于自己组别下级的角色组或管理员
 */
class Role extends Backend
{
 
    public function index()
    {
        $where = [];
        $order = 'id ASC';
        $status = Request::get('status', '');
        if ($status !== ''){
            $where[] = ['status','=',intval($status)];
            $order = '';
        }
        $name = Request::get('name', '');
        if (!empty($name)){
            $where[] = ['name', 'like', $name . '%'];
            $order = '';
        }
        $lists = AuthRole::field('id,pid,name,status')
                    ->where($where)
                    ->order($order)
                    ->select();
        $res = [];
        // $res['total'] = $lists->total();
        $res['list'] = $lists;
        return ResultVo::success($res);
    }
    /**
     * 获取授权列表
     */
    public function authList()
    {
        $id = Request::get('id/d', '');
        $checked_keys = AuthRuleRole::getRuleList($id);
        $rule_list = AuthRule::getLists([], 'id ASC');

        $res['auth_list'] = $rule_list;
        $res['checked_keys'] = $checked_keys;
        return ResultVo::success($res);
    }
    /**
     * 授权
     */
    public function auth()
    {
        $params = Request::post();
        $role_id = isset($params['role_id']) ? $params['role_id'] : '';
        if (!$role_id){
            return ResultVo::error(ErrorCode::NOT_NETWORK);
        }
        $auth_rules = isset($params['auth_rules']) ? $params['auth_rules'] : [];
        $authRuleRole = new AuthRuleRole;
        $result = $authRuleRole->auth($role_id, $auth_rules);
        if(!$result){
            return ResultVo::error(ErrorCode::NOT_NETWORK);
        }
        return ResultVo::success();
    }
    /**
     * 添加
     */
    public function save()
    {
        $params = Request::post();
        // $params['authids'] = json_decode($params['authids'], true);
        // return $params['authids'];
        $validate = Validate::make([
            'pid|父级权限' => 'require',
            'name|名称' => 'require|unique:AuthRole,name',
        ]);
        $result = $validate->check($params);
        if(!$result) {
            return error($validate->getError());
        }
        $result = AuthRole::create($params);
        return ResultVo::success($result);
    }

    /**
     * 编辑
     */
    public function edit() 
    {
        $params = Request::post();
        if (empty($params['id']) || empty($params['name'])){
            return ResultVo::error(ErrorCode::DATA_VALIDATE_FAIL);
        }
        $id = $params['id'];
        $name = strip_tags($params['name']);
        $row = AuthRole::get($id);
        if (!$row)
            return ResultVo::error(ErrorCode::DATA_NOT, "角色不存在");
        // 超级管理员组不允许修改
        $validate = Validate::make([
            'pid|父级权限' => 'require',
            'name|名称' => 'require|unique:AuthRole,name,' . $row->id,
        ]);
        if(!$validate->check($params)){
            return ResultVo::error(ErrorCode::DATA_NOT, $validate->getError());
        }

        $row->name = $name;
        $row->status = $params['status'];
        $result = $row->save();

        if (!$result){
            return ResultVo::error(ErrorCode::DATA_CHANGE);
        }
    }
    /**
     * 删除
     */
    public function delete(){
        $id = Request::post('id/d');
        if (empty($id)){
            return ResultVo::error(ErrorCode::DATA_VALIDATE_FAIL);
        }
        if (!AuthRole::where('id',$id)->delete()){
            return ResultVo::error(ErrorCode::NOT_NETWORK);
        }
        // 删除中间表记录
        AuthRuleRole::where('role_id', $id)->delete();

        return ResultVo::success();

    }

}