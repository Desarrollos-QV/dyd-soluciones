<?php

namespace App\Helpers;

class PhoneHelper
{
    /**
     * Formatea un número telefónico a formato E.164 para México
     * Compatible con SMS y WhatsApp (Twilio)
     */
    public static function formatMX($phone)
    {
        if (!$phone) {
            return null;
        }

        // Eliminar todo lo que no sea número
        $phone = preg_replace('/\D+/', '', $phone);

        // Si viene con 10 dígitos (local)
        if (strlen($phone) === 10) {
            return '+521' . $phone;
        }

        // Si viene con 52 + número (12 dígitos)
        if (strlen($phone) === 12 && str_starts_with($phone, '52')) {
            return '+521' . substr($phone, 2);
        }

        // Si viene con 521 (WhatsApp ready)
        if (strlen($phone) === 13 && str_starts_with($phone, '521')) {
            return '+' . $phone;
        }

        // Si viene con +
        if (strlen($phone) === 14 && str_starts_with($phone, '521')) {
            return '+' . substr($phone, 1);
        }

        // No válido
        return null;
    }

    /**
     * Valida si el número es válido para México
     */
    public static function isValidMX($phone): bool
    {
        return self::formatMX($phone) !== null;
    }
}
