<?php require_once(__DIR__ . "/partials/nav.php"); ?>
<?php
if (!is_logged_in()) {
    flash("You don't have permission to access this page");
    die(header("Location: login.php"));
}
?>
<?php
$query= "Select name, price, quantity, from Products WHERE quantity > 0 limit 25";
$db = getDB();
$stmt= $db->prepare($query);
$results=[];
$r= $stmt->execute();
if ($r) {
    $results=$stmt->fetchALL(PDO::FETCH_ASSOC);
}
?>
<div class="container-fluid">
<div class="h3">Shop</p>
    <?php if (count($results)>0) : ?>
        <div class="card-group">
            <?php foreach($results as $items) :?>
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
                <?php endforeach; ?>
            </div>
        </div>
    <?php else : ?>
        <p>Sorry, everything is sold out. </p>
    <?php endif; ?>
    </div?                
