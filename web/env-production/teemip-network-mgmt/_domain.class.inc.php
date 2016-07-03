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

class _Domain extends DNSObject
{
	/**
	 * Returns name to be displayed within trees
	 */
	public function GetNameForTree()
	{
		return $this->GetName();
	}
	
	/**
	 * Display domain as tree leaf
	 */
	function DisplayAsLeaf(WebPage $oP)
	{
		$oP->add($this->GetHyperlink());
	}
	
	/**
	 * Displays the tabs listing the child blocks and the subnets belonging to a block
	 */
	public function DisplayBareRelations(WebPage $oP, $bEditMode = false)
	{
		// Execute parent function first 
		parent::DisplayBareRelations($oP, $bEditMode);
		
		$sOrgId = $this->Get('org_id');
		if (!$this->IsNew())
		{
			$sDomainName = '%'.$this->GetName();
			$iDomainKey = $this->GetKey();
			
			$aExtraParams = array();
			$aExtraParams['menu'] = false;
			
			// Tab for hosts in the domain
			$oHostsSearch = DBObjectSearch::FromOQL("SELECT IPAddress AS i WHERE i.domain_name LIKE '$sDomainName' AND i.org_id = $sOrgId");
			$oHostsSet = new CMDBObjectSet($oHostsSearch);
			$oP->SetCurrentTab(Dict::Format('Class:Domain/Tab:hosts', $oHostsSet->Count()));
			$oP->p(MetaModel::GetClassIcon('IPAddress').'&nbsp;'.Dict::Format('Class:Domain/Tab:hosts+'));
			$oBlock = new DisplayBlock($oHostsSearch, 'list');
			$oBlock->Display($oP, 'child_hosts', $aExtraParams);
			
			// Tab for child domains
			$oDomainSearch = DBObjectSearch::FromOQL("SELECT Domain AS d WHERE d.parent_id = $iDomainKey");
			$oDomainSet = new CMDBObjectSet($oDomainSearch);
			$oP->SetCurrentTab(Dict::Format('Class:Domain/Tab:child_domain', $oDomainSet->Count()));
			$oP->p(MetaModel::GetClassIcon('Domain').'&nbsp;'.Dict::Format('Class:Domain/Tab:child_domain+'));
			$oBlock = new DisplayBlock($oDomainSearch, 'list');
			$oBlock->Display($oP, 'child_domains', $aExtraParams);
		}
	}
	
	/*
	 * Compute attributes before writing object 
	 */     
	public function ComputeValues()
	{
		$sDomain = $this->Get('name');
		$sParentDomain = $this->Get('parent_name');
		if ($sParentDomain == null)
		{
			// Make sure domain name ends with '.'
			if (substr($sDomain, - 1) != '.')
			{
				$this->Set('name', $sDomain.'.');			
			}
		}
		else
		{
			// Make sure parent_name ends name
			if (substr($sDomain, - 1) != '.')
			{
				$sDomain = $sDomain.'.';
				if (substr($sDomain, - strlen($sParentDomain)) != $sParentDomain)
				{
					$this->Set('name', $sDomain.$sParentDomain);			
				}
				else
				{
					$this->Set('name', $sDomain);
				}
			}
			else
			{
				if (substr($sDomain, - strlen($sParentDomain)) != $sParentDomain)
				{
					$this->Set('name', $sDomain.$sParentDomain);			
				}
			}
		}
	}
	
	/**
	 * Check validity of new IP attributes before creation
	 */
	public function DoCheckToWrite()
	{
		// Run standard iTop checks first
		parent::DoCheckToWrite();
		
		$sOrgId = $this->Get('org_id');
		$iKey = $this->GetKey();
		$sDomain = $this->Get('name');

		// Make sure domain doesn't already exit
		$oDomainSet = new CMDBObjectSet(DBObjectSearch::FromOQL("SELECT Domain AS d WHERE d.name = '$sDomain' AND d.org_id = $sOrgId"));
		while ($oDomain = $oDomainSet->Fetch())
		{
			// Check if it's a creation or a modification
			if ($iKey != $oDomain->GetKey())
			{
				// It's a creation
				//  If class View exist
				//	 If domain is not created in the same view, accept it.
				//   Deny it otherwise
				$bDenyCreation = true;
				if  (MetaModel::IsValidClass('View'))
				{
					if ($this->Get('view_id') != $oDomain->Get('view_id'))
					{
						$bDenyCreation = false;
					}                                     
				}
				if ($bDenyCreation)
				{
					$this->m_aCheckIssues[] = Dict::Format('UI:IPManagement:Action:New:Domain:NameCollision');
					return;
				}
			}
		}
		
	}
}
