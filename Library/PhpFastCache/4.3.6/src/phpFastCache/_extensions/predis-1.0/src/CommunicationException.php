<?php

/*
 * This file is part of the Predis package.
 *
 * (c) Daniele Alessandri <suppakilla@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Predis;

use Exception;
use Predis\Connection\NodeConnectionInterface;

/**
 * Base exception class for network-related errors.
 *
 * @author Daniele Alessandri <suppakilla@gmail.com>
 */
abstract class CommunicationException extends PredisException
{
    private $connection;

    /**
     * @param NodeConnectionInterface $connection Connection that generated the exception.
     * @param string $message Error message.
     * @param int $code Error code.
     * @param Exception $innerException Inner exception for wrapping the original error.
     */
    public function __construct(
        NodeConnectionInterface $connection,
        $message = null,
        $code = null,
        Exception $innerException = null
    ) {
        parent::__construct($message, $code, $innerException);
        $this->connection = $connection;
    }

    /**
     * Helper method to handle Exceptions generated by a connection object.
     *
     * @param CommunicationException $exception Exception.
     *
     * @throws CommunicationException
     */
    public static function handle(CommunicationException $exception)
    {
        if ($exception->shouldResetConnection()) {
            $connection = $exception->getConnection();

            if ($connection->isConnected()) {
                $connection->disconnect();
            }
        }

        throw $exception;
    }

    /**
     * Indicates if the receiver should reset the underlying connection.
     *
     * @return bool
     */
    public function shouldResetConnection()
    {
        return true;
    }

    /**
     * Gets the connection that generated the exception.
     *
     * @return NodeConnectionInterface
     */
    public function getConnection()
    {
        return $this->connection;
    }
}
