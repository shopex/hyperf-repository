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

namespace Shopex\Hyperf\Trait;

trait RespositoryFactory
{
    protected static $entity;

    public static function instance()
    {
        $entity = new self::$entity();
        $entity->setRepositoryClassName(static::class);
        return $entity->getRepository();
    }

}
