<?php
/**
 * tpshop
 * ============================================================================
 * 版权所有 2015-2027 深圳搜豹网络科技有限公司，并保留所有权利。
 * 网站地址: http://www.tp-shop.cn
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和使用 .
 * 不允许对程序代码以任何形式任何目的的再发布。
 * ============================================================================
 * Author: IT宇宙人
 * Date: 2015-09-09
 */
namespace app\common\model;
use think\Db;
use think\Model;
class UserAddress extends Model {

    private $longitude;
    private $latitude;
    public function setLongitudeAttr($value, $data)
    {
        $area_list = Db::name('region')->where('id','IN',[$data['province'],$data['city'],$data['district'],$data['twon']])->order('level asc')->cache(true)->select();
        if($area_list[1]['name'] == '市辖区' || $area_list[1]['name'] == '市辖县'){
            $region = $area_list[0]['name'].$area_list[2]['name'];
        }else{
            $region = $area_list[0]['name'].$area_list[1]['name'].$area_list[2]['name'];
        }
        $query = '';
        if($data['twon'] > 0){
            $query .= $area_list[3]['name'];
        }
        $query .= $data['address'];
        $address_url = "http://api.map.baidu.com/place/v2/search?query=".$query."&region=".$region."&output=json&ak=iR2qhnXd5vrFI9wUuIRG9AWGIqykVNok&page_size=1&page_num=1";
        $address_result = httpRequest($address_url,"get");
        $address_data = json_decode($address_result, true);
        if(count($address_data['results']) == 0){
            if($data['twon'] > 0){
                $query = $area_list[3]['name'];
            }else{
                $query = $area_list[2]['name'];
            }
            $address_url = "http://api.map.baidu.com/place/v2/search?query=".$query."&region=".$region."&output=json&ak=iR2qhnXd5vrFI9wUuIRG9AWGIqykVNok&page_size=1&page_num=1";
            $address_result = httpRequest($address_url,"get");
            $address_data = json_decode($address_result, true);
        }
        if(!empty($address_data['results'][0]['location'])){
            $this->longitude = $address_data['results'][0]['location']['lng'];
            $this->latitude = $address_data['results'][0]['location']['lat'];
        }
        if(empty($this->longitude)){
            $this->longitude = 0;
        }
        return $this->longitude;
    }
    public function setLatitudeAttr($value, $data)
    {
        if(empty($this->latitude)){
            $this->latitude = 0;
        }
        return $this->latitude;
    }

    public function getFullAddressAttr($value, $data)
    {
        $region = Db::name('region')->where('id', 'IN', [$data['province'], $data['city'], $data['district'], $data['twon']])->order('level asc')->cache(true)->column('name');
        return implode(' ', $region) . ' ' .$data['address'];
    }

    public function getAddressAreaAttr($value, $data)
    {
        $region = Db::name('region')->where('id', 'IN', [$data['province'], $data['city'], $data['district'], $data['twon']])->order('level asc')->cache(true)->column('name');
        return implode(' ', $region);
    }

    public function getProvinceNameAttr($value, $data)
    {
        return Db::name('region')->where('id', $data['province'])->cache(true)->value('name');
    }
    public function getCityNameAttr($value, $data){
        return Db::name('region')->where('id', $data['city'])->cache(true)->value('name');
    }
    public function getDistrictNameAttr($value, $data){
        return Db::name('region')->where('id', $data['district'])->cache(true)->value('name');
    }
    public function getTwonNameAttr($value, $data){
        return Db::name('region')->where('id', $data['twon'])->cache(true)->value('name');
    }
}
