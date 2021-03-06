<?php

/*
 * This file is part of the Kreta package.
 *
 * (c) Beñat Espiña <benatespina@gmail.com>
 * (c) Gorka Laucirica <gorka.lauzirika@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace spec\Kreta\Component\VCS\Model;

use Kreta\Component\VCS\Model\Interfaces\RepositoryInterface;
use PhpSpec\ObjectBehavior;

/**
 * Class BranchSpec.
 *
 * @author Beñat Espiña <benatespina@gmail.com>
 * @author Gorka Laucirica <gorka.lauzirika@gmail.com>
 */
class BranchSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('Kreta\Component\VCS\Model\Branch');
    }

    function it_implements_branch_interface()
    {
        $this->shouldImplement('Kreta\Component\VCS\Model\Interfaces\BranchInterface');
    }

    function it_does_not_have_id_by_default()
    {
        $this->getId()->shouldReturn(null);
    }

    function its_name_is_mutable()
    {
        $this->setName('master')->shouldReturn($this);
        $this->getName()->shouldReturn('master');
    }

    function its_repository_is_mutable(RepositoryInterface $repository)
    {
        $this->setRepository($repository)->shouldReturn($this);
        $this->getRepository()->shouldReturn($repository);
    }

    function its_issues_related_is_mutable()
    {
        $this->setIssuesRelated([])->shouldReturn($this);
        $this->getIssuesRelated()->shouldReturn([]);
    }

    function it_generates_url_for_github(RepositoryInterface $repository)
    {
        $repository->getProvider()->shouldBeCalled()->willReturn('github');
        $repository->getName()->shouldBeCalled()->willReturn('kreta/kreta');
        $this->setRepository($repository);
        $this->setName('KRT-42-test-url-generation');

        $this->getUrl()->shouldReturn('https://github.com/kreta/kreta/tree/KRT-42-test-url-generation');
    }

    function it_generates_default_url(RepositoryInterface $repository)
    {
        $repository->getProvider()->shouldBeCalled()->willReturn('default-provider');
        $this->setRepository($repository);
        $this->setName('KRT-42-test-url-generation');

        $this->getUrl()->shouldReturn('');
    }
}
