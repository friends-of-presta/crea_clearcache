<?php
/**
* 2019-2019 Creabilis
*
* NOTICE OF LICENSE
*
* This source file is subject to the Academic Free License (AFL 3.0)
* that is bundled with this package in the file LICENSE.txt.
* It is also available through the world-wide-web at this URL:
* http://opensource.org/licenses/afl-3.0.php
* If you did not receive a copy of the license and are unable to
* obtain it through the world-wide-web, please send an email
* to license@prestashop.com so we can send you a copy immediately.
*
* DISCLAIMER
*
* Do not edit or add to this file if you wish to upgrade PrestaShop to newer
* versions in the future. If you wish to customize PrestaShop for your
* needs please refer to http://www.prestashop.com for more information.
*
*  @author    Jean-François Viguier <contact@creabilis.com>
*  @copyright 2002-2019 Creabilis S.A.R.L.
*  @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*/

if (!defined('_PS_VERSION_')) {
    exit;
}

use PrestaShop\PrestaShop\Adapter\SymfonyContainer;

class Crea_ClearCache extends Module
{
    /* Class members */
    private $templateFile;

    public function __construct()
    {
        $this->name = 'crea_clearcache';
        $this->tab = 'administration';
        $this->version = '1.0.0';
        $this->author = 'Creabilis';
        $this->need_instance = 0;
        $this->bootstrap = true;

        parent::__construct();

        $this->displayName = $this->l('Clear cache button');
        $this->description = $this->l('Add a clear cache button to the backoffice header.');
        $this->confirmUninstall = $this->l('Are you sure you want to uninstall this module?');

        $this->ps_versions_compliancy = array('min' => '1.7.0.0', 'max' => _PS_VERSION_);

        /* Init errors var */
        $this->_errors = array();

        $this->templateFile = '/views/templates/admin/clearcache.tpl';
    }

    public function install()
    {
        $return = parent::install()
        && $this->registerHook('actionAdminControllerSetMedia')
        && $this->registerHook('displayBackOfficeTop');

        return (bool) $return;
    }

    public function hookDisplayBackOfficeTop($params)
    {
        // get clear cache url
        $clear_cache_url = '';
        $sfContainer = SymfonyContainer::getInstance();
        if (!is_null($sfContainer)) {
            $sfRouter = $sfContainer->get('router');
            $clear_cache_url = $sfRouter->generate('admin_clear_cache');
        }

        $this->smarty->assign(array(
            'clear_cache_url' => $clear_cache_url,
            ));

        return $this->display(__FILE__, $this->templateFile);
    }

    public function hookActionAdminControllerSetMedia()
    {
        $this->context->controller->addCss($this->_path . '/views/css/clearcache.css');
    }
}
