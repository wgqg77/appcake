<?php

if ( YII_ENV  == 'dev')
{
    if(file_exists(__DIR__ . '/dev/ad.php'))
    {
        return include(__DIR__ . '/dev/ad.php');
    }
    else
    {
        throw new Exception('The configuration file ' . __DIR__ . '/dev/ad.php  does not exist.');
    }

}
else if (YII_ENV=='test')
{
    if(file_exists(__DIR__ . '/test/ad.php'))
    {
        return include(__DIR__ . '/test/ad.php');
    }
    else
    {
        throw new Exception('The configuration file ' . __DIR__ . '/test/ad.php  does not exist.');
    }
}
else
{
    if(file_exists(__DIR__ . '/prod/ad.php'))
    {
        return include(__DIR__ . '/prod/ad.php');
    }
    else
    {
        throw new Exception('The configuration file ' . __DIR__ . '/prod/ad.php  does not exist.');
    }
}
