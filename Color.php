<?php

namespace CustomEntity;

class Color
{
    /**
     * Versão alternativa usando HSL (mais fiel ao Sass)
     */
    static function lighten($hex, $percentage)
    {
        $hsl = self::hexToHsl($hex);
        $hsl['l'] = min(1, $hsl['l'] + ($percentage / 100));
        return self::hslToHex($hsl);
    }

    static function darken($hex, $percentage)
    {
        $hsl = self::hexToHsl($hex);
        $hsl['l'] = max(0, $hsl['l'] - ($percentage / 100));
        return self::hslToHex($hsl);
    }

    /**
     * Converte cor hexadecimal para RGB
     */
    protected static function hexToRgb($hex)
    {
        $hex = str_replace('#', '', $hex);

        if (strlen($hex) == 3) {
            $r = hexdec(substr($hex, 0, 1) . substr($hex, 0, 1));
            $g = hexdec(substr($hex, 1, 1) . substr($hex, 1, 1));
            $b = hexdec(substr($hex, 2, 1) . substr($hex, 2, 1));
        } else {
            $r = hexdec(substr($hex, 0, 2));
            $g = hexdec(substr($hex, 2, 2));
            $b = hexdec(substr($hex, 4, 2));
        }

        return ['r' => $r, 'g' => $g, 'b' => $b];
    }

    /**
     * Converte RGB para hexadecimal
     */
    protected static function rgbToHex($rgb)
    {
        $hex = '#';
        $hex .= str_pad(dechex($rgb['r']), 2, '0', STR_PAD_LEFT);
        $hex .= str_pad(dechex($rgb['g']), 2, '0', STR_PAD_LEFT);
        $hex .= str_pad(dechex($rgb['b']), 2, '0', STR_PAD_LEFT);

        return $hex;
    }

    /**
     * Ajusta um componente de cor individual
     */
    protected static function adjustColorComponent($component, $amount, $operation)
    {
        if ($operation === 'lighten') {
            return min(255, $component + ($amount * 255 / 100));
        } else {
            return max(0, $component - ($amount * 255 / 100));
        }
    }

    /**
     * Funções auxiliares para conversão HSL
     */
    protected static function hexToHsl($hex)
    {
        $rgb = self::hexToRgb($hex);
        $r = $rgb['r'] / 255;
        $g = $rgb['g'] / 255;
        $b = $rgb['b'] / 255;

        $max = max($r, $g, $b);
        $min = min($r, $g, $b);

        $h = $s = $l = ($max + $min) / 2;

        if ($max == $min) {
            $h = $s = 0;
        } else {
            $d = $max - $min;
            $s = $l > 0.5 ? $d / (2 - $max - $min) : $d / ($max + $min);

            switch ($max) {
                case $r:
                    $h = ($g - $b) / $d + ($g < $b ? 6 : 0);
                    break;
                case $g:
                    $h = ($b - $r) / $d + 2;
                    break;
                case $b:
                    $h = ($r - $g) / $d + 4;
                    break;
            }

            $h /= 6;
        }

        return ['h' => $h, 's' => $s, 'l' => $l];
    }

    protected static function hslToRgb($hsl)
    {
        $h = $hsl['h'];
        $s = $hsl['s'];
        $l = $hsl['l'];

        if ($s == 0) {
            $r = $g = $b = $l;
        } else {
            $q = $l < 0.5 ? $l * (1 + $s) : $l + $s - $l * $s;
            $p = 2 * $l - $q;

            $r = self::hueToRgb($p, $q, $h + 1 / 3);
            $g = self::hueToRgb($p, $q, $h);
            $b = self::hueToRgb($p, $q, $h - 1 / 3);
        }

        return [
            'r' => round($r * 255),
            'g' => round($g * 255),
            'b' => round($b * 255)
        ];
    }

    protected static function hueToRgb($p, $q, $t)
    {
        if ($t < 0) $t += 1;
        if ($t > 1) $t -= 1;
        if ($t < 1 / 6) return $p + ($q - $p) * 6 * $t;
        if ($t < 1 / 2) return $q;
        if ($t < 2 / 3) return $p + ($q - $p) * (2 / 3 - $t) * 6;
        return $p;
    }

    protected static function hslToHex($hsl)
    {
        $rgb = self::hslToRgb($hsl);
        return self::rgbToHex($rgb);
    }
}
