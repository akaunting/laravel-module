<?php

namespace Akaunting\Module\Activators;

use Akaunting\Module\Contracts\ActivatorInterface;
use Akaunting\Module\Module;
use Illuminate\Cache\CacheManager as Cache;
use Illuminate\Config\Repository as Config;
use Illuminate\Container\Container;
use Illuminate\Filesystem\Filesystem;

class File implements ActivatorInterface
{
    public Cache $cache;

    public Filesystem $files;

    public Config $config;

    public array $statuses;

    public function __construct(Container $app)
    {
        $this->cache = $app['cache'];
        $this->files = $app['files'];
        $this->config = $app['config'];
        $this->statuses = $this->getStatuses();
    }

    public function is(Module $module, bool $active): bool
    {
        if (! isset($this->statuses[$module->getAlias()])) {
            $this->setActive($module, $module->get('active', false));
        }

        return $this->statuses[$module->getAlias()] === $active;
    }

    public function enable(Module $module): void
    {
        $this->setActive($module, true);
    }

    public function disable(Module $module): void
    {
        $this->setActive($module, false);
    }

    public function setActive(Module $module, bool $active): void
    {
        $this->statuses[$module->getAlias()] = $active;

        $module->json()->set('active', $active)->save();

        $this->writeJson();

        $this->flushCache();
    }

    public function delete(Module $module): void
    {
        if (! isset($this->statuses[$module->getAlias()])) {
            return;
        }

        unset($this->statuses[$module->getAlias()]);

        $this->writeJson();

        $this->flushCache();
    }

    public function reset(): void
    {
        $path = $this->getFilePath();

        if ($this->files->exists($path)) {
            $this->files->delete($path);
        }

        $this->statuses = [];

        $this->flushCache();
    }

    public function getStatuses(): array
    {
        if (! $this->config->get('module.cache.enabled')) {
            return $this->readJson();
        }

        $key = $this->config->get('module.cache.key') . '.statuses';
        $lifetime = $this->config->get('module.cache.lifetime');

        return $this->cache->remember($key, $lifetime, function () {
            return $this->readJson();
        });
    }

    public function readJson(): array
    {
        $path = $this->getFilePath();

        if (! $this->files->exists($path)) {
            return [];
        }

        return json_decode($this->files->get($path), true);
    }

    public function writeJson(): void
    {
        $this->files->put($this->getFilePath(), json_encode($this->statuses, JSON_PRETTY_PRINT));
    }

    public function flushCache(): void
    {
        $key = $this->config->get('module.cache.key') . '.statuses';

        $this->cache->forget($key);
    }

    public function getFilePath()
    {
        return storage_path('module_statuses.json');
    }
}
