<?php 
  require '../includes/header.php';
?>
<?php 
      if(isset($_POST['addOrdersBooksbtn'])){
        $a=$_POST['obid'];
        $b=$_POST['sid'];
        $c=$_POST['bid'];
        $d=$_POST['order_date'];
        $e=$_POST['ordered_quantity'];
        $f=$_POST['paid_amount'];
        $g=$_POST['order_status'];      
        $insert="insert into orders_books(obid,sid,bid,order_date,ordered_quantity,paid_amount,order_status) values('$a','$b','$c','$d','$e','$f','$g');";
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
        $delete="DELETE FROM orders_books WHERE obid='$delid'";
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
    <li class="breadcrumb-item"><a href="books.php">Books</a></li>
    <li class="breadcrumb-item active" aria-current="page">Orders</li>
  </ol>
</nav>

<div class="card">
    <div class="card-header"><i class="fa fa-edit"></i>Manage Book Orders<br>  
    </div>
    <div class="card-body">      
      <div class="div-action" style="padding-bottom: 20px">
        <button class="btn btn-primary" data-toggle="modal" data-target="#addOrdersBooks"><i class="fa fa-plus"></i>Place Order</button>                
      </div>
<?php 
      $getOrdersBooks="SELECT * FROM orders_books";      
      $result=$connect->query($getOrdersBooks);
      print("<div class='row'>
        <div class='col-sm-12'>
        <table class='table table-striped table-bordered'>
          <thead>
            <tr>
              <th>OrderID</th>
              <th>StudentID</th>
              <th>BookID</th>
              <th>Order Date</th>
              <th>Ordered Quantity</th>
              <th>Grand Total</th>
              <th>Paid Amount</th>
              <th>Due Amount</th>
              <th>Payment Status</th>
              <th>Order Status</th>              
            </tr>
          </thead>
          <tbody>");      
      function setGtDa(string $obid) {
        try {
            $username="root";
            $password="";
            $pdo = new PDO("mysql:host=localhost;dbname=dbms_project", $username, $password);
     
            // calling stored procedure command
            $sql = ' CALL `setGrandTotal_DueAmount_Books`(:id, @p1, @p2, @p3);';
     
            // prepare for execution of the stored procedure
            $stmt = $pdo->prepare($sql);
     
            // pass value to the command
            $stmt->bindParam(':id', $obid, PDO::PARAM_STR);
     
            // execute the stored procedure
            $stmt->execute();
     
            $stmt->closeCursor();
     
            // execute the second query to get customer's level
            $row1 = $pdo->query("SELECT @p1 AS gt")->fetch(PDO::FETCH_ASSOC);

            $stmt->closeCursor();
     
            // execute the third query
            $row2 = $pdo->query("SELECT @p2 AS da")->fetch(PDO::FETCH_ASSOC);
            
            $stmt->closeCursor();
     
            // execute the third query
            $row3 = $pdo->query("SELECT @p3 AS ps")->fetch(PDO::FETCH_ASSOC);
            return array($row1['gt'],$row2['da'],$row3['ps']);
        } catch (PDOException $e) {
            die("Error occurred:" . $e->getMessage());
        }        
      }
      while ($row=$result->fetch_assoc()) {    
        $obid=$row['obid'];        
        $a=$row['sid'];
        $b=$row['bid'];
        $c=$row['order_date'];
        $d=$row['ordered_quantity'];
        list($e,$g,$h)=setGtDa($obid);
        $f=$row['paid_amount'];        
        $i=$row['order_status'];        
        print("<tr>
              <td>".$obid."</td>
              <td>".$a."</td>
              <td>".$b."</td>
              <td>".$c."</td>
              <td>".$d."</td>
              <td>".$e."</td>
              <td>".$f."</td>
              <td>".$g."</td>
              <td>".$h."</td>
              <td>".$i."</td>");
              print("<td><button class='btn btn-danger' data-toggle='modal' id=".$obid." data-target='#delOrdersBooks' onClick='return_del_id(this.id)'><i class='fa fa-trash'></i>Delete</button></td></tr>");              
      }
      print("</tbody>
        </table>
        </div>
      </div>");
 ?>
      
          
    </div>
</div>

<!-----------------------Modals Begin----------------------->
<!-----------------------Add Order Product Modal------------->
<div class="modal fade" tabindex="-1" role="dialog" id="addOrdersBooks">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title"><i class="fa fa-plus"></i> Place Order</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="container">
          <form action="orders_books.php" class="needs-validated" id="modal-form" method="POST">
              <div class="form-group">
                <label for="obid">OrderID:</label>
                <input type="text" class="form-control" id="obid" placeholder="Enter OrderID" name="obid" required>
              </div>
              <div class="form-group">
                <label for="sid">StudentID:</label>
                <input type="text" class="form-control" id="sid" placeholder="Enter StudentID" name="sid" required>
              </div>
              <div class="form-group">
                <label for="bid">BookID:</label>
                <input type="text" class="form-control" id="bid" placeholder="Enter ProductID" name="bid" required>
              </div>
              <div class="form-group">
                <label for="order_date">Order Date:</label>
                <input type="date" class="form-control" id="order_date" name="order_date" required>
              </div>
              <div class="form-group">
                <label for="ordered_quantity">Ordered Quantity:</label>
                <input type="number" class="form-control" id="ordered_quantity" placeholder="Enter Ordered Quantity" name="ordered_quantity" required>
              </div>                            
              <div class="form-group">
                <label for="paid_amount">Paid Amount:</label>
                <input type="number" class="form-control" id="paid_amount" placeholder="Enter Paid Amount" name="paid_amount" required>
              </div>              
              <div class="form-group">
                <label for="order_status">Order Status:</label>
                <div id="order_status">
                  <div class="form-check">
                    <label class="form-check-label" for="a1">
                      <input type="radio" class="form-check-input" id="a1" name="order_status" value="1" checked>Yes
                    </label>
                  </div>
                  <div class="form-check">
                    <label class="form-check-label" for="a2">
                      <input type="radio" class="form-check-input" id="a2" name="order_status" value="0">No
                    </label>      
                  </div>
                </div>
              </div>                   
          </form>
          </div>
        </div>
      <div class="modal-footer">
        <button type="submit" class="btn btn-primary" id="addOrdersBooksbtn" name="addOrdersBooksbtn" form="modal-form">Save Changes</button>
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>
<!--------------------------Add Order Product Modal Ends-------------------------->
<!-----------------------Edit Electronic Product------------->
<div class="modal fade" tabindex="-1" role="dialog" id="editBooks">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title"><i class="fa fa-plus"></i>Edit Product</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="container">
          <form action="electronics.php" class="needs-validated" id="editModalForm" method="POST">
              <div class="form-group">
                <label for="pid">ProductID:</label>
                <input type="text" class="form-control" id="e_pid" placeholder="Enter ProductID" name="pid" required>
              </div>
              <div class="form-group">
                <label for="pname">Product Name:</label>
                <input type="text" class="form-control" id="e_pname" placeholder="Enter Product Name" name="pname" required>
              </div>
              <div class="form-group">
                <label for="category">Category:</label>
                <input type="text" class="form-control" id="e_category" placeholder="Enter Category" name="category" required>
              </div>
              <div class="form-group">
                <label for="pk">Part of Kit?: </label>
                <div id="pk">
                  <div class="form-check">
                    <label class="form-check-label" for="pk1">
                      <input type="radio" class="form-check-input" id="e_pk1" name="partof_kit" value="1" checked>Yes
                    </label>
                  </div>
                  <div class="form-check">
                    <label class="form-check-label" for="pk2">
                      <input type="radio" class="form-check-input" id="e_pk2" name="partof_kit" value="0">No
                    </label>                
                  </div>
                </div>
              </div>
              <div class="form-group">
                <label for="received_quantity">Received Quantity:</label>
                <input type="number" class="form-control" id="e_received_quantity" placeholder="Enter Received Quantity" name="received_quantity" required>
              </div>
              <div class="form-group">
                <label for="date_purchased">Date Purchased:</label>
                <input type="date" class="form-control" id="e_date_purchased" name="date_purchased" required>
              </div>
              <div class="form-group">
                <label for="instk">In Stock:</label>
                <div id="instk">
                  <div class="form-check">
                    <label class="form-check-label" for="in1">
                      <input type="radio" class="form-check-input" id="e_in1" name="in_stock" value="1" checked>Yes
                    </label>
                  </div>
                  <div class="form-check">
                    <label class="form-check-label" for="in2">
                      <input type="radio" class="form-check-input" id="e_in2" name="in_stock" value="0">No
                    </label>                
                  </div>
                </div>
              </div>
              <div class="form-group">
                <label for="cost_original">Cost Original:</label>
                <input type="number" class="form-control" id="e_cost_original" placeholder="Enter Original Cost" name="cost_original" required>
              </div>
              <div class="form-group">
                <label for="selling_cost">Selling Original:</label>
                <input type="number" class="form-control" id="e_selling_original" placeholder="Enter Selling Cost" name="selling_cost" required>
              </div>
              <div class="form-group">
                <label for="supplier_cost">Supplier Name:</label>
                <input type="text" class="form-control" id="e_supplier_name" placeholder="Enter Supplier Name" name="supplier_name" required>
              </div>
              <div class="form-group">
                <label for="avail">Availability:</label>
                <div id="avail">
                  <div class="form-check">
                    <label class="form-check-label" for="a1">
                      <input type="radio" class="form-check-input" id="e_a1" name="availability" value="1" checked>Yes
                    </label>
                  </div>
                  <div class="form-check">
                    <label class="form-check-label" for="a2">
                      <input type="radio" class="form-check-input" id="e_a2" name="availability" value="0">No
                    </label>                
                  </div>
                </div>
              </div>                   
          </form>
          </div>
        </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary" id="editElecbtn" name="editElecbtn" form="modal-edit-form">Save changes</button>
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>
<!------------------------------Edit Elec Product Modal Ends------------------------->
<!------------------------------Delete Product Modal Starts-------------------------->
<div class="modal fade" tabindex="-1" role="dialog" id="delOrdersBooks">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title"><i class="fa fa-trash"></i> Delete Book Order</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <p>Are you sure you want to delete order?</p>
      </div>
      <div class="modal-footer">
        <a class="btn btn-danger" id="delOrdersBooksLink">Confirm</a>
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
      document.getElementById('add_pid').setAttribute('value',clicked_id);
  }
  function return_del_id(clicked_id)
  {
      var x="orders_books.php?id=";
      var y=x.concat(clicked_id);
      document.getElementById('delOrdersBooksLink').setAttribute('href',y);
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