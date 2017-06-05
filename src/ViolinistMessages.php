<?php

class ViolinistMessages {
  public function getPullRequestTitle($item) {
    return sprintf('Update %s from %s to %s', $item[0], $item[1], $item[2]);
  }
  public function getPullRequestBody($item) {
    return sprintf("%s\n***\nThis is an automated pull request from [Violinist](https://violinist.io/): Continuously and automatically monitor and update your composer dependencies.", $this->getPullRequestTitle($item));
  }
}
