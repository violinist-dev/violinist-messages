<?php

use eiriksm\ViolinistMessages\UpdateListItem;
use eiriksm\ViolinistMessages\ViolinistMessages;
use eiriksm\ViolinistMessages\ViolinistUpdate;
use League\CommonMark\GithubFlavoredMarkdownConverter;

require_once "./vendor/autoload.php";

$message = new ViolinistMessages();
$removed = new UpdateListItem('third/removed', '2.2.0');
$removed->setIsRemoved(true);
$removed->setIsNew(false);
$update_items = [
    new UpdateListItem('package/one', '2.0.1', '2.0.2'),
    new UpdateListItem('other/package', '2.0.0'),
    $removed
];
$update = new ViolinistUpdate();
$update->setName('vendor/updated_package');
$update->setCurrentVersion('1.0.0');
$update->setNewVersion('1.0.1');
$update->setUpdatedList($update_items);
$update->setChangedFiles(['README.md', 'composer.json']);
$update->setPackageReleaseNotes([
    '- [Release notes for tag 8.x-1.27](https://www.drupal.org/project/admin_toolbar/releases/8.x-1.27)',
    '- [Release notes for tag 8.x-1.26](https://www.drupal.org/project/admin_toolbar/releases/8.x-1.26)',
    '- [Release notes for tag 8.x-1.25](https://www.drupal.org/project/admin_toolbar/releases/8.x-1.25)',
    '- [Release notes for tag 8.x-1.24](https://www.drupal.org/project/admin_toolbar/releases/8.x-1.24)',
    '- [Release notes for tag 8.x-1.23](https://www.drupal.org/project/admin_toolbar/releases/8.x-1.23)',
    '- [Release notes for tag 8.x-1.22](https://www.drupal.org/project/admin_toolbar/releases/8.x-1.22)',
    '- [Release notes for tag 8.x-1.21](https://www.drupal.org/project/admin_toolbar/releases/8.x-1.21)',
    '- [Release notes for tag 8.x-1.20](https://www.drupal.org/project/admin_toolbar/releases/8.x-1.20)',
    '- [Release notes for tag 8.x-1.19](https://www.drupal.org/project/admin_toolbar/releases/8.x-1.19)',
    '- [Release notes for tag 8.x-1.18](https://www.drupal.org/project/admin_toolbar/releases/8.x-1.18)',
]);
$update->setChangelog('- [e8c563c47](https://github.com/vendor/updated_package/commit/e8c563c47) `Prepare release`
- [87e59a5e7](https://github.com/vendor/updated_package/commit/87e59a5e7) `Update`
- [5b351a5de](https://github.com/vendor/updated_package/commit/5b351a5de) `Change`
- [3d938793e](https://github.com/vendor/updated_package/commit/3d938793e) `Fix`
- [fb0f0f6f8](https://github.com/vendor/updated_package/commit/fb0f0f6f8) `Introduce bug`
- [8f2d1c9c7](https://github.com/vendor/updated_package/commit/8f2d1c9c7) `Prepare release`
- [59da20d73](https://github.com/vendor/updated_package/commit/59da20d73) `Update ChangeLog`');

$converter = new GithubFlavoredMarkdownConverter([
]);

$directory = __DIR__ . "/public";
if (!is_dir($directory)) {
    mkdir($directory);
}

$template = '<html>
<head>
<meta name="viewport" content="width=device-width">
<meta charSet="utf-8"/>
    <style>
        .container {
        max-width: 1024px;
        margin: auto;
        margin-top: 2em;
        }
</style>
</head>
<body>
<div class="container mx-auto">
    %s
</div>
</body>
</html>
';

file_put_contents("$directory/body.html", sprintf($template, $converter->convert($message->getPullRequestBody($update))));
file_put_contents("$directory/index.html", sprintf($template, '    <ul>
    <li>
    <a href="body.html">
        Pull request body
    </a>
</li>
<li>
    <a href="body-group.html">
        Pull request body for group
    </a>
</li>
<li>
    <a href="closed.html">
        Pull request closed
    </a>
</li>
<li>
    <a href="title.html">
        Pull request title
    </a>
</li>
</ul>'));
$other_update = clone $update;
$other_update->setSecurityUpdate(true);

// Let's do some groups that basically copies the thing from the other body.
$update1 = clone $update;
$update1->setName('package/one');

$update2 = clone $update;
$update2->setName('package/two');

$list = [
  $update1,
  $update2,
];
file_put_contents("$directory/body-group.html", sprintf($template, $converter->convert($message->getPullRequestBodyForGroup('Test group', $list))));

file_put_contents("$directory/closed.html", sprintf($template, $converter->convert($message->getPullRequestClosedMessage(123))));
file_put_contents("$directory/title.html", sprintf($template, $converter->convert($message->getPullRequestTitle($update)) . '<hr><h2>Update with security release:</h2>' . $converter->convert($message->getPullRequestTitle($other_update))));
