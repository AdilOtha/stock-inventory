<?php 
  require '../includes/header.php';
?>
<nav aria-label="breadcrumb">
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="../index2.php">Home</a></li>
    <li class="breadcrumb-item"><a href="student.php">Books</a></li>
    <li class="breadcrumb-item active" aria-current="page">Total Ordered Quatities</li>
  </ol>
</nav>

<div class="card">
    <div class="card-header">Total Ordered Quantities</div>
    <div class="card-body">
      <div class="remove-messages"></div>

      <div class="div-action" style="padding-bottom: 20px">
        <a class="btn btn-primary" style="color: white" href="student.php">Go Back</a>
      </div>
<?php 
      $getpending="SELECT o.bid,e.b_name,SUM(o.ordered_quantity) FROM orders_books o 
      inner join books e on o.bid=e.bid group by o.bid,e.b_name";
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
        $a=$row['bid'];
        $b=$row['b_name'];
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