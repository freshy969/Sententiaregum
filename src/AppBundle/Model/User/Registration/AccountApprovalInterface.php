<?php

/*
 * This file is part of the sententiaregum application.
 *
 * Sententiaregum is a social network based on Symfony2 and BackboneJS/ReactJS
 *
 * @copyright (c) 2015 Sententiaregum
 * Please check out the license file in the document root of this application
 */

namespace AppBundle\Model\User\Registration;

/**
 * Account which represents the approval step of the registration.
 *
 * @author Maximilian Bosch <maximilian.bosch.27@gmail.com>
 */
interface AccountApprovalInterface
{
    /**
     * Approves the new user.
     *
     * @param string $activationKey
     * @param string $username
     */
    public function approveByActivationKey($activationKey, $username);
}