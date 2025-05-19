<?php
class ErrorMessageProvider {
    private $db;
    private $lang;

    public function __construct(Database $db, $lang ) {
        $this->db = $db;
        $this->lang = in_array($lang, ['pl', 'en', 'cs']) ? $lang : 'pl';
    }

    public function getMessage($code) {
        $result = $this->db->query("SELECT message_{$this->lang} AS message FROM registration_errors WHERE error_code = ?", "s", [$code]);
        if ($row = $result->fetch_assoc()) {
            return $row['message'];
        }
        return "Nieznany błąd (Unknown error)";
    }
}
