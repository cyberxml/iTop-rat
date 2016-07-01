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

//////////////////////////////////////////////////////////////////////
// Classes in 'teemip-ipv6-mgmt Module'
//////////////////////////////////////////////////////////////////////
//

//
// TeemIp specific attributes
//

Dict::Add('FR FR', 'French', 'Français', array(
	'Core:AttributeIPv6Address' => 'Adresse IPv6',
	'Core:AttributeIPv6Address+' => '',
));

//
// Class: IPv6Block
//

Dict::Add('FR FR', 'French', 'Français', array(
	'Class:IPv6Block' => 'Bloc de Sous-réseaux IPv6',
	'Class:IPv6Block+' => '',
	'Class:IPv6Block/Attribute:parent_id' => 'Parent',
	'Class:IPv6Block/Attribute:parent_id+' => '',
	'Class:IPv6Block/Attribute:parent_name' => 'Nom Parent',
	'Class:IPv6Block/Attribute:parent_name+' => '',
	'Class:IPv6Block/Attribute:firstip' => 'Première IP du Bloc',
	'Class:IPv6Block/Attribute:firstip+' => 'Première IP du Bloc de Sous-réseaux',
	'Class:IPv6Block/Attribute:lastip' => 'Dernière IP du Bloc',
	'Class:IPv6Block/Attribute:lastip+' => 'Dernière IP du Bloc de Sous-réseaux',
));
	
//
// Class: IPv6Subnet
//

Dict::Add('FR FR', 'French', 'Français', array(
	'Class:IPv6Subnet' => 'Sous-réseau IPv6',
	'Class:IPv6Subnet+' => '',
	'Class:IPv6Subnet/Attribute:block_id' => 'Bloc de Sous-réseaux',
	'Class:IPv6Subnet/Attribute:block_id+' => '',
	'Class:IPv6Subnet/Attribute:block_name' => 'Nom Bloc',
	'Class:IPv6Subnet/Attribute:block_name+' => '',
	'Class:IPv6Subnet/Attribute:ip' => 'IP de Sous-réseau',
	'Class:IPv6Subnet/Attribute:ip+' => '',
	'Class:IPv6Subnet/Attribute:mask' => 'Masque',
	'Class:IPv6Subnet/Attribute:mask+' => '',
	'Class:IPv6Subnet/Attribute:mask/Value:64' => 'FFFF:FFFF:FFFF:FFFF:: - /64',
	'Class:IPv6Subnet/Attribute:mask/Value_cidr:64' => '/64',
	'Class:IPv6Subnet/Attribute:gatewayip' => 'IP de la passerelle',
	'Class:IPv6Subnet/Attribute:gatewayip+' => '',
	'Class:IPv6Subnet/Attribute:lastip' => 'Dernière IP du sous-réseau',
	'Class:IPv6Subnet/Attribute:lastip+' => '',
));

//
// Class extensions for IPv6Subnet
//

Dict::Add('FR FR', 'French', 'Français', array(
	'Class:IPv6Subnet/Tab:ipregistered-count' => ' %1$s Réservées et %2$s Allouées',
));
  
//
// Class: IPv6Range
//

Dict::Add('FR FR', 'French', 'Français', array(
	'Class:IPv6Range' => 'Plage d\'Adresses IPv6',
	'Class:IPv6Range+' => '',
	'Class:IPv6Range/Attribute:subnet_id' => 'Sous-réseau',
	'Class:IPv6Range/Attribute:subnet_id+' => '',
	'Class:IPv6Range/Attribute:subnet_ip' => 'IP du Sous-réseau',
	'Class:IPv6Range/Attribute:subnet_ip+' => '',
	'Class:IPv6Range/Attribute:firstip' => 'Première IP de la Plage',
	'Class:IPv6Range/Attribute:firstip+' => '',
	'Class:IPv6Range/Attribute:lastip' => 'Dernière IP de la Plage',
	'Class:IPv6Range/Attribute:lastip+' => '',
));

//
// Class: IPv6Address
//

Dict::Add('FR FR', 'French', 'Français', array(
	'Class:IPv6Address' => 'Adresse IPv6',
	'Class:IPv6Address+' => '',
	'Class:IPv6Address/Attribute:subnet_id' => 'Sous-réseau',
	'Class:IPv6Address/Attribute:subnet_id+' => 'Sous-réseau IPv6',
	'Class:IPv6Address/Attribute:range_id' => 'Plage d\'Adresses',
	'Class:IPv6Address/Attribute:range_id+' => 'Plage d\'Adresses IPv6',
	'Class:IPv6Address/Attribute:ip' => 'Adresse',
	'Class:IPv6Address/Attribute:ip+' => 'Adresse IPv6',
));

