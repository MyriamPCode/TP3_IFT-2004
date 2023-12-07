<!DOCTYPE html>
<html>

<head>
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/water.css@2/out/water.css"/>
</head>

<body>
<?php include 'header.php'; ?>

<form method="post" action="principal.php">
	<p>
		<label for="username">Username: </label>
    	<input type="text" id="username" name="username" />
	</p>
	<p>
		<label for="password">Password: </label>
    	<input type="password" id="password" name="password" />
	</p>
    <input type="submit" value="Submit" />
</form>

<!-- Valider que le username et le password sont valide et present dans la base de donnees -->

<?php include 'footer.php'; ?>
</body>

</html>

