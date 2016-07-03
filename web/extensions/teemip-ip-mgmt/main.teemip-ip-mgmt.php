<?php
// Copyright (C) 2016 TeemIp
//
//   This file is part of TeemIp.
//
//   TeemIp is free software; you can redistribute it and/or modify	
//   it under the terms of the GNU Affero General Public License as published by
//   the Free Software Foundation, either version 3 of the License, or
//   (at your option) any later version.
//
//   TeemIp is distributed in the hope that it will be useful,
//   but WITHOUT ANY WARRANTY; without even the implied warranty of
//   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
//   GNU Affero General Public License for more details.
//
//   You should have received a copy of the GNU Affero General Public License
//   along with TeemIp. If not, see <http://www.gnu.org/licenses/>

/**
 * @copyright   Copyright (C) 2016 TeemIp
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

/*******************
 * Global constants
 */

define('MAX_NB_OF_IPS_TO_DISPLAY', 4096);

define('MAX_IPV4_VALUE', 4294967295);
define('IPV4_BLOCK_MIN_SIZE', 1);
define('IPV4_SUBNET_MAX_SIZE', 65536);

define('ALL_ORGS', 65536);

define('ACTION_NONE', 0);
define('ACTION_SHRINK', 1);
define('ACTION_SPLIT', 2);
define('ACTION_EXPAND', 3);
define('ACTION_PARENT_BLOCK_IS_DELETED', 4);
define('ACTION_BLOCK_IS_DELETED', 5);

define('GLOBAL_CONFIG_DEFAULT_NAME', 'IP Settings');
define('DEFAULT_BLOCK_LOW_WATER_MARK', 60);
define('DEFAULT_BLOCK_HIGH_WATER_MARK', 80);
define('DEFAULT_SUBNET_LOW_WATER_MARK', 60);
define('DEFAULT_SUBNET_HIGH_WATER_MARK', 80);
define('DEFAULT_IPRANGE_LOW_WATER_MARK', 60);
define('DEFAULT_IPRANGE_HIGH_WATER_MARK', 80);

define('DEFAULT_MAX_FREE_SPACE_OFFERS', 10);
define('DEFAULT_MAX_FREE_IP_OFFERS', 10);
define('DEFAULT_MAX_FREE_IP_OFFERS_WITH_PING', 5);
define('DEFAULT_SUBNET_CREATE_MAX_OFFER', 10);

define('RED', "#cc3300");
define('YELLOW', "#ffff00");
define('GREEN', "#33ff00");

define('TIME_TO_WAIT_FOR_PING_LONG', 3);
define('TIME_TO_WAIT_FOR_PING_SHORT', 1);
define('NUMBER_OF_PINGS', 1);
define('FAIL_KEY_FOR_PING', '100%');

define('NETWORK_IP_CODE', 'Network IP');
define('NETWORK_IP_DESC', 'Subnet IP');
define('GATEWAY_IP_CODE', 'Gateway');
define('GATEWAY_IP_DESC', 'Gateway IP');
define('BROADCAST_IP_CODE', 'Broadcast');
define('BROADCAST_IP_DESC', 'Broadcast IP');

/*********************************************
 * Class for handling IPv4 and IPv6 addresses  
 */

abstract class ormIP
{
	abstract public function IsBiggerOrEqual(ormIP $oIp);
	
	abstract public function IsBiggerStrict(ormIP $oIp);

	abstract public function IsSmallerOrEqual(ormIP $oIp);

	abstract public function IsSmallerStrict(ormIP $oIp);

	abstract public function IsEqual(ormIP $oIp);

	abstract public function BitwiseAnd(ormIP $oIp);

	abstract public function BitwiseOr(ormIP $oIp);
	
	abstract public function BitwiseNot();
	
	abstract public function LeftShift();

	abstract public function IP2dec();

	abstract public function Add(ormIP $oIp);

	abstract public function GetNextIp();

	abstract public function GetPreviousIp();

	abstract public function GetSizeInterval(ormIP $oIp);
}

/**********************
 * Host Name Attribute
 */

class AttributeHostName extends AttributeString
{
	public function GetValidationPattern()
	{
		// By default, pattern matches RFC 1123 plus '_'
		// Factorize old regex and protect against backtracking
		//   Old regex: ^(\d|[a-z]|[A-Z]|_)(\d|[a-z]|[A-Z]|-|_)*$
		//   Right regex with atomic grouping: ^(? >\w[\w-]*)$ (no space between ? and >)
		//   Working regex:
		return('^(?=(\w[\w-]*))\1$');
	}
}

/************************
 * Domain Name Attribute
 */

class AttributeDomainName extends AttributeString
{
	public function GetValidationPattern() 
	{
		// By default, pattern matches RFC 1123 plus '_'
		// Factorize old regex and protect against backtracking
		//   Old regex ^(\d|[a-z]|[A-Z]|-|_)+((\.(\d|[a-z]|[A-Z]|-|_)+)*)\.?$
		//   Right regex with atomic grouping: ^(? >\w[\w-]*(\.\w[\w-]*)*\.?)$ (no space between ? and >)
		//   Working regex:
		return ('^(?=(\w[\w-]*(\.\w[\w-]*)*\.?))\1$');
	}
}

/************************
 * Alias List Attribute
 */

class AttributeAliasList extends AttributeText
{
	public function GetValidationPattern()
	{
		// By default, pattern matches a domain name per line
		//   Right regex with atomic grouping: ^(? >(\w[\w-]*(\.\w[\w-]*)*\.?)+(((\R|\n)\w[\w-]*(\.\w[\w-]*)*\.?))*))$ (no space between ? and >)
		//   \R works for PHP preg_match while \n works for javascript
		//   Working regex:
		return('^(?=((\w[\w-]*(\.\w[\w-]*)*\.?)+(((\R|\n)(\w[\w-]*(\.\w[\w-]*)*\.?))*)))\1$');
	}	
}

/************************
 * MAC Address Attribute
 */

class AttributeMacAddress extends AttributeString
{
	public function MakeRealValue($proposedValue, $oHostObj)
	{
		// Translate input value in canonical format used for storage
		// Input value = hyphens (12-34-56-78-90-ab), dots (1234.5678.90ab) or colons (12:34:56:78:90:ab)
		// Canonical Format = colons
		if ($proposedValue != '')
		{
			if ($proposedValue[2] == '-')
			{
				return(strtr($proposedValue, '-', ':'));
			}
			if ($proposedValue[4] == '.')
			{
				$proposedValue = str_replace('.', '', $proposedValue);
				$sOutputMac = '';
				$j = 0;
				for ($i = 0; $i < 12; $i++)
				{
					$sOutputMac[$i + $j] = $proposedValue[$i];
					if (($i > 0) && (is_int(($i - 1)/2)) && ($j < 5))
					{
						$j++;
						$sOutputMac[$i + $j] = ':';
					}
				}
				return(implode('',$sOutputMac));
			}
		}
		return ($proposedValue);
	}
	
