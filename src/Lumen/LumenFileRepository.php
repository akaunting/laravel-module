<?php

namespace Akaunting\Module\Lumen;

use Akaunting\Module\FileRepository;
use Akaunting\Module\Lumen\Module;

class LumenFileRepository extends FileRepository
{
    /**
     * {@inheritdoc}
     */
    protected function createModule(...$args)
    {
        return new Module(...$args);
    }
}
