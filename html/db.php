<?php
/* Database connection settings */
$host = 'localhost';
$user = 'baillar6_project';
$pass = 'projectpass';
$db = 'baillar6_project';
$mysqli = new mysqli($host,$user,$pass,$db) or die($mysqli->error);
