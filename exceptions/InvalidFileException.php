<?php
/**
 * Created by PhpStorm.
 * User: sai
 * Date: 4/2/18
 * Time: 1:53 PM
 * @author Saiat Kalbiev <kalbievich11@gmail.com>
 */

namespace apollo11\envAnalizer\exceptions;


class InvalidFileException extends \Exception
{
    public function __construct($message, $code = 0, Exception $previous = null) {
        parent::__construct($message, $code, $previous);
    }

    public function __toString() {
        return __CLASS__ . ": [{$this->code}]: {$this->message}\n";
    }

}