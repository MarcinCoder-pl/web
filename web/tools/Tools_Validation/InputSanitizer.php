<?php
namespace Tools_Validation;

class InputSanitizer
{
    /**
     * Zapewnia, że tekst jest UTF-8.
     *
     * @param string $input
     * @return string
     */
    public function ensureUtf8(string $input): string
    {
        $encoding = mb_detect_encoding($input, ['UTF-8', 'ISO-8859-1', 'ISO-8859-2', 'Windows-1252', 'Windows-1251', 'ASCII'], true);

        if ($encoding === false) {
            $encoding = 'ISO-8859-1';
        }

        if (strtoupper($encoding) !== 'UTF-8') {
            $input = mb_convert_encoding($input, 'UTF-8', $encoding);
        }

        return $input;
    }

    /**
     * Usuwa niewidoczne znaki.
     *
     * @param string $input
     * @return string
     */
    public function removeInvisibleCharacters(string $input): string
    {
        return preg_replace('/[\x00-\x1F\x7F\x{00AD}\x{200B}-\x{200F}]/u', '', $input);
    }

    /**
     * Pełna sanitizacja pojedynczego tekstu.
     *
     * @param string $input
     * @return string
     */
    public function sanitizeFull(string $input): string
    {
        $utf8 = $this->ensureUtf8($input);
        $clean = $this->removeInvisibleCharacters($utf8);
        return trim($clean);
    }

    /**
     * Rekurencyjnie sanitizuje tablicę.
     *
     * @param array $inputArray
     * @return array
     */
    public function sanitizeArrayUtf8(array $inputArray): array
    {
        foreach ($inputArray as $key => $value) {
            if (is_array($value)) {
                $inputArray[$key] = $this->sanitizeArrayUtf8($value);
            } elseif (is_string($value)) {
                $inputArray[$key] = $this->sanitizeFull($value);
            }
        }

        return $inputArray;
    }
}
