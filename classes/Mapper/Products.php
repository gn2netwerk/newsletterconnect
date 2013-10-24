<?php
/**
 * GN2_NewsletterConnect
 *
 * PHP version 5
 *
 * @category   GN2_NewsletterConnect
 * @package    GN2_NewsletterConnect
 * @subpackage Mapper
 * @author     Dave Holloway <dh@gn2-netwerk.de>
 * @license    GN2 Commercial Addon License http://www.gn2-netwerk.de/
 * @version    GIT: <git_id>
 * @link       http://www.gn2-netwerk.de/
 */

/**
 * Product Mapper
 *
 * @category   GN2_NewsletterConnect
 * @package    GN2_NewsletterConnect
 * @subpackage Mapper
 * @author     Dave Holloway <dh@gn2-netwerk.de>
 * @license    GN2 Commercial Addon License http://www.gn2-netwerk.de/
 * @version    Release: <git_id>
 * @link       http://www.gn2-netwerk.de/
 */
class GN2_NewsletterConnect_Mapper_Products
    extends GN2_NewsletterConnect_Mapper_Abstract
{
    /**
     * Builds the MySQL-Limit, depending on URL parameters.
     *
     * @todo Maybe restructure to get parameter from api.php?
     * @return string SQL LIMIT String
     */
    public function getLimit()
    {
        $start = intVal(oxConfig::getParameter('start'));
        return 'LIMIT '.$start.',50';
    }

    /**
     * Builds the MySQL-Where string, depending on URL parameters.
     *
     * @todo Maybe restructure to get parameters from api.php?
     * @return string SQL WHERE String
     */
    public function getWhere()
    {
        $where = 'WHERE (1';

        /* Fulltext search */
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

        /* Category Search */
        $cat = oxConfig::getParameter('cat');
        if ($cat != "") {
            $where .='
            && ( o2c.OXCATNID = "'.$cat.'" )';
        }

        /* Restrict entity */
        if ($this->entity != "") {
            $where .= ' && a.OXID = "'.$this->entity.'" ';
        }

        $where .= ') && OXTITLE <> ""';
        return $where;
    }

    /**
     * Builds the MySQL-Query for the mapper.
     *
     * @return string MySQL-Query
     */
    protected function getQuery()
    {
        $env = GN2_NewsletterConnect::getEnvironment();

        $qsql = '
        SELECT
            SQL_CALC_FOUND_ROWS
            a.OXID as OXID,
            a.OXTITLE
        FROM
            '.$env->getArticleTableName().' as a
        LEFT JOIN
            oxobject2category as o2c
        ON
            (a.OXID = o2c.OXOBJECTID)

        $WHERE$

        GROUP BY
            a.OXID

        ORDER BY
            a.OXTITLE

        $LIMIT$
        ';

        $qsql = str_replace('$WHERE$', $this->getWhere(), $qsql);
        $qsql = str_replace('$LIMIT$', $this->getLimit(), $qsql);
        return $qsql;
    }

    /**
     * Returns results from the mapper
     *
     * @return GN2_NewsletterConnect_Data_Result Meta & result data
     */
    public function getResults()
    {
        $env = GN2_NewsletterConnect::getEnvironment();
        $qsql = $this->getQuery();
        $rows = oxDb::getDb()->Execute($qsql);

        $total = oxDb::getDb()->Execute('SELECT FOUND_ROWS() as rows');

        $data = array();
        if ($rows != false && $rows->recordCount() > 0) {
                
            while (!$rows->EOF) {
                $article = oxNew('oxarticle');
                $article->disableLazyLoading();
                $article->load($rows->fields[0]);

                $product = new stdClass;
                $product->id = $article->oxarticles__oxid->rawValue;
                $product->title = utf8_encode($article->oxarticles__oxtitle->rawValue);
                $product->price = $article->getFPrice();
                $product->shortdesc = $article->oxarticles__oxshortdesc->rawValue;
                $product->artnum = utf8_encode($article->oxarticles__oxartnum->rawValue);
                $link = $article->getLink();
                $product->url = preg_replace('/\?force_sid.*/', '', $link);
                
                //$product->longdesc = $article->getLongDesc();
                $product->longdesc = utf8_encode($env->getArticleLongDesc($article));

                /* Product Pics */
                $product->pictures = array();
                $picture = ""; $lastPicture = "";

                for ($i=0;$i<12;$i++) {
                    $lastPicture = $picture;
                    $picture = $article->getPictureUrl($i+1);
                    if (strpos($picture, 'nopic.jpg') === false && $lastPicture != $picture) {
                        $product->pictures[] = $picture;
                    }
                }

                /* Product Thumbnails */
                $product->thumbnails = array();
                $picture = ""; $lastPicture = "";

                for ($i=0;$i<12;$i++) {
                    $lastPicture = $picture;
                    $picture = $article->getThumbnailUrl($i+1);
                    if (strpos($picture, 'nopic.jpg') === false && $lastPicture != $picture) {
                        $product->thumbnails[] = $picture;
                    }
                }

                /* Product Attributes */
                $product->attributes = array();
                $attributes = $article->getAttributes();
                foreach ($attributes as $attribute) {
                    $attributeEntry = array();
                    $attributeEntry['title']
                        = $attribute->oxattribute__oxtitle->rawValue;
                    $attributeEntry['value']
                        = $attribute->oxattribute__oxvalue->rawValue;
                    $product->attributes[]
                        = $attributeEntry;
                }

                $data[] = $product;
                $rows->moveNext();
            }
        }

        $dataresult = new GN2_NewsletterConnect_Data_Result;
        $dataresult->setMeta('rows', $total->fields[0]);
        $dataresult->setResult($data);
        return $dataresult;
    }

}
