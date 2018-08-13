<?php
/**
 * Created by PhpStorm.
 * User: sai
 * Date: 4/5/18
 * Time: 1:08 PM
 * @author Saiat Kalbiev <kalbievich11@gmail.com>
 */

namespace apollo11\envAnalyzer;


use apollo11\envAnalyzer\helpers\ArrayHelper;
use apollo11\envAnalyzer\helpers\ConsoleHelper;
use apollo11\envAnalyzer\helpers\FileHelper;

class Php
{
    private $phpFilePath;
    private $phpDistFilePath;
    private $phpArray;
    private $phpDistArray;

    /**
     * Php constructor.
     * @param $phpFilePath
     * @param $phpDistFilePath
     * @throws exceptions\FileNotFoundException
     * @throws exceptions\InvalidFileException
     */
    public function __construct($phpFilePath, $phpDistFilePath)
    {
        $this->setPhpFilePath($phpFilePath);
        $this->setPhpDistFilePath($phpDistFilePath);
    }

    /**
     * @return mixed
     */
    public function getPhpArray()
    {
        return $this->phpArray;
    }

    /**
     * @return mixed
     */
    public function getPhpDistArray()
    {
        return $this->phpDistArray;
    }

    /**
     * @param $phpDistFilePath
     * @author Saiat Kalbiev <kalbievich11@gmail.com>
     * @throws exceptions\FileNotFoundException
     * @throws exceptions\InvalidFileException
     */
    public function setPhpDistFilePath($phpDistFilePath)
    {
//        FileHelper::checkFileValidity($phpDistFilePath);
        $this->phpDistFilePath = $phpDistFilePath;
    }

    /**
     * @return mixed
     */
    public function getPhpDistFilePath()
    {
        return $this->phpDistFilePath;
    }

    /**
     * @param $phpFilePath
     * @author Saiat Kalbiev <kalbievich11@gmail.com>
     * @throws exceptions\FileNotFoundException
     * @throws exceptions\InvalidFileException
     */
    public function setPhpFilePath($phpFilePath)
    {
//        FileHelper::checkFileValidity($phpFilePath);
        $this->phpFilePath = $phpFilePath;
    }

    /**
     * @return mixed
     */
    public function getPhpFilePath()
    {
        return $this->phpFilePath;
    }

    /**
     * @author Saiat Kalbiev <kalbievich11@gmail.com>
     */
    private function setPhpArray()
    {
        $array = require $this->phpFilePath;
        if (!is_array($array)) {
            throw new \InvalidArgumentException("Array should be provided. Got " . gettype($array) . " instead");
        }
        $this->phpArray = $array;
    }

    /**
     * @author Saiat Kalbiev <kalbievich11@gmail.com>
     */
    private function setPhpDistArray()
    {
        $array = require $this->phpDistFilePath;
        if (!is_array($array)) {
            throw new \InvalidArgumentException("Array should be provided. Got " . gettype($array) . " instead");
        }
        $this->phpDistArray = $array;
    }

    /**
     * @author Saiat Kalbiev <kalbievich11@gmail.com>
     */
    public function checkMissingVariables()
    {
        $this->setPhpArray();
        $this->setPhpDistArray();
        $consoleHelper = new ConsoleHelper();

        if (empty($this->phpDistArray)) {
            echo $consoleHelper->getColoredString("Warning: Empty dist array was provided",null,ConsoleHelper::BACKGROUND_YELLOW) . "\n";
            return;
        }

        $difference = ArrayHelper::getMissingValues($this->phpDistArray, $this->phpArray);

        if (count($difference) == 0) {
            echo $consoleHelper->getColoredString("Warning: No missing variables were found",null,ConsoleHelper::BACKGROUND_YELLOW) . "\n";
            return;
        }

        $count = 1;
        foreach ($difference as $key => $value) {
            $tmpKey = $consoleHelper->getColoredString($key,ConsoleHelper::FOREGROUND_GREEN,null);
            echo "{$count}) Insert the value for {$tmpKey}({$value}): ";
            $newValue = readline();
            $difference[$key] = $newValue;
            $count++;
        }

        $ourFileHandle = fopen($this->phpFilePath, 'w');
        fwrite($ourFileHandle, $this->getPhpArrayAsString($difference));
        fclose($ourFileHandle);

        echo $consoleHelper->getColoredString("File has been updated successfully!",null,ConsoleHelper::BACKGROUND_GREEN) ."\n";
    }

    /**
     * @param $difference string
     * @return string
     * @author Saiat Kalbiev <kalbievich11@gmail.com>
     */
    private function getPhpArrayAsString($difference)
    {
        $toBeInsertedArray = array_merge($this->phpArray, $difference);

        $text = "<?php return [ ";
        foreach($toBeInsertedArray as $key => $value){
            if(is_numeric($value)){
                $text .= '"' . $key . '" => ' . '' . $value . ',';
            } else {
                $text .= '"' . $key . '" => ' . '"' . $value . '",';
            }
        }
        $text .= " ]; ?>";
        return $text;
    }
}