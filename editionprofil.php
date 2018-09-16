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


	if (isset($_SESSION['id'])) 
	{
		$req = $bdd->prepare('SELECT * FROM membres WHERE id=?');
		$req->execute(array($_SESSION['id']));
		$user=$req->fetch();

		if (isset($_POST['newpseudo']) AND !empty($_POST['newpseudo']) AND $_POST['newpseudo'] != $user['pseudo'])
		{
			$insertnewpseudo=$bdd->prepare('UPDATE membres SET pseudo=? WHERE id=?');
			$insertnewpseudo->execute (array($_POST['newpseudo'],$_SESSION['id']));
			header("Location: profil.php?id=".$_SESSION['id']);
		}
		
		if (isset($_POST['newemail']) AND !empty($_POST['newemail']) AND $_POST['newemail'] != $user['email'])
		{
			$insertnewemail=$bdd->prepare('UPDATE membres SET email=? WHERE id=?');
			$insertnewemail->execute (array($_POST['newemail'],$_SESSION['id']));
			header("Location: profil.php=".$_SESSION['id']);
		}
		if (isset($_POST['newpseudo']) AND $_POST['newpseudo']==$user['pseudo'] AND $_POST['newpass']==$_POST['newretapez_pass']) 
		{
			header("Location: profil.php?id=".$_SESSION['id']);
		}

		if (isset($_POST['newpass']) AND !empty($_POST['newpass']) AND isset($_POST['newretapez_pass']) AND !empty($_POST['newretapez_pass']))
		{
			$mdp1=sha1('gz'.$_POST['newpass']);
			$mdp2=sha1('gz'.$_POST['newretapez_pass']);
			if ($mdp1==$mdp2) 
			{
				$insertnewpass=$bdd->prepare('UPDATE membres SET pass=? WHERE id=?');
			$insertnewpass->execute (array($mdp1,$_SESSION['id']));
			header("Location: profil.php?id=".$_SESSION['id']);

			}
			else
			{
				$msg='Vos mots de passe ne correspondent pas';
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

 		<h2>Edition de mon profil</h2>
 		<form method="post" action="">
 		<table>
 			<tr>
 				<td align="right"><label for="newpseudo">Pseudo</label></td>
 				<td><input type="text" name="newpseudo" placeholder="PSEUDO" value="<?php echo $user['pseudo'];?>" id="newpseudo"/></td>
 			</tr>

 			<tr>
 				<td align="right"><label for="newpass">Mot de passe</label></td>
 				<td><input type="password" name="newpass" placeholder="MOT DE PASSE" id="newpass" /></td>
 			</tr>

 			<tr>
 				<td align="right"><label for="newretapez_pass">Confirmez mot de passe</label></td>
 				<td><input type="password" name="newretapez_pass" placeholder="CONFIRMEZ MOT DE PASSE" id="newretapez_pass" /></td>
 			</tr>

 			<tr>
 				<td align="right"><label for="email">Email</label></td>
 				<td><input type="email" name="newemail" placeholder="EMAIL" value="<?php echo $user['email'];?>" id="email"/></td>
 			</tr>

 			<tr>
 				<td></td>
 				<td align="center"><br/><input type="submit" name="" value="Mettre à jour mon profil" /></td>
 			</tr>
 		</table>
 		</form>
 		<?php if(isset($msg)){echo $msg;}?>
 		
 	</div>
 
 </body>
 </html>
 <?php
}
else
{
	header("Location: connect.php");
}

 ?>