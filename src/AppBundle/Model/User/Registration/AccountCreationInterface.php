<?php

/*
 * This file is part of the Sententiaregum project.
 *
 * (c) Maximilian Bosch <maximilian.bosch.27@gmail.com>
 * (c) Ben Bieler <benjaminbieler2014@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace AppBundle\Model\User\Registration;

use AppBundle\Model\User\DTO\CreateUserDTO;
use AppBundle\Model\User\Value\RegistrationResult;

/**
 * Interface which represents the account creation step.
 *
 * @author Maximilian Bosch <maximilian.bosch.27@gmail.com>
 */
interface AccountCreationInterface
{
    /**
     * Creates a new user.
     *
     * @param CreateUserDTO $userParameters
     *
     * @return RegistrationResult
     */
    public function registration(CreateUserDTO $userParameters): RegistrationResult;
}
