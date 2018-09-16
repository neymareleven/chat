<?php
	session_start();

	if (isset($_POST['formconnexion'])) 
	{

		$pdo_options[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION;

		try 
		{
			$bdd=new PDO('mysql:host=Localhost;dbname=Sites;charset=utf8','root','',$pdo_options);
		} 
		catch (Exception $e) 
		{
			die ('Erreur: '.$e->getMessage());
		}

		//vérification des identifiants
		$pass_hache=sha1('gz'.$_POST['pass']);
		$req=$bdd->prepare('SELECT * FROM membres WHERE pseudo=:pseudo AND pass=:pass');
		$req->execute( array('pseudo' => $_POST['pseudo'] ,'pass' => $pass_hache ));
		$resultat=$req->fetch();
	

		if (!$resultat) 
		{
			$message="<strong><em>mauvais pseudonyme ou mot de passe</em></strong>";
		}
		else
		{
			$_SESSION['id'] = $resultat ['id'];
			$_SESSION['pseudo'] = $resultat ['pseudo'];
			header("Location: profil.php?id=".$_SESSION['id']);
		}

			$req->closeCursor();
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
			<h1>Connexion</h1><br/>
		<form method="post" action="">
			<p>
				<table>
					<tr>
						<td align="right"><label for="pseudo">Pseudo</label></td>
						<td><input type="text" name="pseudo" id="pseudo" value="<?php if(isset($_POST['pseudo'])) {echo $_POST['pseudo'];}?>" /></td>
					</tr>
					<tr>
						<td align="right"><label for="pass">Mot de passe</label></td>
						<td><input type="password" name="pass" id="pass"/></td>
					</tr>
					
					<tr>
						<td></td>
						<td align="center"><br/><input type="submit" value="Se connecter" name="formconnexion" /></td>
					</tr>
			</table>
			</p>
		</form>
			<p>Vous n'avez pas de compte?<a href="Inscrire.php"> cliquez ici pour vous inscrire</a></p>
			<p><?php if(isset($message)){echo $message;}?></p>
	</div>
	
	</body>
	</html>