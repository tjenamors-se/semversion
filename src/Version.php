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
    protected string $fallbackVersion = '0.0.0';


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
        $version = "{$this->major}.{$this->minor}.{$this->patch}";

        if (!empty($this->preRelease)) {
            $version .= "-{$this->preRelease}";
        }

        if (!empty($this->build)) {
            $version .= "+{$this->build}";
        }

        return $version;
    }
    /**
     * @return string
     */
    public function bumpMajor(): string
    {
        $this->major++;
        $this->minor = 0;
        $this->patch = 0;
        $this->preRelease = null;
        $this->build = null;
        return $this->getVersion();
    }
    /**
     * @return string
     */
    public function bumpMinor(): string
    {
        $this->minor++;
        $this->patch = 0;
        $this->preRelease = null;
        $this->build = null;
        return $this->getVersion();
    }
    /**
     * @return string
     */
    public function bumpPatch(): string
    {
        $this->patch++;
        $this->preRelease = null;
        $this->build = null;
        return $this->getVersion();
    }
    /**
     * @return string
     */
    public function bumpPreRelease(): string
    {
        if ($this->preRelease === null) {
            $this->preRelease = PreReleaseType::ALPHA->value;
        } else {
            $parts = explode('.', $this->preRelease);
            $lastPart = array_pop($parts);

            if (is_numeric($lastPart)) {
                $lastPart++;
            } else {
                if (preg_match('/(\d+)$/', $lastPart, $matches)) {
                    $number = (int)$matches[1] + 1;
                    $lastPart = preg_replace('/\d+$/', "{$number}", $lastPart);
                } else {
                    $lastPart .= '.1';
                }
            }

            $parts[] = $lastPart;
            $this->preRelease = implode('.', $parts);
        }

        return $this->getVersion();
    }
    /**
     * @param string $build
     * @return string
     */
    public function setBuild(string $build): string
    {
        $this->build = $build;
        return $this->getVersion();
    }
}
