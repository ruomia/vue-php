<?php
namespace app\admin\model;

use think\Model;
use think\Session;

class Admin extends Model
{
    protected $table = 'admin';
    
    public function roles()
    {
        return $this->belongsToMany('AuthRole','auth_role_admin',
            'role_id', 'admin_id');
    }

    public function getList($where, $order)
    {
        $list = $this
            // ->with('role')
            ->field('id,username,status')
            // ->hidden(['role'=>['rules','pid','status','pivot']])
            ->where($where)
            ->order($order)
            ->select();
        foreach($list as $k => $v){
            $roles = AuthRoleAdmin::where('admin_id', $v['id'])->field('role_id')->select();
            $temp_rules = [];
            if($roles){
                $temp_rules = $roles->toArray();
                $temp_rules = array_column($temp_rules, 'role_id');
            }
            
            $v['roles'] = $temp_rules;
            $list[$k] = $v;
        }
        $res = [];
        $res['total'] = count($list);
        $res['list'] = $list;
        // trace($res, 'info ');
        return $res;
    }
}