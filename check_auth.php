<?php
/**
 * Created by PhpStorm.
 * User: Лука
 * Date: 19.05.2020
 * Time: 11:32
 */
if ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_COOKIE['user']))
{
    $cookie = md5('adminqHq54gQ5M;[qwertyu]8hB5F{Q1JxTraaj5BAEJxHYmmfSGH9DWC7byu4FpTs3RevZ3jk-4QR(gt-12}');
    $saved = $_COOKIE['user'];
    if ($cookie != $saved)
    {
        header("HTTP/1.1 301 Moved Permanently");
        header('location: login.php');
    }
    else
    {
    }
}
else
{
    header("HTTP/1.1 301 Moved Permanently");
    header('location: login.php');
}