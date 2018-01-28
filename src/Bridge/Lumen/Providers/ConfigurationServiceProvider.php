<?php
declare(strict_types=1);

namespace EoneoPay\Utils\Bridge\Lumen\Providers;

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
     * Register all the application config files.
     *
     * @return void
     */
    public function register(): void
    {
        $configPath = \sprintf('%s/%s', $this->app->basePath(), self::CONFIG_FOLDER_NAME);

        if (\is_dir($configPath)) {
            foreach (\scandir($configPath, SCANDIR_SORT_NONE) as $configFile) {
                // Skip if not php file
                if (!\preg_match('#[a-zA-Z0-9_-]+.php#i', $configFile)) {
                    continue;
                }

                $this->app->configure(\basename($configFile));
            }
        }
    }
}
