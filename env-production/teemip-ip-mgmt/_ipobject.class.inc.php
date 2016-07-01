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

class _IPObject extends cmdbAbstractObject
{
	public function GetNewFormId($sPrefix)
	{
		self::$iGlobalFormId++;
		$this->m_iFormId = $sPrefix.self::$iGlobalFormId;
		return ($this->m_iFormId);
	}

	/**
	 * Create common string for UI displays
	 */
	function MakeUIPath($sOperation)
	{
		$sClass = get_class($this);
		switch ($sOperation)
		{
			case 'findspace':
				return ('UI:IPManagement:Action:FindSpace:'.$sClass.':');
				
			case 'dofindspace':
				return ('UI:IPManagement:Action:DoFindSpace:'.$sClass.':');
				
			case 'listips':
				return ('UI:IPManagement:Action:ListIps:'.$sClass.':');
				
			case 'dolistips':
				return ('UI:IPManagement:Action:DoListIps:'.$sClass.':');
				
			case 'shrinkblock':
			case 'shrinksubnet':
			case 'doshrinkblock':
			case 'doshrinksubnet':
				return ('UI:IPManagement:Action:Shrink:'.$sClass.':');
				
			case 'splitblock':
			case 'splitsubnet':
			case 'dosplitblock':
			case 'dosplitsubnet':
				return ('UI:IPManagement:Action:Split:'.$sClass.':');
					
			case 'expandblock':
			case 'expandsubnet':
			case 'doexpandblock':
			case 'doexpandsubnet':
				return ('UI:IPManagement:Action:Expand:'.$sClass.':');
				
			case 'csvexportips':
				return ('UI:IPManagement:Action:CsvExportIps:'.$sClass.':');
				
			case 'docsvexportips':
				return ('UI:IPManagement:Action:DoCsvExportIps:'.$sClass.':');
				
			case 'docalculator':
				return ('UI:IPManagement:Action:DoCalculator:'.$sClass.':');
				
			case 'calculator':
				return ('UI:IPManagement:Action:Calculator:'.$sClass.':');
			
			case 'delegate':
				return ('UI:IPManagement:Action:Delegate:'.$sClass.':');
			
			default:
				return (':');
		}
	}
	
	/**
	 * Returns default value of an attribute
	 */
	function GetDefaultValueAttribute($sAttribute)
	{
		$sClass = get_class($this);
		$aAllowedValues = MetaModel::GetAllowedValues_att($sClass, $sAttribute);
		if (!empty($aAllowedValues))
		{
			$aValues  = array_keys($aAllowedValues);
			return $aValues[0];
		}
		return '';
	}
	
	/**
	 * Display global parameters associated to the object
	 */
	function DisplayGlobalParametersInLocalModifyForm(WebPage $oP, $sParameter, $aDefault = array())
	{
		// Get Global config object
		$oIpConfig = IPConfig::GetGlobalIPConfig($this->Get('org_id'));
		$aDetails = array();
	
		// Display Parameter with option to be changed for the transaction
		$i = 0;
		$sParam = $sParameter[$i++];
		while ($sParam != null)
		{
			$sInputId = $sParam;
			$oAttDef = MetaModel::GetAttributeDef('IPConfig', $sParam);
			$sValue = (array_key_exists($sParam, $aDefault)) ? $aDefault[$sParam] : $oIpConfig->Get($sParam);
			$sDisplayValue = $oIpConfig->GetEditValue($sParam);
			$iFlags = $oIpConfig->GetAttributeFlags($sParam);
			$aArgs = array('this' => $oIpConfig, 'formPrefix' => '');
			$sHTMLValue = "<span id=\"field_{$sInputId}\">".$oIpConfig->GetFormElementForField($oP, 'IPConfig', $sParam, $oAttDef, $sValue, $sDisplayValue, $sInputId, '', $iFlags, $aArgs).'</span>';
			$aDetails[] = array('label' => '<span title="'.$oAttDef->GetDescription().'">'.$oAttDef->GetLabel().'</span>', 'value' => $sHTMLValue);
			$sParam = $sParameter[$i++];
		}
	
		$oP->Details($aDetails);
	}		

	/*
	 * Perform actions when new object inserted in DB 
	 */
	protected function OnInsert()
	{
		// Run standard checks first
		parent::OnInsert();
		
		if ($this->Get('status') == 'allocated')
		{
			$this->Set('allocation_date', time());
		}
	}
	
	/*
	 * Perform actions when new object inserted in DB 
	 */
	protected function OnUpdate()
	{
		// Run standard checks first
		parent::OnUpdate();
		
		if (($this->Get('status') == 'allocated') && ($this->GetOriginal('status') != 'allocated'))
		{
			$this->Set('allocation_date', time());
		}
	}
	 
}
