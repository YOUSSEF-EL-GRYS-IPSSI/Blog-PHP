<script src="https://code.jquery.com/jquery-3.1.1.slim.min.js"></script>
<?php

include("connexion_bdd/connection_bdd.php");//connexion de la base de données
$PDO=Connection_BDD();
$id=$_GET['id'];
$Stmt=$PDO->exec("SET NAME UTF8");
$Query="DELETE  FROM user  WHERE  id='$id'";								
$Stmt=$PDO->prepare($Query);
$Stmt->execute();
$Count=$Stmt->rowCount();
IF($Count >0){
						echo "
						         <script>
									 $(document).ready(function() {
										window.location.href='user-list.php';});
									     alert('Enregistrement reussie !');
								</script>";
					 							
					    }										
					   else{
		                      echo "<script>
												 $(document).ready(function() {
														window.location.href='user-list.php';});
												     alert('Désolé , enregistrement non reussi , Veuillez réessayer !');
									  </script>;
							 ";									
							}
			$Stmt->closeCursor();
?>