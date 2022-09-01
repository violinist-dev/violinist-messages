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
$update->setUpdatedList($update_items);
$update->setChangedFiles(['README.md', 'composer.json']);

$converter = new GithubFlavoredMarkdownConverter([
]);

$directory = __DIR__ . "/public";
if (!is_dir($directory)) {
    mkdir($directory);
}

$template = '<html>
<head>
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

file_put_contents("$directory/body.html", sprintf($template, $converter->convert($body = $message->getPullRequestBody($update))));
file_put_contents("$directory/index.html", sprintf($template, '    <a href="body.html">
        Pull request body
    </a>'));
