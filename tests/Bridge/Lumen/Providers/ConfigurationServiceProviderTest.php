<?php
declare(strict_types=1);

namespace Tests\EoneoPay\Utils\Bridge\Lumen\Providers;

use EoneoPay\Utils\Bridge\Lumen\Providers\ConfigurationServiceProvider;
use Illuminate\Contracts\Foundation\Application;
use Tests\EoneoPay\Utils\TestCase;

class ConfigurationServiceProviderTest extends TestCase
{
    /**
     * @var array
     */
    private static $configFiles = [
        'config-one',
        'config-two'
    ];

    /**
     * Service provider should call configure on application for all php config files and ignore others.
     */
    public function testRegisterCallsConfigureForFilesInConfig(): void
    {
        $app = \Mockery::mock(Application::class);
        $app->shouldReceive('basePath')->once()->withNoArgs()->andReturn(\realpath(__DIR__));

        foreach (static::$configFiles as $configFile) {
            $app->shouldReceive('configure')
                ->once()
                ->with(\basename(\sprintf('%s.php', $configFile)))
                ->andReturnSelf();
        }

        $app->shouldNotReceive('configure')->once()->with('no-config.txt');

        /** @var \Illuminate\Contracts\Foundation\Application $app */
        $serviceProvider = new ConfigurationServiceProvider($app);
        $serviceProvider->register();

        self::assertInstanceOf(ConfigurationServiceProvider::class, $serviceProvider);
    }
}
