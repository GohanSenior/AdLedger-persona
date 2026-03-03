<?php

class AvatarBuilder
{
    private string $seed;
    private array $options;
    private string $style;

    public function __construct(string $seed, array $options, string $style = 'avataaars')
    {
        $this->seed = $seed;
        $this->options = $options;
        $this->style = $style;
    }

    public function buildUrl(): string
    {
        $base = "https://api.dicebear.com/9.x/{$this->style}/svg?seed=" . urlencode($this->seed);

        foreach ($this->options as $key => $value) {
            $base .= "&{$key}={$value}";
        }

        return $base;
    }
}
