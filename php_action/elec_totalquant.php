<?php 
  require '../includes/header.php';
?>
<nav aria-label="breadcrumb">
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="dashboard.php">Home</a></li>
    <li class="breadcrumb-item"><a href="electronics.php">Electronics</a></li>
    <li class="breadcrumb-item active" aria-current="page">Total Ordered Quantities</li>
  </ol>
</nav>

<div class="card">
    <div class="card-header">Total Ordered Quantities</div>
    <div class="card-body">
      <div class="remove-messages"></div>

      <div class="div-action" style="padding-bottom: 20px">
        <a class="btn btn-primary" style="color: white" href="electronics.php">Go Back</a>
      </div>
<?php 
      $getpending="SELECT o.pid,e.pname,SUM(o.ordered_quantity) FROM orders_elec o inner join electronics e on o.pid=e.pid group by o.pid,e.pname";
      $result=$connect->query($getpending);      
      print("<div class='row'>
        <div class='col-sm-12'>
        <table class='table table-striped table-bordered'>
          <thead>
            <tr>
              <th>ProductID</th>
              <th>ProductName</th>
              <th>Total Ordered Quantity</th>                          
            </tr>
          </thead>
          <tbody>");
      while ($row=$result->fetch_assoc()) {
        $a=$row['pid'];
        $b=$row['pname'];
        $c=$row['SUM(o.ordered_quantity)'];               
        print("<tr>
              <td>".$a."</td>
              <td>".$b."</td>
              <td>".$c."</td>");                            
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