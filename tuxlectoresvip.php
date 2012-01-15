<?php
/**
 * @version 	plg_content_jdownloadsfilelist v2.2 for Joomla 1.6 and Joomla 1.7
 * @release		23:58 jueves, 22 de diciembre de 2011
 * @package		Joomla.Plugin
 * @subpackage	Content.tuxlectoresvip
 * @copyright   Copyright (C) 2011 Miguel Tuyaré - Tux Merlin. All rights reserved.
 * @license     GNU/GPLv3, see LICENSE.php
 * Joomla! is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 * See COPYRIGHT.php for copyright notices and details.
 *
 * If you want to remove the link below it, consider make a donation at www.tuxmerlin.com.ar
 *
 */
defined('_JEXEC') or die;

//Load Joomla library
jimport('joomla.plugin.plugin');


/**
 * Plugin Tux Lectores Vip
 * @procedure	Main Class Helper
 * @package		Joomla.Plugin
 * @subpackage	Content.tuxlectoresvip
*/
class plgContentTuxlectoresvip extends JPlugin
{
	
	/**
	* Plugin Tux Lectores Vip
	* @Procedure	Constructor - Public!
	* @package		Joomla.Plugin
	* @subpackage	Content.tuxlectoresvip
	*/
	public function __construct(& $subject, $config)
	{
		parent::__construct($subject, $config);
		$this->loadLanguage();
	}
	
	
	/**
	* Plugin Tux Lectores Vip
	* @Procedure	Main Load Function - Public!
	* @package		Joomla.Plugin
	* @subpackage	Content.tuxlectoresvip
	*/
	public function onContentPrepare($context, &$article, &$params, $page = 0)
	{		
		// Load Params		
		$shn = $this->params->get('shn',0);
		$user	= JFactory::getUser();
		
		// scan!!
		$regex = "#{tlv==(.*?)}#s";	
		preg_match_all($regex, $article->text, $matches);		

		// Commons vars 
		$count = 0; 	$one = 0;	$auth = array();
		// Verify
		foreach( $matches[1] as $id ) 
		{		
			$auth[] =  $this->getUserName($id);			
			if ($user->id == $id) {	$count ++;}
		}		
		// Exe!
		foreach( $matches[1] as $id ) 
		{		
			if ($count == 0) {
				if ($one == 0) {
					$output .= '<p style="font-size:120%;color:red">'.JText::_('NO_AUTHORIZED').'</p>';
					if ($shn){
						$output .='<p style="font-size:120%"><b>'.JText::_('USERS_AUTHORIZED').':</b></p>';
						$output .='<ul>';
						foreach ($auth as $aut){
							$output .='<li>'.$aut.'</li>';						
						}
						$output .='</ul>';
					}
					$one ++;
				}				
				$article->text = $output;
			} else {
				$output = '';
				$article->text = str_replace("{tlv==$id}", $output, $article->text);
			}
		}
		return true;
	}
	
	
	/**
	* Plugin Tux Lectores Vip
	* @Procedure	User Validate - Protected!
	* @package		Joomla.Plugin
	* @subpackage	Content.tuxlectoresvip
	*/	
	protected function getUserName($id) 
	{
		// Sanitize
		$id = (int) $id;	
		$user	= JFactory::getUser($id);		
		return $user->name;		
	}

}
// End class
?>