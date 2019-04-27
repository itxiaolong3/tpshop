<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/12/19
 * Time: 9:32
 */
namespace app\api\model;

use think\Model;

class BaseModel extends Model
{
    public function __construct($data = [])
    {
        parent::__construct($data);
    }
}