<?php 
  require '../includes/header.php';
?>
<nav aria-label="breadcrumb">
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="dashboard.php">Home</a></li>
    <li class="breadcrumb-item"><a href="student.php">Students</a></li>
    <li class="breadcrumb-item active" aria-current="page">Pending Orders</li>
  </ol>
</nav>

<div class="card">
    <div class="card-header">Pending Orders</div>
    <div class="card-body">
      <div class="remove-messages"></div>

      <div class="div-action" style="padding-bottom: 20px">
        <a class="btn btn-primary" style="color: white" href="student.php">Go Back</a>
      </div>
<?php 
      $getpending="SELECT s.sid,s.sname,s.sbranch,s.ssem FROM student s 
      INNER JOIN orders_books o ON s.sid=o.sid WHERE o.payment_status=0";
      $result=$connect->query($getpending);      
      print("<div class='row'>
        <div class='col-sm-12'>
        <table class='table table-striped table-bordered'>
          <thead>
            <tr>
              <th>StudentID</th>
              <th>Name</th>
              <th>Branch</th>
              <th>Sem</th>              
            </tr>
          </thead>
          <tbody>");
      while ($row=$result->fetch_assoc()) {
        $a=$row['sid'];
        $b=$row['sname'];
        $c=$row['sbranch'];
        $d=$row['ssem'];       
        print("<tr>
              <td>".$a."</td>
              <td>".$b."</td>
              <td>".$c."</td>
              <td>".$d."</td>");                            
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