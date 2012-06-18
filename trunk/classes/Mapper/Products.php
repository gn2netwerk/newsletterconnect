<?php
/**
 * GN2_NewsletterConnect
 * @package gn2_newsletterconnect
 * @copyright GN2 netwerk
 * @link http://www.gn2-netwerk.de/
 * @author Dave Holloway <dh[at]gn2-netwerk[dot]de>
 * @license GN2 Commercial Addon License
 */

class gn2_newsletterconnect_Mapper_Products extends gn2_newsletterconnect_Mapper_Abstract
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
        $q = oxConfig::getParameter('q');
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

        $where .= ')';
        return $where;
    }

    public function getResults()
    {
        $qsql = '
        SELECT
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

        $rows = oxDb::getDb(true)->Execute( $qsql );
        $data = array();
        if( $rows != false && $rows->recordCount() > 0 ) {
            while ( !$rows->EOF ) {
                $article = oxNew('oxarticle');
                $article->disableLazyLoading();
                $article->load($rows->fields["OXID"]);

                $product = new stdClass;
                $product->title = $article->oxarticles__oxtitle->rawValue;
                $product->price = $article->getFPrice();
                $product->shortdesc = $article->oxarticles__oxshortdesc->rawValue;
                $product->artnum = $article->oxarticles__oxartnum->rawValue;

                $product->longdesc = $article->getLongDesc();
                $product->pictures = array();
                for($i=0;$i<12;$i++) {
                    $picture = $article->getPictureUrl($i+1);
                    if (strpos($picture,'nopic.jpg') === false) {
                        $product->pictures[] = $picture;
                    }
                }
                $data[] = $product;
                $rows->moveNext();
            }
        }

        $dataresult = new gn2_newsletterconnect_Data_Result;
        $dataresult->setResult($data);
        return $dataresult;
    }

}
