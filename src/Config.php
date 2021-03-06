<?php

namespace Modus\Config;

use Aura\Di;
use Aura\Di\ContainerBuilder;
use Dotenv\Dotenv;

class Config
{

    /**
     * Environments avaialble to our system.
     */
    const ENV_DEV = 'dev';
    const ENV_STAGING = 'staging';
    const ENV_PRODUCTION = 'production';
    const ENV_TESTING = 'testing';

    protected $environments = [
        self::ENV_DEV,
        self::ENV_STAGING,
        self::ENV_TESTING,
        self::ENV_PRODUCTION,
    ];

    /**
     * @var string The set environment
     */
    protected $environment;

    /**
     * @var string The directory where configurations are stored
     */
    protected $configDir;

    /**
     * @var ContainerBuilder The DI container builder
     */
    protected $containerBuilder;

    /**
     * @var array The parsed configuration array
     */
    protected $config = [];

    /**
     * @var Dotenv
     */
    protected $dotEnv;

    /**
     * @param string $enviornment
     * @param string $configDir
     * @param DotEnv|null $dotenv
     * @throws Exception\InvalidEnvironment
     */
    public function __construct($enviornment, $configDir)
    {
        $this->environment = $this->validateEnvironment($enviornment);
        $this->configDir = realpath($configDir);

        $this->loadConfiguration();
    }

    /**
     * @return array
     */
    public function getConfig()
    {
        return $this->config;
    }

    /**
     * @param string $env
     * @return string
     * @throws Exception\InvalidEnvironment
     */
    protected function validateEnvironment($env)
    {
        if (!in_array($env, $this->environments)) {
            throw new Exception\InvalidEnvironment(sprintf('%s is an invalid environment', $env));
        }

        return $env;
    }

    /**
     * @return array
     */
    protected function loadConfiguration()
    {

        // This is used in the confguration file.
        $env = $this->environment;

        $config = [];
        $env_config = $this->environment . '.php';
        if (file_exists($this->configDir . '/config.php')) {
            $config = array_replace_recursive($config, require($this->configDir . '/config.php'));
        }

        if (file_exists($this->configDir . '/' . $env_config)) {
            $config = array_replace_recursive($config, require($this->configDir . '/' . $env_config));
        }

        if (file_exists($this->configDir . '/local.php')) {
            $config = array_replace_recursive($config, require($this->configDir . '/local.php'));
        }

        $this->config = $config;
        return $config;
    }
}
