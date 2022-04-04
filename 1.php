<?php
/*
1. Алгоритм
Вставить $a в индексный (простой) массив целых чисел после всех элементов, в которых есть цифра 2.
Новый массив создавать нельзя. Использовать функцию array_splice нельзя.
*/

function pushAfter2(&$inputArray,$inputValue)
{
	for($ind=count($inputArray)-1;$ind>=0;$ind--){
		$buf=$inputArray[$ind];
		$endFlag=0;
		while($buf>0){
			if($buf%10==2)
				$endFlag=1;
			$buf/=10;
		}
		if($endFlag==1)
		break;
		$inputArray[$ind+1]=$inputArray[$ind];
	}
	$inputArray[$ind+1]=$inputValue;
}


$arr =array(1,32,34,3,12,45,5,2,12,65,7,4);
var_dump($arr);
$a=777;
pushAfter2($arr,$a);
echo "<br>";
var_dump($arr);
?>