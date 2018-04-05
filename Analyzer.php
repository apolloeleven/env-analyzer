<?php
/**
 * User: sai
 * Date: 4/2/18
 * Time: 1:24 PM
 * @author Saiat Kalbiev <kalbievich11@gmail.com>
 */

namespace apollo11\envAnalyzer;

use Composer\Script\Event;

class Analyzer
{


    /**
     * @param Event $event
     * @author Saiat Kalbiev <kalbievich11@gmail.com>
     * @throws exceptions\FileNotFoundException
     * @throws exceptions\InvalidFileException
     */
    public static function analyzeEnvComposer(Event $event)
    {
        $extras = $event->getComposer()->getPackage()->getExtra();

        if (!isset($extras['apollo11-parameters'])) {
            throw new \InvalidArgumentException('The parameter handler needs to be configured through the extra.apollo11-parameters setting.');
        }
        $configs = $extras['apollo11-parameters'];

        if (!is_array($configs) || empty($configs)) {
            throw new \InvalidArgumentException('The extra.apollo11-parameters setting must be an array that should contain `env-path` and `env-dist-path`.');
        }

        if (isset($configs['env-path']) && isset($configs['env-dist-path'])) {
            self::analyzeEnv($configs['env-path'],$configs['env-dist-path']);
        } else {
            throw new \InvalidArgumentException('Either `env-path` or `env-dist-path` was not found.');
        }

    }

    /**
     * @param $envPath string
     * @param $envDistPath string
     * @author Saiat Kalbiev <kalbievich11@gmail.com>
     * @throws exceptions\FileNotFoundException
     * @throws exceptions\InvalidFileException
     */
    public static function analyzeEnv($envPath, $envDistPath)
    {
        $analyzer = new Env($envPath,$envDistPath);
        $analyzer->checkMissingVariables();
    }

    /**
     * @param Event $event
     * @author Saiat Kalbiev <kalbievich11@gmail.com>
     * @throws exceptions\FileNotFoundException
     * @throws exceptions\InvalidFileException
     */
    public static function analyzePhpComposer(Event $event)
    {
        $extras = $event->getComposer()->getPackage()->getExtra();

        if (!isset($extras['apollo11-parameters'])) {
            throw new \InvalidArgumentException('The parameter handler needs to be configured through the extra.apollo11-parameters setting.');
        }
        $configs = $extras['apollo11-parameters'];

        if (!is_array($configs) || empty($configs)) {
            throw new \InvalidArgumentException('The extra.apollo11-parameters setting must be an array that should contain `php-env-path` and `php-env-dist-path`.');
        }

        if (isset($configs['php-env-path']) && isset($configs['php-env-dist-path'])) {
            self::analyzePhp($configs['php-env-path'],$configs['php-env-dist-path']);
        } else {
            throw new \InvalidArgumentException('Either `php-env-path` or `php-env-dist-path` was not found.');
        }
    }

    /**
     * @param $phpEnvPath
     * @param $phpEnvDistPath
     * @author Saiat Kalbiev <kalbievich11@gmail.com>
     * @throws exceptions\FileNotFoundException
     * @throws exceptions\InvalidFileException
     */
    public static function analyzePhp($phpEnvPath, $phpEnvDistPath)
    {
        $analyzer = new Php($phpEnvPath, $phpEnvDistPath);
        $analyzer->checkMissingVariables();
    }

}