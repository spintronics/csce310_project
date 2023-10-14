<?php
function load($relativePath)
{
  $file = $_SERVER['DOCUMENT_ROOT'] . '/' . $relativePath . '.php';

  if (file_exists($file)) {
    require_once $file;
  }
}

load('util');
load('models/user');
