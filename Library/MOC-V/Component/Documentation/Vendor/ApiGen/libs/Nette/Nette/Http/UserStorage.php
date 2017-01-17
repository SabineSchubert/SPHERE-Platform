<?php

/**
 * This file is part of the Nette Framework (http://nette.org)
 *
 * Copyright (c) 2004 David Grudl (http://davidgrudl.com)
 *
 * For the full copyright and license information, please view
 * the file license.txt that was distributed with this source code.
 */

namespace Nette\Http;

use Nette;
use Nette\Security\IIdentity;

/**
 * Session storage for user object.
 *
 * @author David Grudl, Jan Tichý
 */
class UserStorage extends Nette\Object implements Nette\Security\IUserStorage
{

    /** @var string */
    private $namespace = '';

    /** @var Session */
    private $sessionHandler;

    /** @var SessionSection */
    private $sessionSection;


    public function  __construct(Session $sessionHandler)
    {

        $this->sessionHandler = $sessionHandler;
    }


    /**
     * Sets the authenticated status of this user.
     *
     * @param  bool
     *
     * @return UserStorage Provides a fluent interface
     */
    public function setAuthenticated($state)
    {

        $section = $this->getSessionSection(true);
        $section->authenticated = (bool)$state;

        // Session Fixation defence
        $this->sessionHandler->regenerateId();

        if ($state) {
            $section->reason = null;
            $section->authTime = time(); // informative value

        } else {
            $section->reason = self::MANUAL;
            $section->authTime = null;
        }
        return $this;
    }

    /**
     * Returns and initializes $this->sessionSection.
     *
     * @return SessionSection
     */
    protected function getSessionSection($need)
    {

        if ($this->sessionSection !== null) {
            return $this->sessionSection;
        }

        if (!$need && !$this->sessionHandler->exists()) {
            return null;
        }

        $this->sessionSection = $section = $this->sessionHandler->getSection('Nette.Http.UserStorage/'.$this->namespace);

        if (!$section->identity instanceof IIdentity || !is_bool($section->authenticated)) {
            $section->remove();
        }

        if ($section->authenticated && $section->expireBrowser && !$section->browserCheck) { // check if browser was closed?
            $section->reason = self::BROWSER_CLOSED;
            $section->authenticated = false;
            if ($section->expireIdentity) {
                unset( $section->identity );
            }
        }

        if ($section->authenticated && $section->expireDelta > 0) { // check time expiration
            if ($section->expireTime < time()) {
                $section->reason = self::INACTIVITY;
                $section->authenticated = false;
                if ($section->expireIdentity) {
                    unset( $section->identity );
                }
            }
            $section->expireTime = time() + $section->expireDelta; // sliding expiration
        }

        if (!$section->authenticated) {
            unset( $section->expireTime, $section->expireDelta, $section->expireIdentity,
                $section->expireBrowser, $section->browserCheck, $section->authTime );
        }

        return $this->sessionSection;
    }

    /**
     * Is this user authenticated?
     *
     * @return bool
     */
    public function isAuthenticated()
    {

        $session = $this->getSessionSection(false);
        return $session && $session->authenticated;
    }

    /**
     * Sets the user identity.
     *
     * @param  IIdentity
     *
     * @return UserStorage Provides a fluent interface
     */
    public function setIdentity(IIdentity $identity = null)
    {

        $this->getSessionSection(true)->identity = $identity;
        return $this;
    }

    /**
     * Returns current user identity, if any.
     *
     * @return Nette\Security\IIdentity|NULL
     */
    public function getIdentity()
    {

        $session = $this->getSessionSection(false);
        return $session ? $session->identity : null;
    }

    /**
     * Returns current namespace.
     *
     * @return string
     */
    public function getNamespace()
    {

        return $this->namespace;
    }

    /**
     * Changes namespace; allows more users to share a session.
     *
     * @param  string
     *
     * @return UserStorage Provides a fluent interface
     */
    public function setNamespace($namespace)
    {

        if ($this->namespace !== $namespace) {
            $this->namespace = (string)$namespace;
            $this->sessionSection = null;
        }
        return $this;
    }

    /**
     * Enables log out after inactivity.
     *
     * @param  string|int|DateTime Number of seconds or timestamp
     * @param                      int    Log out when the browser is closed | Clear the identity from persistent storage?
     *
     * @return UserStorage Provides a fluent interface
     */
    public function setExpiration($time, $flags = 0)
    {

        $section = $this->getSessionSection(true);
        if ($time) {
            $time = Nette\DateTime::from($time)->format('U');
            $section->expireTime = $time;
            $section->expireDelta = $time - time();

        } else {
            unset( $section->expireTime, $section->expireDelta );
        }

        $section->expireIdentity = (bool)( $flags & self::CLEAR_IDENTITY );
        $section->expireBrowser = (bool)( $flags & self::BROWSER_CLOSED );
        $section->browserCheck = true;
        $section->setExpiration(0, 'browserCheck');
        $section->setExpiration($time, 'foo'); // time check
        return $this;
    }

    /**
     * Why was user logged out?
     *
     * @return int
     */
    public function getLogoutReason()
    {

        $session = $this->getSessionSection(false);
        return $session ? $session->reason : null;
    }

}
