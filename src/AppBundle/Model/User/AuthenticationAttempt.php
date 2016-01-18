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

namespace AppBundle\Model\User;

use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\Uuid;

/**
 * Model that contains data of an authentication attempt.
 *
 * @author Maximilian Bosch <maximilian.bosch.27@gmail.com>
 *
 * @ORM\Entity
 * @ORM\Table(name="authentication_attempt", indexes={
 *     @ORM\Index(name="auth_attempt_count", columns={"attempt_count"})
 * })
 */
class AuthenticationAttempt
{
    /**
     * @var string
     *
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     * @ORM\Column(name="id", type="guid")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="ip", type="string", length=255, unique=true)
     */
    private $ip;

    /**
     * @var int
     *
     * @ORM\Column(name="attempt_count", type="integer")
     */
    private $attemptCount = 0;

    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->id = Uuid::uuid4()->toString();
    }

    /**
     * Get id.
     *
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Get ip.
     *
     * @return string
     */
    public function getIp()
    {
        return $this->ip;
    }

    /**
     * Set ip.
     *
     * @param string $ip
     *
     * @return $this
     */
    public function setIp($ip)
    {
        $this->ip = (string) $ip;

        return $this;
    }

    /**
     * Get attempt count.
     *
     * @return int
     */
    public function getAttemptCount()
    {
        return $this->attemptCount;
    }

    /**
     * Increase attempt count.
     *
     * @return $this
     */
    public function increaseAttemptCount()
    {
        ++$this->attemptCount;

        return $this;
    }
}