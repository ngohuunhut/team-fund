<?php

namespace Nhn\Demo\Actions;

class Money
{
    public static function format($currency)
    {
        // Remove non-numeric characters except for the comma
        $number = preg_replace('/[^\d,]/', '', $currency);
        // Remove the comma
        $number = str_replace(',', '', $number);

        return $number;
    }
}
