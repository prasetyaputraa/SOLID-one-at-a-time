<?php

namespace Pra\Uploader;

interface Data
{
    public function __construct($data);
}

abstract class Data_Basic implements Data
{
    protected $data;
    protected $created;
    protected $modified;
    protected $mutable = true;

    public function __construct($data)
    {
        $this->data    = $data;
        $this->created = time();
    }

    public function created()
    {
        return $this->created;
    }

    public function modified()
    {
        return $this->modified;
    }
}

class DataMutable extends Data_Basic implements Data
{
    protected $modified = null;

    public function __construct($data)
    {
        parent::__construct($data);
    }

    /*
     * Modify contained data
     *
     * @param boolean $stricType if set true, type checking is done after callback
     *
     * @return Object $this
     */
    public function modify($strictType, $callback = null)
    {
        if ($callback !== null) {
            $data = $callback($data);
        }

        if ($stricType === true) {
            if (gettype($data) !== gettype($this->data)) {
                $this->errors[] = '[Type Error] Strict type turned on, modified data must be the same type. Rolling back data: No modification made';

                return $this;
            }
        }

        $this->data = $data;

        $this->modified = time();

        return $this;
    }
}

class DataImmutable extends Data_Basic implements Data
{
    protected $mutable = false;

    public function __construct($data)
    {
        parent::__construct($data);

        // TODO check whether data is instanceof DataImmutable or DataMutable
        //      if true true then extract the data from the object
        if ($data === DataImmutable::class || $data === DataMutable::class) {
            $data = $data->get();
        }
    }
}
