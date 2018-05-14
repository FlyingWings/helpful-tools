<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 18-5-14
 * Time: 下午2:41
 */

namespace HTools\Library\RedisModel;

use HTools\BaseClass\ListRedisModel;
class Bullets extends ListRedisModel
{
    public function __construct($model_name="bullets"){
        $this->model_name = $model_name;
        parent::__construct();
    }
}