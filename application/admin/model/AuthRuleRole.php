<?php
namespace app\admin\model;

use think\Model;

class AuthRuleRole extends Model 
{
    protected $table = 'auth_rule_role';

    public static function getRuleList($role_id)
    {
        $checked_keys = [];
        $auth_rule = self::where('role_id', $role_id)
            ->field('rule_id')
            ->select();
        foreach ($auth_rule as $k => $v){
            $checked_keys[] = $v['rule_id'];
        }
        return $checked_keys;
    }

    /**
     * 授权
     */
    public function auth($role_id, $rules)
    {
        $rule_access = [];
        foreach ($rules as $k => $v){
            $rule_access[$k]['role_id'] = $role_id;
            $rule_access[$k]['rule_id'] = $v;
        }
        // 先删除
        $this->where('role_id', $role_id)->delete();
        // 再添加
        if(!$this->saveAll($rule_access)){
            return false;
        }
        return true;

        
    }
    public static function getRuleId($roles)
    {
        $data = self::field('rule_id')
            ->where('role_id', 'in', $roles)
            ->select();
        $result = array_unique(array_column($data));
        return $result;
    }
}