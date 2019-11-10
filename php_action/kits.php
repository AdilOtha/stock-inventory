<?php 
  require '../includes/header.php';
?>
<?php 
      if(isset($_POST['submit'])){
        $a=$_POST['kid'];
        $m=$_POST['kname'];
        $b=$_POST['kit_contents'];
        $insert="insert into kits(kid,kname,kit_contents) values('$a','$m','$b')";
        $inres=$connect->query($insert);
        /*if($inres==1){
          echo "Data Inserted!";
        }else{
          echo "Failed!";
        }*/
      }
      if(isset($_REQUEST['id'])){
        $delid=$_REQUEST['id'];
        //echo $delid;
        $delete="DELETE FROM kits WHERE kid='$delid'";
        $delres=$connect->query($delete);
        /*if($delres==1){
          echo "delete successful";        
        }else{
          echo "failed";
        }*/        
      }
 ?>
<nav aria-label="breadcrumb">
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="dashboard.php">Home</a></li>
    <li class="breadcrumb-item active" aria-current="page">Kits</li>
  </ol>
</nav>

<div class="card">
    <div class="card-header"><i class="fa fa-edit"></i>Manage Kits</div>
    <div class="card-body">
      <div class="remove-messages"></div>

      <div class="div-action" style="padding-bottom: 20px">
        <button class="btn btn-primary" data-toggle="modal" data-target="#addKit"><i class="fa fa-plus"></i>Add Kit</button>
      </div>
<?php 
      $de=0;
      $getkit="SELECT * FROM kits";
      $result=$connect->query($getkit);
      $availsql="select k.kid,COUNT(k.pid),k1.kname,SUM(e.availability) from kits_elec k inner join electronics e on k.pid=e.pid inner join kits k1 on k.kid=k1.kid group by k.kid,k1.kname having(COUNT(k.pid)=SUM(e.availability));";
      $availres=$connect->query($availsql);
      $availrow=$availres->fetch_assoc();
      //print_r($availrow);
      $availkitid=$availrow['kid'];
      //print_r($availkitid);
      print("<div class='row'>
        <div class='col-sm-12'>
        <table class='table table-striped table-bordered'>
          <thead>
            <tr>
              <th>KitID</th>
              <th>Kit Name</th>
              <th>Kit Contents</th>
              <th>Kit Availability</th>
            </tr>
          </thead>
          <tbody>");
      while ($row=$result->fetch_assoc()) {        
        $a=$row['kid'];
        $b=$row['kname'];
        $c=$row['kit_contents'];
        //print_r($row);
        print("<tr>
              <td>".$a."</td>
              <td>".$b."</td>
              <td>".$c."</td>");
              if($a==$availkitid){
                print("<td>1</td>");
              }else{
                print("<td>0</td>");
              }              
              print("<td><button class='btn btn-danger' data-toggle='modal' id=".$a." data-target='#delKit' onClick='return_del_id(this.id)'><i class='fa fa-trash'></i>Delete</button></td></tr>");        
      }
      print("</tbody>
        </table>
        </div>
      </div>");
 ?>
      
          
    </div>
</div>

<!-----------------------Modals Begin----------------------->
<div class="modal fade" tabindex="-1" role="dialog" id="addKit">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title"><i class="fa fa-plus"></i>Add Product</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="container">
          <form action="kits.php">
              <div class="form-group">
                <label for="kname">Kit Name:</label>
                <input type="text" class="form-control" id="kname" placeholder="Enter Kit Name" name="kname">
              </div>
              <div class="form-group">
                <label for="kit_contents">Kit Contents:</label>
                <input type="text" class="form-control" id="kit_contents" placeholder="Enter Kit Contents" name="kit_contents">
              </div>
               <!-- /form-group-->                    
          </form>
        </div>
      </div>
      <div class="modal-footer">
        <button type="submit" class="btn btn-primary" data-loading-text="Loading..." id="addKitbtn">Save changes</button>
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

<div class="modal" tabindex="-1" role="dialog" id="editElec">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title"><i class="fa fa-plus"></i>Edit Product</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <p>Modal body text goes here.</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary">Save changes</button>
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

<!------------------------------Delete Product Modal Starts-------------------------->
<div class="modal fade" tabindex="-1" role="dialog" id="delKit">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title"><i class="fa fa-trash"></i> Delete Kit</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <p>Are you sure you want to delete item?</p>
      </div>
      <div class="modal-footer">
        <a class="btn btn-danger" id="delLink">Confirm</a>
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
      </div>
    </div>
  </div>
</div>
<!------------------------------Delete Modal Ends--------------------------------->
<script>
  function return_id(clicked_id)
  {
      document.getElementById('e_pid').setAttribute('value',clicked_id);
  }
  function return_del_id(clicked_id)
  {
      var x="kits.php?id=";
      var y=x.concat(clicked_id);
      document.getElementById('delLink').setAttribute('href',y);
  }
  <?php  
    require '../includes/footer.php';
  ?>