<?php 
  require '../includes/header.php';
?>
<nav aria-label="breadcrumb">
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="dashboard.php">Home</a></li>
    <li class="breadcrumb-item active" aria-current="page">Restock Products</li>
  </ol>
</nav>

<div class="card">
    <div class="card-header">List of Products to be Restocked</div>
    <div class="card-body">
<?php 
      $putRestockSql="CALL `getRestockBooks`()";
      $result=$connect->query($putRestockSql);
      $putRestockSql="CALL `getRestockElectronics`()";
      $result=$connect->query($putRestockSql);
      $getRestockSql="Select distinct id,name from restockproducts";
      $result=$connect->query($getRestockSql);
      print("<div class='row'>
        <div class='col-sm-12'>
        <table class='table table-striped table-bordered'>
          <thead>
            <tr>
              <th>ProductID</th>
              <th>ProductName</th>                                        
            </tr>
          </thead>
          <tbody>");
      while ($row=$result->fetch_assoc()) {
        $a=$row['id'];
        $b=$row['name'];    
        print("<tr>
              <td>".$a."</td>
              <td>".$b."</td>");                            
        }
      print("</tbody>
        </table>
        </div>
      </div>");
 ?>
      
          
    </div>
</div>
<?php 
  require '../includes/footer.php';
?>