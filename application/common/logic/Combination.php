<?php
/**
 * tpshop
 * ============================================================================
 * 版权所有 2015-2027 深圳搜豹网络科技有限公司，并保留所有权利。
 * 网站地址: http://www.tp-shop.cn
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和使用 .
 * 不允许对程序代码以任何形式任何目的的再发布。
 * 如果商业用途务必到官方购买正版授权, 以免引起不必要的法律纠纷.
 * ============================================================================
 */

namespace app\common\logic;

use app\common\util\TpshopException;
use think\Db;


/**
 * 搭配购逻辑
 * Class Combination
 * @package app\common\logic
 */
class Combination
{
    protected $combinationModel;
    protected $combinationId;//搭配购id
    protected $goodsId;//商品规格模型
    protected $itemId;//购买的商品数量

    public function __construct()
    {
        $this->combinationModel = new \app\common\model\Combination();
    }

    /**
     * 获取该商品参加的所有搭配购活动id
     */
    public function getGoodCombination()
    {
        $master_combination_ids = Db::name('combination_goods')->distinct(true)->where(['goods_id' => $this->goodsId, 'item_id' => $this->itemId])->column('combination_id');
        $this->combinationId = $master_combination_ids ? $master_combination_ids : 0;
    }

    /**
     * 获取mobile端的搭配购详情
     * @return array|false|\PDOStatement|string|\think\Collection
     * @throws TpshopException
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function getCombinationDetails()
    {
        $combination_list = $this->combinationModel
            ->with(['combination_goods'])
            ->where(['is_on_sale' => 1, 'start_time' => ['lt', time()], 'end_time' => ['gt', time()], 'combination_id' => ['IN', $this->combinationId]])
            ->select();
        if (!empty($combination_list)) {
            foreach ($combination_list as $list_k => $list_v) {
                $combination_list[$list_k]['count_price'] = 0;
                foreach ($list_v['combination_goods'] as $goods_k => $goods_v) {
                    //没有规格的图片就拿商品的图片
                    $combination_list[$list_k]['combination_goods'][$goods_k]['original_img'] = goods_thum_images($goods_v['goods_id'], 248, 248, $goods_v['item_id']);
                    $combination_list[$list_k]['count_price'] += $goods_v['original_price'] - $goods_v['price'];   //遍历计算总省多少
                }
            }
        }
        return $combination_list?$combination_list:array();
    }

    public function setCombinationId($value)
    {
        $this->combinationId = $value;
    }

    public function setGoodsId($value)
    {
        $this->goodsId = $value;
    }

    public function setItemId($value)
    {
        $this->itemId = $value;
    }

}