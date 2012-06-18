<?php
/**
 * GN2_NewsletterConnect
 *
 * PHP version 5
 *
 * @category GN2_NewsletterConnect
 * @package  GN2_NewsletterConnect
 * @author   Dave Holloway <dh@gn2-netwerk.de>
 * @license  GN2 Commercial Addon License http://www.gn2-netwerk.de
 * @version  GIT: <git_id>
 * @link     http://www.gn2-netwerk.de/
 */

/**
 * GN2_Newsletterconnect_Mapper_Categories - OXID Category Mapper
 *
 * @category GN2_NewsletterConnect
 * @package  GN2_NewsletterConnect
 * @author   Dave Holloway <dh@gn2-netwerk.de>
 * @license  GN2 Commercial Addon License http://www.gn2-netwerk.de
 * @version  Release: <package_version>
 * @link     http://www.gn2-netwerk.de/
 */
class GN2_Newsletterconnect_Mapper_Categories
extends GN2_Newsletterconnect_Mapper_Abstract
{
    /**
     * Builds the category tree and converts to a friendlier format
     *
     * @param mixed $tree Tree array
     *
     * @return array
     */
    private function _buildTree($tree)
    {
        $data = array();
        foreach ($tree as $branch) {
            $entry = new stdClass();
            $entry->id = $branch->oxcategories__oxid->rawValue;
            $entry->name = $branch->oxcategories__oxtitle->rawValue;
            $subcats = $branch->getSubCats();
            if (count($subcats) > 0) {
                $entry->childElements = $this->_buildTree($subcats);
            }
            $data[] = $entry;
        }
        return $data;
    }

    /**
     * Calls the OXID Classes and generates a tree of objects
     *
     * @return gn2_newsletterconnect_Data_Result
     */
    public function getResults()
    {
        $oCategoryTree = oxNew('oxcategorylist');
        $oCategoryTree->buildTree(null, true, true, true);
        $data = $this->_buildTree($oCategoryTree);

        $dataresult = new GN2_Newsletterconnect_Data_Result;
        $dataresult->setResult($data);

        return $dataresult;
    }

}
