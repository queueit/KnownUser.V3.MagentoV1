<?php

class Queueit_Knownuser_Model_Source_Config_Method
{
    const PUSH = 0;
    const PULL = 1;
    const MANUAL = 2;

    public function toOptionArray()
    {
        $options = [
            self::PUSH => 'push',
            self::PULL => 'pull',
            self::MANUAL => 'manual'
        ];

        return $options;
    }
}
