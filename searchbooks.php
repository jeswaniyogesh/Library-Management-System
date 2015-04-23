<!DOCTYPE html>


<html lang="en"class="other-pages-background">
<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
   
    <!-- Bootstrap Core CSS -->
    <link href="css/bootstrap.min.css" rel="stylesheet">
	
     <!-- Custom CSS -->
    <link href="css/style.css" rel="stylesheet">
	
    <title>Search Books</title>
</head>


<body>
<!-- NAVIGATION BAR -->
<nav role="navigation" class="navbar navbar-default navbar-fixed-top">
    <div class="container-fluid">
       <div class="navbar-header">
            <a href="index.php" class="navbar-brand">Eugene McDermott Library</a>
        </div>
        <div id="navbarCollapse" class="collapse navbar-collapse">
            <ul class="nav navbar-nav navbar-right">
                <li class="active"><a href="searchbooks.php">Search Books</a></li>
                <li><a href="bookloans.php">Book Loans</a></li>
                <li><a href="addborrower.php">Manage Borrowers</a></li>
                <li><a href="fines.php">Fines</a></li>
            </ul>
        </div>
    </div>
</nav>
<!-- NAVIGATION BAR -->




<br>
	<h1 style="color:#F00; font-family:'Palatino Linotype', 'Book Antiqua', Palatino, serif; font-weight:700; font-size:55px; text-align:center">SEARCH BOOKS</h1><br>
	<?php
