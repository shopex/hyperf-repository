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

namespace Shopex\Hyperf\Database;

use Hyperf\Database\Model\Builder;
use Hyperf\DbConnection\Model\Model;

class Entity extends Model
{
    /**
     * Create a new Model query builder for the model.
     *
     * @param \Hyperf\Database\Query\Builder $query
     * @return \Hyperf\Database\Model\Builder|static
     */
    public function newModelBuilder($query)
    {
        if (! $this->repository) {
            return new Builder($query);
        }
        if ($this->repository && class_exists($this->repository)) {
            $repository = $this->repository;
            $builder = new $repository($query);
            if ($builder instanceof Builder) {
                return $builder;
            }
        }
        throw new RuntimeException(sprintf('Cannot detect the repository of %s', $this->repository));
    }

    public function setRepositoryClassName($name)
    {
        $this->repository = $name;
    }

    /**
     * @throws RuntimeException when the model does not define the repository class
     */
    public function getRepository()
    {
        return $this->newQuery();
    }
}
