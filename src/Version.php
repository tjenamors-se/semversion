<?php

namespace Tjenamors\Semversion;

class Version
{
    public string $version;
    public int $major;
    public int $minor;
    public int $patch;
    public string|null $preRelease;
    public string|null $build;
    public string $pattern = '/^v?(0|[1-9]\d*)\.(0|[1-9]\d*)\.(0|[1-9]\d*)(?:-((?:0|[1-9]\d*|[a-zA-Z-][0-9a-zA-Z-]*)(?:\.(?:0|[1-9]\d*|[a-zA-Z-][0-9a-zA-Z-]*))*))?(?:\+([0-9a-zA-Z-]+(?:\.[0-9a-zA-Z-]+)*))?$/';
    protected string $fallbackVersion = 'v0.0.0';


    public function __construct(string $version)
    {
        $this->version = $this->validate($version) ? $version : $this->fallbackVersion;
        $this->parseVersion();
    }

    /**
      * @param string $version
      * @return bool
      */
    public function validate(string $version): bool
    {
        return preg_match($this->pattern, $version) === 1;
    }

    /**
      * @return void
      */
    protected function parseVersion(): void
    {
        if(!$this->validate($this->version)) {
            throw new \InvalidArgumentException('Invalid version string');
        }

        if (preg_match($this->pattern, $this->version, $matches)) {
            $this->major = (int)$matches[1];
            $this->minor = (int)$matches[2];
            $this->patch = (int)$matches[3];
            $this->preRelease = isset($matches[4]) ? $matches[4] : null;
            $this->build = isset($matches[5]) ? $matches[5] : null;
        }
    }
    /**
     * @return string
     */
    public function getVersion(): string
    {
        $version = "v{$this->major}.{$this->minor}.{$this->patch}";

        if (!empty($this->preRelease)) {
            $version .= "-{$this->preRelease}";
        }

        if (!empty($this->build)) {
            $version .= "+{$this->build}";
        }

        $this->parseVersion();

        return $version;
    }
}
