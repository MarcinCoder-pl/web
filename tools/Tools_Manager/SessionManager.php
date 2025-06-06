<?php
namespace Tools_Manager;

if (!defined('ACCESS_DOOR')) {
    die('Brak dostępu.');
}

class SessionManager {
    private static ?SessionManager $instance = null;

    private string $sessionName;
    private int $timeout;

    private function __construct(string $sessionName = 'myapp_session', int $timeout = 1800) {
        $this->sessionName = $sessionName;
        $this->timeout = $timeout;

        if (session_status() === PHP_SESSION_NONE) {
            $this->start();
        }
    }

    public static function getInstance(string $sessionName = 'myapp_session', int $timeout = 1800): self {
        if (self::$instance === null) {
            self::$instance = new self($sessionName, $timeout);
        }
        return self::$instance;
    }

    public function start(): void {
        if (session_status() === PHP_SESSION_NONE) {
            session_name($this->sessionName);
            session_set_cookie_params([
                'lifetime' => $this->timeout,
                'path' => '/',
                'domain' => '',
                'secure' => (!$this->isCli() && !empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off'),
                'httponly' => true,
                'samesite' => 'Lax'
            ]);
            session_start();
            $this->checkTimeout();
            $this->resetSessionIfInvalid();
        }
    }

	public function set(string|array $key, mixed $value = null): void {
		if (is_array($key)) {
			// Obsługa tablicy: wiele par klucz => wartość
			foreach ($key as $k => $v) {
				$_SESSION[$k] = $v;
			}
		} else {
			// Pojedyncza wartość
			$_SESSION[$key] = $value;
		}
	}


    public function get(string $key): mixed {
        return $_SESSION[$key] ?? null;
    }

    public function delete(string $key): void {
        unset($_SESSION[$key]);
    }

    public function has(string $key): bool {
        return isset($_SESSION[$key]);
    }

    public function isLoggedIn(): bool {
        return isset($_SESSION['user_id']);
    }

public function login(int|string $userId, string $username = ''): void
{
    $this->set([
        'user_id' => $userId,
        'username' => $username
    ]);
    $this->regenerateSessionId();
}


   
    ///

    public function regenerateSessionId(): void {
        session_regenerate_id(true);
        $this->setSecurityChecks();
    }

    public function logout(): void {
        $_SESSION = [];
        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000,
                $params["path"], $params["domain"],
                $params["secure"], $params["httponly"]
            );
        }
        session_destroy();
        session_write_close();
    }

    public function logoutSecure(string $token, string $action = 'logout'): bool {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && $this->validateCsrfToken($action, $token)) {
            $this->logout();
            return true;
        }
        return false;
    }

    private function checkTimeout(): void {
        if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity']) > $this->timeout) {
            $this->logout();
        } else {
            $_SESSION['last_activity'] = time();
            $this->autoRegenerateSessionId();
        }
    }

    private function autoRegenerateSessionId(): void {
        if (!isset($_SESSION['last_regenerate']) || time() - $_SESSION['last_regenerate'] > 300) {
            $this->regenerateSessionId();
            $_SESSION['last_regenerate'] = time();
        }
    }

    public function generateCsrfToken(string $action): string {
        return AuthToken::generate($action);
    }

    public function validateCsrfToken(string $action, string $token): bool {
        return AuthToken::validate($action, $token);
    }

    public function clearCsrfToken(string $action): void {
        AuthToken::clear($action);
    }

			public function getOrCreateCsrfToken(string $action): string {
				// Jeśli już istnieje i nie wygasł, zwróć
				if (AuthToken::isTokenValid($action)) {
					return $_SESSION['csrf_tokens'][$action]['token'];
				}

				// Wygeneruj nowy
				return $this->generateCsrfToken($action);
			}

    private function isCli(): bool {
        return php_sapi_name() === 'cli' || defined('STDIN');
    }

    public function setSecurityChecks(): void {
        $_SESSION['session_id_check'] = session_id();
        $_SESSION['session_ip_check'] = $this->isCli() ? '127.0.0.1' : ($_SERVER['REMOTE_ADDR'] ?? '');
        $_SESSION['session_ua_check'] = $this->isCli() ? 'CLI' : ($_SERVER['HTTP_USER_AGENT'] ?? '');
    }

    public function resetSessionIfInvalid(): void 
    {
        $currentIp = $this->isCli() ? '127.0.0.1' : ($_SERVER['REMOTE_ADDR'] ?? '');
        $currentUa = $this->isCli() ? 'CLI' : ($_SERVER['HTTP_USER_AGENT'] ?? '');

        $idMatch = isset($_SESSION['session_id_check']) && $_SESSION['session_id_check'] === session_id();
        $ipMatch = isset($_SESSION['session_ip_check']) && $_SESSION['session_ip_check'] === $currentIp;
        $uaMatch = isset($_SESSION['session_ua_check']) && $_SESSION['session_ua_check'] === $currentUa;

        if (!$idMatch || !$ipMatch || !$uaMatch) 
        {
            $this->logout();
            $this->start();
        }
    }
    
    /**
     * Zwraca pełną zawartość sesji – do wglądu użytkownika (RODO)
     */
    public function exportSessionData(): array {
        return $_SESSION;
    }

    /**
     * Usuwa wszystkie dane z sesji i kończy sesję – "prawo do bycia zapomnianym"
     */
    public function deleteSessionData(): void {
        $_SESSION = [];
        session_destroy();
        session_write_close();
    }

    
    
}
