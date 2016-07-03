<?php
SetupWebPage::AddModule(
	__FILE__, // Path to the current file, all other file names are relative to the directory containing this file
	'teemip-ip-mgmt/2.1.0',
	array(
		// Identification
		//
		'label' => 'IP Management',
		'category' => 'business',
		
		// Setup
		//
		'dependencies' => array(
			'itop-config-mgmt/2.0.0',
			'itop-tickets/2.0.0',
			'teemip-network-mgmt/2.0.0'
		),
		'mandatory' => false,
		'visible' => true,
		'installer' => 'IPManagementInstaller',
		
		// Components
		//
		'datamodel' => array(
			'model.teemip-ip-mgmt.php',
			'main.teemip-ip-mgmt.php',
		),
		'data.struct' => array(
			//'data.struct.IPAudit.xml',
		),
		'data.sample' => array(
			'data.sample.IPGlue.xml',
			'data.sample.IPv4Block.xml',
			'data.sample.lnkIPv4BlockToLocation.xml',
			'data.sample.IPv4Subnet.xml',
			'data.sample.lnkIPv4SubnetToLocation.xml',
			'data.sample.IPRangeUsage.xml',
			'data.sample.IPv4Range.xml',
			'data.sample.IPUsage.xml',
			'data.sample.IPv4Address.xml',
		),
		
		// Documentation
		//
		'doc.manual_setup' => '',
		'doc.more_information' => '',
		
		// Default settings
		//
		'settings' => array(
		),
	)
);

if (!class_exists('IPManagementInstaller'))
{
	// Module installation handler
	//
	class IPManagementInstaller extends ModuleInstallerAPI
	{
		public static function BeforeWritingConfig(Config $oConfiguration)
		{
			// If you want to override/force some configuration values, do it here
			return $oConfiguration;
		}

		/**
		 * Handler called before creating or upgrading the database schema
		 * @param $oConfiguration Config The new configuration of the application
		 * @param $sPreviousVersion string PRevious version number of the module (empty string in case of first install)
		 * @param $sCurrentVersion string Current version number of the module
		 */
		public static function BeforeDatabaseCreation(Config $oConfiguration, $sPreviousVersion, $sCurrentVersion)
		{
			// If you want to migrate data from one format to another, do it here
		}
	
		/**
		 * Handler called after the creation/update of the database schema
		 * @param $oConfiguration Config The new configuration of the application
		 * @param $sPreviousVersion string PRevious version number of the module (empty string in case of first install)
		 * @param $sCurrentVersion string Current version number of the module
		 */
		public static function AfterDatabaseCreation(Config $oConfiguration, $sPreviousVersion, $sCurrentVersion)
		{
			// For migration 2.0.0 or 2.0.1 to 2.0.2 only
			// Migrate release_date from IPSubnet to IPObject
			// Delete release_date from IPSubnet
			// Migrate allocation_date and release_date from IPAddress to IPObject
			// Delete allocation_date and release_date from IPAddress
			
			if (($sPreviousVersion == '2.0.0') && ($sCurrentVersion == '2.0.2'))
			{
				SetupPage::log_info("Module teemip-ip-mgmt: migrate allocation_date & release_date to IPObject"); 

				$sIpObjectTable = MetaModel::DBGetTable('IPObject');
				$sIpSubnetTable = MetaModel::DBGetTable('IPSubnet');
				$sCopy = "UPDATE `$sIpObjectTable` AS o JOIN `$sIpSubnetTable` AS s ON o.id = s.id AND (o.finalclass = 'IPv4Subnet' OR o.finalclass = 'IPv6Subnet') SET o.release_date = s.release_date";
				CMDBSource::Query($sCopy);
				$sDeleteColumn = "ALTER TABLE `$sIpSubnetTable` DROP COLUMN release_date ";
				CMDBSource::Query($sDeleteColumn);
				
				$sIpAddressTable = MetaModel::DBGetTable('IPAddress');
				$sCopy = "UPDATE `$sIpObjectTable` AS o JOIN `$sIpAddressTable` AS a ON o.id = a.id AND (o.finalclass = 'IPv4Address' OR o.finalclass = 'IPv6Address') SET o.release_date = a.release_date, o.allocation_date = a.allocation_date";
				CMDBSource::Query($sCopy);
				$sDeleteColumn = "ALTER TABLE `$sIpAddressTable` DROP COLUMN release_date, DROP COLUMN allocation_date ";
				CMDBSource::Query($sDeleteColumn);

				SetupPage::log_info("Module teemip-ip-mgmt: migration done");
			} 
		}
	}
}
