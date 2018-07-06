<?php
/**
 * Created by PhpStorm.
 * User: Dego1n
 * Date: 12.10.2017
 * Time: 12:27
 */

namespace WebSocketsBundle\Topic;


use ApiBundle\Entity\User\AbstractUser;
use Gos\Bundle\WebSocketBundle\Client\ClientManipulatorInterface;
use Gos\Bundle\WebSocketBundle\Router\WampRequest;
use Gos\Bundle\WebSocketBundle\Topic\PushableTopicInterface;
use Gos\Bundle\WebSocketBundle\Topic\TopicInterface;
use Ratchet\ConnectionInterface;
use Ratchet\Wamp\Topic;

class NotificationTopic implements TopicInterface, PushableTopicInterface
{
    protected $clientManipulator;

    /**
     * @param ClientManipulatorInterface $clientManipulator
     */
    public function __construct(ClientManipulatorInterface $clientManipulator)
    {
        $this->clientManipulator = $clientManipulator;
    }
    /**
     * @param  ConnectionInterface $connection
     * @param  Topic $topic
     * @param WampRequest $request
     */
    public function onSubscribe(ConnectionInterface $connection, Topic $topic, WampRequest $request)
    {
    }

    /**
     * @param  ConnectionInterface $connection
     * @param  Topic $topic
     * @param WampRequest $request
     */
    public function onUnSubscribe(ConnectionInterface $connection, Topic $topic, WampRequest $request)
    {
    }

    /**
     * @param  ConnectionInterface $connection
     * @param  Topic $topic
     * @param WampRequest $request
     * @param $event
     * @param  array $exclude
     * @param  array $eligible
     */
    public function onPublish(ConnectionInterface $connection, Topic $topic, WampRequest $request, $event, array $exclude, array $eligible)
    {
    }

    /**
     * @param Topic        $topic
     * @param WampRequest  $request
     * @param array|string $data
     * @param string       $provider The name of pusher who push the data
     */
    public function onPush(Topic $topic, WampRequest $request, $data, $provider)
    {
        if(isset($data["user"]))
        {
            print('should send notification to user_id: '.$data["user"]);
            if(!isset($data["notification"]))
            {
                print('[ERROR] Trying to send notification without notification body');
            }
            foreach($topic as $client)
            {
                $user = $this->clientManipulator->getClient($client);
                if($user instanceof AbstractUser && $user->getId() == $data["user"]) {
                    $client->event($topic->getId(), ['notification' => $data["notification"]]);
                }
            }
        }
        else
        {
            print('[ERROR] Trying to send notification without specifying user');
        }
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'notification.topic';
    }
}