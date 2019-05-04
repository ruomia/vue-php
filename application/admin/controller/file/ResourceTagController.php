<?php

namespace app\admin\controller\file;

use app\admin\controller\Base;
use app\admin\model\FileResourceTag;
use app\common\exception\JsonException;
use app\common\enums\ErrorCode;
use app\common\vo\ResultVo;

/**
 * 资源分组管理
 */
class ResourceTagController extends Base
{

    /**
     * 列表
     */
    public function index()
    {
        $limit = request()->get('limit/d', 20);
        //分页配置
        $paginate = [
            'type' => 'bootstrap',
            'var_page' => 'page',
            'list_rows' => ($limit <= 0 || $limit > 20) ? 20 : $limit,
        ];
        $where = [];
        $lists = FileResourceTag::where($where)
            ->field('id,tag')
            ->paginate($paginate);

        $res = [];
        $res["total"] = $lists->total();
        $res["list"] = $lists->items();
        return ResultVo::success($res);
    }

    /**
     * 添加
     */
    public function add() {

        $tag = request()->post('tag');
        if (empty($tag)){
            return ResultVo::error(ErrorCode::DATA_VALIDATE_FAIL);
        }

        $file_resource_tag = new FileResourceTag();
        $file_resource_tag->tag = $tag;
        $file_resource_tag->create_time = date("Y-m-d H:i:s");
        $file_resource_tag->save();
        $res = [];
        $res["id"] = intval($file_resource_tag->id);
        return ResultVo::success($res);
    }


}