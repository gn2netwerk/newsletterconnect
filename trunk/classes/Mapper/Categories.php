<?php
/**
 * GN2_NewsletterConnect
 * @package gn2_newsletterconnect
 * @copyright GN2 netwerk
 * @link http://www.gn2-netwerk.de/
 * @author Dave Holloway <dh[at]gn2-netwerk[dot]de>
 * @license GN2 Commercial Addon License
 */

class gn2_newsletterconnect_Mapper_Categories extends gn2_newsletterconnect_Mapper_Abstract
{
    private $entity;

    private function buildTree($tree)
    {
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

    public function getResults()
    {
        $oCategoryTree = oxNew( 'oxcategorylist' );
        $oCategoryTree->buildTree( null, true, true, true );
        $data = $this->buildTree($oCategoryTree);

        $dataresult = new gn2_newsletterconnect_Data_Result;
        $dataresult->setResult($data);

        return $dataresult;
    }

    public function restrictEntity($entity)
    {
        $this->entity = $entity;
    }
}
