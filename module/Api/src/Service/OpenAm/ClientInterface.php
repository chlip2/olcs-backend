<?php

namespace Dvsa\Olcs\Api\Service\OpenAm;

/**
 * Class Client
 * @package Dvsa\Olcs\Api\Service\OpenAm
 */
interface ClientInterface
{
    /**
     * Registers a user
     *
     * @param string $username
     * @param string $pid
     * @param string $emailAddress
     * @param string $surname
     * @param string $commonName
     * @param string $realm
     * @param string $password
     *
     * @return void
     * @throws FailedRequestException
     */
    public function registerUser($username, $pid, $emailAddress, $surname, $commonName, $realm, $password);

    /**
     * Updates a user
     *
     * @param string $username
     * @param array $updates
     *
     * @return void
     * @throws FailedRequestException
     */
    public function updateUser($username, $updates);

    /**
     * Deletes (hard delete) a user
     *
     * @param string $username
     *
     * @return void
     * @throws FailedRequestException
     */
    public function deleteUser($username);
}
