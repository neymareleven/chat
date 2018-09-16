<?php
	session_start(); 
	try 
	{
		$bdd = new PDO ('mysql: host=localhost;dbname=Sites;charset=utf8','root','',array(PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION));
		
	} 
	catch (Exception $e) 
	{

		die('Erreur: '.$e->getMessage());
	}

	if (!empty($_POST['message'])) 
	{
		$req=$bdd->prepare('INSERT INTO Chat (pseudo,message,date_envoie_msg ) VALUES (:pseudo,:message,NOW())');
		$req->execute(array('pseudo' => $_SESSION['pseudo'],'message' => $_POST['message'] ));
	}

	if (isset($_GET['id']) AND $_GET['id'] > 0) 
	{
		$getid = intval($_GET['id']);
		$req=$bdd->prepare('SELECT * FROM membres WHERE id=?');
		$req->execute(array($getid));
		$donnees=$req->fetch();
	}

 ?>

 <!DOCTYPE html>
 <html>
 <head>
 	<title>Mon chat</title>
 	<meta charset="utf-8"/>
 	<link rel="stylesheet" href="chat.css">
 </head>
 <body>

 	<div align="center">

 		<h2>Bienvenue sur ce chat <?php echo $donnees['pseudo']; ?> !</h2>
 		<?php

 			if (isset($_SESSION['id']) AND $donnees['id']==$_SESSION['id']) 
 			{
 		?>
 			<form method="post" action="">
 				<p>
 					<table>
 						<tr><td><input type="text" name="pseudo" value="<?php if(isset($_SESSION['pseudo'])){echo $_SESSION['pseudo'];} ?>" disabled="disabled"/></td></tr>
 						<tr><td><textarea name="message" placeholder="MESSAGE"></textarea></td></tr>
 						<tr><td align="center"><input type="submit" value="poster message"/></td></tr>
 					</table>
 				</p>
 				
 			</form>		
 			<a href="deconnexion.php">Se déconnecter</a><br/><br/>
 			<a href="editionprofil.php">Editer mon profil</a><br/>
 		<?php
 				
 			}

 			$reponse=$bdd->query('SELECT pseudo,message,DATE_FORMAT(date_envoie_msg, \'%d/%m/%y à %Hh%imin%ss\') AS date_envoie_msg_fr FROM Chat ORDER BY id DESC');

			while ($don=$reponse->fetch()) 
			{
		?>
		<div class="news">

		<h3><?php echo htmlspecialchars($don['pseudo'])." le ".htmlspecialchars($don['date_envoie_msg_fr']); ?></h3>
		<p><?php echo $don['message']; ?></p>
		
		</div>

		<?php
			}

			$reponse->closeCursor();

 		?>
 		
 	</div>
 
 </body>
 </html>