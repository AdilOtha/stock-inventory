<?php 
  require '../includes/header.php';
?>
<?php 
      if(isset($_POST['submit'])){
        $a=$_POST['bid'];
        $m=$_POST['b_name'];
        $b=$_POST['b_branch'];
        $c=$_POST['b_sem'];
        $d=$_POST['b_subject'];
        $e=$_POST['received_quantity'];
        $f=$_POST['date_purchased'];
        $g=$_POST['in_stock'];
        $h=$_POST['cost_original'];
        $i=$_POST['selling_cost'];
        $j=$_POST['supplier_name'];
        $l=$_POST['availability'];
        $insert="insert into books(bid,b_name,b_branch,b_sem,b_subject,received_quantity,date_purchased,in_stock,cost_original,selling_cost,supplier_name,availability) values('$a','$m','$b','$c','$d','$e','$f','$g','$h','$i','$j','$l');";
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
        $delete="DELETE FROM books WHERE bid='$delid'";
        $delres=$connect->query($delete);
        /*if($delres==1){
          echo "delete successful";        
        }else{
          echo "failed";
        }*/        
      }
      if(isset($_POST['addBookStockbtn'])){
        $add_id=$_POST['add_bid'];
        $add_quant=$_POST['add_quant'];
        $add_date_purchased=$_POST['add_date_purchased'];
        $addStockSql="CALL `addStock`('$add_id','$add_quant','$add_date_purchased')";
        $addRes=$connect->query($addStockSql);
      }
      function setDiscount_Books(int $disc, int $aboveVal) {
        try {
            $username="root";
            $password="";
            $pdo = new PDO("mysql:host=localhost;dbname=dbms_project", $username, $password);
     
            // calling stored procedure command
            $sql = ' CALL `setDiscount_books`(:disc, :aboveVal);';
     
            // prepare for execution of the stored procedure
            $stmt = $pdo->prepare($sql);
     
            // pass value to the command
            $stmt->bindParam(':disc', $disc, PDO::PARAM_INT);
            $stmt->bindParam(':aboveVal', $aboveVal, PDO::PARAM_INT);
     
            // execute the stored procedure
            $stmt->execute();
     
            $stmt->closeCursor();
        } catch (PDOException $e) {
            die("Error occurred:" . $e->getMessage());
        }        
      }
      if(isset($_POST['setBookDiscountbtn'])){
        setDiscount_books($_POST['disc'],$_POST['aboveVal']);
      }
 ?>
<nav aria-label="breadcrumb">
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="dashboard.php">Home</a></li>
    <li class="breadcrumb-item active" aria-current="page">Books</li>
  </ol>
</nav>

<div class="card">
    <div class="card-header"><i class="fa fa-edit"></i>Manage Books<br>
    <?php 
      $totalsql="select COUNT(availability) from books";
      $total=$connect->query($totalsql);
      $totalres=$total->fetch_assoc();
      print("Available Books: ".$totalres['COUNT(availability)']);
      print("<br>");
      $totalcostsql="select SUM(in_stock*selling_cost) from books where availability=1";
      $totalcost=$connect->query($totalcostsql);
      $totalcostres=$totalcost->fetch_assoc();
      print("Total Cost of All Books: ".$totalcostres['SUM(in_stock*selling_cost)']);

      print("<br>");
      $suppliersql="select supplier_name,SUM(received_quantity*cost_original) from books group by supplier_name";
      $supplier=$connect->query($suppliersql);
      $supplierres=$supplier->fetch_assoc();
      print("Supplier1: ".$supplierres['supplier_name'].": ".$supplierres['SUM(received_quantity*cost_original)']);
      print("<br>");
      $supplierres=$supplier->fetch_assoc();
      print("Supplier2: ".$supplierres['supplier_name'].": ".$supplierres['SUM(received_quantity*cost_original)']);
     ?>
    </div>
    <div class="card-body">
      <div class="remove-messages"></div>

      <div class="div-action" style="padding-bottom: 20px">
        <button class="btn btn-primary" data-toggle="modal" data-target="#addElec"><i class="fa fa-plus"></i> Add Product</button>
        <button class="btn btn-primary" data-toggle="modal" data-target="#setBookDiscount"><i class="fa fa-minus"></i> Set Discount</button>
        <a class="btn btn-primary" style="color: white" href="books_totalquant.php">Total Ordered Quantities</a>
      </div>
