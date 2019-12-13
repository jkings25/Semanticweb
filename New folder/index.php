<?php
require_once( "sparqllib.php" );

// SPARQL End-point 
$db = sparql_connect( "http://localhost:3030/Nutrifoods/query" );

if( !$db ) { print sparql_errno() . ": " . sparql_error(). "\n"; exit; }

// Define name space for your ontology
sparql_ns("gi","http://www.semanticweb.org/kings/ontologies/2019/10/untitled-ontology-10#");

//SPARQL Query 
$sparql = "SELECT ?foodname ?Gramofcarb ?vitamins ?Vitaminspresent ?GramFatpresent   ?Weight ?Gramofwater ?Minerals ?Calories ?Vitaminspresent ?Mineralspresent   
	   WHERE { 
		?food a gi:food.
  ?food gi:name ?foodname.
   
  
  OPTIONAL { ?food gi:has_vitamins ?vitamins.}
  OPTIONAL { ?food gi:gramoffat ?GramFatpresent.}
  OPTIONAL { ?food gi:has_minerals ?Minerals.}
  OPTIONAL { ?food gi:numberofvitamin ?Vitaminspresent}
  OPTIONAL { ?food gi:energy ?Calories.}
  OPTIONAL { ?food gi:gramofwater ?Gramofwater}
  OPTIONAL { ?food gi:numberofminerals ?Mineralspresent.}
  OPTIONAL { ?food gi:gramsofcarbs ?Gramofcarb.}
  OPTIONAL { ?food gi:numberofvitamin ?Vitaminspresent.}
  OPTIONAL { ?food gi:weight ?Weight.}
   .";


$sparql .= (isset($_GET["food"]) && !empty($_GET["food"]))?"FILTER(LCASE(STR(?foodname))=\"".strtolower($_GET["food"])."\")":"";
$sparql .= (isset($_GET["calcium"]) && !empty($_GET["calcium"]))?"FILTER(LCASE(STR(?vitamins))=\"".strtolower($_GET["calcium"])."\")":"";

$sparql .=	 "}";
//echo $sparql;
$result = sparql_query( $sparql ); 
if( !$result ) { print sparql_errno() . ": " . sparql_error(). "\n"; exit; }
 
$fields = sparql_field_array( $result );


?>
<html>
<head>
<h9 class='h3 mb-3 mt-4 font-weight-normal text-center'>Nutrifoods</h9>
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=yes">
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
<link href="w3.css" rel="stylesheet"/>
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
<style>
body {
  background-color: Blue;
}

h1 {
  color: red;
  text-align: center;
}

p {
  font-family: verdana;
  font-size: 4px;
}
</style>
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css">
</head>
<body>


<div class="bs-example">
    <ul class="nav nav-tabs">
	<li class="nav-item">
            <a href="#search" class="nav-link active" data-toggle="tab">food Search</a>
        <li class="nav-item">
            <a href="#home" class="nav-link" data-toggle="tab">Home</a>
        </li>
        
        
    </ul>
	<div class="tab-content">
        <div class="tab-pane fade" id="home">
            <h4 class="mt-2"></h4>
            <p><font size="6">Welcome to Nutrifoods </font></p>
			
			<p><font size="4"> <b>A Food Ontology For Everyone.</b></font></p>
			
				
        </div>
	

        <div class="tab-pane fade show active " id="search">
            <h4 class="mt-2"></h4>
            <p></p>
			<div class='col-6 offset-3 text-center'><form method="GET"><input type="text" name="food" placeholder="foodname" /><input type="submit" value="Search" /></form></div>
				<h4 class='h4 mb-3 mt-4 font-weight-normal text-center'>Foods Numbers: <?php echo sparql_num_rows( $result ); ?></h4>
				<div class='col-4 '><table class='table table-bordered table-hover'></div>
				<thead class='thead-light'><tr></tr>

        
	<?php
		foreach( $fields as $field )
		{
			print "<th>$field</th>";
		}
		print "</tr></thead><tbody>";
		while( $row = sparql_fetch_array( $result ) )
		{
			print "<tr>";
			foreach( $fields as $field )
			{
				print "<td>$row[$field]</td>";
			}
			print "</tr>";
		}
		print "";
	?>
</tbody></table></div>

</body>
</html>
 