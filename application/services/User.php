<?php

use Doctrine\ORM\Tools\Pagination as Paginator;

class Service_User extends Sch_Service_Abstract
{

    /**
     * @var string
     */
    protected $_entityName = '\Entities\User';

    /**
     * @param string $email
     * @return \Entities\User
     */
    public function getByEmail($email)
    {
        $user = $this->getRepository()->findOneBy(array('email' => $email));
        return $user;
    }

    /**
     * @param $userName
     * @return \Entities\User
     */
    public function getByUsername($userName)
    {
        $user = $this->getRepository()->findOneBy(array('username' => $userName));
        return $user;
    }

    /**
     * @return array
     */
    public function getEmailsForNotifications()
    {
        $emails = $this->getRepository()->findAll();
        foreach ($emails as $key => $value) {
            /** @var $value \Entities\User */
            $emails[$key] = $value->getEmail();
        }
        return $emails;
    }

    /**
     * @param string $verificationKey
     * @return \Entities\User
     */
    public function getByVerificationKey($verificationKey)
    {
        $em = $this->getEntityManager();
        $verification = $em->getRepository('\Entities\User\Verification')->findOneBy(array('verificationKey' => $verificationKey));
        if ( $verification ) {
            return $verification->getUser();
        }
        return null;
    }

    /**
     * @param \Entities\User $user
     * @return string $verificationKey
     */
    public function getVerificationKey(\Entities\User $user)
    {
        list($usec, $sec) = explode(' ', microtime());
        // Seed the random number generator with above timings
        mt_srand((float)$sec + ((float)$usec * 1000000));
        // Generate hash using GLOBALS and PID
        $verificationKey = sha1(uniqid(mt_rand(), true));
        if ($user->getVerification()) {
            $this->getEntityManager()->remove($user->getVerification());
            $this->getEntityManager()->flush();
        }
        $verification = new \Entities\User\Verification($user, $verificationKey);
        $user->setVerification($verification);
        $this->getEntityManager()->flush();
        return $verificationKey;
    }

    /**
     * @param \Entities\User $user
     * @return string $activationKey
     */
    public function getActivationKey(\Entities\User $user)
    {
        list($usec, $sec) = explode(' ', microtime());
        // Seed the random number generator with above timings
        mt_srand((float)$sec + ((float)$usec * 1000000));
        // Generate hash using GLOBALS and PID
        $activationKey = sha1(uniqid(mt_rand(), true));
        if ($user->getActivation()) {
            $this->getEntityManager()->remove($user->getActivation());
            $this->getEntityManager()->flush();
        }
        $activation = new \Entities\User\Activation($user, $activationKey);
        $user->setActivation($activation);
        $this->getEntityManager()->flush();
        return $activationKey;
    }
    /**
     * @param string $activationKey
     * @return \Entities\User
     */
    public function getByActivationKey($activationKey)
    {
        $em = $this->getEntityManager();
        $activation = $em->getRepository('\Entities\User\Activation')->findOneBy(array('activationKey' => $activationKey));
        if ( $activation ) {
            return $activation->getUser();
        }
        return null;
    }

    /**
     * @param \Entities\User $user
     * @return bool
     */
    public function activate(\Entities\User $user)
    {
        $user->setStatus('Active');
        $user->setRole(Sch_Acl_Roles::PUBLISHER);
        $this->getEntityManager()->remove($user->getActivation());
        $this->getEntityManager()->flush();
        return true;
    }

    /**
     * @param Entities\User $user
     * @param $uid
     * @return object
     */
    public function getFacebookInviteByUid(\Entities\User $user, $uid)
    {
        return $this->getEntityManager()->getRepository('\Entities\Invite\Facebook')
            ->find(array('uid' => $uid, 'user' => $user));
    }

    public function getFacebookInvitesByUser(\Entities\User $user)
    {
        return $this->getEntityManager()->getRepository('\Entities\Invite\Facebook')
            ->findBy(array('user' => $user));
    }

    /**
     * @param Entities\User $user
     * @param $uid
     * @param null $key
     * @return Entities\Invite\Facebook
     * @throws Sch_Service_Exception
     */
    public function createFacebookInvite(\Entities\User $user, $uid, $key = null)
    {
        if ($this->getFacebookInviteByUid($user, $uid)) {
            throw new Sch_Service_Exception('Invitation already given to that user');
        }
        $invite = new \Entities\Invite\Facebook($uid, $user);
        $invite->setCode($key ? : sha1(uniqid(mt_rand(), true)));
        $this->save($invite);
        return $invite;
    }

    /**
     * @param Entities\Invite\Facebook $invite
     * @return Entities\Invite\Facebook
     */
    public function activateFacebookInvite(\Entities\Invite\Facebook $invite)
    {
        $invite->setAcceptDate(new DateTime());
        $this->save($invite);
        return $invite;
    }

}