//
// Class: IPConfig
//

Dict::Add('FR FR', 'French', 'Français', array(
	'Class:IPConfig/Attribute:ipv6_block_min_prefix' => 'Taille Minimum des Blocs de Sous-réseaux IPv6',
	'Class:IPConfig/Attribute:ipv6_block_min_prefix+' => '',
	'Class:IPConfig/Attribute:ipv6_block_min_prefix/Value:48' => '/48',
	'Class:IPConfig/Attribute:ipv6_block_min_prefix/Value:48+' => '',
	'Class:IPConfig/Attribute:ipv6_block_min_prefix/Value:49' => '/49',
	'Class:IPConfig/Attribute:ipv6_block_min_prefix/Value:49+' => '',
	'Class:IPConfig/Attribute:ipv6_block_min_prefix/Value:50' => '/50',
	'Class:IPConfig/Attribute:ipv6_block_min_prefix/Value:50+' => '',
	'Class:IPConfig/Attribute:ipv6_block_min_prefix/Value:51' => '/51',
	'Class:IPConfig/Attribute:ipv6_block_min_prefix/Value:51+' => '',
	'Class:IPConfig/Attribute:ipv6_block_min_prefix/Value:52' => '/52',
	'Class:IPConfig/Attribute:ipv6_block_min_prefix/Value:52+' => '',
	'Class:IPConfig/Attribute:ipv6_block_min_prefix/Value:53' => '/53',
	'Class:IPConfig/Attribute:ipv6_block_min_prefix/Value:53+' => '',
	'Class:IPConfig/Attribute:ipv6_block_min_prefix/Value:54' => '/54',
	'Class:IPConfig/Attribute:ipv6_block_min_prefix/Value:54+' => '',
	'Class:IPConfig/Attribute:ipv6_block_min_prefix/Value:55' => '/55',
	'Class:IPConfig/Attribute:ipv6_block_min_prefix/Value:55+' => '',
	'Class:IPConfig/Attribute:ipv6_block_min_prefix/Value:56' => '/56',
	'Class:IPConfig/Attribute:ipv6_block_min_prefix/Value:56+' => '',
	'Class:IPConfig/Attribute:ipv6_block_min_prefix/Value:57' => '/57',
	'Class:IPConfig/Attribute:ipv6_block_min_prefix/Value:57+' => '',
	'Class:IPConfig/Attribute:ipv6_block_min_prefix/Value:58' => '/58',
	'Class:IPConfig/Attribute:ipv6_block_min_prefix/Value:58+' => '',
	'Class:IPConfig/Attribute:ipv6_block_min_prefix/Value:59' => '/59',
	'Class:IPConfig/Attribute:ipv6_block_min_prefix/Value:59+' => '',
	'Class:IPConfig/Attribute:ipv6_block_min_prefix/Value:60' => '/60',
	'Class:IPConfig/Attribute:ipv6_block_min_prefix/Value:60+' => '',
	'Class:IPConfig/Attribute:ipv6_block_min_prefix/Value:61' => '/61',
	'Class:IPConfig/Attribute:ipv6_block_min_prefix/Value:61+' => '',
	'Class:IPConfig/Attribute:ipv6_block_min_prefix/Value:62' => '/62',
	'Class:IPConfig/Attribute:ipv6_block_min_prefix/Value:62+' => '',
	'Class:IPConfig/Attribute:ipv6_block_min_prefix/Value:63' => '/63',
	'Class:IPConfig/Attribute:ipv6_block_min_prefix/Value:63+' => '',
	'Class:IPConfig/Attribute:ipv6_block_min_prefix/Value:64' => '/64',
	'Class:IPConfig/Attribute:ipv6_block_min_prefix/Value:64+' => '',
	'Class:IPConfig/Attribute:ipv6_block_cidr_aligned' => 'Alignement des Blocs de Sous-réseaux IPv6 sur les blocs CIDR',
	'Class:IPConfig/Attribute:ipv6_block_cidr_aligned+' => '',
	'Class:IPConfig/Attribute:ipv6_block_cidr_aligned/Value:bca_no' => 'Non',
	'Class:IPConfig/Attribute:ipv6_block_cidr_aligned/Value:bca_no+' => '',
	'Class:IPConfig/Attribute:ipv6_block_cidr_aligned/Value:bca_yes' => 'Oui',
	'Class:IPConfig/Attribute:ipv6_block_cidr_aligned/Value:bca_yes+' => '',
	'Class:IPConfig/Attribute:ipv6_gateway_ip_format' => 'IP de la passerelle IPv6',
	'Class:IPConfig/Attribute:ipv6_gateway_ip_format+' => '',
	'Class:IPConfig/Attribute:ipv6_gateway_ip_format/Value:subnetip_plus_1' => 'IP de sous-réseau + 1',
	'Class:IPConfig/Attribute:ipv6_gateway_ip_format/Value:subnetip_plus_1+' => '',
	'Class:IPConfig/Attribute:ipv6_gateway_ip_format/Value:lastip' => 'Dernière IP du sous-réseau',
	'Class:IPConfig/Attribute:ipv6_gateway_ip_format/Value:lastip+' => '',
	'Class:IPConfig/Attribute:ipv6_gateway_ip_format/Value:free_setup' => 'Allocation libre',
	'Class:IPConfig/Attribute:ipv6_gateway_ip_format/Value:free_setup+' => '',
));

