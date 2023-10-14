<?php

namespace App;

use mysqli;

function openConnection()
{
  return new mysqli("db", "root", "admin", "mydb", "3306");
}
