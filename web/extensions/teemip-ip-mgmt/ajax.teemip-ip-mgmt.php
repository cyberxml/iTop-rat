<?php
// Copyright (C) 2014 TeemIp
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
 * @copyright   Copyright (C) 2014 TeemIp
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

if (!defined('__DIR__')) define('__DIR__', dirname(__FILE__));
if (!defined('APPROOT')) require_once(__DIR__.'/../../approot.inc.php');
require_once(APPROOT.'/application/application.inc.php');
require_once(APPROOT.'/application/webpage.class.inc.php');
require_once(APPROOT.'/application/ajaxwebpage.class.inc.php');

/************************************************
 * 
 * Ajax interface for IP Mgmt module starts here
 *
 ************************************************/
try
{
	require_once(APPROOT.'/application/startup.inc.php');
	require_once(APPROOT.'/application/user.preferences.class.inc.php');
	
	require_once(APPROOT.'/application/loginwebpage.class.inc.php');
	LoginWebPage::DoLogin(); // Check user rights and prompt if needed
	
	$oP = new ajax_page("");
	$oP->no_cache();
	
	$oP->add_linked_script(utils::GetAbsoluteUrlModulesRoot()."teemip-ip-mgmt/teemip-ip-mgmt.js");
	
	$operation = utils::ReadParam('operation', '');
	$iVId = utils::ReadParam('vid', '');
	$sClass = utils::ReadParam('class', '');
	
	switch($operation)
	{
		case 'get_ip_creation_form':
			$aDefault = utils::ReadParam('default', array(), false, 'raw_data');
			$oObj = MetaModel::NewObject($sClass);
			foreach($aDefault as $sAttCode => $value)
			{
				if (MetaModel::isValidAttCode($sClass, $sAttCode))
				{
					$oObj->Set($sAttCode, $value);
				}
			}
			$oP->add('<div class="wizContainer" style="vertical-align:top;"><div id="dcr_'.$iVId.'">');
			$oP->add("<h1>".MetaModel::GetClassIcon($sClass)."&nbsp;".Dict::Format('UI:CreationTitle_Class', MetaModel::GetName($sClass))."</h1>\n");
			cmdbAbstractObject::DisplayCreationForm($oP, $sClass, $oObj, array(), array('noRelations' => true));	
			$oP->add('</div></div>');
			$oP->add_ready_script("$('#dcr_{$iVId} form').removeAttr('onsubmit');");
			$oP->add_ready_script("$('#dcr_{$iVId} form').bind('submit', oIpWidget_{$iVId}.DoCreateIpObject);");
		break;
		
		case 'do_create_ip_object':
			$oObj = MetaModel::NewObject($sClass);
			$aErrors = $oObj->UpdateObjectFromPostedForm('');
			if (count($aErrors) == 0)
			{
				list($bRes, $aIssues) = $oObj->CheckToWrite();
				if ($bRes)
				{
					$oObj->DBInsert();
					switch ($sClass)
					{
						case 'IPv4Block':
							$sIcon = $oObj->GetIcon(true, true);
							$sResult = $sIcon.$oObj->GetHyperlink()."&nbsp;[".$oObj->Get('firstip')." - ".$oObj->Get('lastip')."]";
						break;
						
						case 'IPv4Subnet':
							$sIcon = $oObj->GetIcon(true, true);
							$sResult = $sIcon.$oObj->GetHyperlink()."&nbsp;".Dict::S('Class:IPv4Subnet/Attribute:mask/Value_cidr:'.$oObj->Get('mask'));
							// Update IP Change if appropriate
							$iChangeId = utils::ReadParam('changeid', '');
							if ($iChangeId != 0)
							{
								$oIpChange = MetaModel::GetObject('IPv4SubnetChangeCreate', $iChangeId, false /* MustBeFound */);
								if (! is_null($oIpChange))
								{
									$oIpChange->Set('subnet_id', $oObj->GetKey());
									$oIpChange->DBUpdate();
									// Link location if any
									$aDefault = utils::ReadParam('default', array(), false, 'raw_data');
									$iLocationId = $aDefault['location_id'];        
									$oLocation = MetaModel::GetObject('Location', $iLocationId, false /* MustBeFound */);
									if (! is_null($oLocation))
									{
										$oNewLocationLink = MetaModel::NewObject('lnkIPSubnetToLocation');
										$oNewLocationLink->Set('subnet_id', $oObj->GetKey());
										$oNewLocationLink->Set('location_id', $iLocationId);
										$oNewLocationLink->DBInsert();
									}
								}
							}
						break;
						
						case 'IPv4Range':
							$sResult = $oObj->GetHyperlink()."&nbsp;&nbsp;&nbsp;[".$oObj->Get('firstip')." - ".$oObj->Get('lastip')."]";
						break;
						
						case 'IPv4Address':
							$sResult = $oObj->GetHyperlink()."&nbsp;&nbsp; - ".$oObj->GetAsHtml('status')."&nbsp;&nbsp; - ".$oObj->Get('short_name').".".$oObj->Get('domain_name');
							// Update IP Change if appropriate
							$iChangeId = utils::ReadParam('iChangeId', '');
							if ($iChangeId != null)
							{
								$oIpChange = MetaModel::GetObject('IPv4AddressChangeCreate', $iChangeId, false /* MustBeFound */);
								if (! is_null($oIpChange))
								{
									$oIpChange->Set('ip_id', $oObj->GetKey());
									$oIpChange->DBUpdate();
								}
							}
						break;
						
						case 'IPv6Block':
							$sIcon = $oObj->GetIcon(true, true);
							$sResult = $sIcon.$oObj->GetHyperlink()."&nbsp;[".$oObj->Get('firstip')->GetAsCompressed()." - ".$oObj->Get('lastip')->GetAsCompressed()."]";
						break;

						case 'IPv6Subnet':
							$sIcon = $oObj->GetIcon(true, true);
							$sResult = $sIcon.$oObj->GetHyperlink()."&nbsp;".Dict::S('Class:IPv6Subnet/Attribute:mask/Value_cidr:'.$oObj->Get('mask'));
							// Update IP Change if appropriate
							$iChangeId = utils::ReadParam('changeid', '');
							if ($iChangeId != 0)
							{
								$oIpChange = MetaModel::GetObject('IPv6SubnetChangeCreate', $iChangeId, false /* MustBeFound */);
								if (! is_null($oIpChange))
								{
									$oIpChange->Set('subnet_id', $oObj->GetKey());
									$oIpChange->DBUpdate();
									// Link location if any
									$aDefault = utils::ReadParam('default', array(), false, 'raw_data');
									$iLocationId = $aDefault['location_id'];        
									$oLocation = MetaModel::GetObject('Location', $iLocationId, false /* MustBeFound */);
									if (! is_null($oLocation))
									{
										$oNewLocationLink = MetaModel::NewObject('lnkIPSubnetToLocation');
										$oNewLocationLink->Set('subnet_id', $oObj->GetKey());
										$oNewLocationLink->Set('location_id', $iLocationId);
										$oNewLocationLink->DBInsert();
									}
								}
							}
						break;
						
						case 'IPv6Range':
							$sResult = $oObj->GetHyperlink()."&nbsp;&nbsp;&nbsp;[".$oObj->Get('firstip')->GetAsCompressed()." - ".$oObj->Get('lastip')->GetAsCompressed()."]";
						break;

						case 'IPv6Address':
							$sResult = $oObj->GetHyperlink()."&nbsp;&nbsp; - ".$oObj->GetAsHtml('status')."&nbsp;&nbsp; - ".$oObj->Get('short_name').".".$oObj->Get('domain_name');
							// Update IP Change if appropriate
							$iChangeId = utils::ReadParam('iChangeId', '');
							if ($iChangeId != null)
							{
								$oIpChange = MetaModel::GetObject('IPv6AddressChangeCreate', $iChangeId, false /* MustBeFound */);
								if (! is_null($oIpChange))
								{
									$oIpChange->Set('ip_id', $oObj->GetKey());
									$oIpChange->DBUpdate();
								}
							}
						break;
						
						default:
							$sResult = "";
						break;
					}
				}
				else
				{
					$sResult = "&nbsp;".Dict::Format('UI:ObjectCouldNotBeWritten', implode(', ', $aIssues));
				}
			}
			else
			{
				$sResult = implode(' ', $aErrors);		
			}
			echo $sResult; 
		break;
		
		case 'on_form_cancel':
		case 'cancel':
		default:
		break;
	}
	
	$oP->output();
}

catch (Exception $e)
{
	echo $e->GetMessage();
	IssueLog::Error($e->getMessage());
}
