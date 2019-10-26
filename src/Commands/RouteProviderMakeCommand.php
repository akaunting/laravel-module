<?php

namespace Akaunting\Module\Commands;

use Akaunting\Module\Support\Config\GenerateConfigReader;
use Akaunting\Module\Support\Stub;
use Akaunting\Module\Traits\ModuleCommandTrait;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

class RouteProviderMakeCommand extends GeneratorCommand
{
    use ModuleCommandTrait;

    protected $argumentName = 'alias';

    /**
     * The command name.
     *
     * @var string
     */
    protected $name = 'module:route-provider';

    /**
     * The command description.
     *
     * @var string
     */
    protected $description = 'Create a new route service provider for the specified module.';

    /**
     * The command arguments.
     *
     * @return array
     */
    protected function getArguments()
    {
        return [
            ['alias', InputArgument::OPTIONAL, 'The alias of module will be used.'],
        ];
    }

    protected function getOptions()
    {
        return [
            ['force', null, InputOption::VALUE_NONE, 'Force the operation to run when the file already exists.'],
        ];
    }

    /**
     * Get template contents.
     *
     * @return string
     */
    protected function getTemplateContents()
    {
        $module = $this->getModule();

        return (new Stub('/route-provider.stub', [
            'ALIAS'             => $module->getAlias(),
            'NAMESPACE'         => $this->getClassNamespace($module),
            'CLASS'             => $this->getClass(),
            'MODULE'            => $this->getModuleName(),
            'NAME'              => $this->getFileName(),
            'STUDLY_NAME'       => $module->getStudlyName(),
            'MODULE_NAMESPACE'  => $this->laravel['module']->config('namespace'),
            'WEB_ROUTES_PATH'   => $this->getWebRoutesPath(),
            'API_ROUTES_PATH'   => $this->getApiRoutesPath(),
        ]))->render();
    }

    /**
     * @return string
     */
    private function getFileName()
    {
        return 'Route';
    }

    /**
     * Get the destination file path.
     *
     * @return string
     */
    protected function getDestinationFilePath()
    {
        $path = module()->getModulePath($this->getModuleAlias());

        $generatorPath = GenerateConfigReader::read('provider');

        return $path . $generatorPath->getPath() . '/' . $this->getFileName() . '.php';
    }

    /**
     * @return mixed
     */
    protected function getWebRoutesPath()
    {
        return '/' . $this->laravel['module']->config('stubs.files.routes/web', 'Routes/web.php');
    }

    /**
     * @return mixed
     */
    protected function getApiRoutesPath()
    {
        return '/' . $this->laravel['module']->config('stubs.files.routes/api', 'Routes/api.php');
    }

    public function getDefaultNamespace() : string
    {
        return $this->laravel['module']->config('paths.generator.provider.path', 'Providers');
    }
}
