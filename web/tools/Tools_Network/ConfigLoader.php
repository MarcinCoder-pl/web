<?php
namespace Tools_Network;


class ConfigLoader
{
    private array $config = [];

    /**
     * Wczytuje konfigurację z pliku ini.
     * @param string $filePath
     * @throws Exception jeśli plik nie istnieje lub błąd parsowania
     */
    public function __construct(string $filePath)
    {
        if (!file_exists($filePath)) {
            throw new Exception("Plik konfiguracyjny nie istnieje: {$filePath}");
        }

        $parsed = parse_ini_file($filePath, true);
        if ($parsed === false) {
            throw new Exception("Nie udało się wczytać konfiguracji z pliku: {$filePath}");
        }

        $this->config = $parsed;
    }

    /**
     * Pobiera ustawienie z sekcji i klucza
     * @param string $section
     * @param string $key
     * @return string|null
     */
    public function get(string $section, string $key): ?string
    {
        return $this->config[$section][$key] ?? null;
    }

    /**
     * Tworzy i zwraca obiekt Database na podstawie ustawień z pliku.
     * @return Database
     * @throws Exception jeśli brakuje któregoś z ustawień
     */
    public function createDatabase(): Database
    {
        $host = $this->get('database', 'host');
        $username = $this->get('database', 'username');
        $password = $this->get('database', 'password');
        $dbname = $this->get('database', 'dbname');

        if (!$host || !$username || !$dbname) {
            throw new Exception("Brak wymaganych ustawień bazy danych w konfiguracji.");
        }

        return new Database($host, $username, $password ?? '', $dbname);
    }
}
