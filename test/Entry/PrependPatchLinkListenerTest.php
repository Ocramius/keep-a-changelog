<?php
/**
 * @see       https://github.com/phly/keep-a-changelog for the canonical source repository
 * @copyright Copyright (c) 2019 Matthew Weier O'Phinney
 * @license   https://github.com/phly/keep-a-changelog/blob/master/LICENSE.md New BSD License
 */

declare(strict_types=1);

namespace PhlyTest\KeepAChangelog\Entry;

use Phly\KeepAChangelog\Entry\AbstractPrependLinkListener;
use Phly\KeepAChangelog\Entry\PrependPatchLinkListener;
use Prophecy\Argument;
use Prophecy\Prophecy\ObjectProphecy;

class PrependPatchLinkListenerTest extends AbstractPrependLinkListenerTestCase
{
    public function getListener() : AbstractPrependLinkListener
    {
        return new PrependPatchLinkListener();
    }

    /**
     * Setup mock for retrieving empty patch|issue identifier
     */
    public function emptyIdentifierRequested(ObjectProphecy $event) : void
    {
        $event->patchNumber()->willReturn(null);
    }

    /**
     * Setup mock for retrieving invalid patch|issue identifier
     */
    public function invalidIdentifierRequested(ObjectProphecy $event) : void
    {
        $this->identifier = -1;
        $event->patchNumber()->willReturn($this->identifier);
    }

    /**
     * Setup mock for retrieving patch|issue identifier.
     *
     * Should set $identifier.
     */
    public function identifierRequested(ObjectProphecy $event) : void
    {
        $this->identifier = 42;
        $event->patchNumber()->willReturn($this->identifier);
    }

    /**
     * Setup mock for reporting invalid patch|issue identifier
     */
    public function reportInvalidIdentifierRequested(ObjectProphecy $event) : void
    {
        $event
            ->patchNumberIsInvalid($this->identifier)
            ->will($this->voidReturn)
            ->shouldBeCalled();
    }

    /**
     * Setup mock for generating an empty patch|issue link
     */
    public function generateEmptyLinkRequested(ObjectProphecy $provider) : void
    {
        $this->link = '';
        $this->provider
            ->generatePatchLink($this->identifier)
            ->willReturn($this->link)
            ->shouldBeCalled();
    }

    /**
     * Setup mock for generating a patch|issue link
     *
     * Should set $link.
     */
    public function generateLinkRequested(ObjectProphecy $provider) : void
    {
        $this->link = sprintf('[#%s](https://git.mwop.net/issue/%s)', $this->identifier, $this->identifier);
        $this->provider
            ->generatePatchLink($this->identifier)
            ->willReturn($this->link)
            ->shouldBeCalled();
    }

    /**
     * Setup mock for reporting invalid patch|issue link
     */
    public function reportInvalidLinkRequested(ObjectProphecy $event) : void
    {
        $event
            ->patchLinkIsInvalid(empty($this->link) ? '' : Argument::containingString($this->link))
            ->will($this->voidReturn)
            ->shouldBeCalled();
    }
}
