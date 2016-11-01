<?php

if ( YII_ENV  == 'dev')
{
    if(file_exists(__DIR__ . '/dev/weike.php'))
    {
        return include(__DIR__ . '/dev/weike.php');
    }
    else
    {
        throw new Exception('The configuration file ' . __DIR__ . '/dev/weike.php  does not exist.');
    }

}
else if (YII_ENV=='test')
{
    if(file_exists(__DIR__ . '/test/weike.php'))
    {
        return include(__DIR__ . '/test/weike.php');
    }
    else
    {
        throw new Exception('The configuration file ' . __DIR__ . '/test/weike.php  does not exist.');
    }
}
else
{
    if(file_exists(__DIR__ . '/prod/weike.php'))
    {
        return include(__DIR__ . '/prod/weike.php');
    }
    else
    {
        throw new Exception('The configuration file ' . __DIR__ . '/prod/weike.php  does not exist.');
    }
}
