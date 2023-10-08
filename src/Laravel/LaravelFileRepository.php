<?php

namespace Akaunting\Module\Laravel;

use Akaunting\Module\FileRepository;
use Akaunting\Module\Laravel\Module;

class LaravelFileRepository extends FileRepository
{
    /**
     * {@inheritdoc}
     */
    protected function createModule(...$args)
    {
        return new Module(...$args);
    }
}
