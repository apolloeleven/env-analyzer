<?php
/**
 * Created by PhpStorm.
 * User: sai
 * Date: 4/5/18
 * Time: 12:10 PM
 * @author Saiat Kalbiev <kalbievich11@gmail.com>
 */

namespace apollo11\envAnalizer;


use apollo11\envAnalizer\exceptions\FileNotFoundException;
use apollo11\envAnalizer\exceptions\InvalidFileException;

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
        $this->checkFailValidity($environmentPath);
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
        $this->checkFailValidity($environmentDistPath);
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
        $difference = $this->getDifference();

        if(!$difference) {
            echo "No missing variables were found in Environment file \n";
            return;
        }

        foreach($difference as $key => $value){
            $envValue = readline("[ENV] -- Insert the value for `{$key}`({$value}): ");
            if ( preg_match('/\s/',$envValue) ){
                $envValue = '"' . $envValue . '"';
            }
            $txt = $key . " = " . $envValue;
            $myfile = file_put_contents($this->environmentPath, "\n".$txt.PHP_EOL , FILE_APPEND | LOCK_EX);
        }
        return;
    }

    /**
     * @author Saiat Kalbiev <kalbievich11@gmail.com>
     * @throws FileNotFoundException
     * @throws InvalidFileException
     */
    private function setEnvironment()
    {
        $this->checkFailValidity($this->environmentPath);
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
        $this->checkFailValidity($this->environmentDistPath);
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

    /**
     * @param $filePath string
     * @author Saiat Kalbiev <kalbievich11@gmail.com>
     * @throws FileNotFoundException
     * @throws InvalidFileException
     */
    protected function checkFailValidity($filePath)
    {
        if (!file_exists($filePath)) {
            throw new FileNotFoundException("File with path: {$filePath} was not found");
        }

        if (!is_readable($filePath) || !is_file($filePath)) {
            throw new InvalidFileException("File with path {$filePath} was either not readable or is a directory");
        }
    }

    /**
     * @return array
     * @author Saiat Kalbiev <kalbievich11@gmail.com>
     */
    private function getDifference()
    {
        $returnArray = [];
        foreach ($this->environmentDist as $environmentDistKey => $environmentDistValue) {
            if(!key_exists($environmentDistKey,$this->environment)) {
                $returnArray[$environmentDistKey] = $environmentDistValue;
            }
        }
        return $returnArray;
    }
}