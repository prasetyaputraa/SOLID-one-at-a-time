<?php

namespace Pra\Uploader;

use Error as Error;

interface Uploader
{
    public function validate(Pra\Uploader\Data $data);
    
    public function upload(Pra\Uploader\Data $data, $destination);
}

abstract class Uploader_Basic implements Uploader
{
    protected $containData  = false;
    protected $dataValidity = false;

    protected $data   = null;
    protected $errors = null;

    protected $success     = 0;
    protected $destination = '';

    public function __construct()
    {
        $this->errors = new Error();
    }

    public function validate(Pra\Uploader\Data $data)
    {
        if ($data !== null || empty($data) || $data !== '') {
            $this->data = $data;

            $this->containData = true;
        } else {
            $this->errors = array(
                '[Errors] Data Empty: Expecting data with value, empty data or null value given.'
            );
        }
    }

    public function getErrors()
    {
        if ($this->errors) {
            return $this->errors;
        }

        if ($this->data === null) {
            return '[No Errors] No operations had been performed';
        }

        return '[No Errors] No Errors Found';
    }

    public function getSuccess()
    {
        return $this->success;
    }

    public abstract function upload(Pra\Uploader\Data $data, $destination);
}

class Uploader_File extends Uploader_Basic implements Uploader
{
    public function validate(Pra\Uploader\Data $data)
    {
        parent::validate($data);

        if (empty($this->errors)) {
            //do validate here;
            
            $this->dataValidity = true;
        }

        return $this;
    }

    public function upload(Pra\Uploader\Data $data, $destination)
    {
        if (!$this->dataValidity) {
            $this->errors[] = '[Errors] Forbidden Upload: Trying to upload invalid data.';

            return $this;
        }

        if (!empty($destination)) {
            $this->success = 1;
            $this->destination = $destination;

            return $this;
        }
    }
}

$file = 'this is  a file';

$upl = new Uploader_File();

echo '<pre>';
var_dump(get_class($upl));
echo '</pre>';

try{
    $upl->validate($file);
} catch (Exception $e) {
    echo '<pre>';
    var_dump($e->getMessage());
    echo '</pre>';
}

echo '<pre>';
var_dump($upl);
echo '</pre>';
