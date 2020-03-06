ip_collector
============

A Symfony project created on March 5, 2020, 4:22 pm.

### Library is implemented

Naming convention is a bit messy, but I decided not to change to save time(app isn't production)

### Unit tests cover 

Repository/IpSqlRepository
Service/IpStorage

### Comments

Classes wasn't documented, due to lack of time. In real projects it is MUST activity.

### DB

    CREATE TABLE `ip` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `ip` varchar(15) NOT NULL,
    `counter` bigint(20) NOT NULL DEFAULT '0',
     PRIMARY KEY (`id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=latin1;

    ALTER TABLE `ip` ADD KEY `ip` (`ip`);
