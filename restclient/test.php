<?php
$str = "555\ntest     \n\n";
$pattern = '/[\n, ]/';
print_r(preg_split($pattern, $str));
#foreach ($containers as $value) {
# 	 // loop through values 
# 	 print($value);
# 	 echo "<br>";
# 	 
#} 