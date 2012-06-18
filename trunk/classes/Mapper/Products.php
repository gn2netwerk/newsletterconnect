<?php
/**
 * GN2_NewsletterConnect
 *
 * PHP version 5
 *
 * @category   GN2_Newsletterconnect
 * @package    GN2_Newsletterconnect
 * @subpackage Mapper
 * @author     Dave Holloway <dh@gn2-netwerk.de>
 * @license    GN2 Commercial Addon License http://www.gn2-netwerk.de/
 * @version    GIT: <git_id>
 * @link       http://www.gn2-netwerk.de/
 */

/**
 * GN2_Newsletterconnect_Mapper_Products - Product Mapper
 *
 * @category   GN2_Newsletterconnect
 * @package    GN2_Newsletterconnect
 * @subpackage Mapper
 * @author     Dave Holloway <dh@gn2-netwerk.de>
 * @license    GN2 Commercial Addon License http://www.gn2-netwerk.de/
 * @version    Release: <package_version>
 * @link       http://www.gn2-netwerk.de/
 */
class GN2_Newsletterconnect_Mapper_Products
extends GN2_Newsletterconnect_Mapper_Abstract
{
    public function getLimit()
    {
        $start = intVal(oxConfig::getParameter('start'));

        return 'LIMIT '.$start.',50';
    }

    public function getWhere()
    {
        $where = 'WHERE (1';

        # Fulltext search
        $q = mysql_real_escape_string(oxConfig::getParameter('q'));
        if ($q != '') {
            $where .='
            && (
                   ( a.OXTITLE LIKE "%'.$q.'%")
                || ( a.OXSHORTDESC LIKE "%'.$q.'%")
                || ( a.OXARTNUM LIKE "%'.$q.'%")
               )
            ';
        }

        # Category Search
        $cat = oxConfig::getParameter('cat');
        if ($cat != "") {
            $where .='
            && ( o2c.OXCATNID = "'.$cat.'" )';
        }

        # Restrict entity
        if ($this->entity != "") {
            $where .= ' && a.OXID = "'.$this->entity.'" ';
        }

        $where .= ')';
        return $where;
    }

    protected function getQuery($count=false) {
        $qsql = '
        SELECT
            SQL_CALC_FOUND_ROWS
            a.OXID as OXID,
            a.OXTITLE
        FROM
            oxobject2category as o2c
        LEFT JOIN
            oxv_oxarticles as a
        ON
            (a.OXID = o2c.OXOBJECTID)

        $WHERE$

        ORDER BY
            a.OXTITLE

        $LIMIT$
        ';

        $qsql = str_replace('$WHERE$',$this->getWhere(),$qsql);
        $qsql = str_replace('$LIMIT$',$this->getLimit(),$qsql);
        return $qsql;
    }

    public function getResults()
    {
        $qsql = $this->getQuery();
        $rows = oxDb::getDb(true)->Execute( $qsql );

        $total = oxDb::getDb(true)->Execute('SELECT FOUND_ROWS() as rows');

        $data = array();
        if( $rows != false && $rows->recordCount() > 0 ) {
            while ( !$rows->EOF ) {
                $article = oxNew('oxarticle');
                $article->disableLazyLoading();
                $article->load($rows->fields["OXID"]);

                $product = new stdClass;
                $product->id = $article->oxarticles__oxid->rawValue;
                $product->title = $article->oxarticles__oxtitle->rawValue;
                $product->price = $article->getFPrice();
                $product->shortdesc = $article->oxarticles__oxshortdesc->rawValue;
                $product->artnum = $article->oxarticles__oxartnum->rawValue;
                $product->url = $article->getLink();

                $product->longdesc = $article->getLongDesc();
                $product->pictures = array();
                for($i=0;$i<12;$i++) {
                    $picture = $article->getPictureUrl($i+1);
                    if (strpos($picture,'nopic.jpg') === false) {
                        $product->pictures[] = $picture;
                    }
                }

                /* Product Thumbnails */
                $product->thumbnails = array();
                for($i=0;$i<12;$i++) {
                    $picture = $article->getThumbnailUrl($i+1);
                    if (strpos($picture,'nopic.jpg') === false) {
                        $product->thumbnails[] = $picture;
                    }
                }

                /* Product Attributes */
                $product->attributes = array();
                $attributes = $article->getAttributes();
                foreach ($attributes as $attribute) {
                    $attributeEntry = array();
                    $attributeEntry['title'] = $attribute->oxattribute__oxtitle->rawValue;
                    $attributeEntry['value'] = $attribute->oxattribute__oxvalue->rawValue;
                    $product->attributes[] = $attributeEntry;
                }

                $data[] = $product;
                $rows->moveNext();
            }
        }

        $dataresult = new gn2_newsletterconnect_Data_Result;
        $dataresult->setMeta('rows', $total->fields['rows']);
        $dataresult->setResult($data);
        return $dataresult;
    }

}
