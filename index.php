<?php
	include_once('connection.php');

	$resultset = mysqli_query($handle, "SELECT * FROM `users`");

	while ($row = mysqli_fetch_assoc($resultset)){
		$users[] = $row;
	}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
	<meta charset="utf-8">
	<title>Задание</title>
	<link rel="stylesheet" href="style.css">
</head>
<body>
<span>Загрузить фаил для обработки:</span>
<form name="person" enctype="multipart/form-data">
	<input type="file" name="filename" id="file">
</form>
<br>
<button onclick="sendXml()">Загрузить фаил</button>
<br>
<br>
<br>
<br>
<br>
<span>Сортировка срабатывает при нажатии на № , login, password, name, email</span>

<div id="table_add">
	<?php if (isset($users)){ ?>
		<table id="grid">
			<thead>
				<tr>
					<th data-type="number">№</th>
					<th data-type="string">login</th>
					<th data-type="string">password</th>
					<th data-type="string">name</th>
					<th data-type="string">email</th>
				</tr>
			</thead>
			<tbody>

			<?php foreach ($users as $key => $value){
				if ($key < 24) {
					?>
					<tr class="pagination_true">
						<td><?= $key+1 ?></td>
						<td><?= $value['login'] ?></td>
						<td><?= $value['password'] ?></td>
						<td><?= $value['name'] ?></td>
						<td><?= $value['email'] ?></td>
					</tr>
				<?php } else { ?>
					<tr class="pagination">
						<td><?= $key+1 ?></td>
						<td><?= $value['login'] ?></td>
						<td><?= $value['password'] ?></td>
						<td><?= $value['name'] ?></td>
						<td><?= $value['email'] ?></td>
					</tr>
				<?php } } ?>
			</tbody>
		</table>

		<?php for ($x = 0; $x < ceil(count($users)/25); $x++) {
			if ($x == 0) { ?>
				<input type="radio" class="pagination__input" id="pagintionChoice<?=$x+1?>"
					   name="pagintion" data-num='<?=$x+1?>' checked>
				<label class="ml-15" for="pagintionChoice<?=$x+1?>"><?=$x+1?></label>
			<?php } else { ?>
				<input type="radio" class="pagination__input" id="pagintionChoice<?=$x+1?>"
					   name="pagintion" data-num='<?=$x+1?>'>
				<label class="ml-15" for="pagintionChoice<?=$x+1?>"><?=$x+1?></label>
			<?php } ?>
		<?php } ?>
	<?php } ?>
</div>
<div class="report"></div>
<script type="text/javascript" src="site.js"></script>
<?php if (isset($users)){echo '<script type="text/javascript">sort();pagination();</script>';} ?>
</body>
</html>