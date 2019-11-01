<?php

namespace Akaunting\Module\Providers;

use Akaunting\Module\Commands\CommandMakeCommand;
use Akaunting\Module\Commands\ControllerMakeCommand;
use Akaunting\Module\Commands\DeleteCommand;
use Akaunting\Module\Commands\DisableCommand;
use Akaunting\Module\Commands\DumpCommand;
use Akaunting\Module\Commands\EnableCommand;
use Akaunting\Module\Commands\EventMakeCommand;
use Akaunting\Module\Commands\FactoryMakeCommand;
use Akaunting\Module\Commands\InstallCommand;
use Akaunting\Module\Commands\JobMakeCommand;
use Akaunting\Module\Commands\ListCommand;
use Akaunting\Module\Commands\ListenerMakeCommand;
use Akaunting\Module\Commands\MailMakeCommand;
use Akaunting\Module\Commands\MiddlewareMakeCommand;
use Akaunting\Module\Commands\MigrateCommand;
use Akaunting\Module\Commands\MigrateRefreshCommand;
use Akaunting\Module\Commands\MigrateResetCommand;
use Akaunting\Module\Commands\MigrateRollbackCommand;
use Akaunting\Module\Commands\MigrateStatusCommand;
use Akaunting\Module\Commands\MigrationMakeCommand;
use Akaunting\Module\Commands\ModelMakeCommand;
use Akaunting\Module\Commands\ModuleMakeCommand;
use Akaunting\Module\Commands\NotificationMakeCommand;
use Akaunting\Module\Commands\PolicyMakeCommand;
use Akaunting\Module\Commands\ProviderMakeCommand;
use Akaunting\Module\Commands\PublishCommand;
use Akaunting\Module\Commands\PublishConfigurationCommand;
use Akaunting\Module\Commands\PublishMigrationCommand;
use Akaunting\Module\Commands\PublishTranslationCommand;
use Akaunting\Module\Commands\RequestMakeCommand;
use Akaunting\Module\Commands\ResourceMakeCommand;
use Akaunting\Module\Commands\RuleMakeCommand;
use Akaunting\Module\Commands\SeedCommand;
use Akaunting\Module\Commands\SeedMakeCommand;
use Akaunting\Module\Commands\SetupCommand;
use Akaunting\Module\Commands\TestMakeCommand;
use Akaunting\Module\Commands\UnUseCommand;
use Akaunting\Module\Commands\UpdateCommand;
use Akaunting\Module\Commands\UseCommand;
use Illuminate\Support\ServiceProvider;

class Console extends ServiceProvider
{
    /**
     * The available commands
     *
     * @var array
     */
    protected $commands = [
        CommandMakeCommand::class,
        ControllerMakeCommand::class,
        DeleteCommand::class,
        DisableCommand::class,
        DumpCommand::class,
        EnableCommand::class,
        EventMakeCommand::class,
        JobMakeCommand::class,
        ListenerMakeCommand::class,
        MailMakeCommand::class,
        MiddlewareMakeCommand::class,
        NotificationMakeCommand::class,
        ProviderMakeCommand::class,
        InstallCommand::class,
        ListCommand::class,
        ModuleMakeCommand::class,
        FactoryMakeCommand::class,
        PolicyMakeCommand::class,
        RequestMakeCommand::class,
        RuleMakeCommand::class,
        MigrateCommand::class,
        MigrateRefreshCommand::class,
        MigrateResetCommand::class,
        MigrateRollbackCommand::class,
        MigrateStatusCommand::class,
        MigrationMakeCommand::class,
        ModelMakeCommand::class,
        PublishCommand::class,
        PublishConfigurationCommand::class,
        PublishMigrationCommand::class,
        PublishTranslationCommand::class,
        SeedCommand::class,
        SeedMakeCommand::class,
        SetupCommand::class,
        UnUseCommand::class,
        UpdateCommand::class,
        UseCommand::class,
        ResourceMakeCommand::class,
        TestMakeCommand::class,
    ];

    /**
     * Register the commands.
     */
    public function register()
    {
        $this->commands($this->commands);
    }

    /**
     * @return array
     */
    public function provides()
    {
        $provides = $this->commands;

        return $provides;
    }
}
