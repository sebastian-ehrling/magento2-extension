<?php

/*
 * @author     M2E Pro Developers Team
 * @copyright  M2E LTD
 * @license    Commercial use is forbidden
 */

namespace Ess\M2ePro\Controller\Adminhtml\Ebay\Category;

/**
 * Class \Ess\M2ePro\Controller\Adminhtml\Ebay\Category\ViewSecondaryGrid
 */
class ViewSecondaryGrid extends \Ess\M2ePro\Controller\Adminhtml\Ebay\Category
{
    //########################################

    public function execute()
    {
        $this->setRuleData('ebay_rule_category');
        $this->setAjaxContent($this->createBlock('Ebay_Category_View_Tabs_ProductsSecondary_Grid'));
        return $this->getResult();
    }

    //########################################
}
