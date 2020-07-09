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

namespace Shopex\Hyperf;

use Hyperf\Database\Model\Builder;
use Shopex\Hyperf\Interfaces\RepositoryInterface;

class Repository extends Builder implements RepositoryInterface
{
    use Traits\RepositoryFactory;
    use Traits\RepositoryTools;
}
