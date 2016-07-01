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

class _IPSubnet extends IPObject
{
	/**
	 * Returns size of subnet
	 */
	public function GetSize()
	{
		return 1;
	}
	
	/**
	 * Return % of occupancy of objects linked to $this
	 */
	public function GetOccupancy($sObject)
	{
		return 0;
	}
	
	/**
	 * Return next operation after current one
	 */
	function GetNextOperation($sOperation)
	{
		switch ($sOperation)
		{
			case 'findspace': return 'dofindspace';
			case 'dofindspace': return 'findspace';
				
			case 'listips': return 'dolistips';
			case 'dolistips': return 'listips';
				
			case 'shrinksubnet': return 'doshrinksubnet';
			case 'doshrinksubnet': return 'shrinksubnet';
				
			case 'splitsubnet': return 'dosplitsubnet';
			case 'dosplitsubnet': return 'splitsubnet';
				
			case 'expandsubnet': return 'doexpandsubnet';
			case 'doexpandsubnet': return 'expandsubnet';
			
			case 'csvexportips': return 'docsvexportips';
			case 'docsvexportips': return 'csvexportips';
	
			case 'calculator': return 'docalculator';
			case 'docalculator': return 'calculator';
	
			default: return '';
		}
	}
	
	/**
	 * Get parameters used for operation
	 */
	function GetPostedParam($sOperation)
	{
		$aParam = array();
		switch ($sOperation)
		{
			case 'dofindspace':
				$aParam['rangesize'] = utils::ReadPostedParam('rangesize', '', 'raw_data');
				$aParam['maxoffer'] = utils::ReadPostedParam('maxoffer', 'DEFAULT_MAX_FREE_SPACE_OFFERS', 'raw_data');
				$aParam['status_subnet'] = '';
				$aParam['type'] = '';
				$aParam['location_id'] = '';
				$aParam['requestor_id'] = '';
			break;
					
			case 'dolistips':
				$aParam['first_ip'] = utils::ReadPostedParam('attr_firstip', '', 'raw_data');
				$aParam['last_ip'] = utils::ReadPostedParam('attr_lastip', '', 'raw_data');
				$aParam['status_ip'] = $this->GetDefaultValueAttribute('status');
				$aParam['short_name'] = '';
				$aParam['domain_id'] = '';
				$aParam['usage_id'] = '';
				$aParam['requestor_id'] = '';
			break;
			
			case 'doshrinksubnet':
			case 'dosplitsubnet':
			case 'doexpandsubnet':
				$aParam['scale_id'] = utils::ReadPostedParam('scale_id', '', 'raw_data');
				$aParam['requestor_id'] = utils::ReadPostedParam('attr_requestor_id', null);
			break;

			case 'docsvexportips':
				$aParam['first_ip'] = utils::ReadPostedParam('attr_firstip', '', 'raw_data');
				$aParam['last_ip'] = utils::ReadPostedParam('attr_lastip', '', 'raw_data');
			break;
			
			case 'docalculator':
				$aParam['ip'] = utils::ReadPostedParam('attr_ip', '', 'raw_data');
				$aParam['mask'] = utils::ReadPostedParam('attr_gatewayip', '', 'raw_data');
				$aParam['cidr'] = utils::ReadPostedParam('cidr', '', 'raw_data');
			break;
			
			default:
				break;
		}
		return $aParam;
	}

	/**
	 * Provides attributes' parameters
	 */		 
	public function GetAttributeParams($sAttCode)
	{
		$aParams = array();
		if (($sAttCode == 'ip_occupancy') || ($sAttCode == 'range_occupancy'))
		{
			if ($sAttCode == 'ip_occupancy')
			{
				$Occupancy = $this->GetOccupancy('IPv4Address');
			}
			else
			{
				$Occupancy = $this->GetOccupancy('IPv4Range');
			}
			$sOrgId = $this->Get('org_id');
			if ($sOrgId != null)
			{
				$sLowWaterMark = IPConfig::GetFromGlobalIPConfig('subnet_low_watermark', $sOrgId);
				$sHighWaterMark = IPConfig::GetFromGlobalIPConfig('subnet_high_watermark', $sOrgId);
				if ($Occupancy >= $sHighWaterMark)
				{
					$sColor = RED;
				}
				else if ($Occupancy >= $sLowWaterMark)
				{
					$sColor = YELLOW;
				}
				else
				{
					$sColor = GREEN;
				}
				$aParams ['value'] = round ($Occupancy, 0);
				$aParams ['color'] = $sColor;
			}
			else
			{
				$aParams ['value'] = 0;
				$aParams ['color'] = GREEN;
			}
		}
		else
		{
			$aParams ['value'] = 0;
			$aParams ['color'] = GREEN;
		}
		return ($aParams);
	}
	
}
