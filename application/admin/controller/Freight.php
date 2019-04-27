<?php
/**
 * tpshop
 * ============================================================================
 * 版权所有 2015-2027 深圳搜豹网络科技有限公司，并保留所有权利。
 * 网站地址: http://www.tp-shop.cn
 * ----------------------------------------------------------------------------
 * 商业用途务必到官方购买正版授权, 使用盗版将严厉追究您的法律责任。
 * ============================================================================
 * 运费模板管理
 * Date: 2017-11-14
 */

namespace app\admin\controller;

use app\common\model\FreightTemplate;
use think\Db;
use think\Loader;
use think\Page;

class Freight extends Base
{

    public function index()
    {
        $FreightTemplate = new FreightTemplate();
        $count = $FreightTemplate->where('')->count();
        $Page = new Page($count, 10);
        $show = $Page->show();
        $template_list = $FreightTemplate->append(['type_desc'])->with('freightConfig')->limit($Page->firstRow . ',' . $Page->listRows)->select();
        $this->assign('page', $show);
        $this->assign('template_list', $template_list);
        return $this->fetch();
    }

    public function info()
    {
        $template_id = input('template_id');
        if ($template_id) {
            $FreightTemplate = new FreightTemplate();
            $freightTemplate = $FreightTemplate->with('freightConfig')->where(['template_id' => $template_id])->find();
            if (empty($freightTemplate)) {
                $this->error('非法操作');
            }
            $this->assign('freightTemplate', $freightTemplate);
        }
        return $this->fetch();
    }

    /**
     *  保存运费模板
     * @throws \think\Exception
     */
    public function save()
    {
        $template_id = input('template_id/d');
        $template_name = input('template_name/s');
        $type = input('type/d');
        $is_enable_default = input('is_enable_default/d');
        $config_list = input('config_list/a', []);
        $data = input('post.');
        $freightTemplateValidate = Loader::validate('FreightTemplate');
        if (!$freightTemplateValidate->batch()->check($data)) {
            $this->ajaxReturn(['status' => 0, 'msg' => '操作失败', 'result' => $freightTemplateValidate->getError()]);
        }
        if (empty($template_id)) {
            //添加模板
            $freightTemplate = new FreightTemplate();
        } else {
            //更新模板
            $freightTemplate = FreightTemplate::get(['template_id' => $template_id]);
        }
        $freightTemplate['template_name'] = $template_name;
        $freightTemplate['type'] = $type;
        $freightTemplate['is_enable_default'] = $is_enable_default;
        $freightTemplate->save();
        $config_list_count = count($config_list);
        $config_id_arr = Db::name('freight_config')->where(['template_id' => $template_id])->getField('config_id', true);
        $update_config_id_arr = [];
        if ($config_list_count > 0) {
            for ($i = 0; $i < $config_list_count; $i++) {
                $freight_config_data = [
                    'first_unit' => $config_list[$i]['first_unit'],
                    'first_money' => $config_list[$i]['first_money'],
                    'continue_unit' => $config_list[$i]['continue_unit'],
                    'continue_money' => $config_list[$i]['continue_money'],
                    'template_id' => $freightTemplate['template_id'],
                    'is_default' => $config_list[$i]['is_default'],
                ];
                if (empty($config_list[$i]['config_id'])) {
                    //新增配送区域
                    $config_id = Db::name('freight_config')->insertGetId($freight_config_data);
                    if(!empty($config_list[$i]['area_ids'])){
                        $area_id_arr = explode(',', $config_list[$i]['area_ids']);
                        if ($config_id !== false) {
                            foreach ($area_id_arr as $areaKey => $areaVal) {
                                Db::name('freight_region')->add(['template_id'=>$freightTemplate['template_id'],'config_id' => $config_id, 'region_id' => $areaVal]);
                            }
                        }
                    }
                } else {
                    //更新配送区域
                    array_push($update_config_id_arr, $config_list[$i]['config_id']);
                    $config_result = Db::name('freight_config')->where(['config_id' => $config_list[$i]['config_id']])->save($freight_config_data);
                    if ($config_result !== false) {
                        Db::name('freight_region')->where(['config_id' => $config_list[$i]['config_id']])->delete();
                        if(!empty($config_list[$i]['area_ids'])){
                            $area_id_arr = explode(',', $config_list[$i]['area_ids']);
                            foreach ($area_id_arr as $areaKey => $areaVal) {
                                Db::name('freight_region')->add(['template_id'=>$freightTemplate['template_id'],'config_id' => $config_list[$i]['config_id'], 'region_id' => $areaVal]);
                            }
                        }
                    }
                }
            }
        }
        $delete_config_id_arr = array_diff($config_id_arr, $update_config_id_arr);
        if (count($delete_config_id_arr) > 0) {
            Db::name('freight_region')->where(['config_id' => ['IN', $delete_config_id_arr]])->delete();
            Db::name('freight_config')->where(['config_id' => ['IN', $delete_config_id_arr]])->delete();
        }
        $this->checkFreightTemplate($freightTemplate->template_id);
        $this->ajaxReturn(['status' => 1, 'msg' => '保存成功', 'result' => '']);
    }

    /**
     * 删除运费模板
     * @throws \think\Exception
     */
    public function delete()
    {
        $template_id = input('template_id');
        $action = input('action');
        if (empty($template_id)) {
            $this->ajaxReturn(['status' => 0, 'msg' => '参数错误', 'result' => '']);
        }
        if ($action != 'confirm') {
            $goods_count = Db::name('goods')->where(['template_id' => $template_id])->count();
            if ($goods_count > 0) {
                $this->ajaxReturn(['status' => -1, 'msg' => '已有' . $goods_count . '种商品使用该运费模板，确定删除该模板吗？继续删除将把使用该运费模板的商品设置成包邮。', 'result' => '']);
            }
        }
        Db::name('goods')->where(['template_id' => $template_id])->update(['template_id' => 0, 'is_free_shipping' => 1]);
        Db::name('freight_region')->where(['template_id' => $template_id])->delete();
        Db::name('freight_config')->where(['template_id' => $template_id])->delete();
        $delete = Db::name('freight_template')->where(['template_id' => $template_id])->delete();
        if ($delete !== false) {
            $this->ajaxReturn(['status' => 1, 'msg' => '删除成功', 'result' => '']);
        } else {
            $this->ajaxReturn(['status' => 0, 'msg' => '删除失败', 'result' => '']);
        }
    }


    public function area()
    {
        $province_list = Db::name('region')->where(array('parent_id' => 0, 'level' => 1))->select();
        $this->assign('province_list', $province_list);
        return $this->fetch();
    }

    /**
     * 检查模板，如果模板下没有配送区域配置，就删除该模板
     * @param $template_id
     */
    private function checkFreightTemplate($template_id)
    {
        $freight_config = Db::name('freight_config')->where(['template_id' => $template_id])->find();
        if (empty($freight_config)) {
            Db::name('freight_template')->where('template_id', $template_id)->delete();
        }
    }

}