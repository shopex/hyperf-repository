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

trait RepositoryFactory
{
    protected static $entity;

    public static function instance()
    {
        $className = static::$entity;
        $entity = new $className();
        $entity->setRepositoryClassName(static::class);
        return $entity->getRepository();
    }

}
