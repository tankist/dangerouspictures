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
     * @return \Entities\User[]
     */
    public function getRootUsers()
    {
        return $this->getRepository()->findBy(array('role' => Sch_Acl_Roles::ADMIN));
    }

    /**
     * @return \Entities\User
     */
    public function getFirstRootUser()
    {
        return $this->getRepository()->findOneBy(array('role' => Sch_Acl_Roles::ADMIN));
    }

}
