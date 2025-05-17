<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Formats a date string safely, returning an empty string if the date is invalid.
 *
 * @param string $date   The date string to format (e.g., '2025-05-17')
 * @param string $format The desired output format (default: 'Y-m-d')
 * @return string The formatted date or an empty string if invalid
 */
if (!function_exists('formatSafeDate')) {
    function formatSafeDate($date, $format = 'Y-m-d') {
        if (!empty($date) && $date !== '0000-00-00' && strtotime($date)) {
            return date($format, strtotime($date));
        }
        return '';
    }
}

/**
 * Cleans a search value by removing problematic characters and optionally splitting company and contact names.
 *
 * @param mixed $value The value to clean
 * @return string The cleaned value
 */
if (!function_exists('cleanSearchValue')) {
    function cleanSearchValue($value) {
        // Return empty string if the value is null or empty
        if (empty($value) || !is_string($value)) {
            return '';
        }

        // Trim whitespace
        $value = trim($value);

        // Replace '??' with a space or dash
        $value = preg_replace('/\?\?/', ' - ', $value);

        // Remove excessive whitespace
        $value = preg_replace('/\s+/', ' ', $value);

        // Remove non-printable characters or excessive punctuation
        $value = preg_replace('/[^\w\s.-]/', '', $value);

        // Split into company and contacts if a separator is present
        $parts = preg_split('/\s*-\s*/', $value, 2);
        if (count($parts) > 1) {
            $company = trim($parts[0]);
            $contacts = trim($parts[1]);
            // Keep only the company name
            $value = $company;
        }

        return $value;
    }
}