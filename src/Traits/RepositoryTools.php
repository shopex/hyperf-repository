<?php

declare(strict_types=1);
/**
 * This file is part of Hyperf.
 *
 * @link     https://www.hyperf.io
 * @document https://doc.hyperf.io
 * @contact  group@hyperf.io
 * @license  https://github.com/hyperf/hyperf/blob/master/LICENSE
 */

namespace Shopex\Hyperf\Traits;

trait RepositoryTools
{
    /**
     * 通过主键id/ids获取信息
     *
     * @param $id id/id数组
     * @param bool $useCache 是否使用模型缓存
     *
     * @return array
     */
    public function find($id, $useCache = true)
    {
        $instance = $this->getModel();

        if ($useCache === true) {
            $modelCache = is_array($id) ? $instance->findManyFromCache($id) : $instance->findFromCache($id);
            return isset($modelCache) && $modelCache ? $modelCache->toArray() : [];
        }
        $query = $instance->query()->find($id);

        return $query ? $query->toArray() : [];
    }

    /**
     * 通过主键id/ids指定字段获取信息
     *
     * @param $id id/id数组
     * @param array $cols
     *
     * @return array
     */
    public function findForSelect($id, $cols = ['*'])
    {
        $instance = $this->getModel();
        $query = $instance->query();

        if (is_array($cols) && $cols[0] != '*') {
            $query->select($cols);
        }
        $result = $instance->find($id);
        return $result ? $result->toArray() : [];
    }

    /**
     * 创建/修改记录
     *
     * @param array $data 保存数据
     * @param bool $type 是否强制写入，适用于主键是规则生成情况
     *
     * @return array
     */
    public function saveData($data, $type = false)
    {
        $id = null;
        $result = [];
        $instance = $this->getModel();
        $primaryKey = $instance->getKeyName();
        if (isset($data[$primaryKey]) && $data[$primaryKey] && !$type) {
            $id = $data[$primaryKey];
            unset($data[$primaryKey]);
            $query = $instance->query()->find($id);
            foreach ($data as $k => $v) {
                $query->$k = $v;
            }
            $query->save();
            return $query ? $query->toArray() : [];
        } else {
            foreach ($data as $k => $v) {
                if ($k === $primaryKey) {
                    $id = $v;
                }
                $instance->$k = $v;
            }
            $instance->save();
            // if (!$id) {
            //     $id = $instance->$primaryKey;
            // }
            return $instance ? $instance->toArray() : [];
        }
    }
    
    /**
     * 更新数据表字段数据
     *
     * @param $filter 筛选条件
     * @param $data   更新数据
     *
     * @return array
     */
    public function updateOneBy($filter, $data)
    {
        $instance = $this->getModel();
        if (is_array($filter) && !empty($filter)) {
            foreach ($filter as $k => $v) {
                if (is_array($v)) {
                    if (strtolower($v[0]) == 'in') {
                        $instance = $instance->whereIn($k, explode(',', $v[1]));
                    } else {
                        $instance = $instance->where($k, $v[0], $v[1]);
                    }
                } else {
                    $instance = $instance->where($k, $v);
                }
            }
        }
        
        $query = $instance->first();
        foreach ($data as $k => $v) {
            $query->$k = $v;
        }
        $query->save();
        return $query ? $query->toArray() : [];
    }

    /**
     * 根据条件获取一条结果
     *
     * @param array $filter 查询条件
     * @param array $cols 显示的字段
     *
     * @return array
     */
    public function findOneBy($filter, $cols = ['*'])
    {
        $instance = $this->getModel();

        if (is_array($filter) && !empty($filter)) {
            foreach ($filter as $k => $v) {
                if (is_array($v)) {
                    if (strtolower($v[0]) == 'in') {
                        $instance = $instance->whereIn($k, explode(',', $v[1]));
                    } else {
                        $instance = $instance->where($k, $v[0], $v[1]);
                    }
                } else {
                    $instance = $instance->where($k, $v);
                }
                //$instance = is_array($v) ? $instance->where($k, $v[0], $v[1]) : $instance->where($k, $v);
            }
        }

        if (is_array($cols) && $cols[0] != '*') {
            $instance->select($cols);
        }

        $query = $instance->first();

        $result =  empty($query) ? [] : $query->toArray();

        return $result;
    }

    /**
     * 根据条件获取结果
     *
     * @param array $filter 查询条件
     * @param array $cols 显示的字段
     * @param integer $page 页码
     * @param integer $page_size 每页数量
     * @param string $order_by 排序方式
     * @param boolean $total 是否查询数量
     *
     * @return array
     */
    public function getList($filter, $cols = ['*'], $page = 1, $page_size = 10, $order_by = [], $total = true)
    {
        $instance = $this->getModel();

        if (is_array($filter) && !empty($filter)) {
            foreach ($filter as $k => $v) {
                if (is_array($v)) {
                    if (strtolower($v[0]) == 'in') {
                        $instance = $instance->whereIn($k, explode(',', $v[1]));
                    } else {
                        $instance = $instance->where($k, $v[0], $v[1]);
                    }
                } else {
                    $instance = $instance->where($k, $v);
                }
            }
        }

        if (is_array($cols) && $cols[0] != '*') {
            $instance->select($cols);
        }

        if ($total) {
            $count = $instance->count();
        }

        if (!empty($order_by) || !is_array($order_by)) {
            foreach ($order_by as $field => $ascdesc) {
                $instance->orderBy($field, $ascdesc);
            }
        }

        $instance->offset(($page - 1) * $page_size)->limit($page_size);
        $query = $instance->get();

        $list =  empty($query) ? [] : $query->toArray();
        $result = ['list' => $list];
        $total ? $result['total_count'] = $count : [];

        return $result;
    }

    /**
     * 根据ids删除
     *
     * @param $ids 删除的主键ids
     *
     * @return int
     */
    public function deleteByIds($ids)
    {
        $instance = $this->getModel();

        return $instance->destroy($ids);
    }

    /**
     * 根据条件删除
     *
     * @param array $where 删除的条件
     *
     * @return mixed
     */
    public function deleteByWhere(array $where = [])
    {
        $instance = $this->getModel();

        return $instance->where($where)->delete();
    }
    
    /**
     * 根据条件获取记录条数
     *
     * @param array $filter 查询条件
     *
     * @return integer
     */
    public function newcount($filter)
    {
        $instance = $this->getModel();
    
        if (is_array($filter) && !empty($filter)) {
            foreach ($filter as $k => $v) {
                if (is_array($v)) {
                    if (strtolower($v[0]) == 'in') {
                        $instance = $instance->whereIn($k, explode(',', $v[1]));
                    } else {
                        $instance = $instance->where($k, $v[0], $v[1]);
                    }
                } else {
                    $instance = $instance->where($k, $v);
                }
            }
        }
        
        $count = $instance->count();
        
        return $count;
    }

}
