<?php
/**
 * @access protected
 * @author Judzhin Miles <info[woof-woof]msbios.com>
 */

declare(strict_types=1);

namespace TODO;

use Ratchet\ConnectionInterface;
use Ratchet\MessageComponentInterface;
use Zend\InputFilter\InputFilterInterface;
use Zend\Json\Decoder;
use Zend\Json\Encoder;
use Zend\Json\Json;

/**
 * Class MessageComponent
 * @package TODO
 */
class MessageComponent implements MessageComponentInterface
{
    /** @var InputFilterInterface */
    protected $inputFilter;

    /** @var MessageResolver */
    protected $resolver;

    /** @var \SplObjectStorage */
    protected $clients;

    /**
     * MessageComponent constructor.
     * @param InputFilterInterface $inputFilter
     * @param MessageResolver $resolver
     */
    public function __construct(InputFilterInterface $inputFilter, MessageResolver $resolver)
    {
        $this->inputFilter = $inputFilter;
        $this->resolver = $resolver;
        $this->clients = new \SplObjectStorage;
    }

    /**
     * @param ConnectionInterface $connection
     */
    public function onOpen(ConnectionInterface $connection)
    {
        $this->clients->attach($connection);
    }

    /**
     * @param ConnectionInterface $from
     * @param string $msg
     */
    public function onMessage(ConnectionInterface $from, $msg)
    {
        /** @var array $message */
        $message = Decoder::decode($msg, Json::TYPE_ARRAY);

        if ($this->inputFilter->setData($message)->isValid()) {

            /** @var array $values */
            $values = $this->inputFilter->getValues();

            if ($this->resolver->resolve($values)) {
                /** @var ConnectionInterface $client */
                foreach ($this->clients as $client) {
                    if ($from !== $client) {
                        $client->send(Encoder::encode($message));
                    }
                }
            };

        }
    }

    /**
     * @param ConnectionInterface $conn
     */
    public function onClose(ConnectionInterface $conn)
    {
        $this->clients->detach($conn);
    }

    /**
     * @param ConnectionInterface $conn
     * @param \Exception $e
     */
    public function onError(ConnectionInterface $conn, \Exception $e)
    {
        echo "An error has occurred: {$e->getMessage()}\n";
        $conn->close();
    }
}