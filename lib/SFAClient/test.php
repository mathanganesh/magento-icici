<?php

/* load extension and check it */
function check_extension() {
  if(!extension_loaded('java')) {
    $sapi_type = php_sapi_name();
    if ($sapi_type == "cgi" || $sapi_type == "cgi-fcgi" || $sapi_type == "cli") {
      if(!(PHP_SHLIB_SUFFIX=="so" && @dl('java.so'))&&!(PHP_SHLIB_SUFFIX=="dll" && @dl('php_java.dll'))&&!(include_once("java/Java.php"))) {
	echo "java extension not installed.";
	exit(2);
      }
    } else {
    	echo "java extension not installed.";
    }
  }
}

check_extension();
if(1){

  phpinfo();
  print "\n\n";
  
  $v = new Java("java.lang.System");
  $p = @$v->getProperties();
  if($ex=java_last_exception_get()) {
    $trace = new Java("java.io.ByteArrayOutputStream");
    $ex->printStackTrace(new java("java.io.PrintStream", $trace));
    echo "Exception $ex occured:<br>\n" . $trace . "\n";
    exit(1);
  }
  $arr=$p;
  foreach ($arr as $key => $value) {
    print $key . " -> " .  $value . "<br>\n";
  }
  echo "<br>\n";
  echo "<br>\n";
  
  // java.util.Date example
  $formatter = new Java('java.text.SimpleDateFormat',
                        "EEEE, MMMM dd, yyyy 'at' h:mm:ss a zzzz");

  print $formatter->format(new Java('java.util.Date'));  
  echo "<br>\n";
  
  
 } else {
  
  phpinfo();
  print "\n\n";

}
?>
