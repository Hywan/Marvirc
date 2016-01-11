<?php

namespace Marvirc\Action;

interface IAction
{
    public static function getPattern();

    public static function getUsage();

    public static function compute(array $data);
}
