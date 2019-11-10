<?php 
  require '../includes/header.php';
?>
<nav aria-label="breadcrumb">
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="dashboard.php">Home</a></li>
    <li class="breadcrumb-item"><a href="notes.php">Notes</a></li>
    <li class="breadcrumb-item active" aria-current="page">Sorted by Students</li>
  </ol>
</nav>

<div class="card">
    <div class="card-header">Submitted by Students</div>
    <div class="card-body">
      <div class="remove-messages"></div>

      <div class="div-action" style="padding-bottom: 20px">
        <a class="btn btn-primary" style="color: white" href="notes.php">Go Back</a>
      </div>
<?php 
      $getpending="SELECT s.sid,s.sname,s.sbranch,s.ssem,u.nid,n1.n_subject FROM uploads u INNER JOIN student s ON u.sid=s.sid INNER JOIN notes n1 ON u.nid=n1.nid";
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
              <th>NoteID</th>
              <th>NoteSubject</th>                          
            </tr>
          </thead>
          <tbody>");
      while ($row=$result->fetch_assoc()) {
        $a=$row['sid'];
        $b=$row['sname'];
        $c=$row['sbranch'];
        $d=$row['ssem'];
        $e=$row['nid'];
        $f=$row['n_subject'];
        print("<tr>
              <td>".$a."</td>
              <td>".$b."</td>
              <td>".$c."</td>
              <td>".$d."</td>
              <td>".$e."</td>
              <td>".$f."</td>");                            
        }
      print("</tbody>
        </table>
        </div>
      </div>");


      $numsql="select u.sid,s.sname,s.sbranch,s.ssem,COUNT(u.nid) from uploads u inner join student s on u.sid=s.sid group by u.sid";
      $numres=$connect->query($numsql);      
      print("<div class='row'>
        <div class='col-sm-12'>
        <table class='table table-striped table-bordered'>
          <thead>
            <tr>
              <th>StudentID</th>
              <th>Name</th>
              <th>Branch</th>
              <th>Sem</th>
              <th>No. of Notes</th>                          
            </tr>
          </thead>
          <tbody>");
      while ($row=$numres->fetch_assoc()) {
        $a=$row['sid'];
        $b=$row['sname'];
        $c=$row['sbranch'];
        $d=$row['ssem'];
        $e=$row['COUNT(u.nid)'];
        print("<tr>
              <td>".$a."</td>
              <td>".$b."</td>
              <td>".$c."</td>
              <td>".$d."</td>
              <td>".$e."</td>");                            
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