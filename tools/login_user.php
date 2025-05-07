<?php
	if (session_status() === PHP_SESSION_NONE) 
	{
        session_start();
    }
   define('ACCESS', true);
require_once 'csrf_token.php';
require_once '../config/db_config.php'; 
// DEBUG - pokaż tokeny
echo "<pre>";
echo "Token z POST: " . ($_POST['csrf_token'] ?? 'BRAK') . "\n";
echo "Token z SESJI: " . ($_SESSION['csrf_token'] ?? 'BRAK') . "\n";
echo "</pre>";

if ($_SERVER['REQUEST_METHOD'] === 'POST') 
{
    $token = $_POST['csrf_token'] ?? '';

    if (validateCsrfToken($token))
    {	clearCsrfToken();
		$username = trim($_POST['username'] ?? '');
		$password = $_POST['password'] ?? '';
		
		$conn = new mysqli($db_host, $db_user, $db_password, $db_name);
		if ($conn->connect_error) 
		{
			    die("Błąd połączenia z bazą danych: " . $conn->connect_error);
		}
		
		$stmt = $conn->prepare(LOGIN_ACC);
		$stmt->bind_param("s", $username);
		if( $stmt->execute() )
		{
			echo"wykonano zapytanie" ;
			$result = $stmt->get_result();
			if ($result->num_rows === 1) 
			{
				$row = $result->fetch_assoc();
				if (password_verify($password, $row['haslo']))
				{
					echo "Zalogowano poprawnie!";
					$_SESSION['username'] = $username;
					setcookie('username', $_SESSION['username'], time()+3600, "/", false, true );
					header('Location: ../index.php?strona=dashboard');
				}
				else
				{
					echo "Nieprawidłowe hasło.";
				}
			}
			else
			{
				echo "urzytkownik nie istnieje";
			}
		}
		else
		{
			echo "nie wykonano";
		}
		$stmt->close();
		$conn->close();
    }
    header('Location: ../index.php?strona=home');
}
