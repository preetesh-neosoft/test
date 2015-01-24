<?php
$installer = $this;
$installer->startSetup();
$sql=<<<SQLTEXT
CREATE TABLE IF NOT EXISTS `become_retailer` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `first_name` varchar(255) NOT NULL,
  `last_name` varchar(255) NOT NULL,
  `title` varchar(255) NOT NULL,
  `store_name` varchar(255) NOT NULL,
  `store_location` varchar(50) NOT NULL,
  `street_address` varchar(255) NOT NULL,
  `city` varchar(255) NOT NULL,
  `state` varchar(255) NOT NULL,
  `zip` int(11) NOT NULL,
  `country` varchar(255) NOT NULL,
  `vat` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `confirm_email` varchar(255) NOT NULL,
  `website` varchar(255) NOT NULL,
  `fax` int(11) NOT NULL,
  `type_of_retail` varchar(255) NOT NULL,
  `speciality` varchar(255) NOT NULL,
  `price_range` varchar(255) NOT NULL,
  `clientele` int(11) NOT NULL,
  `decor` varchar(255) NOT NULL,
  `area` varchar(255) NOT NULL,
  `other_details` text NOT NULL,
  `store_open` varchar(5) NOT NULL,
  `business_years` int(11) NOT NULL,
  `number_of_locations` int(11) NOT NULL,
  `brand_one` varchar(255) NOT NULL,
  `brand_two` varchar(255) NOT NULL,
  `brand_three` varchar(255) NOT NULL,
  `mailing_street_address` varchar(255) NOT NULL,
  `mailing_city` varchar(255) NOT NULL,
  `mailing_state` varchar(255) NOT NULL,
  `mailing_zip` int(11) NOT NULL,
  `mailing_country` varchar(255) NOT NULL,
  `phone_number` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;
SQLTEXT;

$installer->run($sql);
//demo 
//Mage::getModel('core/url_rewrite')->setId(null);
//demo 
$installer->endSetup();
	 