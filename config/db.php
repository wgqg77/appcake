<?php

if ( YII_ENV  == 'dev')
{
    if(file_exists(__DIR__ . '/dev/db.php'))
    {
        return include(__DIR__ . '/dev/db.php');
    }
    else
    {
        throw new Exception('The configuration file ' . __DIR__ . '/dev/db.php  does not exist.');
    }

}
else if (YII_ENV=='test')
{
    if(file_exists(__DIR__ . '/test/db.php'))
    {
        return include(__DIR__ . '/test/db.php');
    }
    else
    {
        throw new Exception('The configuration file ' . __DIR__ . '/test/db.php  does not exist.');
    }
}
else
{
    if(file_exists(__DIR__ . '/prod/db.php'))
    {
        return include(__DIR__ . '/prod/db.php');
    }
    else
    {
        throw new Exception('The configuration file ' . __DIR__ . '/prod/db.php  does not exist.');
    }
}
