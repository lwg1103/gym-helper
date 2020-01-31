<?php

namespace App\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use App\Model\ExcerciseInstanceResult;

class ExcerciseResultFilter extends AbstractExtension
{
    public function getFilters()
    {
        return [
            new TwigFilter('excercise_result', [$this, 'resultToString']),
        ];
    }

    public function resultToString($result)
    {
        return ExcerciseInstanceResult::toString($result);
    }
}
