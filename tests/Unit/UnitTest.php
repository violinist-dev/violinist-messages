<?php

namespace Violinist\ViolinistMessages\Tests\Unit;

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

    protected function getLegacyItem()
    {
        return [
          'vendor/package',
          '3',
          '4'
        ];
    }
}
