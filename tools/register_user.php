<?php
 // Rozpoczynamy sesję
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}//////////////

define('ACCESS', true);
 
require_once '../config/db_config.php'; // Połączenie z bazą danych
require_once 'validate_form_fields.php';
require_once 'csrf_token.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST')
{	
	$token = $_POST['csrf_token'] ?? '';
	if (validateCsrfToken( $token) )
	{
		if (isset($_POST['username'], $_POST['password'], $_POST['password_confirm']))
		{
			if( strlen($_POST['username']) >= MIN_LENGTH_REGISTER && strlen($_POST['username']) <= MAX_LENGTH_REGISTER  && strlen($_POST['password']) >= MIN_LENGTH_REGISTER )
			{ 
				if ($_POST['password'] === $_POST['password_confirm'])
				{
					$hashed_pass = haszujHaslo($_POST['password']);
					$login = convert_to_utf8( $_POST['username'] );

					if( isAlphanumeric($login) )
					{
						$conn = new mysqli($db_host, $db_user, $db_password, $db_name);

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
									$_SESSION['username'] = $login;

									setcookie('username', $_SESSION['username'], time()+3600, "/", false, true );
									header('Location: /index.php?strona=dashboard');
 								}
 								else
 								{
 									echo "nie wykonano";
 									header('Location: ../index.php?strona=home');
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
