<?php
namespace app\admin\controller\auth;

use app\common\controller\Backend;
use app\admin\model\AuthRule;
use think\facade\Request;
use think\facade\Validate;
use app\common\vo\ResultVo;
use app\common\enums\ErrorCode;
class Rule extends Backend
{   
    public function index()
    {
        $rule_list = AuthRule::getLists();

        $res['total'] = count($rule_list);
        $res['list'] = $rule_list;
        return ResultVo::success($res);

    }

    /**
     * 添加
     */
    public function add()
    {
        $params = Request::post();
        if (empty($params['name']) || empty($params['status'])){
            return ResultVo::error(ErrorCode::DATA_VALIDATE_FAIL);
        }
        $validate = Validate::make([
            'name|规则' => 'require|unique:AuthRule,name',
        ]);
        $result = $validate->check($params);
        if(!$result) {
            return ResultVo::error(ErrorCode::DATA_REPEAT, $validate->getError());
        }
        $pid = !empty($data['pid']) ? $data['pid'] : 0;
        if ($pid){
            $info = AuthRule::where('id',$pid)
                ->field('id')
                ->find();
            if (!$info){
                return ResultVo::error(ErrorCode::NOT_NETWORK);
            }
        }
        $result = AuthRule::create($params);

        if (!$result){
            return ResultVo::error(ErrorCode::NOT_NETWORK);
        }
        return ResultVo::success($result);
    }
    public function edit($id = NULL)
    {
        // $row = $this->model->get(['id' => $ids]);
        $row = AuthRule::get($id);
        if(!$row)
            $this->error('No Results were found');
        if (Request::isPost())
        {
            $params = Request::post();
            if($params)
            {
                if(count($params) == 1) {
                    $row->ismenu = $params['ismenu'];
                    $row->save();
                    $this->success();
                }
                if(!$params['ismenu'] && !$params['pid'])
                {
                    $this->error('The non-menu rule must have parent');
                }
                // 这里需要针对name做唯一验证
                $validate = Validate::make([
                    'name|规则' => 'require|unique:AuthRule,name,' . $row->id,
                ]);
                $result = $validate->check($params);
                if(!$result) {
                    return ResultVo::error(ErrorCode::DATA_REPEAT, $validate->getError());
                }
                // AuthRule::where('id',$row->id)->update($params);
                $row->pid = $params['pid'];
                $row->name = $params['name'];
                $row->title = $params['title'];
                $row->status = $params['status'];
                $row->condition = $params['condition'];
                $row->status = $params['status'];
                $result = $row->save();
                
                if (!$result){
                    return ResultVo::error(ErrorCode::DATA_CHANGE);
                }
        
                return ResultVo::success();
            }
            $this->error();
        }

    }
    
    /**
     * 删除
     */
    public function delete()
    {
        $id = Request::post('id/d');
        if (empty($id)){
            return ResultVo::error(ErrorCode::DATA_VALIDATE_FAIL);
        }
        // 下面有子节点，不能删除
        $sub = AuthRule::where('pid', $id)->field('id')->find();
        if ($sub){
            return ResultVo::error(ErrorCode::NOT_NETWORK);
        }
        if (!AuthRule::where('id',$id)->delete()){
            return ResultVo::error(ErrorCode::NOT_NETWORK);
        }

        // 删除授权的权限

        return ResultVo::success();
    }
}