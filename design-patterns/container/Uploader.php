<?php

interface Uploader
{
    public function validate($data);
    
    public function upload($data, $destination);
}

abstract class Uploader_Basic implements Uploader
{
    protected $containData  = false;
    protected $dataValidity = false;

    protected $data    = null;
    protected $errors  = null;

    protected $success = 0;
    protected $destination = '';

    public function validate($data)
    {
        if ($data !== null && empty($data)) {
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
        return $this->errors;
    }

    public function getSuccess()
    {
        return $this->success;
    }

    public abstract function upload($data, $destination);
}

class Uploader_File extends Uploader_Basic implements Uploader
{
    public function validate($data)
    {
        parent::validate($data);

        if (empty($this->errors)) {
            //do validate here;
            
            $this->dataValidity = true;
        }

        return $this;
    }

    public function upload($data, $destination)
    {
        if ($this->dataValidity) {
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

$file = '';

$upl = new Uploader_File();

var_dump($upl->getSuccess());
