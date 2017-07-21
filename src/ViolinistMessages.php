<?php

namespace eiriksm\ViolinistMessages;

class ViolinistMessages {

  /**
   * @var \Twig_Environment
   */
  private $twig;

  /**
   * ViolinistMessages constructor.
   */
  public function __construct() {
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
  public function getPullRequestTitleLegacy($item) {
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
  public function getPullRequestBodyLegacy($item) {
    $msg = ViolinistUpdate::fromLegacyFormat($item);
    return $this->getPullRequestBody($msg);
  }

  /**
   * @param \eiriksm\ViolinistMessages\ViolinistUpdate $msg
   *
   * @return string
   */
  public function getPullRequestBody(ViolinistUpdate $msg) {
    return $this->twig->load('pull-request-body.twig')->render([
      'title' => $this->getPullRequestTitle($msg),
      'changelog' => $msg->getChangelog(),
    ]);
  }

  /**
   * @param \eiriksm\ViolinistMessages\ViolinistUpdate $msg
   *
   * @return string
   */
  public function getPullRequestTitle(ViolinistUpdate $msg) {
    return $this->twig->load('pull-request-title.twig')->render([
      'name' => $msg->getName(),
      'current_version' => $msg->getCurrentVersion(),
      'new_version' => $msg->getNewVersion(),
    ]);
  }
}