//
// Application Menu
//

Dict::Add('FR FR', 'French', 'Français', array(
	'Menu:IPSpace:IPv6Objects' => 'Objets IPv6',
	'Menu:IPSpace:IPv6Objects+' => 'Objets IPv6',
	'Menu:Ipv6ShortCut' => 'Raccourcis IPv6',
	'Menu:Ipv6ShortCut+' => 'Raccourcis IPv6',  
	'Menu:IPv6Block' => 'Blocs de Sous-réseaux',
	'Menu:IPv6Block+' => 'Blocs de Sous-réseaux IPv6',
	'Menu:IPv6Subnet' => 'Sous-réseaux',
	'Menu:IPv6Subnet+' => 'Sous-réseaux IPv6',
	'Menu:IPv6Subnet:Allocated' => 'Sous-réseaux alloués',
	'Menu:IPv6Subnet:Allocated+' => 'Liste des sous-réseaux IPv6 alloués',
	'Menu:IPv6Range' => 'Plages d\'Adresses IP',
	'Menu:IPv6Range+' => 'Plages d\'Adresses IPv6',
	'Menu:IPv6Address' => 'Adresses IP',
	'Menu:IPv6Address+' => 'Adresses IPv6',

//
// Management of Subnet Blocks
//
	// Creation Management	
	'UI:IPManagement:Action:New:IPv6Block:NotIPv6' => 'Ces IPs ne sont pas IPv6',

	// Display details of subnet blocks
	'UI:IPManagement:Action:Details:IPv6Block' => 'Détails',
	'UI:IPManagement:Action:Details:IPv6Block+' => '',
	
	// Display list of subnet blocks
	'UI:IPManagement:Action:DisplayList:IPv6Block' => 'Afficher la Liste',
	'UI:IPManagement:Action:DisplayList:IPv6Block+' => '',
	'UI:IPManagement:Action:DisplayList:IPv6Block:PageTitle_Class' => 'Blocs de sous-réseaux IPv6',
	'UI:IPManagement:Action:DisplayList:IPv6Block:Title_Class' => 'Blocs de sous-réseaux IPv6',
	                                       
	// Display tree of subnet blocks
	'UI:IPManagement:Action:DisplayTree:IPv6Block' => 'Afficher l\'Arbre',
	'UI:IPManagement:Action:DisplayTree:IPv6Block+' => '',
	'UI:IPManagement:Action:DisplayTree:IPv6Block:PageTitle_Class' => 'Blocs de sous-réseaux IPv6',
	'UI:IPManagement:Action:DisplayTree:IPv6Block:Title_Class' => 'Blocs de sous-réseaux IPv6',
	'UI:IPManagement:Action:DisplayTree:IPv6Block:OrgName' => 'Organisation %1$s',
	
	// Shrink action on subnet blocks
	'UI:IPManagement:Action:Shrink:IPv6Block' => 'Réduire',
	'UI:IPManagement:Action:Shrink:IPv6Block+' => '',
	'UI:IPManagement:Action:Shrink:IPv6Block:Summary' => 'Résumé',
	'UI:IPManagement:Action:Shrink:IPv6Block:Summary+' => '',
	'UI:IPManagement:Action:Shrink:IPv6Block:PageTitle_Object_Class' => 'Réduire %1$s - %2$s',
	'UI:IPManagement:Action:Shrink:IPv6Block:Title_Class_Object' => 'Réduire %1$s: <span class="hilite">%2$s</span>',
	'UI:IPManagement:Action:Shrink:IPv6Block:NewFirstIP' => 'Nouvelle première IP du Bloc :',
	'UI:IPManagement:Action:Shrink:IPv6Block:NewLastIP' => 'Nouvelle dernière IP du Bloc :',            
	'UI:IPManagement:Action:Shrink:IPv6Block:IsDelegated' => 'Ce bloc est délégué et ne peut donc être réduit !',
	'UI:IPManagement:Action:Shrink:IPv6Block:CannotBeShrunk' =>  'Le bloc ne peut être réduit: %1$s',
	'UI:IPManagement:Action:Shrink:IPv6Block:SmallerThanMinSize' => 'La taille du Bloc ne peut être plus petite que /%1$s !',
	'UI:IPManagement:Action:Shrink:IPv6Block:Done' => '%1$s <span class="hilite">%2$s</span> a été réduit.',
	
	// Split action on subnet blocks
	'UI:IPManagement:Action:Split:IPv6Block' => 'Couper',
	'UI:IPManagement:Action:Split:IPv6Block+' => '',
	'UI:IPManagement:Action:Split:IPv6Block:Summary' => 'Résumé',
	'UI:IPManagement:Action:Split:IPv6Block:Summary+' => '',
	'UI:IPManagement:Action:Split:IPv6Block:PageTitle_Object_Class' => 'Couper %1$s - %2$s',
	'UI:IPManagement:Action:Split:IPv6Block:Title_Class_Object' => 'Couper %1$s: <span class="hilite">%2$s</span>',
	'UI:IPManagement:Action:Split:IPv6Block:At' => 'Première IP du nouveau Bloc de Sous-réseaux :',
	'UI:IPManagement:Action:Split:IPv6Block:NameNewBlock' => 'Nom du nouveau Bloc de Sous-réseaux :',
	'UI:IPManagement:Action:Split:IPv6Block:IsDelegated' => 'Ce bloc est délégué et ne peut donc être coupé !',
	'UI:IPManagement:Action:Split:IPv6Block:CannotBeSplit' =>  'Le Bloc ne peut être coupé: %1$s',
	'UI:IPManagement:Action:Split:IPv6Block:SmallerThanMinSize' => 'La taille du bloc ne peut être inférieure à /%1$s !',
	'UI:IPManagement:Action:Split:IPv6Block:Done' => '%1$s: <span class="hilite">%2$s</span> a été coupé.',	
	
	// Expand action on subnet blocks
	'UI:IPManagement:Action:Expand:IPv6Block' => 'Etendre',
	'UI:IPManagement:Action:Expand:IPv6Block+' => '',
	'UI:IPManagement:Action:Expand:IPv6Block:Summary' => 'Résumé',
	'UI:IPManagement:Action:Expand:IPv6Block:Summary+' => '',
	'UI:IPManagement:Action:Expand:IPv6Block:PageTitle_Object_Class' => 'Etendre %1$s - %2$s',
	'UI:IPManagement:Action:Expand:IPv6Block:Title_Class_Object' => 'Etendre %1$s: <span class="hilite">%2$s</span>',
	'UI:IPManagement:Action:Expand:IPv6Block:NewFirstIP' => 'Nouvelle première IP du Bloc :',
	'UI:IPManagement:Action:Expand:IPv6Block:NewLastIP' => 'Nouvelle dernière IP du Bloc :',
	'UI:IPManagement:Action:Expand:IPv6Block:IsDelegated' => 'Ce bloc est délégué et ne peut donc être étendu !',
	'UI:IPManagement:Action:Expand:IPv6Block:CannotBeExpanded' =>  'Le bloc ne peut être étendu: %1$s',
	'UI:IPManagement:Action:Expand:IPv6Block:SmallerThanMinSize' => 'La taille du Bloc ne peut être plus petite que %1$s !',
	'UI:IPManagement:Action:Expand:IPv6Block:Done' => '%1$s <span class="hilite">%2$s</span> a été étendu.',

	// List space action on subnet blocks 
	'UI:IPManagement:Action:ListSpace:IPv6Block' => 'Lister l\'espace',                                               
	'UI:IPManagement:Action:ListSpace:IPv6Block:PageTitle_Object_Class' => '%1$s - Espace',
	'UI:IPManagement:Action:ListSpace:IPv6Block:Title_Class_Object' => 'Espace dans %1$s: <span class="hilite">%2$s</span>',
	'UI:IPManagement:Action:ListSpace:IPv6Block:FreeSpace' => 'Libre [%1$s - %2$s] - %3$s IPs - %4$.2f %%',
	
	// Find Space action on subnet blocks
	'UI:IPManagement:Action:FindSpace:IPv6Block' => 'Rechercher de l\'espace',
	'UI:IPManagement:Action:FindSpace:IPv6Block:PageTitle_Object_Class' => '%1$s - Recherche d\'espace',
	'UI:IPManagement:Action:FindSpace:IPv6Block:Title_Class_Object' => 'Recherche d\'espace IP dans %1$s : <span class="hilite">%2$s</span>',
	'UI:IPManagement:Action:FindSpace:IPv6Block:SizeOfSpace' => 'Taille de l\'espace à rechercher :',
	'UI:IPManagement:Action:FindSpace:IPv6Block:MaxNumberOfOffers' => 'Nombre maximum d\'offres :',
	
	// Do find Space action on subnet blocks
	'UI:IPManagement:Action:DoFindSpace:IPv6Block:PageTitle_Object_Class' => '%1$s - Rechercher de l\'espace',
	'UI:IPManagement:Action:DoFindSpace:IPv6Block:Title_Class_Object' => 'Espace dans %1$s: <span class="hilite">%2$s</span>',
	'UI:IPManagement:Action:DoFindSpace:IPv6Block:Summary' => '%1$s premiers /%2$s dans le bloc',
	'UI:IPManagement:Action:DoFindSpace:IPv6Block:CreateAsBlock' => 'Créer en tant que bloc fils',
	'UI:IPManagement:Action:DoFindSpace:IPv6Block:CreateAsSubnet' => 'Créer en tant que sous-réseau',

	// Delegate action on subnet blocks
	'UI:IPManagement:Action:Delegate:IPv6Block' => 'Déléguer',
	'UI:IPManagement:Action:Delegate:IPv6Block:PageTitle_Object_Class' => '%1$s - Déléguer',
	'UI:IPManagement:Action:Delegate:IPv6Block:Title_Class_Object' => 'Délègue %1$s <span class="hilite">%2$s</span> à l\' organisation fille',
	'UI:IPManagement:Action:Delegate:IPv6Block:ChildBlock' => 'Organisation fille à qui déléguer le bloc :',
	'UI:IPManagement:Action:Delegate:IPv6Block:NoChildOrg' => 'L\'organization dont dépend le bloc n\'a pas de fille. Le bloc ne peut donc être délégué !',
	'UI:IPManagement:Action:Delegate:IPv6Block:CannotBeDelegated' => 'Le bloc ne peut être délégué : %1$s',
	'UI:IPManagement:Action:Delegate:IPv6Block:Done' => '%1$s <span class="hilite">%2$s</span> a été délégué.',

//
// Management of Subnets
//
	// Operations on subnets
	'UI:IPManagement:Action:xxx:IPv6Subnet:OperationNotAllowed' => 'Opération non autorisée sur des sous-réseaux IPv6 !',

	// Display details of subnet
	'UI:IPManagement:Action:Details:IPv6Subnet' => 'Détails',
	'UI:IPManagement:Action:Details:IPv6Subnet+' => '',

	// Display list of subnets
	'UI:IPManagement:Action:DisplayList:IPv6Subnet' => 'Afficher la Liste',
	'UI:IPManagement:Action:DisplayList:IPv6Subnet+' => '',
	'UI:IPManagement:Action:DisplayList:IPv6Subnet:PageTitle_Class' => 'Sous-réseau IPv6',
	'UI:IPManagement:Action:DisplayList:IPv6Subnet:Title_Class' => 'Sous-réseau IPv6',
	
	// Display tree of subnets
	'UI:IPManagement:Action:DisplayTree:IPv6Subnet' => 'Afficher l\'Arbre',
	'UI:IPManagement:Action:DisplayTree:IPv6Subnet+' => '',
	'UI:IPManagement:Action:DisplayTree:IPv6Subnet:PageTitle_Class' => 'Sous-réseau IPv6',
	'UI:IPManagement:Action:DisplayTree:IPv6Subnet:Title_Class' => 'Sous-réseau IPv6',
	'UI:IPManagement:Action:DisplayTree:IPv6Subnet:OrgName' => 'Organisation %1$s',
	
	// Find space action on subnets 
	'UI:IPManagement:Action:FindSpace:IPv6Subnet' => 'Recherche d\'Espace',
	'UI:IPManagement:Action:FindSpace:IPv6Subnet:PageTitle_Object_Class' => '%1$s - Recherche d\'espace',
	'UI:IPManagement:Action:FindSpace:IPv6Subnet:Title_Class_Object' => 'Recherche d\'espace IP dans %1$s: <span class="hilite">%2$s</span>',
	'UI:IPManagement:Action:FindSpace:IPv6Subnet:SizeTooSmall' => 'Le Sous-Réseau est trop petit pour y rechercher un espace !',
	'UI:IPManagement:Action:FindSpace:IPv6Subnet:SizeOfRange' => 'Taille de l\'espace à rechercher :',
	'UI:IPManagement:Action:FindSpace:IPv6Subnet:MaxNumberOfOffers' => 'Nombre maximum d\'offres :',
	
	// Do find Space action on subnet
	'UI:IPManagement:Action:DoFindSpace:IPv6Subnet:PageTitle_Object_Class' => '%1$s - Recherche d\'espace',
	'UI:IPManagement:Action:DoFindSpace:IPv6Subnet:Title_Class_Object' => 'Espace dans %1$s: <span class="hilite">%2$s</span>',
	'UI:IPManagement:Action:DoFindSpace:IPv6Subnet:Summary' => '%1$s premières %2$s Plages d\'IPs libres dans le sous-réseau',
	'UI:IPManagement:Action:DoFindSpace:IPv6Subnet:RangeTooBig' => 'L\'espace demandé ne tient pas dans le sous-réseau. Veuillez choisir une taille plus petite.',
	'UI:IPManagement:Action:DoFindSpace:IPv6Subnet:CreateAsRange' => 'Créer en tant que Plage d\'IPs',

	// List IPs action on subnets 
	'UI:IPManagement:Action:ListIps:IPv6Subnet' => 'Lister et allouer IPs',                                               
	'UI:IPManagement:Action:ListIps:IPv6Subnet:PageTitle_Object_Class' => '%1$s - IPs',
	'UI:IPManagement:Action:ListIps:IPv6Subnet:Title_Class_Object' => 'IPs contenues dans le %1$s: <span class="hilite">%2$s</span>',
	'UI:IPManagement:Action:ListIps:IPv6Subnet:Subtitle_ListRange' => 'Le Sous-réseau est trop grand pour lister toutes les IPs en une seule page. Merci de sélectionner une plage à afficher:',                                               
	'UI:IPManagement:Action:ListIps:IPv6Subnet:FirstIP' => 'Première IP de la plage',                                               
	'UI:IPManagement:Action:ListIps:IPv6Subnet:LastIP' => 'Dernière IP de la plage',                                               
	
	// Do list IPs action on subnet
	'UI:IPManagement:Action:DoListIps:IPv6Subnet' => 'Lister et allouer IPs',                                               
	'UI:IPManagement:Action:DoListIps:IPv6Subnet:PageTitle_Object_Class' => '%1$s - IPs',
	'UI:IPManagement:Action:DoListIps:IPv6Subnet:Title_Class_Object' => 'Liste partielle des IPs contenues dans le %1$s: <span class="hilite">%2$s</span>',
 	'UI:IPManagement:Action:DoListIps:IPv6Subnet:CannotBeListed' => 'Les IPs ne peuvent être listées: %1$s',
	'UI:IPManagement:Action:DoListIps:IPv6Subnet:FirstIPOutOfSubnet' => 'La première IP est hors du sous-réseau !',
	'UI:IPManagement:Action:DoListIps:IPv6Subnet:LastIPOutOfSubnet' => 'La dernière IP est hors du sous-réseau !',
	'UI:IPManagement:Action:DoListIps:IPv6Subnet:FirstIpBiggerThanLastIp' => 'La première IP de la plage est plus grande que la dernière !',

	// CSV Export action on subnets
	'UI:IPManagement:Action:CsvExportIps:IPv6Subnet' => 'Export CSV des IPs',
	'UI:IPManagement:Action:CsvExportIps:IPv6Subnet:PageTitle_Object_Class' => '%1$s - %2$s Export CSV des IPs',
	'UI:IPManagement:Action:CsvExportIps:IPv6Subnet:Title_Class_Object' => 'Export CSV des IPs pour %1$s: <span class="hilite">%2$s</span>',
	'UI:IPManagement:Action:CsvExportIps:IPv6Subnet:Subtitle_ListRange' => 'Le Sous-réseau est trop grand pour exporter toutes les IPs en une seule page. Merci de sélectionner une plage à exporter:',                                               
	'UI:IPManagement:Action:CsvExportIps:IPv6Subnet:FirstIP' => 'Première IP de la plage',                                               
	'UI:IPManagement:Action:CsvExportIps:IPv6Subnet:LastIP' => 'Dernière IP de la plage',                                               
	
	// Do CSV export IPs action on subnet
	'UI:IPManagement:Action:DoCsvExportIps:IPv6Subnet' => 'Export CSV des IPs',                                               
	'UI:IPManagement:Action:DoCsvExportIps:IPv6Subnet:PageTitle_Object_Class' => '%1$s - %2$s Export CSV des IPs',
	'UI:IPManagement:Action:DoCsvExportIps:IPv6Subnet:Title_Class_Object' => 'Export CSV partiel des IPs pour %1$s: <span class="hilite">%2$s</span>',
 	'UI:IPManagement:Action:DoCsvExportIps:IPv6Subnet:CannotBeListed' => 'Les IPs ne peuvent être listées: %1$s',
	'UI:IPManagement:Action:DoCsvExportIps:IPv6Subnet:FirstIPOutOfSubnet' => 'La première IP est hors du sous-réseau !',
	'UI:IPManagement:Action:DoCsvExportIps:IPv6Subnet:LastIPOutOfSubnet' => 'La dernière IP est hors du sous-réseau !',
	'UI:IPManagement:Action:DoCsvExportIps:IPv6Subnet:FirstIpBiggerThanLastIp' => 'La première IP de la plage est plus grande que la dernière !',

	// Subnet calculator
	'UI:IPManagement:Action:Calculator:IPv6Subnet' => 'Calculateur de Sous-réseaux',
	'UI:IPManagement:Action:Calculator:IPv6Subnet:PageTitle_Object_Class' => '%2$s Calculateur',
	'UI:IPManagement:Action:Calculator:IPv6Subnet:Title_Class_Object' => 'Calculateur pour %1$s',
	'UI:IPManagement:Action:Calculator:IPv6Subnet:IP' => 'Adresse',
	'UI:IPManagement:Action:Calculator:IPv6Subnet:Prefix' => 'Préfixe',

	// Do Subnet calculator
	'UI:IPManagement:Action:DoCalculator:IPv6Subnet' => 'Calculateur de Sous-réseaux',
	'UI:IPManagement:Action:DoCalculator:IPv6Subnet:PageTitle_Object_Class' => '%2$s Calculateur',
	'UI:IPManagement:Action:DoCalculator:IPv6Subnet:Title_Class_Object' => '%1$s - Résultat du calculateur',
	'UI:IPManagement:Action:DoCalculator:IPv6Subnet:CompressedIP' => 'Adresse IP - Format compressé',
	'UI:IPManagement:Action:DoCalculator:IPv6Subnet:CanonicalIP' => 'Adresse IP - Format canonique',
	'UI:IPManagement:Action:DoCalculator:IPv6Subnet:Prefix' => 'Préfixe',
	'UI:IPManagement:Action:DoCalculator:IPv6Subnet:PrefixMask' => 'Masque Préfixe',
	'UI:IPManagement:Action:DoCalculator:IPv6Subnet:NetworkIP' => 'IP du Sous-réseau',
	'UI:IPManagement:Action:DoCalculator:IPv6Subnet:LastIP' => 'Dernière IP',
	'UI:IPManagement:Action:DoCalculator:IPv6Subnet:IPNumber' => 'Nombre d\'IPs',
	'UI:IPManagement:Action:DoCalculator:IPv6Subnet:PreviousSubnet' => 'IP du Sous-réseau précédent',
	'UI:IPManagement:Action:DoCalculator:IPv6Subnet:PreviousSubnet:NA' => 'Non Applicable',
	'UI:IPManagement:Action:DoCalculator:IPv6Subnet:NextSubnet' => 'IP du Sous-réseau suivant',
	'UI:IPManagement:Action:DoCalculator:IPv6Subnet:NextSubnet:NA' => 'Non Applicable',
	'UI:IPManagement:Action:DoCalculator:IPv6Subnet:CannotRun' => 'Le calculateur de Sous-réseau ne peut tourner: %1$s',
	'UI:IPManagement:Action:DoCalculator:IPv6Subnet:EnterPrefix' => 'Entrer un préfixe!',
	'UI:IPManagement:Action:DoCalculator:IPv6Subnet:WrongPrefix' => 'Le préfixe est invalide!',

//
// Management of IP ranges
//
	// Display details of IP Range
	'UI:IPManagement:Action:Details:IPv6Range' => 'Détails',
	'UI:IPManagement:Action:Details:IPv6Range+' => '',

	// List IPs action on IP Ranges 
	'UI:IPManagement:Action:ListIps:IPv6Range' => 'Lister et allouer IPs',                                               
	'UI:IPManagement:Action:ListIps:IPv6Range:PageTitle_Object_Class' => '%1$s - IPs',
	'UI:IPManagement:Action:ListIps:IPv6Range:Title_Class_Object' => 'IPs contenues dans %1$s: <span class="hilite">%2$s</span>',
	'UI:IPManagement:Action:ListIps:IPv6Range:Subtitle_ListRange' => 'La plage d\'IPs est trop grande pour lister toutes les IPs en une seule page. Merci de sélectionner une sous plage à afficher:',                                               
	'UI:IPManagement:Action:ListIps:IPv6Range:FirstIP' => 'Première IP de la plage',                                               
	'UI:IPManagement:Action:ListIps:IPv6Range:LastIP' => 'Dernière IP de la plage',                                               
	
	// Do list IPs action on IP Ranges 
	'UI:IPManagement:Action:DoListIps:IPv6Range' => 'Lister et allouer IPs',                                               
	'UI:IPManagement:Action:DoListIps:IPv6Range:PageTitle_Object_Class' => '%1$s - IPs',
	'UI:IPManagement:Action:DoListIps:IPv6Range:Title_Class_Object' => 'Liste partielle des IPs contenues dans la %1$s: <span class="hilite">%2$s</span>',
	'UI:IPManagement:Action:DoListIps:IPv6Range:CannotBeListed' => 'La plage d\'IPs ne peut être listée: %1$s',
	'UI:IPManagement:Action:DoListIps:IPv6Range:FirstIPOutOfRange' => 'La première IP est hors de la plage !',
	'UI:IPManagement:Action:DoListIps:IPv6Range:LastIPOutOfRange' => 'La dernière IP est hors de la plage !',
	'UI:IPManagement:Action:DoListIps:IPv6Range:FirstIpBiggerThanLastIp' => 'La première IP de la plage est plus grande que la dernière !',

	// CSV Export action on IP Ranges
	'UI:IPManagement:Action:CsvExportIps:IPv6Range' => 'Export CSV des IPs',
	'UI:IPManagement:Action:CsvExportIps:IPv6Range:PageTitle_Object_Class' => '%1$s - %2$s export CSV des IPs',
	'UI:IPManagement:Action:CsvExportIps:IPv6Range:Title_Class_Object' => 'Export CSV des IPs pour %1$s: <span class="hilite">%2$s</span>',
	'UI:IPManagement:Action:CsvExportIps:IPv6Range:Subtitle_ListRange' => 'La plage d\'IPs est trop grande pour exporter toutes les IPs en une seule fois. Merci de sélectionner une sous plage à exporter:',                                               
	'UI:IPManagement:Action:CsvExportIps:IPv6Range:FirstIP' => 'Première IP de la plage',                                               
	'UI:IPManagement:Action:CsvExportIps:IPv6Range:LastIP' => 'Dernière IP de la plage',                                               
	
	// Do CSV Export IPs action on IP Ranges
	'UI:IPManagement:Action:DoCsvExportIps:IPv6Range' => 'Export CSV des IPs',                                               
	'UI:IPManagement:Action:DoCsvExportIps:IPv6Range:PageTitle_Object_Class' => '%1$s - %2$s export CSV des IPs',
	'UI:IPManagement:Action:DoCsvExportIps:IPv6Range:Title_Class_Object' => 'Export CSV partiel des IPs pour %1$s: <span class="hilite">%2$s</span>',
	'UI:IPManagement:Action:DoCsvExportIps:IPv6Range:CannotBeListed' => 'La plage ne peut être exportée: %1$s',
	'UI:IPManagement:Action:DoCsvExportIps:IPv6Range:FirstIPOutOfRange' => 'La première IP est hors de la plage !',
	'UI:IPManagement:Action:DoCsvExportIps:IPv6Range:LastIPOutOfRange' => 'La dernière IP est hors de la plage !',
	'UI:IPManagement:Action:DoCsvExportIps:IPv6Range:FirstIpBiggerThanLastIp' => 'La première IP de la plage est plus grande que la dernière !',
	
));
