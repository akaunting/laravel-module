<?php

namespace Akaunting\Module;

use Akaunting\Module\Contracts\ActivatorInterface;
use Illuminate\Cache\CacheManager;
use Illuminate\Container\Container;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Illuminate\Support\Traits\Macroable;
use Illuminate\Translation\Translator;

abstract class Module
{
    use Macroable;

    /**
     * The laravel|lumen application instance.
     *
     * @var \Illuminate\Contracts\Foundation\Application|\Laravel\Lumen\Application
     */
    protected $app;

    /**
     * The module alias.
     *
     * @var
     */
    protected $alias;

    /**
     * The module path.
     *
     * @var string
     */
    protected $path;

    /**
     * @var array of cached Json objects, keyed by filename
     */
    protected $moduleJson = [];

    /**
     * @var CacheManager
     */
    private $cache;

    /**
     * @var Filesystem
     */
    private $files;

    /**
     * @var Translator
     */
    private $translator;

    /**
     * @var ActivatorInterface
     */
    private $activator;

    /**
     * The constructor.
     *
     * @param Container $app
     * @param $alias
     * @param $path
     */
    public function __construct(Container $app, string $alias, $path)
    {
        $this->alias = $alias;
        $this->path = $path;
        $this->cache = $app['cache'];
        $this->files = $app['files'];
        $this->translator = $app['translator'];
        $this->activator = $app[ActivatorInterface::class];
        $this->app = $app;
    }

    /**
     * Get name.
     *
     * @return string
     */
    public function getName()
    {
        $name = trans($this->alias . '::general.name');

        if ($name == $this->alias . '::general.name') {
            $name = Str::title(str_replace('-', ' ', $this->get('name', $this->alias)));
        }

        return $name;
    }

    /**
     * Get name in lower case.
     *
     * @return string
     */
    public function getLowerName()
    {
        return Str::kebab($this->alias);
    }

    /**
     * Get name in studly case.
     *
     * @return string
     */
    public function getStudlyName()
    {
        return Str::studly($this->alias);
    }

    /**
     * Get name in snake case.
     *
     * @return string
     */
    public function getSnakeName()
    {
        return Str::snake($this->alias);
    }

    /**
     * Get description.
     *
     * @return string
     */
    public function getDescription()
    {
        $description = trans($this->alias . '::general.description');

        if ($description == $this->alias . '::general.description') {
            $description = $this->get('description');
        }

        return $description;
    }

    /**
     * Get alias.
     *
     * @return string
     */
    public function getAlias()
    {
        return Str::kebab($this->alias);
    }

    /**
     * Get priority.
     *
     * @return string
     */
    public function getPriority()
    {
        return $this->get('priority');
    }

    /**
     * Get module requirements.
     *
     * @return array
     */
    public function getRequires()
    {
        return $this->get('requires');
    }

    /**
     * Get path.
     *
     * @return string
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * Set path.
     *
     * @param string $path
     *
     * @return $this
     */
    public function setPath($path)
    {
        $this->path = $path;

        return $this;
    }

    /**
     * Register the module.
     */
    public function register()
    {
        $this->autoload('register');

        $this->registerAliases();

        $this->registerProviders();

        $this->fireEvent('register');
    }

    /**
     * Boot the module.
     */
    public function boot()
    {
        if (config('module.autoload.translations') === true) {
            $this->loadTranslations();
        }

        $this->autoload('boot');

        $this->fireEvent('boot');
    }

    public function autoload(string $stage): void
    {
        if (config('module.autoload.files') == $stage) {
            $this->loadFiles();
        }

        if (config('module.autoload.composer') == $stage) {
            $this->loadComposer();
        }
    }

    /**
     * Load the translations of this module.
     */
    protected function loadTranslations(): void
    {
        $name = $this->getAlias();

        $path = $this->getPath() . '/Resources/lang';

        if (is_dir($path)) {
            $this->loadTranslationsFrom($path, $name);
        }
    }

