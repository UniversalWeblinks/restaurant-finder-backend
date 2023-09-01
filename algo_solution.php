<?php 
function stockMerchantSolution($n, $arr) {
    // lets initialize a count variable to count pairs and return the value
    $count = 0;

    //sort the given array
    $sorted_arr = sort($arr);

    //loop through the sorted array s
    for ($i=0; $i <  $n - 1; $i++) { 
        if($sorted_arr[$i] == $sorted_arr[$i+1]){
            //if there is a pair, increment our count variable
            $count++;
            //also increment i to skip the next item
            $i += 1;
        }
    }
    
    //finally return the count value
    return $count;
  }
  
  stockMerchantSolution(9, [10, 20, 20, 10, 10, 30, 50, 10, 20]);

  ?>