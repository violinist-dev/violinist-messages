<?php

namespace eiriksm\ViolinistMessages;

class ViolinistMessages
{

    /**
     * @var \Twig_Environment
     */
    private $twig;

    /**
     * ViolinistMessages constructor.
     */
    public function __construct()
    {
        $loader = new \Twig_Loader_Filesystem(__DIR__ . '/../templates');
        $this->twig = new \Twig_Environment($loader);
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