    /**
     * Load the files from this module.
     */
    protected function loadFiles(): void
    {
        foreach ($this->get('files', []) as $file) {
            include_once $this->getPath() . '/' . $file;
        }
    }

    /**
     * Load the composer of this module.
     */
    protected function loadComposer(): void
    {
        $autoload = $this->getPath() . '/vendor/autoload.php';

        if (! is_file($autoload)) {
            return;
        }

        include $autoload;
    }

    /**
     * Get json contents from the cache, setting as needed.
     *
     * @param string $file
     *
     * @return Json
     */
    public function json($file = null) : Json
    {
        if ($file === null) {
            $file = 'module.json';
        }

        return Arr::get($this->moduleJson, $file, function () use ($file) {
            return $this->moduleJson[$file] = new Json($this->getPath() . '/' . $file, $this->files);
        });
    }

    /**
     * Get a specific data from json file by given the key.
     *
     * @param string $key
     * @param null $default
     *
     * @return mixed
     */
    public function get(string $key, $default = null)
    {
        return $this->json()->get($key, $default);
    }

    /**
     * Get a specific data from composer.json file by given the key.
     *
     * @param $key
     * @param null $default
     *
     * @return mixed
     */
    public function getComposerAttr($key, $default = null)
    {
        return $this->json('composer.json')->get($key, $default);
    }

    /**
     * Register the module event.
     *
     * @param string $event
     */
    protected function fireEvent($event)
    {
        $this->app['events']->dispatch(sprintf('module.%s.' . $event, $this->getLowerName()), [$this]);
    }
    /**
     * Register the aliases from this module.
     */
    abstract public function registerAliases();

    /**
     * Register the service providers from this module.
     */
    abstract public function registerProviders();

    /**
     * Get the path to the cached *_module.php file.
     *
     * @return string
     */
    abstract public function getCachedServicesPath();

    /**
     * Handle call __toString.
     *
     * @return string
     */
    public function __toString()
    {
        return $this->getStudlyName();
    }

    /**
     * Determine whether the given status same with the current module status.
     */
    public function isStatus(bool $status) : bool
    {
        return $this->activator->is($this, $status);
    }

    /**
     * Determine whether the current module activated.
     */
    public function enabled() : bool
    {
        return $this->activator->is($this, true);
    }

    /**
     *  Determine whether the current module not disabled.
     */
    public function disabled() : bool
    {
        return $this->activator->is($this, false);
    }

    /**
     * Set active state for current module.
     */
    public function setActive(bool $active): void
    {
        $this->activator->setActive($this, $active);
    }

    /**
     * Disable the current module.
     */
    public function disable(): void
    {
        $this->fireEvent('disabling');

        $this->activator->disable($this);

        $this->flushCache();

        $this->fireEvent('disabled');
    }

    /**
     * Enable the current module.
     */
    public function enable(): void
    {
        $this->fireEvent('enabling');

        $this->activator->enable($this);

        $this->flushCache();

        $this->fireEvent('enabled');
    }

    /**
     * Delete the current module.
     */
    public function delete(): bool
    {
        $this->activator->delete($this);

        return $this->json()->getFilesystem()->deleteDirectory($this->getPath());
    }

    /**
     * Get extra path.
     *
     * @param string $path
     *
     * @return string
     */
    public function getExtraPath(string $path) : string
    {
        return $this->getPath() . '/' . $path;
    }

    /**
     * Handle call to __get method.
     *
     * @param $key
     *
     * @return mixed
     */
    public function __get($key)
    {
        return $this->get($key);
    }

    private function flushCache(): void
    {
        if (config('module.cache.enabled')) {
            $this->cache->store()->flush();
        }
    }

    /**
     * Register a translation file namespace.
     *
     * @param  string  $path
     * @param  string  $namespace
     * @return void
     */
    private function loadTranslationsFrom(string $path, string $namespace): void
    {
        $this->translator->addNamespace($namespace, $path);
    }
}
