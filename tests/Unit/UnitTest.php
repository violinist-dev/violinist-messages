<?php

namespace Violinist\ViolinistMessages\Tests\Unit;

use eiriksm\ViolinistMessages\UpdateListItem;
use eiriksm\ViolinistMessages\ViolinistMessages;
use eiriksm\ViolinistMessages\ViolinistUpdate;
use PHPUnit\Framework\TestCase;

class UnitTest extends TestCase
{
    public function testFalsyEmptyCustomMessage()
    {
        $update = new ViolinistUpdate();
        $this->assertTrue(!$update->getCustomMessage());
    }

    public function testCustomMessageInOutput()
    {
        $update = new ViolinistUpdate();
        $update->setCustomMessage('MY CUSTOM MESSAGE');
        $message = new ViolinistMessages();
        $this->assertFalse(strpos($message->getPullRequestBody($update), 'This is an automated pull request'));
        $this->assertFalse(strpos($message->getPullRequestBody($update), 'MY CUSTOM') === false);
    }

    public function testPullRequestClosed()
    {
        $id = 123;
        $message = new ViolinistMessages();
        self::assertEquals('This will now be closed, since it has been superseded by #123.
', $message->getPullRequestClosedMessage($id));
    }

    public function testSetters()
    {
        $update = new ViolinistUpdate();
        $update->setChangelog('changelog');
        $this->assertEquals('changelog', $update->getChangelog());
        $this->assertTrue(!$update->isSecurityUpdate());
        $update->setSecurityUpdate(true);
        $this->assertTrue($update->isSecurityUpdate());
        $update->setName('name');
        $this->assertEquals('name', $update->getName());
        $update->setNewVersion('2');
        $this->assertEquals('2', $update->getNewVersion());
        $update->setCurrentVersion('1');
        $this->assertEquals('1', $update->getCurrentVersion());
        $update->setChangedFiles(['file1', 'file2']);
        self::assertEquals(['file1', 'file2'], $update->getChangedFiles());
        $update->setPackageReleaseNotes([
            '- [Release notes for tag 8.x-1.27](https://www.drupal.org/project/admin_toolbar/releases/8.x-1.27)',
            '- [Release notes for tag 8.x-1.26](https://www.drupal.org/project/admin_toolbar/releases/8.x-1.26)',
        ]);
        self::assertEquals([
            '- [Release notes for tag 8.x-1.27](https://www.drupal.org/project/admin_toolbar/releases/8.x-1.27)',
            '- [Release notes for tag 8.x-1.26](https://www.drupal.org/project/admin_toolbar/releases/8.x-1.26)',
        ], $update->getPackageReleaseNotes());
    }

    public function testLegacyFormat()
    {
        $update = ViolinistUpdate::fromLegacyFormat($this->getLegacyItem());
        $this->assertEquals('vendor/package', $update->getName());
        $this->assertEquals('3', $update->getCurrentVersion());
        $this->assertEquals('4', $update->getNewVersion());
    }

    public function testLegacyTitle()
    {
        $message = new ViolinistMessages();
        $title = $message->getPullRequestTitleLegacy($this->getLegacyItem());
        // For some reason there is a new line in there.
        $this->assertEquals('Update vendor/package from 3 to 4
', $title);
    }

    public function testLegacyBody()
    {
        $message = new ViolinistMessages();
        $body = $message->getPullRequestBodyLegacy($this->getLegacyItem());
        // Jeez. That function does not make much use of its item. Just checking
        // for the default text in here then.
        $this->assertFalse(strpos($body, 'This is an automated pull request') === false);
    }

    public function testUpdateList()
    {
        $message = new ViolinistMessages();
        $update_items = [
            new UpdateListItem('first/updated', '2.0.1', '2.0.2'),
            new UpdateListItem('other/new', '2.0.0'),
        ];
        $update = new ViolinistUpdate();
        $update->setUpdatedList($update_items);
        $body = $message->getPullRequestBody($update);
        $this->assertEquals('If you have a high test coverage index, and your tests for this pull request are passing, it should be both safe and recommended to merge this update.

### Updated packages

Some times an update also needs new or updated dependencies to be installed. Even if this branch is for updating one dependency, it might contain other installs or updates. All of the updates in this branch can be found here:

- first/updated: 2.0.1 (updated from 2.0.2)
- other/new: 2.0.0 (new package, previously not installed)



### Working with this branch

If you find you need to update the codebase to be able to merge this branch (for example update some tests or rebuild some assets), please note that violinist will force push to this branch to keep it up to date. This means you should not work on this branch directly, since you might lose your work. [Read more about branches created by violinist.io here](https://docs.violinist.io/introduction/branches/).

***
This is an automated pull request from [Violinist](https://violinist.io/): Continuously and automatically monitor and update your composer dependencies. Have ideas on how to improve this message? All violinist messages are open-source, and [can be improved here](https://github.com/violinist-dev/violinist-messages).', trim($body));
    }

    public function testUpdateListOneRemoved()
    {
        $message = new ViolinistMessages();
        $removed = new UpdateListItem('third/removed', 'x');
        $removed->setIsRemoved(true);
        $removed->setIsNew(false);
        $update_items = [
            new UpdateListItem('first/updated', '2.0.1', '2.0.2'),
            new UpdateListItem('other/new', '2.0.0'),
            $removed,
        ];
        $update = new ViolinistUpdate();
        $update->setUpdatedList($update_items);
        $body = trim($message->getPullRequestBody($update));
        $this->assertEquals('If you have a high test coverage index, and your tests for this pull request are passing, it should be both safe and recommended to merge this update.

### Updated packages

Some times an update also needs new or updated dependencies to be installed. Even if this branch is for updating one dependency, it might contain other installs or updates. All of the updates in this branch can be found here:

- first/updated: 2.0.1 (updated from 2.0.2)
- other/new: 2.0.0 (new package, previously not installed)
- third/removed x (package was removed)



### Working with this branch

If you find you need to update the codebase to be able to merge this branch (for example update some tests or rebuild some assets), please note that violinist will force push to this branch to keep it up to date. This means you should not work on this branch directly, since you might lose your work. [Read more about branches created by violinist.io here](https://docs.violinist.io/introduction/branches/).

***
This is an automated pull request from [Violinist](https://violinist.io/): Continuously and automatically monitor and update your composer dependencies. Have ideas on how to improve this message? All violinist messages are open-source, and [can be improved here](https://github.com/violinist-dev/violinist-messages).', $body);
    }

  public function testBodyGroup()
  {
    $message = new ViolinistMessages();
    $first_list_item = new ViolinistUpdate();
    $first_list_item->setName('first/updated');
    $first_list_item->setCurrentVersion('2.0.1');
    $first_list_item->setNewVersion('2.0.2');
    $first_list_item->setPackageReleaseNotes([
        '- [Release notes for tag 8.x-1.27](https://www.drupal.org/project/admin_toolbar/releases/8.x-1.27)',
        '- [Release notes for tag 8.x-1.26](https://www.drupal.org/project/admin_toolbar/releases/8.x-1.26)',
    ]);
    $body = trim($message->getPullRequestBodyForGroup('Test group', [$first_list_item]));
    $this->assertEquals('This pull request updates the packages inside `Test group` to the latest version available (and inside your package constraint). The packages updated are listed below, along with available information for them.

If you have a high test coverage index, and your tests for this pull request are passing, it should be both safe and recommended to merge this update.

## Summary

| Package | Current version | New version |
| ------- | --------------- | ----------- |
| first/updated | `2.0.1` | `2.0.2` |

## first/updated (2.0.1 → 2.0.2)

### Release notes

Here are the release notes for all versions released between your current running version, and the version this PR updates the package to.

<details>
    <summary>List of release notes</summary>

- [Release notes for tag 8.x-1.27](https://www.drupal.org/project/admin_toolbar/releases/8.x-1.27)
- [Release notes for tag 8.x-1.26](https://www.drupal.org/project/admin_toolbar/releases/8.x-1.26)

</details>





### Working with this branch

If you find you need to update the codebase to be able to merge this branch (for example update some tests or rebuild some assets), please note that violinist will force push to this branch to keep it up to date. This means you should not work on this branch directly, since you might lose your work. [Read more about branches created by violinist.io here](https://docs.violinist.io/introduction/branches/).

***
This is an automated pull request from [Violinist](https://violinist.io/): Continuously and automatically monitor and update your composer dependencies. Have ideas on how to improve this message? All violinist messages are open-source, and [can be improved here](https://github.com/violinist-dev/violinist-messages).', $body);
  }

    protected function getLegacyItem()
    {
        return [
          'vendor/package',
          '3',
          '4',
        ];
    }
}
