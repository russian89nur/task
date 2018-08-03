<?php
	include_once('connection.php');

	// Проверяем загружен ли файл
	if(is_uploaded_file($_FILES["filename"]["tmp_name"]))
	{
		// Если файл загружен успешно, перемещаем его из временной директории в конечную
		move_uploaded_file($_FILES["filename"]["tmp_name"], "file/".$_FILES["filename"]["name"]);


		// запрос к таблице users
		$resultset = mysqli_query($handle, "SELECT * FROM `users`");
		$xml = simplexml_load_file('file/'.$_FILES["filename"]["name"]);

		// таблица front
		$table_front = '<table id="grid"><thead><tr><th data-type="number">№</th><th data-type="string">login</th><th data-type="string">password</th><th data-type="string">name</th><th data-type="string">email</th></tr></thead><tbody>';
		$key = 1;

		// проверяем ее на пустоту, если пустая то записываем все из фаила в базу данных
		if (mysqli_num_rows($resultset) != 0) {

			while ($row = mysqli_fetch_assoc($resultset)){
				$users[] = $row;
			}


			$str_update_name = "UPDATE `users` SET `name`= (case";
			$str_update_name_logo = "(";

			$str_update_email = "UPDATE `users` SET `email`= (case";
			$str_update_email_logo = "(";

			$str_delete = "DELETE FROM `users` WHERE";

			$check__num_del = 0;
			$check__num_update = 0;
			$check__num_process = 0;


			foreach ($users as $k => $val) {
				$check__num_process++;
				$check_login = false;
				foreach ($xml->user as $user){
					if ($val['login'] == $user->login) {

						$check_login = true;

						// валидация имени
						if ($val['name'] != $user->name){
							$str_update_name .= " when `login`='".$val['login']."' then '".$user->name."'";
							$str_update_name_logo .= "'".$user->login."',";
						}

						// валидация email
						if ($val['email'] != $user->email){
							$str_update_email .= " when `login`='".$val['login']."' then '".$user->email."'";
							$str_update_email_logo .= "'".$user->login."',";
						}

						if ($val['email'] != $user->email || $val['name'] != $user->name){
							$check__num_update++;
						}

						if ($key < 25) {
							$table_front .= '<tr class="pagination_true"><td>'.$key.'</td><td>'.$val['login'].'</td><td>'.$val['password'].'</td><td>'.$user->name.'</td><td>'.$user->email.'</td></tr>';
						} else {
							$table_front .= '<tr class="pagination"><td>'.$key.'</td><td>'.$val['login'].'</td><td>'.$val['password'].'</td><td>'.$user->name.'</td><td>'.$user->email.'</td></tr>';
						}
						$key++;
					}
				}
				// валидация на то что вообще есть ли такой user
				if (!$check_login) {
					$str_delete .= " `login` = '".$val['login']."' or ";
					$check__num_del++;
				}

			}


			$str_update_name_logo = substr($str_update_name_logo, 0, -1);
			$str_update_name_logo .= ")";
			$str_update_name .= "end) WHERE `login` in".$str_update_name_logo;
			mysqli_query($handle, $str_update_name);

			$str_update_email_logo = substr($str_update_name_logo, 0, -1);
			$str_update_email_logo .= ")";
			$str_update_email .= "end) WHERE `login` in".$str_update_email_logo;
			mysqli_query($handle, $str_update_email);

			$str_delete = substr($str_delete, 0, -3);
			mysqli_query($handle, $str_delete);


			$table_front .= '</tbody></table>';
			// пагинация front
			$key--;
			for ($x = 0; $x < ceil($key/25); $x++) {
				if ($x == 0) {
					$table_front .= '<input type="radio" class="pagination__input" id="pagintionChoice'.($x+1).'" name="pagintion" data-num="'.($x+1).'" checked><label class="ml-15" for="pagintionChoice'.($x+1).'">'.($x+1).'</label>';
				} else {
					$table_front .= '<input type="radio" class="pagination__input" id="pagintionChoice'.($x+1).'" name="pagintion" data-num="'.($x+1).'"><label class="ml-15" for="pagintionChoice'.($x+1).'">'.($x+1).'</label>';
				}
			}
			echo $table_front."|-_-|".$check__num_del."|-_-|".$check__num_update."|-_-|".$check__num_process;
		} else {

			$str = '';

			foreach ($xml->user as $user){
				$str .= "('";
				$str .= $user->login."','";
				$str .= $user->password."','";
				$str .= "логин','";
				$str .= "логин@example.com";
				$str .= "'),";
				if ($key < 25) {
					$table_front .= '<tr class="pagination_true"><td>'.$key.'</td><td>'.$user->login.'</td><td>'.$user->password.'</td><td>логин</td><td>логин@example.com</td></tr>';
				} else {
					$table_front .= '<tr class="pagination"><td>'.$key.'</td><td>'.$user->login.'</td><td>'.$user->password.'</td><td>логин</td><td>логин@example.com</td></tr>';
				}
				$key++;
			}

			$table_front .= '</tbody></table>';

			$str = substr($str, 0, -1);

			// запись в бд
			$sql = "INSERT INTO `users` (login, password, name, email) VALUES ".$str;
			mysqli_query($handle, $sql);

			// пагинация front
			$key--;
			for ($x = 0; $x < ceil($key/25); $x++) {
				if ($x == 0) {
					$table_front .= '<input type="radio" class="pagination__input" id="pagintionChoice'.($x+1).'" name="pagintion" data-num="'.($x+1).'" checked><label class="ml-15" for="pagintionChoice'.($x+1).'">'.($x+1).'</label>';
				} else {
					$table_front .= '<input type="radio" class="pagination__input" id="pagintionChoice'.($x+1).'" name="pagintion" data-num="'.($x+1).'"><label class="ml-15" for="pagintionChoice'.($x+1).'">'.($x+1).'</label>';
				}
			}

			echo $table_front;
		}
		mysqli_close($handle);
	} else {
		echo("Error");
	}
?>