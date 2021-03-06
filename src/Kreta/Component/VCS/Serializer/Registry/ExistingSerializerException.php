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

namespace Kreta\Component\VCS\Serializer\Registry;

/**
 * Class ExistingSerializerException.
 *
 * @author Beñat Espiña <benatespina@gmail.com>
 * @author Gorka Laucirica <gorka.lauzirika@gmail.com>
 */
class ExistingSerializerException extends \InvalidArgumentException
{
    /**
     * Constructor.
     *
     * @param string $provider The provider
     * @param string $event    The event
     */
    public function __construct($provider, $event)
    {
        parent::__construct(sprintf('Serializer for "%s"\'s "%s" event already exists', $provider, $event));
    }
}
