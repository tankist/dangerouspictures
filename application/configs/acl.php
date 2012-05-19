<?php

$acl = new Zend_Acl();

// Roles

$acl->addRole(new Zend_Acl_Role(Sch_Acl_Roles::GUEST));
$acl->addRole(new Zend_Acl_Role(Sch_Acl_Roles::NON_ACTIVE_PUBLISHER), Sch_Acl_Roles::GUEST);
$acl->addRole(new Zend_Acl_Role(Sch_Acl_Roles::PUBLISHER), Sch_Acl_Roles::GUEST);
$acl->addRole(new Zend_Acl_Role(Sch_Acl_Roles::DEVELOPER), Sch_Acl_Roles::PUBLISHER);
$acl->addRole(new Zend_Acl_Role(Sch_Acl_Roles::ADMIN), Sch_Acl_Roles::PUBLISHER);

/**
 * Базовые ресурсы
 */
$acl->addResource(new Zend_Acl_Resource('index'));
$acl->addResource(new Zend_Acl_Resource('category'));
$acl->addResource(new Zend_Acl_Resource('publisher'));
$acl->addResource(new Zend_Acl_Resource('auth'), 'index');
$acl->addResource(new Zend_Acl_Resource('error'), 'index');
$acl->addResource(new Zend_Acl_Resource('developer'), 'publisher');
$acl->addResource(new Zend_Acl_Resource('backend'));

// Resources
// format: <module>.<controller>
$acl->addResource(new Zend_Acl_Resource('default'), 'index');
$acl->addResource(new Zend_Acl_Resource('admin'), 'backend');
$acl->addResource(new Zend_Acl_Resource('admin.root'), 'index');

// Access rights

$acl->deny(Sch_Acl_Roles::GUEST, null);
$acl->allow(null, 'index');
$acl->allow(null, 'category');

$acl->deny(Sch_Acl_Roles::NON_ACTIVE_PUBLISHER, 'default');

$acl->allow(Sch_Acl_Roles::PUBLISHER, null);

$acl->deny(null, 'backend');
$acl->deny(null, 'developer');

$acl->allow(Sch_Acl_Roles::DEVELOPER, 'developer');

$acl->allow(Sch_Acl_Roles::ADMIN, 'backend');
$acl->allow(Sch_Acl_Roles::ADMIN, 'developer');

return $acl;