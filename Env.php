<?php
/**
 * Created by PhpStorm.
 * User: sai
 * Date: 4/5/18
 * Time: 12:10 PM
 * @author Saiat Kalbiev <kalbievich11@gmail.com>
 */

namespace apollo11\envAnalyzer;


use apollo11\envAnalyzer\exceptions\FileNotFoundException;
use apollo11\envAnalyzer\exceptions\InvalidFileException;
use apollo11\envAnalyzer\helpers\ArrayHelper;
use apollo11\envAnalyzer\helpers\ConsoleHelper;
use apollo11\envAnalyzer\helpers\FileHelper;

class Env
{
    const KEY = 'key';
    const VALUE = 'value';

    private $environmentPath;
    private $environmentDistPath;
    private $environment;
    private $environmentDist;

    /**
     * Env constructor.
     * @param $environmentPath string
     * @param $environmentDistPath string
     * @throws FileNotFoundException
     * @throws InvalidFileException
     */
    public function __construct($environmentPath, $environmentDistPath)
    {
        $this->setEnvironmentPath($environmentPath);
        $this->setEnvironmentDistPath($environmentDistPath);
    }


    /**
     * @param $environmentPath string
     * @author Saiat Kalbiev <kalbievich11@gmail.com>
     * @throws FileNotFoundException
     * @throws InvalidFileException
     */
    public function setEnvironmentPath($environmentPath)
    {
//        FileHelper::checkFileValidity($environmentPath);
        $this->environmentPath = $environmentPath;
    }


    /**
     * @param $environmentDistPath string
     * @author Saiat Kalbiev <kalbievich11@gmail.com>
     * @throws FileNotFoundException
     * @throws InvalidFileException
     */
    public function setEnvironmentDistPath($environmentDistPath)
    {
//        FileHelper::checkFileValidity($environmentDistPath);
        $this->environmentDistPath = $environmentDistPath;
    }

    /**
     * @author Saiat Kalbiev <kalbievich11@gmail.com>
     * @throws FileNotFoundException
     * @throws InvalidFileException
     */
    private function validate()
    {
        $this->setEnvironment();
        $this->setEnvironmentDist();
    }

    /**
     * @author Saiat Kalbiev <kalbievich11@gmail.com>
     * @throws FileNotFoundException
     * @throws InvalidFileException
     */
    public function checkMissingVariables()
    {
        $this->validate();
        $difference = ArrayHelper::getMissingValues($this->environmentDist,$this->environment);
        $consoleHelper = new ConsoleHelper();

        if(!$difference) {
            echo $consoleHelper->getColoredString("Warning: No missing variables were found in Environment file.",null,ConsoleHelper::BACKGROUND_YELLOW) . "\n";
            return;
        }

        $maxKeyLength = 0;
        foreach($difference as $key => $value) {
            $maxKeyLength = max($maxKeyLength, strlen($key));
        }
        foreach($difference as $key => $value){
            $tmpKey = $consoleHelper->getColoredString($key,ConsoleHelper::FOREGROUND_GREEN,null);
            echo "[ENV] -- Insert the value for `{$tmpKey}`({$value}): ";
            $envValue = readline() ?: $value;
            if ( preg_match('/\s/',$envValue) ){
                $envValue = '"' . $envValue . '"';
            }
            $txt = str_pad($key, $maxKeyLength + 2, ' ', STR_PAD_RIGHT) . " = " . $envValue;
            file_put_contents($this->environmentPath, $txt.PHP_EOL , FILE_APPEND | LOCK_EX);
        }
        echo $consoleHelper->getColoredString("Env file has been updated successfully!",null,ConsoleHelper::BACKGROUND_GREEN) ."\n";
        return;
    }

    /**
     * @author Saiat Kalbiev <kalbievich11@gmail.com>
     * @throws FileNotFoundException
     * @throws InvalidFileException
     */
    private function setEnvironment()
    {
        if (!file_exists($this->environmentPath)){
            touch($this->environmentPath);
        }
        FileHelper::isValidConfigFile($this->environmentPath);
        $variables = [];
        $lines = file($this->environmentPath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

        foreach ($lines as $line) {

            if (strpos(trim($line), '#') === 0) {
                continue;
            }

            if (strpos($line, '=') !== false) {
                $keyValuePair = static::getKeyValuePair($line);
                $variables[$keyValuePair[self::KEY]] = $keyValuePair[self::VALUE];
            }

        }

        $this->environment = $variables;
    }


    /**
     * @author Saiat Kalbiev <kalbievich11@gmail.com>
     * @throws FileNotFoundException
     * @throws InvalidFileException
     */
    private function setEnvironmentDist()
    {
        FileHelper::isValidSampleConfigFile($this->environmentDistPath);
        $variables = [];
        $lines = file($this->environmentDistPath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

        foreach ($lines as $line) {

            if (strpos(trim($line), '#') === 0) {
                continue;
            }

            if (strpos($line, '=') !== false) {
                $keyValuePair = static::getKeyValuePair($line);
                $variables[$keyValuePair[self::KEY]] = $keyValuePair[self::VALUE];
            }

        }

        $this->environmentDist = $variables;
    }

    /**
     * @param $name string
     * @param null $value
     * @return array
     * @author Saiat Kalbiev <kalbievich11@gmail.com>
     */
    protected static function getKeyValuePair($name, $value = null)
    {
        if (strpos($name, '=') !== false) {
            $result = array_map('trim', explode('=', $name, 2));
            return [
                self::KEY => $result[0],
                self::VALUE => $result[1]
            ];
        }
        return [
            self::KEY => $name,
            self::VALUE => $value
        ];
    }

}