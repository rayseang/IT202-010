<?php require_once(__DIR__ . "/partials/nav.php"); ?>
<?php
if (!is_logged_in()) {
    flash("You don't have permission to access this page");
    die(header("Location: login.php"));
}
?>
<?php
$query= "Select id,name, price, quantity from Products WHERE quantity > 0 limit 25";
$db = getDB();
$stmt= $db->prepare($query);
$results=[];
$r= $stmt->execute();
echo var_export($stmt->errorInfo(), true);
if ($r) {
    $results=$stmt->fetchAll(PDO::FETCH_ASSOC);
}
?>
<script>
function addToCart(itemId){
    console.log("adding, itemId");
    $.post("api/add_to_cart.php",{itemId: itemId},(data, status)=>{
        console.log("response",data,status);
    });

}
function getCart(){
    $.get("api/get_cart.php", (data, status)=>{
        console.log("response", data, status);
        let $cartContainer= $("#cart");
        $cartContainer.html("");
        let cart = JSON.parse(data).cart;
        cat.forEach(item=>{
            let $item = $("<div></div>").text(item.name+"="+item.price + " " + item.quantity+ "=" +item.sub)
            $cartContainer.append($item)
        })

    })
}
$(document).ready(()=>{
    getCart();
});
</script>
<div class="container-fluid">
<div class="h3">Shop</p>
    <?php if (count($results)>0) : ?>
        <div class="card-group">
            <?php foreach($results as $item) :?>
                <div class="card" style="max-width:20m">
                    <div class="card-body">
                        <h5 class="card-title"><?php safer_echo($item["name"]);?></h5>
                        <div class="card-text">
                            <div class="row">
                                <div class="col">
                                    <?php safer_echo($item["price"]);?>
                                </div>
                                <div class="col">
                                Stock: <?php safer_echo($item["quantity"]);?></div>
                            </div>
                        </div>
                    </div>
                        <div class="card-footer">
                            <button onclick="addToCart"<?php safer_echo($item['id']);?>"">Add to Cart</button>
                    </div>
                </div>
            </div>
         </div>               
                <?php endforeach; ?>
            </div>
        </div>
    </div> 
    <?php else : ?>
        <p> Sorry, everything is sold out.</p>
    <?php endif;?>
</div>    
