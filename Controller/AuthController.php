<?php
/*
 *
 */

namespace Lopi\Bundle\PusherBundle\Controller;

use Symfony\Component\DependencyInjection\ContainerAware;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

/**
 * @author Richard Fullmer <richard.fullmer@opensoftdev.com>
 */
class AuthController extends ContainerAware
{

    /**
     * Implement http://pusher.com/docs/authenticating_users
     * and       http://pusher.com/docs/auth_signatures
     *
     * @param Request $request
     */
    public function authAction(Request $request)
    {
        $socketId = $request->get('socket_id');
        $channelName = $request->get('channel_name');
        $pusher = $this->container->get('lopi_pusher.pusher');

        if (!$this->container->get('lopi_pusher.authenticator')->authenticate($socketId, $channelName)) {
            throw new AccessDeniedException('Request authentication denied');
        }

        $auth = $pusher->getChannelAuth($channelName, $socketId,
            $this->container->get('lopi_pusher.authenticator')->getUserId(),
            $this->container->get('lopi_pusher.authenticator')->getUserInfo()
        );

        return new Response($auth, 200, array('Content-Type' => 'application/json'));
    }

}
