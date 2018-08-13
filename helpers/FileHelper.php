<?php
/**
 * Created by PhpStorm.
 * User: sai
 * Date: 4/5/18
 * Time: 1:23 PM
 * @author Saiat Kalbiev <kalbievich11@gmail.com>
 */

namespace apollo11\envAnalyzer\helpers;


use apollo11\envAnalyzer\exceptions\FileNotFoundException;
use apollo11\envAnalyzer\exceptions\InvalidFileException;

class FileHelper
{

    /**
     * @param $filePath
     * @author Saiat Kalbiev <kalbievich11@gmail.com>
     * @throws FileNotFoundException
     * @throws InvalidFileException
     */
    public static function checkFileValidity($filePath)
    {
        if (!file_exists($filePath)) {
            throw new FileNotFoundException("File with path: {$filePath} was not found");
        }

        if (!is_readable($filePath) || !is_file($filePath)) {
            throw new InvalidFileException("File with path {$filePath} was either not readable or is a directory");
        }
    }

    public static function isValidConfigFile($filePath)
    {
        if (!is_writable($filePath) || !is_file($filePath)) {
            throw new InvalidFileException("File with path {$filePath} is not writable or is a directory");
        }
    }

    public static function isValidSampleConfigFile($filePath)
    {
        if (!file_exists($filePath)) {
            throw new FileNotFoundException("File with path: {$filePath} does not exist");
        }

        if (!is_readable($filePath) || !is_file($filePath)) {
            throw new InvalidFileException("File with path {$filePath} was either not readable or is a directory");
        }
    }

}