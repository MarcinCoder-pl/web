<?php
// Rozpoczynamy sesję
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}//////////////

///////////
define('ACCESS', true);
require_once '../config/database_conf.php'; // Połączenie z bazą danych
require_once 'check_acc.php';
require_once 'csrf_token.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST')
{	
	if (validateCsrfToken($_POST['csrf_token'] ?? '') )
	{
		if (isset($_POST['username'], $_POST['password'], $_POST['password_confirm']))
		{
			if( strlen($_POST['username']) >= MIN_LENGTH_REGISTER && strlen($_POST['username']) <= MAX_LENGTH_REGISTER  && strlen($_POST['password']) >= MIN_LENGTH_REGISTER )
			{ 
				if ($_POST['password'] === $_POST['password_confirm'])
				{
					$hashed_pass = haszujHaslo($_POST['password']);
				
					$login = convert_to_utf8( $_POST['username'] );
					
					//echo ($login);
					
					if( isAlphanumeric($login) )
					{
						$conn = new mysqli($host, $db_user, $password, $dbname);
						if ( $conn->connect_error )
						{
							die("Połączenie nie powiodło się: " . $conn->connect_error);
						}
						if( !isLoginTaken( $conn, $login) )
						{
								$stmt = $conn->prepare(ADD_ACC);
								$stmt->bind_param("ss", $login, $hashed_pass );

								if( $stmt->execute() )
								{
									echo"wykonano";
								}
								else
								{
									echo "nie wykonano";
								}
								$stmt->close();
								$conn->close();
						}
					}
				}
			}
		}
	}
}


?>
