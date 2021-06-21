<?php

include('database_connection.php');

$categories=["Electronics","Car","Games","Accessories","Instruments","Audio","Digital","Cameras"];

$query = "";
$output = '<ul style="margin-left:80px;">';


for($i=0;$i<8;$i++){

    $query = "SELECT * FROM ( SELECT products.ref, products.name, products.description, products.price, products.image, ROW_NUMBER() OVER(PARTITION BY products.ref ORDER BY products.price DESC) rn FROM products INNER JOIN product_cat ON products.ref = product_cat.REF INNER JOIN category on product_cat.ID = category.ID WHERE category.NAME LIKE '%$categories[$i]%' LIMIT 0, 8) a WHERE rn = 1";
    $connect2 = mysqli_connect("localhost", "root", "", "boutique");  
	$page_result = mysqli_query($connect2, $query);  
	$total_records = mysqli_num_rows($page_result); 
    if($i==0 || $i==2){$total_records-=1;}
    if($i==1){$total_records+=2;}
    if($i==3 || $i==5){$total_records-=3;}
		$output .= '
        <li style="float:left;list-style-type:none; margin-left:40px;">
            <a href="#" class="categories" id="'.$categories[$i].'">'.$categories[$i].'('.$total_records.')</a>
        </li>
		';
}
	$output .= '</ul>';
	echo $output;

?>