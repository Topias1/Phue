<?php
namespace Phue\Command;

use Phue\Client;
use Phue\Helper\ColorConversion;
use Phue\Transport\TransportInterface;

class SetLightState implements CommandInterface, ActionableInterface
{
    // Constants definitions (unchanged)

    protected string $lightId;
    protected array $params = [];

    public static function getAlertModes(): array
    {
        return [
            self::ALERT_NONE,
            self::ALERT_SELECT,
            self::ALERT_LONG_SELECT
        ];
    }

    public static function getEffectModes(): array
    {
        return [
            self::EFFECT_NONE,
            self::EFFECT_COLORLOOP
        ];
    }

    public function __construct($light)
    {
        $this->lightId = (string) $light;
    }

    public function on(bool $flag = true): self
    {
        $this->params['on'] = $flag;
        return $this;
    }

    public function brightness(int $level = self::BRIGHTNESS_MAX): self
    {
        if (! (self::BRIGHTNESS_MIN <= $level && $level <= self::BRIGHTNESS_MAX)) {
            throw new \InvalidArgumentException(
                "Brightness must be between " . self::BRIGHTNESS_MIN . " and " . self::BRIGHTNESS_MAX
            );
        }

        $this->params['bri'] = $level;
        return $this;
    }

    public function hue(int $value): self
    {
        if (! (self::HUE_MIN <= $value && $value <= self::HUE_MAX)) {
            throw new \InvalidArgumentException(
                "Hue value must be between " . self::HUE_MIN . " and " . self::HUE_MAX
            );
        }

        $this->params['hue'] = $value;
        return $this;
    }

    public function saturation(int $value): self
    {
        if (! (self::SATURATION_MIN <= $value && $value <= self::SATURATION_MAX)) {
            throw new \InvalidArgumentException(
                "Saturation value must be between " . self::SATURATION_MIN . " and " . self::SATURATION_MAX
            );
        }

        $this->params['sat'] = $value;
        return $this;
    }

    public function xy(float $x, float $y): self
    {
        foreach ([$x, $y] as $value) {
            if (! (self::XY_MIN <= $value && $value <= self::XY_MAX)) {
                throw new \InvalidArgumentException(
                    "x/y value must be between " . self::XY_MIN . " and " . self::XY_MAX
                );
            }
        }

        $this->params['xy'] = [$x, $y];
        return $this;
    }

    public function rgb(int $red, int $green, int $blue): self
    {
        foreach ([$red, $green, $blue] as $value) {
            if (! (self::RGB_MIN <= $value && $value <= self::RGB_MAX)) {
                throw new \InvalidArgumentException(
                    "RGB values must be between " . self::RGB_MIN . " and " . self::RGB_MAX
                );
            }
        }

        $xy = ColorConversion::convertRGBToXY($red, $green, $blue);
        return $this->xy($xy['x'], $xy['y'])->brightness($xy['bri']);
    }

    public function colorTemp(int $value): self
    {
        if (! (self::COLOR_TEMP_MIN <= $value && $value <= self::COLOR_TEMP_MAX)) {
            throw new \InvalidArgumentException(
                "Color temperature value must be between " . self::COLOR_TEMP_MIN . " and " . self::COLOR_TEMP_MAX
            );
        }

        $this->params['ct'] = $value;
        return $this;
    }

    public function alert(string $mode = self::ALERT_LONG_SELECT): self
    {
        if (! in_array($mode, self::getAlertModes())) {
            throw new \InvalidArgumentException("{$mode} is not a valid alert mode");
        }

        $this->params['alert'] = $mode;
        return $this;
    }

    public function effect(string $mode = self::EFFECT_COLORLOOP): self
    {
        if (! in_array($mode, self::getEffectModes())) {
            throw new \InvalidArgumentException("{$mode} is not a valid effect mode");
        }

        $this->params['effect'] = $mode;
        return $this;
    }

    public function transitionTime(float $seconds): self
    {
        if ($seconds < 0) {
            throw new \InvalidArgumentException("Time must be at least 0");
        }

        $this->params['transitiontime'] = (int) ($seconds * 10);
        return $this;
    }

    public function send(Client $client): mixed
    {
        $params = $this->getActionableParams($client);
        $client->getTransport()->sendRequest(
            "/api/{$client->getUsername()}" . $params['address'],
            $params['method'],
            $params['body']
        );
    }

    public function getActionableParams(Client $client): array
    {
        return [
            'address' => "/lights/{$this->lightId}/state",
            'method' => TransportInterface::METHOD_PUT,
            'body' => (object) $this->params
        ];
    }
}