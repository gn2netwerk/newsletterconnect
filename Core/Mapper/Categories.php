<?php
/**
 * GN2_NewsletterConnect
 * @category GN2_NewsletterConnect
 * @package  GN2_NewsletterConnect
 * @author   gn2 netwerk <kontakt@gn2.de>
 * @license  GN2 Commercial Addon License http://www.gn2-netwerk.de/
 * @version  GIT: <git_id>
 * @link     http://www.gn2-netwerk.de/
 */


/**
 * Category Mapper
 */
class GN2_NewsletterConnect_Mapper_Categories
    extends GN2_NewsletterConnect_Mapper_Abstract
{
    /**
     * Recursively builds up a tree of categories
     *
     * @param oxcategorylist $tree OXID CategoryList Object
     *
     * @return array Array containing tree data
     */
    private function _buildTree($tree)
    {
        $data = array();
        foreach ($tree as $branch) {
            $entry = new stdClass();
            $entry->id = $branch->oxcategories__oxid->rawValue;
            $entry->name = GN2_Utilities::MailingWorksUtf8Encode($branch->oxcategories__oxtitle->rawValue);
            $subcats = $branch->getSubCats();
            if (count($subcats) > 0) {
                $entry->childElements = $this->_buildtree($subcats);
            }
            $data[] = $entry;
        }
        return $data;
    }

    /**
     * Returns results from the mapper
     *
     * @return GN2_NewsletterConnect_Data_Result Meta & result data
     */
    public function getResults()
    {
        $oCategoryTree = oxNew(\OxidEsales\Eshop\Application\Model\CategoryList::class);
        $oCategoryTree->buildTree(null, true, true, true);
        $data = $this->_buildTree($oCategoryTree);

        $dataresult = new GN2_NewsletterConnect_Data_Result;
        $dataresult->setResult($data);

        return $dataresult;
    }

}
