<?php

namespace eiriksm\ViolinistMessages;

class UpdateListItem
{
    private $packageName;
    private $oldVersion;
    private $newVersion;
    private $isNew = false;

    public function __construct($packageName, $newVersion, $oldVersion = null)
    {
        $this->packageName = $packageName;
        $this->newVersion = $newVersion;
        $this->oldVersion = $oldVersion;
        if (!$this->oldVersion) {
            // Just assume it is new then?
            $this->isNew = true;
        }
    }

    /**
     * @return bool
     */
    public function isNew(): bool
    {
        return $this->isNew;
    }

    /**
     * @param bool $isNew
     */
    public function setIsNew(bool $isNew)
    {
        $this->isNew = $isNew;
    }

    /**
     * @return mixed
     */
    public function getPackageName()
    {
        return $this->packageName;
    }

    /**
     * @param mixed $packageName
     */
    public function setPackageName($packageName)
    {
        $this->packageName = $packageName;
    }

    /**
     * @return mixed
     */
    public function getOldVersion()
    {
        return $this->oldVersion;
    }

    /**
     * @param mixed $oldVersion
     */
    public function setOldVersion($oldVersion)
    {
        $this->oldVersion = $oldVersion;
    }

    /**
     * @return mixed
     */
    public function getNewVersion()
    {
        return $this->newVersion;
    }

    /**
     * @param mixed $newVersion
     */
    public function setNewVersion($newVersion)
    {
        $this->newVersion = $newVersion;
    }
}
