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

namespace spec\Kreta\Component\VCS\Serializer\Github;

use Kreta\Component\VCS\Factory\CommitFactory;
use Kreta\Component\VCS\Model\Interfaces\BranchInterface;
use Kreta\Component\VCS\Model\Interfaces\CommitInterface;
use Kreta\Component\VCS\Model\Interfaces\RepositoryInterface;
use Kreta\Component\VCS\Repository\BranchRepository;
use Kreta\Component\VCS\Repository\RepositoryRepository;
use PhpSpec\ObjectBehavior;

/**
 * Class CommitSerializerSpec.
 *
 * @author Beñat Espiña <benatespina@gmail.com>
 * @author Gorka Laucirica <gorka.lauzirika@gmail.com>
 */
class CommitSerializerSpec extends ObjectBehavior
{
    function let(CommitFactory $factory, RepositoryRepository $repositoryRepository, BranchRepository $branchRepository)
    {
        $this->beConstructedWith($factory, $repositoryRepository, $branchRepository);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Kreta\Component\VCS\Serializer\Github\CommitSerializer');
    }

    function it_implement_serializer_interface()
    {
        $this->shouldImplement('Kreta\Component\VCS\Serializer\Interfaces\SerializerInterface');
    }

    function it_deserializes_commit_info(
        CommitFactory $factory,
        CommitInterface $commit,
        RepositoryRepository $repositoryRepository,
        BranchRepository $branchRepository,
        RepositoryInterface $repository,
        BranchInterface $branch
    ) {
        $json = json_encode(
            [
                'ref'         => 'refs/heads/master',
                'head_commit' => [
                    'id'      => '11231',
                    'message' => 'Test commit',
                    'author'  => ['username' => 'gorkalaucirica'],
                    'url'     => 'http://github.com/kreta/kreta',
                ],
                'repository'  => ['full_name' => 'kreta/kreta'],
            ]
        );
        $repositoryRepository->findOneBy(['name' => 'kreta/kreta'])->shouldBeCalled()->willReturn($repository);
        $branchRepository->findOrCreateBranch($repository, 'master')->shouldBeCalled()->willReturn($branch);

        $factory->create('11231', 'Test commit', $branch, 'gorkalaucirica', 'http://github.com/kreta/kreta')
            ->shouldBeCalled()->willReturn($commit);

        $this->deserialize($json)->shouldReturn($commit);
    }

    function it_does_not_deserialize_commit_info_because_the_repository_does_not_exist(
        RepositoryRepository $repositoryRepository
    ) {
        $json = json_encode(
            [
                'ref'         => 'refs/heads/master',
                'head_commit' => [
                    'id'      => '11231',
                    'message' => 'Test commit',
                    'author'  => ['username' => 'gorkalaucirica'],
                    'url'     => 'http://github.com/kreta/kreta',
                ],
                'repository'  => ['full_name' => 'kreta/kreta'],
            ]
        );
        $repositoryRepository->findOneBy(['name' => 'kreta/kreta'])->shouldBeCalled()->willReturn(null);

        $this->deserialize($json)->shouldReturn(null);
    }
}
