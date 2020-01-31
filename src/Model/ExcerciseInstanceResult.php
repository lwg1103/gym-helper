<?php

namespace App\Model;

class ExcerciseInstanceResult
{
    const Todo    = 0;
    const Done    = 1;
    const TooEasy = 2;
    const Ok      = 3;
    const TooHard = 4;

    public static function toString($result)
    {
        switch ($result)
        {
            case self::Todo:
                return "TODO";
            case self::Done:
                return "Done";
            case self::TooEasy:
                return "Easy";
            case self::Ok:
                return "Ok";
            case self::Todo:
                return "Hard";
        }
    }

}
