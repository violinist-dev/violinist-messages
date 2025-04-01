<?php

namespace eiriksm\ViolinistMessages;

class ViolinistMessages
{

    /**
     * @var \Twig\Environment
     */
    private $twig;

    /**
     * ViolinistMessages constructor.
     */
    public function __construct()
    {
         $loader = new \Twig\Loader\FilesystemLoader(__DIR__ . '/../templates');
         $this->twig = new \Twig\Environment($loader, []);
    }

    /**
     * Create title from the legacy format.
     *
     * @param array $item
     *
     * @return string
     */
    public function getPullRequestTitleLegacy($item)
    {
        $msg = ViolinistUpdate::fromLegacyFormat($item);
        return $this->getPullRequestTitle($msg);
    }

    /**
     * Create body from the legacy format.
     *
     * @param array $item
     *
     * @return string
     */
    public function getPullRequestBodyLegacy($item)
    {
        $msg = ViolinistUpdate::fromLegacyFormat($item);
        return $this->getPullRequestBody($msg);
    }

  /**
   * @param \eiriksm\ViolinistMessages\ViolinistUpdate[] $update_list
   */
    public function getPullRequestBodyForGroup(string $group_name, array $update_list = [])
    {
        $twig = $this->twig->load('pull-request-body-group.twig');
        $update_list_array = [];
        foreach ($update_list as $update) {
            $update_list_array[] = [
                'name' => $update->getName(),
                'release_notes' => $update->getPackageReleaseNotes(),
                'changed_files' => $update->getChangedFiles(),
                'changelog' => $update->getChangelog(),
                'version_from' => $update->getCurrentVersion(),
                'version_to' => $update->getNewVersion(),
            ];
        }
        return $twig->render([
            'update_list' => $update_list_array,
            'group_name' => $group_name,
        ]);
    }

    /**
     * @param \eiriksm\ViolinistMessages\ViolinistUpdate $msg
     *
     * @return string
     */
    public function getPullRequestBody(ViolinistUpdate $msg)
    {
        $twig = $this->twig->load('pull-request-body.twig');
        return $twig->render([
            'updated_list' => $this->getUpdatedList($msg->getUpdatedList()),
            'title' => $this->getPullRequestTitle($msg),
            'changelog' => $msg->getChangelog(),
            'changed_files' => $msg->getChangedFiles(),
            'release_notes' => $msg->getPackageReleaseNotes(),
            'custom_message' => $msg->getCustomMessage(),
            'package' => $msg->getName(),
        ]);
    }

    public function getPullRequestClosedMessage($new_pr_id)
    {
        $twig = $this->twig->load('pull-request-closed-message.twig');
        return $twig->render([
            'new_pr_id' => $new_pr_id,
        ]);
    }

    /**
     * @param UpdateListItem[] $list
     */
    protected function getUpdatedList(array $list)
    {
        // Create some nice looking markdown for this.
        $lines = [];
        foreach ($list as $item) {
            if ($item->isNew()) {
                $lines[] = sprintf('- %s: %s (new package, previously not installed)', $item->getPackageName(), $item->getNewVersion());
            } else if ($item->isRemoved()) {
                $lines[] = sprintf('- %s %s (package was removed)', $item->getPackageName(), $item->getNewVersion());
            } else {
                $lines[] = sprintf('- %s: %s (updated from %s)', $item->getPackageName(), $item->getNewVersion(), $item->getOldVersion());
            }
        }
        return implode("\n", $lines);
    }

    /**
     * @param \eiriksm\ViolinistMessages\ViolinistUpdate $msg
     *
     * @return string
     */
    public function getPullRequestTitle(ViolinistUpdate $msg)
    {
        return $this->twig->load('pull-request-title.twig')->render([
            'security_prefix' => $msg->isSecurityUpdate() ? '[SECURITY] ' : '',
            'name' => $msg->getName(),
            'current_version' => $msg->getCurrentVersion(),
            'new_version' => $msg->getNewVersion(),
        ]);
    }
}