	protected function GetMacAtFormat($sMac, $oHostObject)
	{
		// Return $sMac at format set by global parameters
		if (($sMac != '') && ($oHostObject != null))
		{
			$sMacAddressOutputFormat = $oHostObject->GetAttributeParams($this->GetCode());
			switch($sMacAddressOutputFormat)
			{
				case 'hyphens':
				// Return hyphens format
				return(strtr($sMac, ':', '-'));
				
				case 'dots':
				// Return dots format
				$sMac = str_replace(':', '', $sMac);
				$sOutputMac = '';
				$j = 0;
				for ($i = 0; $i < 12; $i++)
				{
					$sOutputMac[$i + $j] = $sMac[$i];
					if (($i == 3) || ($i == 7))
					{
						$j++;
						$sOutputMac[$i + $j] = '.';
					}
				}
				return(implode('',$sOutputMac));
				
				case 'colons':
				default:
				break;
			}
		}
		// Return default = registered = colons format
		return($sMac);
	}
	
	public function GetAsCSV($sValue, $sSeparator = ',', $sTextQualifier = '"', $oHostObject = null, $bLocalize = true)
	{
		$sFrom = array("\r\n", $sTextQualifier);
		$sTo = array("\n", $sTextQualifier.$sTextQualifier);
		$sEscaped = str_replace($sFrom, $sTo, (string)$this->GetMacAtFormat($sValue, $oHostObject));
		return $sTextQualifier.$sEscaped.$sTextQualifier;
	}

	public function GetAsHTML($sValue, $oHostObject = null, $bLocalize = true)
	{
		return Str::pure2html((string)$this->GetMacAtFormat($sValue, $oHostObject));
	}

	public function GetAsXML($sValue, $oHostObject = null, $bLocalize = true)
	{
		// XML being used by programs, we return canonical value of MAC 
		return Str::pure2xml((string)$sValue);
	}

	public function GetEditValue($sAttCode, $oHostObject = null)
	{
		return (string)$this->GetMacAtFormat($sAttCode, $oHostObject);
	}
	
	public function GetValidationPattern()
	{
		// By default, all 3 official pattern (colons, hyphens, dots) are accepted as input
		return('^((\d|([a-f]|[A-F])){2}-){5}(\d|([a-f]|[A-F])){2}$|^((\d|([a-f]|[A-F])){4}.){2}(\d|([a-f]|[A-F])){4}$|^((\d|([a-f]|[A-F])){2}:){5}(\d|([a-f]|[A-F])){2}$');
	}
}

/***********************
 * Percentage Attribute
 */

class AttributeIPPercentage extends AttributeInteger
{
	public function GetAsHTML($sValue, $oHostObject = null, $bLocalize = true)
	{
		// Display attribute as bar graph. Value & colors are provided by object holding attribute. 
		$iWidth = 5; // Total width of the percentage bar graph, in em...
		if ($oHostObject != null)
		{
			$aParams = array();
			$aParams = $oHostObject->GetAttributeParams($this->GetCode());
			$sValue = $aParams ['value'];
			$sColor = $aParams ['color'];
		}
		else
		{
			$sValue = 0;
			$sColor = GREEN;
		}
		$iValue = (int)$sValue;
		$iPercentWidth = ($iWidth * $iValue) / 100;
		return "<div style=\"width:{$iWidth}em;-moz-border-radius: 3px;-webkit-border-radius: 3px;border-radius: 3px;display:inline-block;border: 1px #ccc solid;\"><div style=\"width:{$iPercentWidth}em; display:inline-block;background-color:$sColor;\">&nbsp;</div></div>&nbsp;$sValue %";
	}

	public function GetAsCSV($sValue, $sSeparator = ',', $sTextQualifier = '"', $oHostObject = null, $bLocalize = true)
	{
		if ($oHostObject != null)
		{
			$aParams = array();
			$aParams = $oHostObject->GetAttributeParams($this->GetCode());
			$sValue = $aParams ['value'];
		}
		else
		{
			$sValue = 0;
		}
		//$sEscaped = (string)mylong2ip($sValue);
		$sEscaped = (string)$sValue;
		return $sTextQualifier.$sEscaped.$sTextQualifier;
	}
}

/**************************
 * Functions to handle IPs
 */
 
function myip2long($sIp)
{
	//return(($sIp == '255.255.255.255') ? MAX_IPV4_VALUE : ip2long($sIp)); // Doesn't work for IPs > 128.0.0.0
	return(($sIp == '255.255.255.255') ? MAX_IPV4_VALUE : sprintf("%u", ip2long($sIp))); // OK so far... 
} 

function mylong2ip ($iIp)
{
	return(long2ip($iIp));
}

/******************************
 * Triggers related to IP classes
 *  . IPTriggerOnWaterMark 
 */

