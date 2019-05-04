<?php
namespace app\admin\model;
use think\Model;

class AuthRoleAdmin extends Model 
{
    protected $table = 'auth_role_admin';

    public static function getRoleId($admin_id)
    {
  
        $roles = self::field('role_id')
        ->where('admin_id', $req->admin_id)
        ->select();
        $result = array_column($roles, 'role_id');
        return $result;
    }
}