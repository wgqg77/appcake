<?php

if ( YII_ENV  == 'dev')
{
    if(file_exists(__DIR__ . '/dev/cake.php'))
    {
        return include(__DIR__ . '/dev/cake.php');
    }
    else
    {
        throw new Exception('The configuration file ' . __DIR__ . '/dev/cake.php  does not exist.');
    }

}
else if (YII_ENV=='test')
{
    if(file_exists(__DIR__ . '/test/cake.php'))
    {
        return include(__DIR__ . '/test/cake.php');
    }
    else
    {
        throw new Exception('The configuration file ' . __DIR__ . '/test/cake.php  does not exist.');
    }
}
else
{
    if(file_exists(__DIR__ . '/prod/cake.php'))
    {
        return include(__DIR__ . '/prod/cake.php');
    }
    else
    {
        throw new Exception('The configuration file ' . __DIR__ . '/prod/cake.php  does not exist.');
    }
}
