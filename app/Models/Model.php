<?php

namespace App\Models;


use Z1px\App\Models\Model as BaseModel;

class Model extends BaseModel
{

    /**
     * 是否开启数据库表增删改记录
     * @var bool
     */
    protected $tables_operated = false;

    /**
     * 查询条件构造
     * @param $data
     * @param array $params
     * @return mixed
     */
    protected function toWhere(object $data, array $params): object
    {
        if(!empty($params)){
            foreach ($params as $key=>$value){
                if(empty($value) && !is_numeric($value)) continue;
                switch ($key){
                    case 'title':
                        $data = $data->where($key, 'like', "%{$value}%");
                        break;
                    case 'start_date':
                        $data = $data->whereDate('created_at', '>=', $value);
                        break;
                    case 'end_date':
                        $data = $data->whereDate('created_at', '<=', $value);
                        break;
                    case 'date_range':
                        list($start_date, $end_date) = $value;
                        $data = $data->whereDate('created_at', '>=', $start_date)
                            ->whereDate('created_at', '<=', $end_date);
                        unset($start_date, $end_date);
                        break;
                    default:
                        if($this->isFillable($key)){
                            if(is_array($value)){
                                $data = $data->whereIn($key, $value);
                            }else{
                                $data = $data->where($key, $value);
                            }
                        }
                }
            }
        }
        return $data;
    }

}
