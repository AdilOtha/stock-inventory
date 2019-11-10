<?php 
  require '../includes/header.php';
?>
<?php 
      if(isset($_POST['submit'])){
        $a=$_POST['nid'];
        $b=$_POST['n_branch'];
        $c=$_POST['n_subject'];
        $d=$_POST['n_sem'];
        $e=$_POST['sessional_no.'];
        $l=$_POST['availability'];
        $insert="insert into notes(nid,n_branch,n_subject,n_sem,availability) values('$a','$b','$c','$d','$e','$l');";
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
        $delete="DELETE FROM notes WHERE nid='$delid'";
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
    <li class="breadcrumb-item active" aria-current="page">Notes</li>
  </ol>
</nav>

<div class="card">
    <div class="card-header"><i class="fa fa-edit"></i>Manage Notes</div>
    <div class="card-body">
      <div class="remove-messages"></div>

      <div class="div-action" style="padding-bottom: 20px">
        <button class="btn btn-primary" data-toggle="modal" data-target="#addNote"><i class="fa fa-plus"></i>Add Note</button>
        <a class="btn btn-primary" style="color: white" href="notes_stuwise.php">Sort by Student</a>
      </div>
<?php 
      $getelec="SELECT * FROM notes";
      $result=$connect->query($getelec);      
      print("<div class='row'>
        <div class='col-sm-12'>
        <table class='table table-striped table-bordered'>
          <thead>
            <tr>
              <th>NoteID</th>
              <th>Branch</th>
              <th>Subject</th>
              <th>Sem</th>
              <th>Sessional No.</th>
              <th>Availability</th>
            </tr>
          </thead>
          <tbody>");
      while ($row=$result->fetch_assoc()) {
        $a=$row['nid'];
        $b=$row['n_branch'];
        $c=$row['n_subject'];
        $d=$row['n_sem'];
        $e=$row['sessional_no.'];
        $l=$row['availability'];        
        print("<tr>
              <td>".$a."</td>
              <td>".$b."</td>
              <td>".$c."</td>
              <td>".$d."</td>
              <td>".$e."</td>
              <td>".$l."</td>");
              print("<td><button class='btn btn-danger' data-toggle='modal' id=".$a." data-target='#delNote' onClick='return_del_id(this.id)'><i class='fa fa-trash'></i>Delete</button></td></tr>");              
      }
      print("</tbody>
        </table>
        </div>
      </div>");
 ?>
      
          
    </div>
</div>

<!-----------------------Modals Begin----------------------->
<!-----------------------Add Electronic Product------------->
<div class="modal fade" tabindex="-1" role="dialog" id="addNote">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title"><i class="fa fa-plus"></i>Add Note</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="container">
          <form action="notes.php" class="needs-validated" id="modal-form" method="POST">
              <div class="form-group">
                <label for="nid">NoteID:</label>
                <input type="text" class="form-control" id="nid" placeholder="Enter NoteID" name="nid" required>
              </div>            
              <div class="form-group">
                <label for="n_branch">Branch:</label>
                <input type="text" class="form-control" id="n_branch" placeholder="Enter Branch" name="n_branch" required>
              </div>
              <div class="form-group">
                <label for="n_subject">Subject:</label>
                <input type="text" class="form-control" id="n_subject" placeholder="Enter Subject" name="n_subject" required>
              </div>
              <div class="form-group">
                <label for="n_sem">Semester:</label>
                <input type="number" class="form-control" id="n_sem" placeholder="Enter Semester" name="n_sem" required>
              </div>              
              <div class="form-group">
                <label for="sessional_no.">Sessional No.:</label>
                <input type="number" class="form-control" id="sessional_no." placeholder="Enter Sessional No." name="sessional_no." required>
              </div>              
              <div class="form-group">
                <label for="avail">Availability:</label>
                <div id="avail">
                  <div class="form-check">
                    <label class="form-check-label" for="a1">
                      <input type="radio" class="form-check-input" id="a1" name="availability" value="1" checked>Yes
                    </label>
                  </div>
                  <div class="form-check">
                    <label class="form-check-label" for="a2">
                      <input type="radio" class="form-check-input" id="a2" name="availability" value="0">No
                    </label>                
                  </div>
                </div>
              </div>                   
          </form>
          </div>
        </div>
      <div class="modal-footer">
        <button type="submit" class="btn btn-primary" id="addDelbtn" name="submit" form="modal-form">Save Changes</button>
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>
<!--------------------------Add Elec Product Modal Ends-------------------------->
<!-----------------------Edit Electronic Product------------->
<div class="modal fade" tabindex="-1" role="dialog" id="addElec">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title"><i class="fa fa-plus"></i>Add Product</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="container">
          <form action="books.php" class="needs-validated" id="modal-form" method="POST">
              <div class="form-group">
                <label for="bid">BookID:</label>
                <input type="text" class="form-control" id="bid" placeholder="Enter BookID" name="bid" required>
              </div>
              <div class="form-group">
                <label for="b_name">Book Name:</label>
                <input type="text" class="form-control" id="b_name" placeholder="Enter Book Name" name="b_name" required>
              </div>
              <div class="form-group">
                <label for="b_branch">Branch:</label>
                <input type="text" class="form-control" id="b_branch" placeholder="Enter Branch" name="b_branch" required>
              </div>
              <div class="form-group">
                <label for="b_sem">Semester:</label>
                <input type="number" class="form-control" id="b_sem" placeholder="Enter Semester" name="b_sem" required>
              </div>
              <div class="form-group">
                <label for="b_subject">Subject:</label>
                <input type="text" class="form-control" id="b_subject" placeholder="Enter Subject" name="b_subject" required>
              </div>
              <div class="form-group">
                <label for="received_quantity">Received Quantity:</label>
                <input type="number" class="form-control" id="received_quantity" placeholder="Enter Received Quantity" name="received_quantity" required>
              </div>
              <div class="form-group">
                <label for="date_purchased">Date Purchased:</label>
                <input type="date" class="form-control" id="date_purchased" name="date_purchased" required>
              </div>
              <div class="form-group">
                <label for="instk">In Stock:</label>
                <div id="instk">
                  <div class="form-check">
                    <label class="form-check-label" for="in1">
                      <input type="radio" class="form-check-input" id="in1" name="in_stock" value="1" checked>Yes
                    </label>
                  </div>
                  <div class="form-check">
                    <label class="form-check-label" for="in2">
                      <input type="radio" class="form-check-input" id="in2" name="in_stock" value="0">No
                    </label>                
                  </div>
                </div>
              </div>
              <div class="form-group">
                <label for="cost_original">Cost Original:</label>
                <input type="number" class="form-control" id="cost_original" placeholder="Enter Original Cost" name="cost_original" required>
              </div>
              <div class="form-group">
                <label for="selling_cost">Selling Original:</label>
                <input type="number" class="form-control" id="selling_original" placeholder="Enter Selling Cost" name="selling_cost" required>
              </div>
              <div class="form-group">
                <label for="supplier_cost">Supplier Name:</label>
                <input type="text" class="form-control" id="supplier_name" placeholder="Enter Supplier Name" name="supplier_name" required>
              </div>
              <div class="form-group">
                <label for="avail">Availability:</label>
                <div id="avail">
                  <div class="form-check">
                    <label class="form-check-label" for="a1">
                      <input type="radio" class="form-check-input" id="a1" name="availability" value="1" checked>Yes
                    </label>
                  </div>
                  <div class="form-check">
                    <label class="form-check-label" for="a2">
                      <input type="radio" class="form-check-input" id="a2" name="availability" value="0">No
                    </label>                
                  </div>
                </div>
              </div>                   
          </form>
          </div>
        </div>
      <div class="modal-footer">
        <button type="submit" class="btn btn-primary" id="editElecbtn" name="submit" form="modal-form">Save Changes</button>
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>
<!------------------------------Edit Elec Product Modal Ends------------------------->
<!------------------------------Delete Product Modal Starts-------------------------->
<div class="modal fade" tabindex="-1" role="dialog" id="delNote">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title"><i class="fa fa-trash"></i> Delete Product</h5>
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
      var x="notes.php?id=";
      var y=x.concat(clicked_id);
      document.getElementById('delLink').setAttribute('href',y);
  }
  /*$(document).ready(function(){
    $('#editModalForm').submit(function(){
     
        // show that something is loading
        //$('#response').html("<b>Loading response...</b>");
         
        /*
         * 'post_receiver.php' - where you will pass the form data
         * $(this).serialize() - to easily read form data
         * function(data){... - data contains the response from post_receiver.php
         
        $.ajax({
            type: 'POST',
            url: 'electronics.php', 
            data: $(this).serialize()
        })
        .done(function(data){
             
            // show the response
            $('#response').html(data);
             
        })
        .fail(function() {
         
            // just in case posting your form failed
            alert( "Posting failed." );
             
        });
 
        // to prevent refreshing the whole page page
        return false;
 
    });
});*/
$(document).ready(function(){
    $('#editElec').on('click',function(){
        var user_id = document.getElementById('e_pid').getAttribute('value');
        $.ajax({
            type:'POST',
            url:'getdata.php',
            dataType: "JSON",
            data:{pid:user_id},
            success:function(data){
                    alert("success");
                    var a=document.getElementById('e_pname');
                    a.value=data.pname;
                    //$('#e_category').text(data.result.category);
                    /*$('#e_name').text(data.result.name);
                    $('#e_received_quantity').text(data.result.email);
                    $('#e_date_purchased').text(data.result.phone);    */
                                                      
                  }
        });
    });
});
</script>
<!------------------Modals End----------------------------------------->
<?php 
  require '../includes/footer.php';
?>