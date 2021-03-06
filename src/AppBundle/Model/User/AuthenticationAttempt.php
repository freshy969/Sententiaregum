<?php

/*
 * This file is part of the Sententiaregum project.
 *
 * (c) Maximilian Bosch <maximilian@mbosch.me>
 * (c) Ben Bieler <ben@benbieler.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace AppBundle\Model\User;

use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\Uuid;

/**
 * Model that contains data of an authentication attempt.
 *
 * @author Maximilian Bosch <maximilian@mbosch.me>
 *
 * @ORM\Entity
 * @ORM\Table(name="authentication_attempt", indexes={
 *     @ORM\Index(name="auth_attempt_count", columns={"attempt_count"}),
 *     @ORM\Index(name="auth_last_datetime_range", columns={"latest_date_time"}),
 *     @ORM\Index(name="auth_ip", columns={"ip"})
 * })
 * @ORM\Cache(usage="READ_WRITE", region="strict")
 */
class AuthenticationAttempt implements \Serializable
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
     * NOTE: ip can't be unique as IPs from failed requests and IPs from successful request are stored in this table.
     * @ORM\Column(name="ip", type="string", length=255)
     */
    private $ip;

    /**
     * @var int
     *
     * @ORM\Column(name="attempt_count", type="integer")
     */
    private $attemptCount = 0;

    /**
     * @var \DateTime[]
     *
     * @ORM\Column(name="last_date_time_range", type="date_time_array")
     */
    private $lastDateTimeRange = [];

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="latest_date_time", type="datetime")
     */
    private $latestDateTime;

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
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * Get ip.
     *
     * @return string
     */
    public function getIp(): string
    {
        return $this->ip;
    }

    /**
     * Set ip.
     *
     * @param string $ip
     *
     * @return AuthenticationAttempt
     */
    public function setIp(string $ip): self
    {
        $this->ip = (string) $ip;

        return $this;
    }

    /**
     * Get attempt count.
     *
     * @return int
     */
    public function getAttemptCount(): int
    {
        return $this->attemptCount;
    }

    /**
     * Increase attempt count.
     *
     * @return AuthenticationAttempt
     */
    public function increaseAttemptCount(): self
    {
        ++$this->attemptCount;

        $now                  = new \DateTime();
        $this->latestDateTime = $now;

        if (count($this->lastDateTimeRange) === (User::MAX_FAILED_ATTEMPTS_FROM_IP + 1)) {
            array_pop($this->lastDateTimeRange);
        }

        array_unshift($this->lastDateTimeRange, $now);

        return $this;
    }

    /**
     * Getter for the last datetime of a failed auth attempt.
     *
     * @return \DateTime
     */
    public function getLatestFailedAttemptTime(): \DateTime
    {
        return $this->latestDateTime;
    }

    /**
     * Getter for the last datetime items.
     *
     * @return \DateTime[]
     */
    public function getLastFailedAttemptTimesInRange(): array
    {
        return $this->lastDateTimeRange;
    }

    /**
     * {@inheritdoc}
     */
    public function serialize(): string
    {
        return serialize([
            $this->id,
            $this->ip,
            $this->attemptCount,
            $this->lastDateTimeRange,
            $this->latestDateTime,
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function unserialize($serialized): void
    {
        [$this->id, $this->ip, $this->attemptCount, $this->lastDateTimeRange, $this->latestDateTime] = unserialize($serialized);
    }
}
