<?php

namespace eiriksm\ViolinistMessages;

class ViolinistUpdate
{

    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $currentVersion;

    /**
     * @var string
     */
    private $newVersion;

    /**
     * @var string
     */
    private $changelog;

    /**
     * @var bool
     */
    private $securityUpdate;

    /**
     * @var string|null
     */
    private $customMessage = null;

    /**
     * @var UpdateListItem[]
     */
    private $updatedList = [];

    private $changedFiles = [];

    /**
     * @return array
     */
    public function getChangedFiles(): array
    {
        return $this->changedFiles;
    }

    /**
     * @param array $changedFiles
     */
    public function setChangedFiles(array $changedFiles)
    {
        $this->changedFiles = $changedFiles;
    }



    /**
     * @return UpdateListItem[]
     */
    public function getUpdatedList()
    {
        return $this->updatedList;
    }

    /**
     * @param UpdateListItem[] $updatedList
     */
    public function setUpdatedList(array $updatedList)
    {
        $this->updatedList = $updatedList;
    }

    /**
     * @return null|string
     */
    public function getCustomMessage()
    {
        return $this->customMessage;
    }

    /**
     * @param null|string $customMessage
     */
    public function setCustomMessage($customMessage)
    {
        $this->customMessage = $customMessage;
    }

    /**
     * @return bool
     */
    public function isSecurityUpdate()
    {
        return $this->securityUpdate;
    }

    /**
     * @param bool $securityUpdate
     */
    public function setSecurityUpdate($securityUpdate)
    {
        $this->securityUpdate = $securityUpdate;
    }

    /**
     * @return string
     */
    public function getChangelog()
    {
        return $this->changelog;
    }

    /**
     * @param string $changelog
     */
    public function setChangelog($changelog)
    {
        $this->changelog = $changelog;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getCurrentVersion()
    {
        return $this->currentVersion;
    }

    /**
     * @param string $currentVersion
     */
    public function setCurrentVersion($currentVersion)
    {
        $this->currentVersion = $currentVersion;
    }

    /**
     * @return string
     */
    public function getNewVersion()
    {
        return $this->newVersion;
    }

    /**
     * @param string $newVersion
     */
    public function setNewVersion($newVersion)
    {
        $this->newVersion = $newVersion;
    }

    /**
     * @param array $item
     *
     * @return \eiriksm\ViolinistMessages\ViolinistUpdate
     */
    public static function fromLegacyFormat(array $item)
    {
        $update = new ViolinistUpdate();
        $update->setName($item[0]);
        $update->setCurrentVersion($item[1]);
        $update->setNewVersion($item[2]);
        return $update;
    }
}
