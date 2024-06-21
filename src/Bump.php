<?php

namespace Tjenamors\Semversion;

class Bump
{
    public function __construct(private Version $version)
    {
    }

    /**
     * @return string
     */
    public function major(): string
    {
        $this->version->major++;
        $this->version->minor = 0;
        $this->version->patch = 0;
        $this->version->preRelease = null;
        $this->version->build = null;
        return $this->version->getVersion();
    }
    /**
     * @return string
     */
    public function minor(): string
    {
        $this->version->minor++;
        $this->version->patch = 0;
        $this->version->preRelease = null;
        $this->version->build = null;
        return $this->version->getVersion();
    }
    /**
     * @return string
     */
    public function patch(): string
    {
        $this->version->patch++;
        $this->version->preRelease = null;
        $this->version->build = null;
        return $this->version->getVersion();
    }
    /**
     * @return string
     */
    public function preRelease(): string
    {
        if ($this->version->preRelease === null) {
            $this->version->preRelease = PreReleaseType::ALPHA->value;
        } else {
            $parts = explode('.', $this->version->preRelease);
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
            $this->version->preRelease = implode('.', $parts);
        }

        return $this->version->getVersion();
    }
    /**
     * @param string $build
     * @return string
     */
    public function setBuild(string $build): string
    {
        $this->version->build = $build;
        return $this->version->getVersion();
    }

}
