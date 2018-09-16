<?php
			if (isset($_POST['forminscription'])) 
			{
			
				
				if (isset($_POST['pseudo']) AND isset($_POST['pass']) AND isset($_POST['retapez_pass']) AND isset($_POST['email']) AND !empty($_POST['pseudo']) AND !empty($_POST['pass']) AND !empty($_POST['retapez_pass']) AND !empty($_POST['email'])) 
				{
					$pdo_options[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION;

					try 
					{
						$bdd= new PDO('mysql:host=Localhost;dbname=Sites;charset=utf8', 'root','',$pdo_options);
					} 
					catch (Exception $e) 
					{
						die ('Erreur:'.$e->getMessage());
					}
					
					$req=$bdd->prepare('SELECT id FROM membres WHERE pseudo=:pseudo');
						$req->execute( array('pseudo' => $_POST['pseudo'] ));
						$resultat=$req->fetch();

					if($_POST['pass'] != $_POST['retapez_pass'])
					{
						$msg= "<strong><em>Vos mot de passe ne sont pas identique</em></strong>";
					}
					
					elseif(!preg_match("#^[a-z0-9._-]+@[a-z0-9._-]{2,}\.[a-z]{2,4}$#", $_POST['email']))
					{
						$msg= "l'adresse<strong><em> ".$_POST['email']." </em></strong>n'est pas correcte";
					}

					elseif ($resultat && sizeof($resultat) > 0) 
					{
						$msg= "Le pseudonyme<strong><em> ".$_POST['pseudo']." </em></strong>existe déjà prenez en un autre";
					} 
					else 
					{
						$pass_hache=sha1('gz'.$_POST['pass']);

						$req1=$bdd->prepare('INSERT INTO membres (pseudo,pass,email,date_inscription) VALUES (:pseudo,:pass,:email,NOW()) ');
						$req1->execute(array(
							'pseudo'=>$_POST['pseudo'],
							'pass'=>$pass_hache,
							'email'=>$_POST['email']));
						header( "Location: connect.php");
					}
						$req->closeCursor();
					
				}
				else
				{
					$msg= "<strong><em>Veuillez remplir tous les champs du forrmulaire</em></strong>";

				}
			}

			?>

<!DOCTYPE html>
	<html>
		<head>
			<title>Mon chat</title>
			<meta charset="utf-8"/>
		</head>
		<body>
			<div align="center">
				<h1>Inscription</h1><br/>
			<form method="post" action="">
				<p>
					<table>
						<tr>
							<td align="right"><label for="pseudo">Pseudo</label></td>
							<td><input type="text" name="pseudo" id="pseudo" placeholder="PSEUDO" value="<?php if(isset($_POST['pseudo'])){echo $_POST['pseudo'];}?>" /></td>
						</tr>
						<tr>
							<td align="right"><label for="pass">Mot de passe</label></td>
							<td><input type="password" name="pass" id="pass" placeholder="MOT DE PASSE"/></td>
						</tr>
						<tr>
							<td align="right"><label for="retapez_pass">Confirmez mot de passe</label></td> 
							<td><input type="password" name="retapez_pass" id="retapez_pass" placeholder="CONFIRMEZ MOT DE PASSE" /></td>
						</tr>
						<tr>
							<td align="right"><label for="email">Email</label></td>
							<td><input type="email" name="email" id="email" placeholder="ADRESSE EMAIL"  value="<?php if(isset($_POST['email'])){echo $_POST['email'];}?>"/></td>
						</tr>
						<tr>
							<td></td>
							<td align="center"><br/><input type="submit" value="Je m'inscris" name="forminscription" /></td>
						</tr>
				</table>
				</p>
			</form>
				<p><a href=connect.php>retouner à la page de connection</a></p>
				<p><?php if(isset($msg)){echo $msg;}?></p>
		</div>
		</body>
	</html>