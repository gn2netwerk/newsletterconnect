<?php
/**
 * GN2_NewsletterConnect
 * @package gn2_newsletterconnect
 * @copyright GN2 netwerk
 * @link http://www.gn2-netwerk.de/
 * @author Dave Holloway <dh[at]gn2-netwerk[dot]de>
 * @license GN2 Commercial Addon License
 */

/**
 * OXID Category Mapper
 */
class gn2_newsletterconnect_Mapper_Categories extends gn2_newsletterconnect_Mapper_Abstract
{
    /**
     * Builds the category tree and converts to a friendlier format
     * @param mixed $tree
     * @return array
     */
    private function buildTree($tree)
    {
        $data = array();
        foreach ($tree as $branch) {
            $entry = new stdClass();
            $entry->id = $branch->oxcategories__oxid->rawValue;
            $entry->name = $branch->oxcategories__oxtitle->rawValue;
            $subcats = $branch->getSubCats();
            if (count($subcats) > 0) {
                $entry->childElements = $this->buildtree($subcats);
            }
            $data[] = $entry;
        }
        return $data;
    }

    /**
     * Calls the OXID Classes and generates a tree of objects
     * @return gn2_newsletterconnect_Data_Result
     */
    public function getResults()
    {
        $oCategoryTree = oxNew( 'oxcategorylist' );
        $oCategoryTree->buildTree( null, true, true, true );
        $data = $this->buildTree($oCategoryTree);

        $dataresult = new gn2_newsletterconnect_Data_Result;
        $dataresult->setResult($data);

        return $dataresult;
    }

}
