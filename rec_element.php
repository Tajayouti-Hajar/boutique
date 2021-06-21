<?php



	// Connect database 
	$connect = mysqli_connect("localhost", "root", "", "boutique");

	$limit = 8;

	if (isset($_POST['page_no'])) {
	    $page_no = $_POST['page_no'];
	}else{
	    $page_no = 1;
	}

	$cat_set=false;
	$category='';
	if(isset($_POST["cat"]))  
	{  
	 $category = $_POST["cat"];
	 $cat_set=true;

	} 

	$offset = ($page_no-1) * $limit;

	$query = "";
if($cat_set){
	$query="SELECT * FROM ( SELECT products.ref, products.name, products.description, products.price, products.image, ROW_NUMBER() OVER(PARTITION BY products.ref ORDER BY products.price DESC) rn FROM products INNER JOIN product_cat ON products.ref = product_cat.REF INNER JOIN category on product_cat.ID = category.ID WHERE category.NAME LIKE '%$category%' ORDER BY price DESC LIMIT $offset, $limit) a WHERE rn = 1 ";
	$page_query = "SELECT * FROM ( SELECT products.ref, products.name, products.description, products.price, products.image, ROW_NUMBER() OVER(PARTITION BY products.ref ORDER BY products.price DESC) rn FROM products INNER JOIN product_cat ON products.ref = product_cat.REF INNER JOIN category on product_cat.ID = category.ID WHERE category.NAME LIKE '%$category%' LIMIT $offset, $limit) a WHERE rn = 1";
}else{
	$query = "SELECT * FROM products ORDER BY price DESC LIMIT $offset, $limit";
	$page_query = "SELECT * FROM products ORDER BY price DESC"; 
}

	$result = mysqli_query($connect, $query);

	$output = "";

	if (mysqli_num_rows($result) > 0) {

	while ($row = mysqli_fetch_assoc($result)) {

		$output .= '
		<div class="col-md-3" style="margin-top:12px;">  
            <div style="border:1px solid #333; background-color:#f1f1f1; border-radius:5px; padding:16px; height:350px; position:relative;" align="center">
            	<img src="'.$row["image"].'" class="img-responsive" style="width: 100px; height: 100px; object-fit: cover;" /><br />
            	<h4 class="text-info">'.$row["name"].'</h4>
            	<h4 class="text-danger">$ '.$row["price"] .'</h4>
            	<input type="text" name="quantity" id="quantity' . $row["ref"] .'" class="form-control" value="1"" />
            	<input type="hidden" name="hidden_name" id="name'.$row["ref"].'" value="'.$row["name"].'" />
            	<input type="hidden" name="hidden_price" id="price'.$row["ref"].'" value="'.$row["price"].'" />
            	<input type="button" name="add_to_cart" id="'.$row["ref"].'" style=" background:purple;margin-top:5px; position:absolute; right: 0; bottom:0;" class="btn btn-success form-control add_to_cart" value="Add to Cart" />
            </div>
        </div>
		';
	} 


	$sql = "SELECT * FROM products";

	$records = mysqli_query($connect, $sql);

	$totalRecords = mysqli_num_rows($records);

	$totalPage = ceil($totalRecords/$limit);

	$output.="<ul class='pagination justify-content-center' style='margin:40px 220px;' ><li class='page-item'><a class='page-link' href=''>previous</a></li>";

	for ($i=1; $i <= $totalPage ; $i++) { 
	   if ($i == $page_no) {
		$active = "active";
	   }else{
		$active = "";
	   }

	    $output.="<li class='page-item $active'><a class='page-link' id='$i' href=''>$i</a></li>";
	}

	$output .= "<li class='page-item'><a class='page-link'  href=''>next</a></ul>";

	echo $output;

	}

?>