<?php 
      $getelec="SELECT * FROM books";
      $result=$connect->query($getelec);      
      print("<div class='row'>
        <div class='col-sm-12'>
        <table class='table table-striped table-bordered'>
          <thead>
            <tr>
              <th>BookID</th>
              <th>Name</th>
              <th>Branch</th>
              <th>Sem</th>
              <th>Subject</th>
              <th>Received Quantity</th>
              <th>Date Purchased</th>
              <th>In Stock</th>
              <th>Cost Original</th>
              <th>Selling Cost</th>
              <th>Supplier Name</th>
              <th>Net Cost</th>
              <th>Availability</th>
            </tr>
          </thead>
          <tbody>");
      while ($row=$result->fetch_assoc()) {
        $a=$row['bid'];
        $m=$row['b_name'];
        $b=$row['b_branch'];
        $c=$row['b_sem'];
        $d=$row['b_subject'];
        $e=$row['received_quantity'];
        $f=$row['date_purchased'];
        $g=$row['in_stock'];
        $h=$row['cost_original'];
        $i=$row['selling_cost'];
        $j=$row['supplier_name'];
        $k=$g*$i;
        $l=$row['availability'];        
        print("<tr>
              <td>".$a."</td>
              <td>".$m."</td>
              <td>".$b."</td>
              <td>".$c."</td>
              <td>".$d."</td>
              <td>".$e."</td>
              <td>".$f."</td>
              <td>".$g."</td>
              <td>".$h."</td>
              <td>".$i."</td>
              <td>".$j."</td>
              <td>".$k."</td>
              <td>".$l."</td>              
              <td><button class='btn btn-info' data-toggle='modal' data-target='#addBookStock' id=".$a." 
              onClick='return_id(this.id)'><i class='fa fa-plus-square'></i>Add</button></td>");
              print("<td><button class='btn btn-danger' data-toggle='modal' id=".$a." data-target='#delBooks' onClick='return_del_id(this.id)'><i class='fa fa-trash'></i>Delete</button></td></tr>");              
      }
      print("</tbody>
        </table>
        </div>
      </div>");
 ?>
      
          
    </div>
</div>

<!-----------------------Modals Begin----------------------->
<!-----------------------Add Book Product------------->
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
        <button type="submit" class="btn btn-primary" id="addElecbtn" name="submit" form="modal-form">Save Changes</button>
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>
<!--------------------------Add Book Product Modal Ends-------------------------->
<!-----------------------Edit Book Product------------->
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
<!------------------------------Edit Book Product Modal Ends------------------------->
<!------------------------------Delete Product Modal Starts-------------------------->
<div class="modal fade" tabindex="-1" role="dialog" id="delBooks">
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
<!------------------------------Add Books Stock Modal Starts-------------------------->

<div class="modal fade" tabindex="-1" role="dialog" id="addBookStock">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title"><i class="fa fa-plus"></i>Add Book Stock</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="container">
          <form action="books.php" class="needs-validated" id="addStockModalForm" method="POST">
              <div class="form-group">
                <label for="add_bid">ProductID:</label>
                <input type="text" class="form-control" id="add_bid" placeholder="Enter Book ProductID" name="add_bid" required>
              </div>            
              <div class="form-group">
                <label for="add_quant">Add Quantity:</label>
                <input type="number" class="form-control" id="add_quant" placeholder="Enter Quantity to Add" name="add_quant" required>
              </div>
              <div class="form-group">
                <label for="add_date_purchased">Date Purchased:</label>
                <input type="date" class="form-control" id="add_date_purchased" name="add_date_purchased" required>
              </div>
          </form>
          </div>
        </div>
      <div class="modal-footer">
        <button type="submit" class="btn btn-primary" id="addBookStockbtn" name="addBookStockbtn" form="addStockModalForm">Save changes</button>
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>
<!------------------------------Add Books Stock Modal Ends--------------------------------->
<!------------------------------Set Books Discount Modal Starts-------------------------->
<div class="modal fade" tabindex="-1" role="dialog" id="setBookDiscount">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title"><i class="fa fa-plus"></i>Set Book Discount</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="container">
          <form action="books.php" class="needs-validated" id="setDiscountModalForm" method="POST">                          
              <div class="form-group">
                <label for="disc">Discount:</label>
                <input type="number" class="form-control" id="disc" placeholder="Enter Discount to Set" name="disc" required>
              </div>
              <div class="form-group">
                <label for="aboveVal">Above Value:</label>
                <input type="number" class="form-control" id="aboveVal" placeholder="Enter Above Value" name="aboveVal" required>
              </div>             
          </form>
          </div>
        </div>
      <div class="modal-footer">
        <button type="submit" class="btn btn-primary" id="setBookDiscountbtn" name="setBookDiscountbtn" form="setDiscountModalForm">Save changes</button>
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>
<!------------------------------Set Books Discount Modal Ends--------------------------------->
<script>
  function return_id(clicked_id)
  {      
      document.getElementById('add_bid').setAttribute('value',clicked_id);
  }
  function return_del_id(clicked_id)
  {
      var x="books.php?id=";
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