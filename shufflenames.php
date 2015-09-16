<?php
# see http://www.patrickpatoray.com/index.php?Page=112 to get php in Automator

/* a function to change "Lastname, Firstname" to Firstname Lastname */
function reorderName($string) {
	// if index of ",\s" then split on ",\s"
	if ($split_string = preg_split('/,\s*/', trim($string, "\""), 2)){
		// var_dump($split_string);
		$temp = $split_string[0];
		$split_string[0] = $split_string[1];
		$split_string[1] = $temp;
		return implode(" ", $split_string);
	} else { // otherwise there is no need to reorder
		return false;
	}
}

/* a function to loop through a double array and find names */
function walk_over($db_array){
	$new_array = array();
	// count 1st level and loop through
	for ($i=0; $i < count($db_array); $i++) { 
		// count 2nd level and loop through
		for ($x=0; $x < count($db_array[$i]); $x++) { 
			// find pattern "Lastname, Firstname"
			if (preg_match('/"?[A-Za-z]+,\s[A-Za-z]+"?/', $db_array[$i][$x])){
				// if found, add that item to a new array & get out of loop
				$new_array[] = reorderName($db_array[$i][$x]); // append to array
				break; // next iteration of first loop level
			}
		}
	}		
	return $new_array;
}

/* main program */
if (count($argv)==1) { // if there isn't an extra argument supplied
	echo "add a csv file name\n"; 
	return false; 
} elseif (count($argv)>2) { // if there are too many arguments
	echo "use only one csv file name\n"; 
	return false; 
} else {
	$filename = $argv[1].".csv"; # get text from the argument list (a csv file)
	if(file_exists($filename)) { // test that file exists
		/* from array of strings: ""Student","ID","Phone","E-mail Address","Audit","Class","Status","Credits","CEUs""
			to double array: 
			[0]=>
			string(7) "Student"
			[1]=>
			string(2) "ID" ...  
		*/
		$csv = array_map('str_getcsv', file($filename));
		$names = walk_over($csv); // create an array of names from the csv file
		shuffle($names); #randomly shuffle the names
		echo implode(", ", $names)."\n"; // print names
	} else { // no file exists
		echo "no such file\n";
		return false;
	}	
}
?>