//If GET request is true, then show book search form and search results
if (isset($_GET['bookID']) || isset($_GET['bookTitle']) || isset($_GET['bookAuthor']))
{
	if (isset($_GET['bookID']))
		$bookID=$_GET['bookID'];
	else
		$bookID='';
	
	if (isset($_GET['bookTitle']))
		$bookTitle=$_GET['bookTitle'];
	else
		$bookTitle='';
	
	if (isset($_GET['bookAuthor']))
		$bookAuthor=$_GET['bookAuthor'];
	else
		$bookAuthor='';
	
	if (isset($_GET['branchID']))
		$branchID=$_GET['branchID'];
	else
		$branchID='';
	
	if (isset($_GET['branchName']))
		$branchName=$_GET['branchName'];
	else
		$branchName='';
?>

<!-- Pre-filled Book Search Form based on search-->
	<form class="form-horizontal" action="searchbooks.php" method="get">
        <div class="form-group">
            <label for="bookID" class="control-label col-xs-2" style="color:#CCC; font-weight:bold; font-size:20px">Book ID</label>
            <div class="col-xs-10">
                <input type="text" class="form-control" name="bookID" placeholder="Enter Book ID....." style="max-width:1000px; font-weight:bold" value="<?php echo $bookID?>">
            </div>
        </div>
        <div class="form-group">
            <label for="bookTitle" class="control-label col-xs-2" style="color:#CCC; font-weight:bold; font-size:20px">Title</label>
            <div class="col-xs-10">
                <input type="text" class="form-control" name="bookTitle" placeholder="Enter Title....." style="max-width:1000px; font-weight:bold" value="<?php echo $bookTitle?>">
            </div>
        </div>
        <div class="form-group">
            <label for="bookAuthor" class="control-label col-xs-2" style="color:#CCC; font-weight:bold; font-size:20px">Author</label>
            <div class="col-xs-10">
                <input type="text" class="form-control" name="bookAuthor" placeholder="Enter Author....." style="max-width:1000px; font-weight:bold" value="<?php echo $bookAuthor?>">
            </div>
        </div>
       <div class="form-group">
            <label for="branchID" class="control-label col-xs-2" style="color:#CCC; font-weight:bold; font-size:20px">Branch ID</label>
            <div class="col-xs-10">
                <input type="text" class="form-control" name="branchID" placeholder="Enter Branch ID....." style="max-width:1000px; font-weight:bold" value="<?php echo $branchID?>">
            </div>
        </div>
        <div class="form-group">
            <label for="branchName" class="control-label col-xs-2" style="color:#CCC; font-weight:bold; font-size:20px">Branch Name</label>
            <div class="col-xs-10">
                <input type="text" class="form-control" name="branchName" placeholder="Enter Branch Name....." style="max-width:1000px; font-weight:bold" value="<?php echo $branchName?>">
            </div>
        </div>
         <div class="form-group">
            <div class="col-xs-offset-2 col-xs-10">
                <button type="submit" class="btn btn-primary custom-button" style="background:#F00; font-weight:bold">Search</button>
            </div>
        </div>
    </form>	
<!-- Pre-filled Book Search Form based on search-->	
	
	
	
	
	
	
	
	
	
<?php	
	include('config.php');
	$query= "SELECT BOOK.Book_id, Title, Author_name, BOOK_COPIES.Branch_id, No_of_copies FROM BOOK, BOOK_AUTHORS, BOOK_COPIES, LIBRARY_BRANCH WHERE BOOK.Book_id = BOOK_AUTHORS.Book_id AND BOOK.Book_id = BOOK_COPIES.Book_id AND BOOK_COPIES.Branch_id = LIBRARY_BRANCH.Branch_id AND BOOK.Book_id LIKE '%" . $bookID . "%' AND BOOK.Title LIKE '%" . $bookTitle . "%' AND BOOK_AUTHORS.Author_name LIKE '%" . $bookAuthor . "%' AND LIBRARY_BRANCH.Branch_id LIKE '%" . $branchID . "%' AND LIBRARY_BRANCH.Branch_name LIKE '%" . $branchName . "%'";
	$res= mysqli_query($con,$query);
	
	if($res == FALSE) {
    	die(mysql_error()); // TODO: better error handling
	}
	$rowCount = mysqli_num_rows($res);
	
	if($rowCount>0){
?>
	<table class="table table-center-align" style="color:#000; background:#999; max-width:1200px;">
        <caption class="text-right" style="color:#C00; font-style:italic; font-weight:bold; font-size:20px"><?php echo $rowCount;?> Results</caption>  
    <thead>
            <tr>
                <th>Book ID</th>
                <th>Title</th>
                <th>Author</th>
                <th>Branch ID</th>
                <th>Total # Copies</th>
                <th>Available # Copies </th>
                <th></th>
            </tr>
        </thead>
        <tbody>       

<?php	
	while($resArray=mysqli_fetch_array($res))
	{
		//Calculate available no. of books in specified library
		$query1 = "SELECT * FROM BOOK_LOANS WHERE Branch_id LIKE '%" . $resArray['Branch_id'] . "' AND Book_id LIKE '" . $resArray['Book_id'] . "' AND Date_in='0000-00-00'";
		$res1= mysqli_query($con,$query1);
		$checkedOutCount = mysqli_num_rows($res1);

	
?>
     	<tr>
            <td><?php echo $resArray['Book_id']; ?></td>
            <td><?php echo $resArray['Title']; ?></td>
            <td><?php echo $resArray['Author_name']; ?></td>
            <td><?php echo $resArray['Branch_id']; ?></td>
            <td><?php echo $resArray['No_of_copies']; ?></td>
            <td><?php echo $resArray['No_of_copies'] - $checkedOutCount; ?></td>
            <td>
            	<?php if(($resArray['No_of_copies'] - $checkedOutCount) >0){//Book is avaialable in this particular library
					?>
            		<button type="button" id=" <?php echo $resArray['Book_id'].$resArray['Branch_id'];?> " class="btn btn-primary" style="background:#090;" data-bookID="<?php echo $resArray['Book_id']; ?>" data-bookTitle="<?php echo $resArray['Title'];?>" data-branchID="<?php echo $resArray['Branch_id']; ?>" onClick="checkout(this.id)">Checkout</button>
            		<?php
					  }//closing bracket for: Book is avaialable in this particular library
					  else{//Book is not available in this particular library
					  ?>
					  		 <button type="button" id=" <?php echo $resArray['Book_id'].$resArray['Branch_id'];?> " class="btn btn-primary disabled" style="background:#090;" data-bookID="<?php echo $resArray['Book_id']; ?>" data-bookTitle="<?php echo $resArray['Title'];?>" data-branchID="<?php echo $resArray['Branch_id']; ?>" onClick="checkout(this.id)">Checkout</button>
					 <?php
                      }
					?>	  
            </td>
		</tr>
<?php   
    }
?>

		</tbody>
	</table>
    
<?php
	}//if($rowCount>0) ends
	else{
?>
		<table class="table table-center-align" style="color:#000; background:#999; max-width:1200px;">
        <caption class="text-left" style="color:#C00; font-style:italic; font-weight:bold; font-size:25px">No such book available</caption> 
        </table>
<?php
	}//else of 'if($rowCount>0)' ends
}//Get request 'if' ends 

