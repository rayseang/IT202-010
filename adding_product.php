<?php require_once(__DIR__ . "/partials/nav.php"); ?>
<?php
if (!has_role("Admin)) {
    //this will redirect to login and kill the rest of this script (prevent it from executing)
    flash("You don't have permission to access this page");
    die(header("Location: login.php"));
}
?>

<?php
        if(isset($_POST["name"])){
                $name=safe_get($_POST,"name","");
                $quantity=(int)safe_get($_POST, "quantity", 0);
                $price= (int)safe_get($_POST, "price", 0);

                if(!empty($name) &&$quantity>0&& $price>0){
                        $db=getDB();
                        $query="INSERT INTO Products (name, quantity, price, user_id) Values (:n,:q, :p, :u)";
                        $stmt= $db->prepare($query);
                        $r= $stmt->execute([
                                ":n"=>$name,
                                ":q"=>$quantity,
                                ":p"=>$price,
                                ":u"=>get_user_id()
                        ]);
                        if($r){
                                flash("Added item to Products Table");
                        }
                        else{
                                flash("Error adding item to Products Table: ". var_export($stmt->errorInfo(), true));
                        }
                }
        }
?>

<div class="container-fluid">
        <div class="h3">Add Product</div>
        <form method="POST">
                <div>
                        <label for="name" class="form-label">Product Name</label>
                        <input type="text" name="name" id="name" class="form-control" required/>
                </div>
                <div>
                        <label for="q" class="form-label">Quantity</label>
                        <input type="number" name="quantity" id="q" class="form-control" required/>
                </div>
                <div>
                        <label for="p" class="form-label">Price</label>
                        <textarea name="price" id="p" class="form-control" required></textarea>
                </div>

                <div>
                        <input type="submit" class="btn btn-success btn" value="Add Product"/>
                </div>
        </form>
</div>


<?php require_once(__DIR__ . "/../lib/helpers.php");



