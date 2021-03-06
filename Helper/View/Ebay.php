<?php

/*
 * @author     M2E Pro Developers Team
 * @copyright  M2E LTD
 * @license    Commercial use is forbidden
 */

namespace Ess\M2ePro\Helper\View;

/**
 * Class \Ess\M2ePro\Helper\View\Ebay
 */
class Ebay extends \Ess\M2ePro\Helper\AbstractHelper
{
    const NICK  = 'ebay';

    const WIZARD_INSTALLATION_NICK = 'installationEbay';
    const MENU_ROOT_NODE_NICK = 'Ess_M2ePro::ebay';

    const MODE_SIMPLE = 'simple';
    const MODE_ADVANCED = 'advanced';

    protected $ebayFactory;
    protected $activeRecordFactory;
    protected $modelFactory;

    //########################################

    public function __construct(
        \Ess\M2ePro\Model\ActiveRecord\Component\Parent\Ebay\Factory $ebayFactory,
        \Ess\M2ePro\Model\ActiveRecord\Factory $activeRecordFactory,
        \Ess\M2ePro\Model\ActiveRecord\Factory $modelFactory,
        \Ess\M2ePro\Helper\Factory $helperFactory,
        \Magento\Framework\App\Helper\Context $context
    ) {
        $this->ebayFactory = $ebayFactory;
        $this->activeRecordFactory = $activeRecordFactory;
        $this->modelFactory = $modelFactory;
        parent::__construct($helperFactory, $context);
    }

    //########################################

    public function getTitle()
    {
        return $this->getHelper('Module\Translation')->__('eBay Integration');
    }

    //########################################

    public function getMenuRootNodeLabel()
    {
        return $this->getTitle();
    }

    //########################################

    public function getWizardInstallationNick()
    {
        return self::WIZARD_INSTALLATION_NICK;
    }

    public function isInstallationWizardFinished()
    {
        return $this->getHelper('Module\Wizard')->isFinished(
            $this->getWizardInstallationNick()
        );
    }

    //########################################

    public function isFeedbacksShouldBeShown($accountId = null)
    {
        $accountCollection = $this->modelFactory->getObject('Ebay\Account')->getCollection();
        $accountCollection->addFieldToFilter('feedbacks_receive', 1);

        $feedbackCollection = $this->activeRecordFactory->getObject('Ebay\Feedback')->getCollection();

        if ($accountId !== null) {
            $accountCollection->addFieldToFilter(
                'account_id',
                $accountId
            );
            $feedbackCollection->addFieldToFilter(
                'account_id',
                $accountId
            );
        }

        return $accountCollection->getSize() || $feedbackCollection->getSize();
    }

    //----------------------------------------

    public function isDuplicatesFilterShouldBeShown($listingId = null)
    {
        $sessionCache = $this->getHelper('Data_Cache_Runtime');

        if ($sessionCache->getValue('is_duplicates_filter_should_be_shown') !== null) {
            return $sessionCache->getValue('is_duplicates_filter_should_be_shown');
        }

        $collection = $this->ebayFactory->getObject('Listing\Product')->getCollection();
        $collection->addFieldToFilter('is_duplicate', 1);
        $listingId && $collection->addFieldToFilter('listing_id', (int)$listingId);

        $result = (bool)$collection->getSize();
        $sessionCache->setValue('is_duplicates_filter_should_be_shown', $result);

        return $result;
    }

    //########################################
}
