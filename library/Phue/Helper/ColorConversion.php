<?php
/**
 * Phue: Philips Hue PHP Client
 *
 * @author    Michael Squires <sqmk@php.net>
 * @copyright Copyright (c) 2012 Michael K. Squires
 * @license   http://github.com/sqmk/Phue/wiki/License
 */
namespace Phue\Helper;

/**
 * Helper for converting colors to and from rgb
 */
class ColorConversion
{
    // Constants for the Wide RGB D65 conversion formula
    const RGB_TO_XYZ_COEFFICIENTS = [
        'red' => [0.664511, 0.154324, 0.162028],
        'green' => [0.283881, 0.668433, 0.047685],
        'blue' => [0.000000, 0.072310, 0.986039]
    ];

    const XYZ_TO_RGB_COEFFICIENTS = [
        'red' => [1.656492, -0.354851, -0.255038],
        'green' => [-0.707196, 1.655397, 0.036152],
        'blue' => [0.051713, -0.121364, 1.011530]
    ];

    // Gamma correction constants
    const GAMMA_THRESHOLD = 0.04045;
    const GAMMA_SCALE = 12.92;
    const GAMMA_EXPONENT = 2.4;
    const GAMMA_OFFSET = 0.055;
    
    const REVERSE_GAMMA_THRESHOLD = 0.0031308;
    const REVERSE_GAMMA_SCALE = 1.0 / 2.4;

    /**
     * Converts RGB values to XY values
     * Based on: http://stackoverflow.com/a/22649803
     *
     * @param int $red   Red value
     * @param int $green Green value
     * @param int $blue  Blue value
     *
     * @return array x, y, bri key/value
     */
    public static function convertRGBToXY(int $red, int $green, int $blue): array
    {
        // Normalize the values to 1
        $normalizedToOne = [
            'red' => $red / 255,
            'green' => $green / 255,
            'blue' => $blue / 255
        ];

        // Make colors more vivid
        foreach ($normalizedToOne as $key => $normalized) {
            $color[$key] = $normalized > self::GAMMA_THRESHOLD
                ? pow(($normalized + self::GAMMA_OFFSET) / (1.0 + self::GAMMA_OFFSET), self::GAMMA_EXPONENT)
                : $normalized / self::GAMMA_SCALE;
        }

        // Convert to XYZ using the Wide RGB D65 formula
        $xyz = [
            'x' => $color['red'] * self::RGB_TO_XYZ_COEFFICIENTS['red'][0] + $color['green'] * self::RGB_TO_XYZ_COEFFICIENTS['green'][0] + $color['blue'] * self::RGB_TO_XYZ_COEFFICIENTS['blue'][0],
            'y' => $color['red'] * self::RGB_TO_XYZ_COEFFICIENTS['red'][1] + $color['green'] * self::RGB_TO_XYZ_COEFFICIENTS['green'][1] + $color['blue'] * self::RGB_TO_XYZ_COEFFICIENTS['blue'][1],
            'z' => $color['red'] * self::RGB_TO_XYZ_COEFFICIENTS['red'][2] + $color['green'] * self::RGB_TO_XYZ_COEFFICIENTS['green'][2] + $color['blue'] * self::RGB_TO_XYZ_COEFFICIENTS['blue'][2]
        ];

        // Calculate the x/y values
        $sum = array_sum($xyz);
        if ($sum == 0) {
            $x = $y = 0;
        } else {
            $x = $xyz['x'] / $sum;
            $y = $xyz['y'] / $sum;
        }

        return [
            'x'   => $x,
            'y'   => $y,
            'bri' => round($xyz['y'] * 255)
        ];
    }

    /**
     * Converts XY (and brightness) values to RGB
     *
     * @param float $x X value
     * @param float $y Y value
     * @param int $bri Brightness value
     *
     * @return array red, green, blue key/value
     */
    public static function convertXYToRGB(float $x, float $y, int $bri = 255): array
    {
        // Calculate XYZ
        $z = 1.0 - $x - $y;
        $xyz = [
            'y' => $bri / 255,
            'x' => ($xyz['y'] / $y) * $x,
            'z' => ($xyz['y'] / $y) * $z
        ];

        // Convert to RGB using Wide RGB D65 conversion
        $color = [
            'red' => $xyz['x'] * self::XYZ_TO_RGB_COEFFICIENTS['red'][0] + $xyz['y'] * self::XYZ_TO_RGB_COEFFICIENTS['red'][1] + $xyz['z'] * self::XYZ_TO_RGB_COEFFICIENTS['red'][2],
            'green' => $xyz['x'] * self::XYZ_TO_RGB_COEFFICIENTS['green'][0] + $xyz['y'] * self::XYZ_TO_RGB_COEFFICIENTS['green'][1] + $xyz['z'] * self::XYZ_TO_RGB_COEFFICIENTS['green'][2],
            'blue' => $xyz['x'] * self::XYZ_TO_RGB_COEFFICIENTS['blue'][0] + $xyz['y'] * self::XYZ_TO_RGB_COEFFICIENTS['blue'][1] + $xyz['z'] * self::XYZ_TO_RGB_COEFFICIENTS['blue'][2]
        ];

        foreach ($color as $key => $normalized) {
            // Apply reverse gamma correction
            if ($normalized <= self::REVERSE_GAMMA_THRESHOLD) {
                $color[$key] = self::GAMMA_SCALE * $normalized;
            } else {
                $color[$key] = (1.0 + self::GAMMA_OFFSET) * pow($normalized, self::REVERSE_GAMMA_SCALE) - self::GAMMA_OFFSET;
            }

            // Scale back from a maximum of 1 to a maximum of 255
            $color[$key] = round($color[$key] * 255);
        }

        return $color;
    }
}