//Show book search form if GET request is not true
else
{
?>

	<!-- Book Search Form -->		
	<form class="form-horizontal" action="searchbooks.php" method="get">
        <div class="form-group">
            <label for="bookID" class="control-label col-xs-2" style="color:#CCC; font-weight:bold; font-size:20px">Book ID</label>
            <div class="col-xs-10">
                <input type="text" class="form-control" name="bookID" placeholder="Enter Book ID....." style="max-width:1000px; font-weight:bold">
            </div>
        </div>
        <div class="form-group">
            <label for="bookTitle" class="control-label col-xs-2" style="color:#CCC; font-weight:bold; font-size:20px">Title</label>
            <div class="col-xs-10">
                <input type="text" class="form-control" name="bookTitle" placeholder="Enter Title....." style="max-width:1000px; font-weight:bold">
            </div>
        </div>
        <div class="form-group">
            <label for="bookAuthor" class="control-label col-xs-2" style="color:#CCC; font-weight:bold; font-size:20px">Author</label>
            <div class="col-xs-10">
                <input type="text" class="form-control" name="bookAuthor" placeholder="Enter Author....." style="max-width:1000px; font-weight:bold">
            </div>
        </div>
        <div class="form-group">
            <label for="branchID" class="control-label col-xs-2" style="color:#CCC; font-weight:bold; font-size:20px">Branch ID</label>
            <div class="col-xs-10">
                <input type="text" class="form-control" name="branchID" placeholder="Enter Branch ID....." style="max-width:1000px; font-weight:bold">
            </div>
        </div>
        <div class="form-group">
            <label for="branchName" class="control-label col-xs-2" style="color:#CCC; font-weight:bold; font-size:20px">Branch Name</label>
            <div class="col-xs-10">
                <input type="text" class="form-control" name="branchName" placeholder="Enter Branch Name....." style="max-width:1000px; font-weight:bold">
            </div>
        </div>
        <div class="form-group">
            <div class="col-xs-offset-2 col-xs-10">
                <button type="submit" class="btn btn-primary custom-button" style="background:#F00; font-weight:bold">Search</button>
            </div>
        </div>
	</form>
	<!-- Book Search Form-->
<?php
}//Get request 'if' ends 
//run this code if GET Request is true












?>

<!-- jQuery Version 1.11.0 -->
    <script src="js/jquery-1.11.0.js"></script>

    <!-- Bootstrap Core JavaScript -->
    <script src="js/bootstrap.min.js"></script>

	<script>
	function checkout(buttonID){
		var button=document.getElementById(buttonID);
		
		var bookID = button.getAttribute("data-bookID");
		var bookTitle = button.getAttribute("data-bookTitle");
		var branchID = button.getAttribute("data-branchID");
		
		window.location.href = 'checkout.php?bookID=' + bookID + '&branchID=' + branchID; 
	}
	</script>

<script>
/*
function resetData() {
	  $.ajax({
           type: "POST",
           url: 'reset.php',
		   data: {},
           success:function(html) {
           }

      });
 }
*/
</script>








</body>


<footer>
	
</footer>

</html>