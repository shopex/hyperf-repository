<?php

namespace Shopex\Hyperf\Interfaces;

Interface RepositoryInterface
{

    public function find($id, $useCache = true);
    public function findForSelect($id, $cols = ['*']);
    public function saveData($data, $type = false);
    public function findOneBy($filter, $cols = ['*']);
    public function getList($filter, $cols = ['*'], $page = 1, $page_size = 10, $order_by = [], $total = true);
    public function deleteByIds($ids);
    public function deleteByWhere(array $where = []);

}