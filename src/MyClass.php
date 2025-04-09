<?php

namespace App;

class MyClass
{
    public int $someField;

    public function __construct()
    {
        for ($i = 1; $i < 1000000; $i += 1)
        {
            $arr[$i] = 21312312312;
        }

        $this->someField = rand(0, 1000);
    }

}