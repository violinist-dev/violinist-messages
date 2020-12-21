<?php

namespace eiriksm\ViolinistMessages;

class UpdateListItem
{
    private $packageName;
    private $oldVersion;
    private $newVersion;
    private $isNew = false;
    private $isRemoved = false;

    public function __construct($packageName, $newVersion, $oldVersion = null)
    {
        $this->packageName = $packageName;
        $this->newVersion = $newVersion;
        $this->oldVersion = $oldVersion;
        if (!$this->oldVersion) {
            // Just assume it is new then?
            $this->setIsNew(true);
        }
    }

    /**
     * @return bool
     */
    public function isRemoved(): bool
    {
        return $this->isRemoved;
    }

    /**
     * @param bool $isNew
     */
    public function setIsRemoved(bool $isRemoved)
    {
        $this->isRemoved = $isRemoved;
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
     * @return mixed
     */
    public function getOldVersion()
    {
        return $this->oldVersion;
    }

    /**
     * @return mixed
     */
    public function getNewVersion()
    {
        return $this->newVersion;
    }
}