class IPTriggerOnWaterMark extends Trigger
{
	public static function Init()
	{
		$aParams = array
		(
			"category" => "bizmodel",
			"key_type" => "autoincrement",
			"name_attcode" => "description",
			"state_attcode" => "",
			"reconc_keys" => array(),
			"db_table" => "priv_trigger_onwatermark",
			"db_key_field" => "id",
			"db_finalclass_field" => "",
			"display_template" => "",
			"icon" => utils::GetAbsoluteUrlModulesRoot().'teemip-ip-mgmt/images/ipbell.png',
		);
		MetaModel::Init_Params($aParams);
		MetaModel::Init_InheritAttributes();
		
		MetaModel::Init_AddAttribute(new AttributeExternalKey("org_id", array("targetclass"=>"Organization", "jointype"=>null, "allowed_values"=>null, "sql"=>"org_id", "is_null_allowed"=>false, "on_target_delete"=>DEL_MANUAL, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeExternalField("org_name", array("allowed_values"=>null, "extkey_attcode"=>'org_id', "target_attcode"=>'name')));
		MetaModel::Init_AddAttribute(new AttributeEnum("target_class", array("allowed_values"=>new ValueSetEnum('IPv4Subnet,IPv4Range,IPv6Subnet,IPv6Range'), "sql"=>"target_class", "default_value"=>"IPv4Subnet", "is_null_allowed"=>false, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeEnum("event", array("allowed_values"=>new ValueSetEnum('cross_high,cross_low'), "sql"=>"event", "default_value"=>"cross_high", "is_null_allowed"=>true, "depends_on"=>array())));
		
		// Display lists
		MetaModel::Init_SetZListItems('details', array('org_id', 'description', 'target_class', 'event', 'action_list')); // Attributes to be displayed for the complete details
		MetaModel::Init_SetZListItems('list', array('finalclass', 'target_class', 'description', 'event', 'org_id')); // Attributes to be displayed for a list
	}
	
	public function IsInScope(DBObject $oObject)
	{
		$sTargetClass = $this->Get('target_class');
		return  ($oObject instanceof $sTargetClass);
	}
}

/***********************************
 * Plugin to extend the Popup Menus
 */

class IPMgmtExtraMenus implements iPopupMenuExtension
{
	public static function EnumItems($iMenuId, $param)
	{
		switch($iMenuId)
		{
			case iPopupMenuExtension::MENU_OBJLIST_ACTIONS:	// $param is a DBObjectSet
				$oSet = $param;
				if ($oSet->Count() == 1)
				{
					// Menu for single objects only 
					$oObj = $oSet->Fetch();
					
					// Additional actions for IPBlocks
					if ($oObj instanceof IPBlock)
					{
						$operation = utils::ReadParam('operation', '');
						$sClass = get_class($oObj);
						
						// Unique org is selected as we have a single object
						$id = $oObj->GetKey();
						$iBlockSize = $oObj->GetBlockSize();
						
						$oAppContext = new ApplicationContext();
						$aParams = $oAppContext->GetAsHash();
						$aParams['class'] = $sClass;
						$aParams['id'] = $id;
						$aParams['filter'] = $param->GetFilter()->serialize();
						switch ($operation)
						{
							case 'displaytree':
								if (UserRights::IsActionAllowed($sClass, UR_ACTION_MODIFY) == UR_ALLOWED_YES)
								{
									if ($iBlockSize == 1)
									{
										$aResult[] = new SeparatorPopupMenuItem();
										$aParams['operation'] = 'delegate';
										$sMenu = 'UI:IPManagement:Action:Delegate:'.$sClass;
										$aResult[] = new URLPopupMenuItem($sMenu, Dict::S($sMenu), utils::GetAbsoluteUrlModulePage('teemip-ip-mgmt', 'ui.teemip-ip-mgmt.php', $aParams));
										
										$aResult[] = new SeparatorPopupMenuItem();						
										$aParams['operation'] = 'expandblock';
										$sMenu = 'UI:IPManagement:Action:Expand:'.$sClass;
										$aResult[] = new URLPopupMenuItem($sMenu, Dict::S($sMenu), utils::GetAbsoluteUrlModulePage('teemip-ip-mgmt', 'ui.teemip-ip-mgmt.php', $aParams));
										
										$aResult[] = new SeparatorPopupMenuItem();
										$aParams['operation'] = 'listspace';
										$sMenu = 'UI:IPManagement:Action:ListSpace:'.$sClass;
										$aResult[] = new URLPopupMenuItem($sMenu, Dict::S($sMenu), utils::GetAbsoluteUrlModulePage('teemip-ip-mgmt', 'ui.teemip-ip-mgmt.php', $aParams));
									}
									else
									{
										$aResult[] = new SeparatorPopupMenuItem();
										$aParams['operation'] = 'delegate';
										$sMenu = 'UI:IPManagement:Action:Delegate:'.$sClass;
										$aResult[] = new URLPopupMenuItem($sMenu, Dict::S($sMenu), utils::GetAbsoluteUrlModulePage('teemip-ip-mgmt', 'ui.teemip-ip-mgmt.php', $aParams));
										
										$aResult[] = new SeparatorPopupMenuItem();
										$aParams['operation'] = 'shrinkblock';
										$sMenu = 'UI:IPManagement:Action:Shrink:'.$sClass;
										$aResult[] = new URLPopupMenuItem($sMenu, Dict::S($sMenu), utils::GetAbsoluteUrlModulePage('teemip-ip-mgmt', 'ui.teemip-ip-mgmt.php', $aParams));
										
										$aParams['operation'] = 'splitblock';
										$sMenu = 'UI:IPManagement:Action:Split:'.$sClass;
										$aResult[] = new URLPopupMenuItem($sMenu, Dict::S($sMenu), utils::GetAbsoluteUrlModulePage('teemip-ip-mgmt', 'ui.teemip-ip-mgmt.php', $aParams));
										
										$aParams['operation'] = 'expandblock';
										$sMenu = 'UI:IPManagement:Action:Expand:'.$sClass;
										$aResult[] = new URLPopupMenuItem($sMenu, Dict::S($sMenu), utils::GetAbsoluteUrlModulePage('teemip-ip-mgmt', 'ui.teemip-ip-mgmt.php', $aParams));
										$aResult[] = new SeparatorPopupMenuItem();
													
										$aParams['operation'] = 'listspace';
										$sMenu = 'UI:IPManagement:Action:ListSpace:'.$sClass;
										$aResult[] = new URLPopupMenuItem($sMenu, Dict::S($sMenu), utils::GetAbsoluteUrlModulePage('teemip-ip-mgmt', 'ui.teemip-ip-mgmt.php', $aParams));

										$aParams['operation'] = 'findspace';
										$sMenu = 'UI:IPManagement:Action:FindSpace:'.$sClass;
										$aResult[] = new URLPopupMenuItem($sMenu, Dict::S($sMenu), utils::GetAbsoluteUrlModulePage('teemip-ip-mgmt', 'ui.teemip-ip-mgmt.php', $aParams));
									}
								}
								else
								{
									$aResult[] = new SeparatorPopupMenuItem();
									$aParams['operation'] = 'listspace';
									$sMenu = 'UI:IPManagement:Action:ListSpace:'.$sClass;
									$aResult[] = new URLPopupMenuItem($sMenu, Dict::S($sMenu), utils::GetAbsoluteUrlModulePage('teemip-ip-mgmt', 'ui.teemip-ip-mgmt.php', $aParams));
								}
							break;
										
							case 'displaylist':
							default:
								if (UserRights::IsActionAllowed($sClass, UR_ACTION_MODIFY) == UR_ALLOWED_YES)
								{
										$aResult[] = new SeparatorPopupMenuItem();
										$aParams['operation'] = 'delegate';
										$sMenu = 'UI:IPManagement:Action:Delegate:'.$sClass;
										$aResult[] = new URLPopupMenuItem($sMenu, Dict::S($sMenu), utils::GetAbsoluteUrlModulePage('teemip-ip-mgmt', 'ui.teemip-ip-mgmt.php', $aParams));
										
										$aResult[] = new SeparatorPopupMenuItem();
										$aParams['operation'] = 'shrinkblock';
										$sMenu = 'UI:IPManagement:Action:Shrink:'.$sClass;
										$aResult[] = new URLPopupMenuItem($sMenu, Dict::S($sMenu), utils::GetAbsoluteUrlModulePage('teemip-ip-mgmt', 'ui.teemip-ip-mgmt.php', $aParams));
										
										$aParams['operation'] = 'splitblock';
										$sMenu = 'UI:IPManagement:Action:Split:'.$sClass;
										$aResult[] = new URLPopupMenuItem($sMenu, Dict::S($sMenu), utils::GetAbsoluteUrlModulePage('teemip-ip-mgmt', 'ui.teemip-ip-mgmt.php', $aParams));
										
										$aParams['operation'] = 'expandblock';
										$sMenu = 'UI:IPManagement:Action:Expand:'.$sClass;
										$aResult[] = new URLPopupMenuItem($sMenu, Dict::S($sMenu), utils::GetAbsoluteUrlModulePage('teemip-ip-mgmt', 'ui.teemip-ip-mgmt.php', $aParams));
										$aResult[] = new SeparatorPopupMenuItem();
													
										$aParams['operation'] = 'listspace';
										$sMenu = 'UI:IPManagement:Action:ListSpace:'.$sClass;
										$aResult[] = new URLPopupMenuItem($sMenu, Dict::S($sMenu), utils::GetAbsoluteUrlModulePage('teemip-ip-mgmt', 'ui.teemip-ip-mgmt.php', $aParams));

										$aParams['operation'] = 'findspace';
										$sMenu = 'UI:IPManagement:Action:FindSpace:'.$sClass;
										$aResult[] = new URLPopupMenuItem($sMenu, Dict::S($sMenu), utils::GetAbsoluteUrlModulePage('teemip-ip-mgmt', 'ui.teemip-ip-mgmt.php', $aParams));
								}
								else
								{
									$aResult[] = new SeparatorPopupMenuItem();
									$aParams['operation'] = 'listspace';
									$sMenu = 'UI:IPManagement:Action:ListSpace:'.$sClass;
									$aResult[] = new URLPopupMenuItem($sMenu, Dict::S($sMenu), utils::GetAbsoluteUrlModulePage('teemip-ip-mgmt', 'ui.teemip-ip-mgmt.php', $aParams));
								}
							break;
						}
					}
					// Additional actions for IPv4Subnets
					elseif ($oObj instanceof IPSubnet)
					{
						$operation = utils::ReadParam('operation', '');
						$sClass = get_class($oObj);
						
						// Unique org is selected as we have a single object
						$id = $oObj->GetKey();
						
						$oAppContext = new ApplicationContext();
						$aParams = $oAppContext->GetAsHash();
						$aParams['class'] = $sClass;
						$aParams['id'] = $id;
						$aParams['filter'] = $param->GetFilter()->serialize();
						if ($oObj instanceof IPv4Subnet)
						{
							if (UserRights::IsActionAllowed('IPv4Subnet', UR_ACTION_MODIFY) == UR_ALLOWED_YES)
							{
								$aResult[] = new SeparatorPopupMenuItem();
								$aParams['operation'] = 'shrinksubnet';
								$sMenu = 'UI:IPManagement:Action:Shrink:IPv4Subnet';
								$aResult[] = new URLPopupMenuItem($sMenu, Dict::S($sMenu), utils::GetAbsoluteUrlModulePage('teemip-ip-mgmt', 'ui.teemip-ip-mgmt.php', $aParams));
							
								$aParams['operation'] = 'splitsubnet';
								$sMenu = 'UI:IPManagement:Action:Split:IPv4Subnet';
								$aResult[] = new URLPopupMenuItem($sMenu, Dict::S($sMenu), utils::GetAbsoluteUrlModulePage('teemip-ip-mgmt', 'ui.teemip-ip-mgmt.php', $aParams));
								
								$aParams['operation'] = 'expandsubnet';
								$sMenu = 'UI:IPManagement:Action:Expand:IPv4Subnet';
								$aResult[] = new URLPopupMenuItem($sMenu, Dict::S($sMenu), utils::GetAbsoluteUrlModulePage('teemip-ip-mgmt', 'ui.teemip-ip-mgmt.php', $aParams));
								$aResult[] = new SeparatorPopupMenuItem();
											
								$aParams['operation'] = 'listips';
								$sMenu = 'UI:IPManagement:Action:ListIps:IPv4Subnet';
								$aResult[] = new URLPopupMenuItem($sMenu, Dict::S($sMenu), utils::GetAbsoluteUrlModulePage('teemip-ip-mgmt', 'ui.teemip-ip-mgmt.php', $aParams));
								$aParams['operation'] = 'findspace';
								$sMenu = 'UI:IPManagement:Action:FindSpace:IPv4Subnet';
								$aResult[] = new URLPopupMenuItem($sMenu, Dict::S($sMenu), utils::GetAbsoluteUrlModulePage('teemip-ip-mgmt', 'ui.teemip-ip-mgmt.php', $aParams));
								
								$aParams['operation'] = 'csvexportips';
								$sMenu = 'UI:IPManagement:Action:CsvExportIps:IPv4Subnet';
								$aResult[] = new URLPopupMenuItem($sMenu, Dict::S($sMenu), utils::GetAbsoluteUrlModulePage('teemip-ip-mgmt', 'ui.teemip-ip-mgmt.php', $aParams));
							}
							else
							{
								$aResult[] = new SeparatorPopupMenuItem();
											
								$aParams['operation'] = 'listips';
								$sMenu = 'UI:IPManagement:Action:ListIps:IPv4Subnet';
								$aResult[] = new URLPopupMenuItem($sMenu, Dict::S($sMenu), utils::GetAbsoluteUrlModulePage('teemip-ip-mgmt', 'ui.teemip-ip-mgmt.php', $aParams));
	
								$aParams['operation'] = 'csvexportips';
								$sMenu = 'UI:IPManagement:Action:CsvExportIps:IPv4Subnet';
								$aResult[] = new URLPopupMenuItem($sMenu, Dict::S($sMenu), utils::GetAbsoluteUrlModulePage('teemip-ip-mgmt', 'ui.teemip-ip-mgmt.php', $aParams));
							}
						}
						// Additional actions for IPv6Subnets
						elseif ($oObj instanceof IPv6Subnet)
						{
							$operation = utils::ReadParam('operation', '');
							
							// Unique org is selected as we have a single object
							$id = $oObj->GetKey();
							
							$oAppContext = new ApplicationContext();
							$aParams = $oAppContext->GetAsHash();
							$aParams['class'] = $sClass;
							$aParams['id'] = $id;
							$aParams['filter'] = $param->GetFilter()->serialize();
							switch ($operation)
							{
								case 'displaytree':
								case 'displaylist':
								default:
									if (UserRights::IsActionAllowed('IPv6Subnet', UR_ACTION_MODIFY) == UR_ALLOWED_YES)
									{
										$aResult[] = new SeparatorPopupMenuItem();
													
										$aParams['operation'] = 'listips';
										$sMenu = 'UI:IPManagement:Action:ListIps:IPv6Subnet';
										$aResult[] = new URLPopupMenuItem($sMenu, Dict::S($sMenu), utils::GetAbsoluteUrlModulePage('teemip-ip-mgmt', 'ui.teemip-ip-mgmt.php', $aParams));
										
										$aParams['operation'] = 'findspace';
										$sMenu = 'UI:IPManagement:Action:FindSpace:IPv6Subnet';
										$aResult[] = new URLPopupMenuItem($sMenu, Dict::S($sMenu), utils::GetAbsoluteUrlModulePage('teemip-ip-mgmt', 'ui.teemip-ip-mgmt.php', $aParams));
										
										$aParams['operation'] = 'csvexportips';
										$sMenu = 'UI:IPManagement:Action:CsvExportIps:IPv6Subnet';
										$aResult[] = new URLPopupMenuItem($sMenu, Dict::S($sMenu), utils::GetAbsoluteUrlModulePage('teemip-ip-mgmt', 'ui.teemip-ip-mgmt.php', $aParams));
									}
									else
									{
										$aResult[] = new SeparatorPopupMenuItem();
													
										$aParams['operation'] = 'listips';
										$sMenu = 'UI:IPManagement:Action:ListIps:IPv6Subnet';
										$aResult[] = new URLPopupMenuItem($sMenu, Dict::S($sMenu), utils::GetAbsoluteUrlModulePage('teemip-ip-mgmt', 'ui.teemip-ip-mgmt.php', $aParams));
										
										$aParams['operation'] = 'csvexportips';
										$sMenu = 'UI:IPManagement:Action:CsvExportIps:IPv6Subnet';
										$aResult[] = new URLPopupMenuItem($sMenu, Dict::S($sMenu), utils::GetAbsoluteUrlModulePage('teemip-ip-mgmt', 'ui.teemip-ip-mgmt.php', $aParams));
									}
								break;
							}
						}
					}
					else
					{
						$aResult = array();
					}
				}
				else
				{
					$aResult = array();
				}
			break;
			
			case iPopupMenuExtension::MENU_OBJLIST_TOOLKIT: // $param is a DBObjectSet
				$oSet = $param;
				$aResult = array();
				$oObj = $oSet->Fetch();
				
				// Additional actions for IPBlocks
				if ($oObj instanceof IPBlock)
				{
					$operation = utils::ReadParam('operation', '');
					$sClass = get_class($oObj);
					
					$oAppContext = new ApplicationContext();
					$aParams = $oAppContext->GetAsHash();
					$aParams['class'] = $sClass;
					switch ($operation)
					{
						case 'displaytree':
							$aResult[] = new SeparatorPopupMenuItem();
							$aParams['operation'] = 'displaylist';
							$sMenu = 'UI:IPManagement:Action:DisplayList:'.$sClass;
							$aResult[] = new URLPopupMenuItem($sMenu, Dict::S($sMenu), utils::GetAbsoluteUrlModulePage('teemip-ip-mgmt', 'ui.teemip-ip-mgmt.php', $aParams));
						break;
										
						case 'displaylist':
						default:
							$aResult[] = new SeparatorPopupMenuItem();
							$aParams['operation'] = 'displaytree';
							$sMenu = 'UI:IPManagement:Action:DisplayTree:'.$sClass;
							$aResult[] = new URLPopupMenuItem($sMenu, Dict::S($sMenu), utils::GetAbsoluteUrlModulePage('teemip-ip-mgmt', 'ui.teemip-ip-mgmt.php', $aParams));
						break;
					}
				}
				// Additional actions for IPSubnets
				else if ($oObj instanceof IPSubnet)
				{
					$operation = utils::ReadParam('operation', '');
					$sClass = get_class($oObj);
					
					$oAppContext = new ApplicationContext();
					$aParams = $oAppContext->GetAsHash();
					$aParams['class'] = $sClass;
					switch ($operation)
					{
						case 'displaytree':
							$aResult[] = new SeparatorPopupMenuItem();
							$aParams['operation'] = 'displaylist';
							$sMenu = 'UI:IPManagement:Action:DisplayList:'.$sClass;
							$aResult[] = new URLPopupMenuItem($sMenu, Dict::S($sMenu), utils::GetAbsoluteUrlModulePage('teemip-ip-mgmt', 'ui.teemip-ip-mgmt.php', $aParams));
						break;
						
						case 'docalculator':
							$aResult[] = new SeparatorPopupMenuItem();
							$aParams['operation'] = 'displaylist';
							$sMenu = 'UI:IPManagement:Action:DisplayList:'.$sClass;
							$aResult[] = new URLPopupMenuItem($sMenu, Dict::S($sMenu), utils::GetAbsoluteUrlModulePage('teemip-ip-mgmt', 'ui.teemip-ip-mgmt.php', $aParams));
							$aParams['operation'] = 'displaytree';
							$sMenu = 'UI:IPManagement:Action:DisplayTree:'.$sClass;
							$aResult[] = new URLPopupMenuItem($sMenu, Dict::S($sMenu), utils::GetAbsoluteUrlModulePage('teemip-ip-mgmt', 'ui.teemip-ip-mgmt.php', $aParams));
						break;
										
						case 'displaylist':
						default:
							$aResult[] = new SeparatorPopupMenuItem();
							$aParams['operation'] = 'displaytree';
							$sMenu = 'UI:IPManagement:Action:DisplayTree:'.$sClass;
							$aResult[] = new URLPopupMenuItem($sMenu, Dict::S($sMenu), utils::GetAbsoluteUrlModulePage('teemip-ip-mgmt', 'ui.teemip-ip-mgmt.php', $aParams));
						break;
					}
					$aResult[] = new SeparatorPopupMenuItem();
					$aParams['operation'] = 'calculator';
					$sMenu = 'UI:IPManagement:Action:Calculator:'.$sClass;
					$aResult[] = new URLPopupMenuItem($sMenu, Dict::S($sMenu), utils::GetAbsoluteUrlModulePage('teemip-ip-mgmt', 'ui.teemip-ip-mgmt.php', $aParams));
				}
				// Additional actions for Domain
				elseif ($oObj instanceof Domain)
				{
					$operation = utils::ReadParam('operation', '');
					$sClass = get_class($oObj);
					
					$oAppContext = new ApplicationContext();
					$aParams = $oAppContext->GetAsHash();
					$aParams['class'] = $sClass;
					switch ($operation)
					{
						case 'displaytree':
							$sContext = $oAppContext->GetForLink();
							$sFilter = utils::ReadParam('filter', '');
							$aResult[] = new SeparatorPopupMenuItem();
							$sMenu = 'UI:IPManagement:Action:DisplayList:Domain';
							$aResult[] = new URLPopupMenuItem($sMenu, Dict::S($sMenu), utils::GetAbsoluteUrlAppRoot()."pages/UI.php?operation=search&$sContext&filter=$sFilter");
						break;
										
						case 'displaylist':
						default:
							$aResult[] = new SeparatorPopupMenuItem();
							$aParams['operation'] = 'displaytree';
							$sMenu = 'UI:IPManagement:Action:DisplayTree:Domain';
							$aResult[] = new URLPopupMenuItem($sMenu, Dict::S($sMenu), utils::GetAbsoluteUrlModulePage('teemip-ip-mgmt', 'ui.teemip-ip-mgmt.php', $aParams));
						break;
					}
				}
				else
				{
					$aResult = array();
				}
			break;
			
			case iPopupMenuExtension::MENU_OBJDETAILS_ACTIONS: // $param is a DBObject
				$oObj = $param;
				$aResult = array();
				
				// Additional actions for IPBlocks
				if ($oObj instanceof IPBlock)
				{
					$oAppContext = new ApplicationContext();
					$sContext = $oAppContext->GetForLink();

					$id = $oObj->GetKey();
					$iBlockSize = $oObj->GetBlockSize();						
					$sClass = get_class($oObj);
					
					$aParams = $oAppContext->GetAsHash();
					$aParams['class'] = $sClass;
					$aParams['id'] = $id;
					if (UserRights::IsActionAllowed('IPBlock', UR_ACTION_MODIFY) == UR_ALLOWED_YES)
					{
						if ($iBlockSize == 1)
						{
							$aResult[] = new SeparatorPopupMenuItem();
							$aParams['operation'] = 'delegate';
							$sMenu = 'UI:IPManagement:Action:Delegate:'.$sClass;
							$aResult[] = new URLPopupMenuItem($sMenu, Dict::S($sMenu), utils::GetAbsoluteUrlModulePage('teemip-ip-mgmt', 'ui.teemip-ip-mgmt.php', $aParams));
							
							$aResult[] = new SeparatorPopupMenuItem();								
							$aParams['operation'] = 'expandblock';
							$sMenu = 'UI:IPManagement:Action:Expand:'.$sClass;
							$aResult[] = new URLPopupMenuItem($sMenu, Dict::S($sMenu), utils::GetAbsoluteUrlModulePage('teemip-ip-mgmt', 'ui.teemip-ip-mgmt.php', $aParams));
							$aResult[] = new SeparatorPopupMenuItem();
						}
						else
						{
							$aResult[] = new SeparatorPopupMenuItem();
							$aParams['operation'] = 'delegate';
							$sMenu = 'UI:IPManagement:Action:Delegate:'.$sClass;
							$aResult[] = new URLPopupMenuItem($sMenu, Dict::S($sMenu), utils::GetAbsoluteUrlModulePage('teemip-ip-mgmt', 'ui.teemip-ip-mgmt.php', $aParams));
							
							$aResult[] = new SeparatorPopupMenuItem();
							$aParams['operation'] = 'shrinkblock';
							$sMenu = 'UI:IPManagement:Action:Shrink:'.$sClass;
							$aResult[] = new URLPopupMenuItem($sMenu, Dict::S($sMenu), utils::GetAbsoluteUrlModulePage('teemip-ip-mgmt', 'ui.teemip-ip-mgmt.php', $aParams));
							
							$aParams['operation'] = 'splitblock';
							$sMenu = 'UI:IPManagement:Action:Split:'.$sClass;
							$aResult[] = new URLPopupMenuItem($sMenu, Dict::S($sMenu), utils::GetAbsoluteUrlModulePage('teemip-ip-mgmt', 'ui.teemip-ip-mgmt.php', $aParams));
							
							$aParams['operation'] = 'expandblock';
							$sMenu = 'UI:IPManagement:Action:Expand:'.$sClass;
							$aResult[] = new URLPopupMenuItem($sMenu, Dict::S($sMenu), utils::GetAbsoluteUrlModulePage('teemip-ip-mgmt', 'ui.teemip-ip-mgmt.php', $aParams));
							$aResult[] = new SeparatorPopupMenuItem();
						}
					}
					$operation = utils::ReadParam('operation', '');
					switch ($operation)
					{
						case 'apply_new':
						case 'apply_modify':
						case 'details':
							$aParams['operation'] = 'listspace';
							$sMenu = 'UI:IPManagement:Action:ListSpace:'.$sClass;
							$aResult[] = new URLPopupMenuItem($sMenu, Dict::S($sMenu), utils::GetAbsoluteUrlModulePage('teemip-ip-mgmt', 'ui.teemip-ip-mgmt.php', $aParams));
							if (UserRights::IsActionAllowed($sClass, UR_ACTION_MODIFY) == UR_ALLOWED_YES)
							{
								if ($iBlockSize > 1)
								{
									$aParams['operation'] = 'findspace';
									$sMenu = 'UI:IPManagement:Action:FindSpace:'.$sClass;
									$aResult[] = new URLPopupMenuItem($sMenu, Dict::S($sMenu), utils::GetAbsoluteUrlModulePage('teemip-ip-mgmt', 'ui.teemip-ip-mgmt.php', $aParams));
								}
							}
						break;
						
						case 'listspace':
							if (UserRights::IsActionAllowed($sClass, UR_ACTION_MODIFY) == UR_ALLOWED_YES)
							{
								$aParams['operation'] = 'findspace';
								$sMenu = 'UI:IPManagement:Action:FindSpace:'.$sClass;
								$aResult[] = new URLPopupMenuItem($sMenu, Dict::S($sMenu), utils::GetAbsoluteUrlModulePage('teemip-ip-mgmt', 'ui.teemip-ip-mgmt.php', $aParams));
							}
							$aResult[] = new URLPopupMenuItem('UI:IPManagement:Action:Details:'.$sClass, Dict::S('UI:IPManagement:Action:Details:'.$sClass), utils::GetAbsoluteUrlAppRoot()."pages/UI.php?operation=details&class=$sClass&id=$id&$sContext");
						break;
						
						case 'dofindspace':
							$aParams['operation'] = 'listspace';
							$sMenu = 'UI:IPManagement:Action:ListSpace:'.$sClass;
							$aResult[] = new URLPopupMenuItem($sMenu, Dict::S($sMenu), utils::GetAbsoluteUrlModulePage('teemip-ip-mgmt', 'ui.teemip-ip-mgmt.php', $aParams));
							if (UserRights::IsActionAllowed($sClass, UR_ACTION_MODIFY) == UR_ALLOWED_YES)
							{
								$aParams['operation'] = 'findspace';
								$sMenu = 'UI:IPManagement:Action:FindSpace:'.$sClass;
								$aResult[] = new URLPopupMenuItem($sMenu, Dict::S($sMenu), utils::GetAbsoluteUrlModulePage('teemip-ip-mgmt', 'ui.teemip-ip-mgmt.php', $aParams));
							}
							$aResult[] = new URLPopupMenuItem('UI:IPManagement:Action:Details:'.$sClass, Dict::S('UI:IPManagement:Action:Details:'.$sClass), utils::GetAbsoluteUrlAppRoot()."pages/UI.php?operation=details&class=$sClass&id=$id&$sContext");
						break;
						
						default:
						break;
					}
				}
				// Additional actions for IPSubnets
				else if ($oObj instanceof IPSubnet)
				{
					$oAppContext = new ApplicationContext();
					$sContext = $oAppContext->GetForLink();
					
					$id = $oObj->GetKey();
					$sClass = get_class($oObj);

					$aParams = $oAppContext->GetAsHash();
					$aParams['class'] = $sClass;
					$aParams['id'] = $id;
					if ($oObj instanceof IPv4Subnet)
					{
						if (UserRights::IsActionAllowed('IPv4Subnet', UR_ACTION_MODIFY) == UR_ALLOWED_YES)
						{
							$aResult[] = new SeparatorPopupMenuItem();
							$aParams['operation'] = 'shrinksubnet';
							$sMenu = 'UI:IPManagement:Action:Shrink:IPv4Subnet';
							$aResult[] = new URLPopupMenuItem($sMenu, Dict::S($sMenu), utils::GetAbsoluteUrlModulePage('teemip-ip-mgmt', 'ui.teemip-ip-mgmt.php', $aParams));

							$aParams['operation'] = 'splitsubnet';
							$sMenu = 'UI:IPManagement:Action:Split:IPv4Subnet';
							$aResult[] = new URLPopupMenuItem($sMenu, Dict::S($sMenu), utils::GetAbsoluteUrlModulePage('teemip-ip-mgmt', 'ui.teemip-ip-mgmt.php', $aParams));
							
							$aParams['operation'] = 'expandsubnet';
							$sMenu = 'UI:IPManagement:Action:Expand:IPv4Subnet';
							$aResult[] = new URLPopupMenuItem($sMenu, Dict::S($sMenu), utils::GetAbsoluteUrlModulePage('teemip-ip-mgmt', 'ui.teemip-ip-mgmt.php', $aParams));
							$aResult[] = new SeparatorPopupMenuItem();
						}
					}
					else if ($oObj instanceof IPv6Subnet)
					{
						if (UserRights::IsActionAllowed('IPv6Subnet', UR_ACTION_MODIFY) == UR_ALLOWED_YES)
						{
							$aResult[] = new SeparatorPopupMenuItem();
						}
					}
					else
					{
						return array();
					}
					
					$operation = utils::ReadParam('operation', '');
					switch ($operation)
					{
						case 'apply_new':
						case 'apply_modify':
						case 'details':
							$aParams['operation'] = 'listips';
							$sMenu = 'UI:IPManagement:Action:ListIps:'.$sClass;
							$aResult[] = new URLPopupMenuItem($sMenu, Dict::S($sMenu), utils::GetAbsoluteUrlModulePage('teemip-ip-mgmt', 'ui.teemip-ip-mgmt.php', $aParams));
							if (UserRights::IsActionAllowed($sClass, UR_ACTION_MODIFY) == UR_ALLOWED_YES)
							{
								$aParams['operation'] = 'findspace';
								$sMenu = 'UI:IPManagement:Action:FindSpace:'.$sClass;
								$aResult[] = new URLPopupMenuItem($sMenu, Dict::S($sMenu), utils::GetAbsoluteUrlModulePage('teemip-ip-mgmt', 'ui.teemip-ip-mgmt.php', $aParams));
							}
							$aParams['operation'] = 'csvexportips';
							$sMenu = 'UI:IPManagement:Action:CsvExportIps:'.$sClass;
							$aResult[] = new URLPopupMenuItem($sMenu, Dict::S($sMenu), utils::GetAbsoluteUrlModulePage('teemip-ip-mgmt', 'ui.teemip-ip-mgmt.php', $aParams));
						break;
						
						case 'listips':
							if (UserRights::IsActionAllowed($sClass, UR_ACTION_MODIFY) == UR_ALLOWED_YES)
							{
								$aParams['operation'] = 'findspace';
								$sMenu = 'UI:IPManagement:Action:FindSpace:'.$sClass;
								$aResult[] = new URLPopupMenuItem($sMenu, Dict::S($sMenu), utils::GetAbsoluteUrlModulePage('teemip-ip-mgmt', 'ui.teemip-ip-mgmt.php', $aParams));
							}
							$aResult[] = new URLPopupMenuItem('UI:IPManagement:Action:Details:'.$sClass, Dict::S('UI:IPManagement:Action:Details:'.$sClass), utils::GetAbsoluteUrlAppRoot()."pages/UI.php?operation=details&class=$sClass&id=$id&$sContext");
							$aParams['operation'] = 'csvexportips';
							$sMenu = 'UI:IPManagement:Action:CsvExportIps:'.$sClass;
							$aResult[] = new URLPopupMenuItem($sMenu, Dict::S($sMenu), utils::GetAbsoluteUrlModulePage('teemip-ip-mgmt', 'ui.teemip-ip-mgmt.php', $aParams));
						break;
						
						case 'dofindspace':
						case 'docalculator':
							$aParams['operation'] = 'listips';
							$sMenu = 'UI:IPManagement:Action:ListIps:'.$sClass;
							$aResult[] = new URLPopupMenuItem($sMenu, Dict::S($sMenu), utils::GetAbsoluteUrlModulePage('teemip-ip-mgmt', 'ui.teemip-ip-mgmt.php', $aParams));
							if (UserRights::IsActionAllowed($sClass, UR_ACTION_MODIFY) == UR_ALLOWED_YES)
							{
								$aParams['operation'] = 'findspace';
								$sMenu = 'UI:IPManagement:Action:FindSpace:'.$sClass;
								$aResult[] = new URLPopupMenuItem($sMenu, Dict::S($sMenu), utils::GetAbsoluteUrlModulePage('teemip-ip-mgmt', 'ui.teemip-ip-mgmt.php', $aParams));
							}
							$sMenu = 'UI:IPManagement:Action:Details:'.$sClass;
							$aResult[] = new URLPopupMenuItem($sMenu, Dict::S($sMenu), utils::GetAbsoluteUrlAppRoot()."pages/UI.php?operation=details&class=$sClass&id=$id&$sContext");
							$aParams['operation'] = 'csvexportips';
							$sMenu = 'UI:IPManagement:Action:CsvExportIps:'.$sClass;
							$aResult[] = new URLPopupMenuItem($sMenu, Dict::S($sMenu), utils::GetAbsoluteUrlModulePage('teemip-ip-mgmt', 'ui.teemip-ip-mgmt.php', $aParams));
						break;
						
						case 'csvexportips':
							$aParams['operation'] = 'listips';
							$sMenu = 'UI:IPManagement:Action:ListIps:'.$sClass;
							$aResult[] = new URLPopupMenuItem($sMenu, Dict::S($sMenu), utils::GetAbsoluteUrlModulePage('teemip-ip-mgmt', 'ui.teemip-ip-mgmt.php', $aParams));
							if (UserRights::IsActionAllowed($sClass, UR_ACTION_MODIFY) == UR_ALLOWED_YES)
							{
								$aParams['operation'] = 'findspace';
								$sMenu = 'UI:IPManagement:Action:FindSpace:'.$sClass;
								$aResult[] = new URLPopupMenuItem($sMenu, Dict::S($sMenu), utils::GetAbsoluteUrlModulePage('teemip-ip-mgmt', 'ui.teemip-ip-mgmt.php', $aParams));
							}
							$sMenu = 'UI:IPManagement:Action:Details:'.$sClass;
							$aResult[] = new URLPopupMenuItem($sMenu, Dict::S($sMenu), utils::GetAbsoluteUrlAppRoot()."pages/UI.php?operation=details&class=$sClass&id=$id&$sContext");
						break;
						
						case 'dolistips':
						case 'docsvexportips':
							$aParams['operation'] = 'listips';
							$sMenu = 'UI:IPManagement:Action:ListIps:'.$sClass;
							$aResult[] = new URLPopupMenuItem($sMenu, Dict::S($sMenu), utils::GetAbsoluteUrlModulePage('teemip-ip-mgmt', 'ui.teemip-ip-mgmt.php', $aParams));
							if (UserRights::IsActionAllowed($sClass, UR_ACTION_MODIFY) == UR_ALLOWED_YES)
							{
								$aParams['operation'] = 'findspace';
								$sMenu = 'UI:IPManagement:Action:FindSpace:'.$sClass;
								$aResult[] = new URLPopupMenuItem($sMenu, Dict::S($sMenu), utils::GetAbsoluteUrlModulePage('teemip-ip-mgmt', 'ui.teemip-ip-mgmt.php', $aParams));
							}
							$sMenu = 'UI:IPManagement:Action:Details:'.$sClass;
							$aResult[] = new URLPopupMenuItem($sMenu, Dict::S($sMenu), utils::GetAbsoluteUrlAppRoot()."pages/UI.php?operation=details&class=$sClass&id=$id&$sContext");
							$aParams['operation'] = 'csvexportips';
							$sMenu = 'UI:IPManagement:Action:CsvExportIps:'.$sClass;
							$aResult[] = new URLPopupMenuItem($sMenu, Dict::S($sMenu), utils::GetAbsoluteUrlModulePage('teemip-ip-mgmt', 'ui.teemip-ip-mgmt.php', $aParams));
						break;
						
						default:
						break;
					}
					$aResult[] = new SeparatorPopupMenuItem();
					$aParams['operation'] = 'calculator';
					$sMenu = 'UI:IPManagement:Action:Calculator:'.$sClass;
					$aResult[] = new URLPopupMenuItem($sMenu, Dict::S($sMenu), utils::GetAbsoluteUrlModulePage('teemip-ip-mgmt', 'ui.teemip-ip-mgmt.php', $aParams));
				}
				// Additional actions for IPRange
				else if ($oObj instanceof IPRange)
				{
					$oAppContext = new ApplicationContext();
					$sContext = $oAppContext->GetForLink();

					$id = $oObj->GetKey();
					$sClass = get_class($oObj);
						
					$aParams = $oAppContext->GetAsHash();
					$aParams['class'] = $sClass;
					$aParams['id'] = $id;
					$operation = utils::ReadParam('operation', '');
					switch ($operation)
					{
						case 'listips':
							$aResult[] = new SeparatorPopupMenuItem();
							$sMenu = 'UI:IPManagement:Action:Details:'.$sClass;
							$aResult[] = new URLPopupMenuItem($sMenu, Dict::S($sMenu), utils::GetAbsoluteUrlAppRoot()."pages/UI.php?operation=details&class=$sClass&id=$id&$sContext");
								
							$aParams['operation'] = 'csvexportips';
							$sMenu = 'UI:IPManagement:Action:CsvExportIps:'.$sClass;
							$aResult[] = new URLPopupMenuItem($sMenu, Dict::S($sMenu), utils::GetAbsoluteUrlModulePage('teemip-ip-mgmt', 'ui.teemip-ip-mgmt.php', $aParams));
							break;
							
						case 'csvexportips':
							$aResult[] = new SeparatorPopupMenuItem();
							$aParams['operation'] = 'listips';
							$sMenu = 'UI:IPManagement:Action:ListIps:'.$sClass;
							$aResult[] = new URLPopupMenuItem($sMenu, Dict::S($sMenu), utils::GetAbsoluteUrlModulePage('teemip-ip-mgmt', 'ui.teemip-ip-mgmt.php', $aParams));
							
							$sMenu = 'UI:IPManagement:Action:Details:'.$sClass;
							$aResult[] = new URLPopupMenuItem($sMenu, Dict::S($sMenu), utils::GetAbsoluteUrlAppRoot()."pages/UI.php?operation=details&class=$sClass&id=$id&$sContext");
							break;
							
						case 'dolistips':
						case 'docsvexportips':
							$aResult[] = new SeparatorPopupMenuItem();
							$aParams['operation'] = 'listips';
							$sMenu = 'UI:IPManagement:Action:ListIps:'.$sClass;
							$aResult[] = new URLPopupMenuItem($sMenu, Dict::S($sMenu), utils::GetAbsoluteUrlModulePage('teemip-ip-mgmt', 'ui.teemip-ip-mgmt.php', $aParams));
							
							$sMenu = 'UI:IPManagement:Action:Details:'.$sClass;
							$aResult[] = new URLPopupMenuItem($sMenu, Dict::S($sMenu), utils::GetAbsoluteUrlAppRoot()."pages/UI.php?operation=details&class=$sClass&id=$id&$sContext");
							
							$aParams['operation'] = 'csvexportips';
							$sMenu = 'UI:IPManagement:Action:CsvExportIps:'.$sClass;
							$aResult[] = new URLPopupMenuItem($sMenu, Dict::S($sMenu), utils::GetAbsoluteUrlModulePage('teemip-ip-mgmt', 'ui.teemip-ip-mgmt.php', $aParams));
							break;
							
						default:
							$aResult[] = new SeparatorPopupMenuItem();
							$aParams['operation'] = 'listips';
							$sMenu = 'UI:IPManagement:Action:ListIps:'.$sClass;
							$aResult[] = new URLPopupMenuItem($sMenu, Dict::S($sMenu), utils::GetAbsoluteUrlModulePage('teemip-ip-mgmt', 'ui.teemip-ip-mgmt.php', $aParams));
							
							$aParams['operation'] = 'csvexportips';
							$sMenu = 'UI:IPManagement:Action:CsvExportIps:'.$sClass;
							$aResult[] = new URLPopupMenuItem($sMenu, Dict::S($sMenu), utils::GetAbsoluteUrlModulePage('teemip-ip-mgmt', 'ui.teemip-ip-mgmt.php', $aParams));
							break;
					}
				}
			break;
			
			case iPopupMenuExtension::MENU_DASHBOARD_ACTIONS:
				// $param is a Dashboard
				$aResult = array();
				break;
			
			default:
				// Unknown type of menu, do nothing
				$aResult = array();
				break;
		}
		return $aResult;
	}
}
