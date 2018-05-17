<?php
declare(strict_types=1);

namespace EoneoPay\Utils\Bridge\Lumen\Providers;

use GlobIterator;
use Illuminate\Support\ServiceProvider;

class ConfigurationServiceProvider extends ServiceProvider
{
    /**
     * The config folder name.
     *
     * @var string
     */
    private const CONFIG_FOLDER_NAME = 'config';

    /**
     * The config file pattern.
     *
     * @var string
     */
    private const CONFIG_FILE_PATTERN = '*.php';

    /**
     * Register all the application config files.
     *
     * @return void
     */
    public function register(): void
    {
        $configPath = \sprintf('%s/%s', $this->app->basePath(), self::CONFIG_FOLDER_NAME);

        if (\is_dir($configPath) === false) {
            return;
        }

        $globIterator = new GlobIterator(\sprintf('%s/%s', $configPath, self::CONFIG_FILE_PATTERN));

        foreach ($globIterator as $configFile) {
            /** @noinspection PhpUndefinedMethodInspection Method exists in \Laravel\Lumen\Application */
            $this->app->configure(\str_replace('.php', '', $configFile->getBasename()));
        }
    }
}
