<?php require_once(__DIR__ . "/partials/nav.php"); ?>
<?php
if (!is_logged_in()) {
    flash("You don't have permission to access this page");
    die(header("Location: login.php"));
}
?>
<?php 
$db=getDB();
$results=[];
$query="SELECT c.id, name, p.id as product_id, p.price, c.quantity, (p.price * c.quantity) as sub, (c.price-p.price) as diff. p.name FROM Cart c JOIN Products p on c.product_id = p.id WHERE c.user = :uid ";    $stmt= $db->prepare($query);
$r= $stmr->execute([
        "uid:"=>get_user_id()
    ]);
if($r){
        $result = $stmt ->fetchAll(PDO::FETCH_ASSOC);
    }
?>
<?php 
    if(isset($_POST["purchase"])){
        $total=0;
        foreach($results as $item){
            $total += (int)safe_get($item, "sub", "0");
        }
        $balance=get_point_balace();

        if($total> $balance){
            flash(" You can't afford this");
        }
        else{
            flash("You can afford this");
            $query= "SELECT IFNULL(MAX(order_id),1) as oid FROM Orders";
            $stmt=$db->prepare($query);
            $r=$stmt->execute();
            if($r){
                $order=$stmt->fetch(PDO::FETCH_ASSOC);
                if($order && isset($order["oid"])){
                    $order_id= (int)$order["oid"];
                    $order_id++;

                     $query= "INSERT INTO Orders (product_id, quantity, user_id, price) SELECT product_id, quantity, user_id, price, :oid FROM Cart WHERE Cart.user_id=:uid";
                     $stmt=$db->prepare($query);
                     $r= $stmt->execute([":uid"=>get_user_id(), ":iod"=>$order_id]);
                     if($r){
                        $query= "UPDATE Products set quantity = quantity - :q WHERE id = :pid";
                        $stmt= $db->prepare($query);
                         foreach ($results as $item){
                             $stmt->execute([":pid"=>(int)safe_get($item, "product_id", -1)]);
                         }
                         changePoints(get_user_id(), $total, "Purchase Order ID: $order_id");
                         $query= "DELETE FROM Cart WHERE user_id=:uid";
                         $stmt=$db->prepare($query);
                         $r= $stmt->execute([":uid"->get_user_id()]);
                         if($r){
                             flash( "Your order has been processed, Thanks for shopping with us");
                             die(header("Location: shop.php"));                        
                     }
                     else{
                         flash("Error placing order:". var_export($stmt->errorInfo(), true));
                     }
                }
                else{
                    flash("Error deleting order:". var_export($stmt->errorInfo(), true));
                }
            }
            else {
                flash( "Error getting max". var_export($stmr->errorInfo));
            }
        }
    }
}    
?>
<div class="contatiner">
<div class="h3">Cart</div>
<?php if(count($results)>0):?>
    <ul class= "list-group">
        <div class="row fw-bold">
                    <div class="col">Name</div>
                    <div class="col">Quantity</div>
                    <div class="col">Price></div>
                    <div class="col">Subtotal></div>
                    <div class="col">Difference></div>
                </div>
        <?php $total=0 ;?>       
        <?php foreach($results as $item):?>
        <?php $total += (int)safe_get($item, "sub", "o");?>
            <li class= "list-group-item">
                <div class="row">
                    <div class="col"><?php safer_echo(safe_get(item,"name","N/a"));?></div>
                    <div class="col"><?php safer_echo(safe_get(item,"quantity","?"));?></div>
                    <div class="col"><?php safer_echo(safe_get(item,"price","0.00"));?></div>
                    <div class="col"><?php safer_echo(safe_get(item,"sub","0.00"));?></div>
                    <div class="col"><?php safer_echo(safe_get(item,"diff","0.00"));?></div>
                    <div class="col-1"><button onclick="deleteCartitem(<?php safe_get($item, 'id', -1);?>) class="btn btn-danger"></div>
                </div>
            </li>
        <?php endforeach; ?>
        <div class="row fw-bold text-end">
                    <div class="col-12">Total:<?php safer_echo($total)?></div>
        </div>   
    </ul>
    <div class="row">
        <div class ="col">
            <form method= "post">
                <input type="submit" name="purchase" class="btn btn-success" value="purchase" /> 
            </form>
    </div>                       
<?php else:?>
    <p> No items in your cart </p>
<?php endif?>
</div>

<script>
function deleteCartitem(id){
    if(id){
        $.post("api/remove_item_from_cart_.php", {cart_id :id}, (data, status)=>{
            data= JSON.parse(data);
            if(data.status === 200){
                window.location.reload();
            }
        });
    }
}
