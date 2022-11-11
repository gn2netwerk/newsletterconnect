<?php
/**
 * @copyright   (c) gn2
 * @link        https://www.gn2.de/
 */

namespace Gn2\NewsletterConnect\Core\Api\Mapper;

use \Gn2\NewsletterConnect\Core\Api\Data\Result;
use \Gn2\NewsletterConnect\Core\Api\Help\Utilities;
use OxidEsales\Eshop\Application\Model\CategoryList;
use stdClass;

/**
 * Category Mapper
 */
class Categories
    extends MapperAbstract
{
    /**
     * Recursively builds up a tree of categories
     *
     * @param object oxcategorylist $tree OXID CategoryList Object
     *
     * @return array Array containing tree data
     */
    private function _buildTree($tree)
    {
        $data = array();
        foreach ($tree as $branch) {
            $entry = new stdClass();
            $entry->id = $branch->oxcategories__oxid->rawValue;
            $entry->name = Utilities::utf8Encode($branch->oxcategories__oxtitle->rawValue);
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
     * @return Result Meta & result data
     */
    public function getResults()
    {
        $oCategoryTree = oxNew(CategoryList::class);
        //$oCategoryTree->buildTree(null, true, true, true);
        $oCategoryTree->loadList();

        $data = $this->_buildTree($oCategoryTree);

        $dataresult = new Result;
        $dataresult->setResult($data);

        return $dataresult;
    }

}
