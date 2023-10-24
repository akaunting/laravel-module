<?php

namespace Akaunting\Module\Contracts;

use Akaunting\Module\Module;

interface ActivatorInterface
{
    public function is(Module $module, bool $active): bool;

    public function enable(Module $module): void;

    public function disable(Module $module): void;

    public function setActive(Module $module, bool $active): void;

    public function delete(Module $module): void;

    public function reset(): void;
}
