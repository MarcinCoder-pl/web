<?php

namespace Tools_Network;
if (!defined('ACCESS_DOOR')) {
    die('Brak dostępu.');
	}
class Database 
{
    private $connection;

    public function __construct($host, $username, $password, $dbname, $formatText = "utf8mb4") 
    {
        $this->connection = new \mysqli($host, $username, $password, $dbname);
        if ($this->connection->connect_error) {
            die("Błąd połączenia: " . $this->connection->connect_error);
        }
        $this->connection->set_charset($formatText);
    }

    public function setCharset($charset) 
    {
        $this->connection->set_charset($charset);
    }
//////////////////////////////////////////////////////////////////////////////////////////////////////////////
    public function prepareAndExecute($query, $params = []) 												//
    {																										//
        $stmt = $this->connection->prepare($query);															//
        if (!$stmt) {																						//
            die("Nie udało się przygotować zapytania: " . $this->connection->error);						//
        }																									//
																											//
        if (!empty($params)) {																				//
            $types = $this->getParamTypes($params);															//
            $stmt->bind_param($types, ...$params);															//
        }																									//
																											//
        $stmt->execute();																					//
        $result = $stmt->get_result();																		//
																											//
        $output = $result ?: true;																			//
																											//
        $stmt->close();																						//
																											//
        return $output;																						//
    }																										//
																											//
    private function getParamTypes($params) 																//
    {																										//
        $types = '';																						//
        foreach ($params as $param) {																		//
            if (is_int($param)) {																			//
                $types .= 'i';																				//
            } elseif (is_float($param)) {																	//
                $types .= 'd';																				//
            } elseif (is_bool($param)) {																	//
                $types .= 'i';																				//
            } elseif (is_null($param)) {																	//
                $types .= 's';																				//
            } else {																						//
                $types .= 's';																				//
            }																								//
        }																									//
        return $types;																						//
    }																										//
//////////////////////////////////////////////////////////////////////////////////////////////////////////////


    /**
     * Wykonuje zapytanie SELECT i zwraca wiele rekordów jako tablicę asocjacyjną.
     */
    public function select(string $query, array $params = []): array
    {
        $result = $this->prepareAndExecute($query, $params);
        return $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
    }

    /**
     * Wykonuje zapytanie SELECT i zwraca jeden rekord lub null, jeśli brak wyniku.
     */
    public function selectOne(string $query, array $params = []): ?array
    {
        $result = $this->prepareAndExecute($query, $params);
        return $result ? $result->fetch_assoc() : null;
    }

    /**
     * Wykonuje zapytanie INSERT i zwraca ID ostatnio dodanego rekordu.
     */
    public function insert(string $query, array $params = []): int
    {
        $this->prepareAndExecute($query, $params);
        return $this->connection->insert_id;
    }

    /**
     * Wykonuje zapytanie UPDATE i zwraca liczbę zmienionych wierszy.
     */
    public function update(string $query, array $params = []): int
    {
        $stmt = $this->connection->prepare($query);
        if (!$stmt) {
            die("Błąd przygotowania zapytania: " . $this->connection->error);
        }

        if (!empty($params)) {
            $types = $this->getParamTypes($params);
            $stmt->bind_param($types, ...$params);
        }

        $stmt->execute();
        $affected = $stmt->affected_rows;
        $stmt->close();
        return $affected;
    }

    /**
     * Wykonuje zapytanie DELETE i zwraca liczbę usuniętych wierszy.
     */
    public function delete(string $query, array $params = []): int
    {
        return $this->update($query, $params);
    }


    public function beginTransaction()
    {
        $this->connection->begin_transaction();
    }

    public function commit()
    {
        $this->connection->commit();
    }

    public function rollback()
    {
        $this->connection->rollback();
    }

    public function getConnection()
    {
        return $this->connection;
    }

    public function __destruct() 
    {
        if ($this->connection) {
            $this->connection->close();
        }
    }
}
