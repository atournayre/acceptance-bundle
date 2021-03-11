<?php

namespace Atournayre\AcceptanceBundle;

use Atournayre\AcceptanceBundle\DependencyInjection\AtournayreAcceptanceExtension;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class AtournayreAcceptanceBundle extends Bundle
{
    public function getContainerExtension()
    {
        if (null === $this->extension) {
            $this->extension = new AtournayreAcceptanceExtension();
        }

        return $this->extension;
    }
}
