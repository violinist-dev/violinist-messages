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
        $a = 'b';
        $this->assertEquals('If you have a high test coverage index, and your tests for this pull request are passing, it should be both safe and recommended to merge this update.

### Updated packages

Some times an update also needs new or updated dependencies to be installed. Even if this branch is for updating one dependency, it might contain other installs or updates. All of the updates in this branch can be found here:

- first/updated: 2.0.1 (updated from 2.0.2)
- other/new: 2.0.0 (new package, previously not installed)



***
This is an automated pull request from [Violinist](https://violinist.io/): Continuously and automatically monitor and update your composer dependencies. Have ideas on how to improve this message? All violinist messages are open-source, and [can be improved here](https://github.com/violinist-dev/violinist-messages).
', $body);
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
            $removed
        ];
        $update = new ViolinistUpdate();
        $update->setUpdatedList($update_items);
        $body = $message->getPullRequestBody($update);
        $this->assertEquals('If you have a high test coverage index, and your tests for this pull request are passing, it should be both safe and recommended to merge this update.

### Updated packages

Some times an update also needs new or updated dependencies to be installed. Even if this branch is for updating one dependency, it might contain other installs or updates. All of the updates in this branch can be found here:

- first/updated: 2.0.1 (updated from 2.0.2)
- other/new: 2.0.0 (new package, previously not installed)
- third/removed x (package was removed)



***
This is an automated pull request from [Violinist](https://violinist.io/): Continuously and automatically monitor and update your composer dependencies. Have ideas on how to improve this message? All violinist messages are open-source, and [can be improved here](https://github.com/violinist-dev/violinist-messages).
', $body);
    }

    protected function getLegacyItem()
    {
        return [
          'vendor/package',
          '3',
          '4'
        ];
    }
}
