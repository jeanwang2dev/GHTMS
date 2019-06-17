<?php

  //** Load env.php file */
  $path = dirname(__FILE__) .'/env.php'; //include_once dirname(__FILE__) .'/env.php';

  if(realpath($path)){
    require($path);
  }else{
    echo "error^^^env file not exist!";
  }

  if(!function_exists('env')) {
      function env($key, $default = null)
      {
          $value = getenv($key);
          if ($value === false) {
              return $default;
          }
          return $value;
      }
  }

  /** Load Validation.php file */
  $path =  dirname(__FILE__) .'/Validation.php';

  if(realpath($path)){
      require($path);
  }else{
      echo "error^^^Validation file not exist!";
  }

?>