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
 * Product Mapper
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
        $start = intVal(GN2_NewsletterConnect::getOXParameter('start'));
        return 'LIMIT ' . $start . ',50';
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
        $oOXDB = \OxidEsales\Eshop\Core\DatabaseProvider::getDb();
        $q = GN2_NewsletterConnect::getOXParameter('q');

        if ($q != '') {
            $q = $oOXDB->quote("%" . $q . "%");

            $where .= '
            && (
                   ( a.OXTITLE LIKE ' . $q . ')
                || ( a.OXSHORTDESC LIKE ' . $q . ')
                || ( a.OXARTNUM LIKE ' . $q . ')
               )
            ';
        }

        /* Category Search */
        $cat = GN2_NewsletterConnect::getOXParameter('cat');
        if ($cat != "") {
            $cat = $oOXDB->quote($cat);
            $where .= '
            && ( o2c.OXCATNID = ' . $cat . ' )';
        }

        /* Restrict entity */
        if ($this->entity != "") {
            $entity = $oOXDB->quote($this->entity);
            $where .= ' && a.OXID = ' . $entity . ' ';
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
            ' . $env->getArticleTableName() . ' as a
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
     * @return GN2_NewsletterConnect_Data_Result
     * @throws \OxidEsales\Eshop\Core\Exception\DatabaseConnectionException
     * @throws \OxidEsales\Eshop\Core\Exception\DatabaseErrorException
     */
    public function getResults()
    {
        $env = GN2_NewsletterConnect::getEnvironment();
        $oxver = GN2_NewsletterConnect::getOXVersion();

        $qsql = $this->getQuery();

        $oDb = \OxidEsales\Eshop\Core\DatabaseProvider::getDb();

        $rows = $oDb->select($qsql);
        $total = $oDb->select('SELECT FOUND_ROWS() as rows');

        $data = array();
        if ($rows != false && $rows->count() > 0) {

            while (!$rows->EOF) {
                $article = oxNew(\OxidEsales\Eshop\Application\Model\Article::class);
                $article->disableLazyLoading();
                $article->load($rows->fields[0]);

                $product = new stdClass;
                $product->id = $article->oxarticles__oxid->rawValue;
                $product->title = GN2_Utilities::MailingWorksUtf8Encode($article->oxarticles__oxtitle->rawValue);//utf8_encode($article->oxarticles__oxtitle->rawValue);

                $fActPrice = $article->getFPrice();
                if (strpos($fActPrice, ",") > 0 && strpos($fActPrice, ".") > 0) {
                    /*
                    das Komma, kommt nach dem Punkt
                    Punkt (Tausender) l�schen
                    Komma (Dezimalstelle) durch Punkt ersetzen
                     */
                    if (strpos($fActPrice, ",") > strpos($fActPrice, ".")) {
                        $fActPrice = str_replace(".", "", $fActPrice);
                        $fActPrice = str_replace(",", ".", $fActPrice);
                    } else {
                        $fActPrice = str_replace(",", "", $fActPrice);
                    }
                } else {
                    /*
                    wenn nur das Komma vorhanden ist
                    Komma (Dezimalstelle) durch Punkt ersetzen
                     */
                    if (strpos($fActPrice, ",") > 0) {
                        $fActPrice = str_replace(",", ".", $fActPrice);
                    }
                }

                $fActPrice = floatval($fActPrice);
                $fActPrice = number_format($fActPrice, 2, ".", "");
                $product->price = $fActPrice;

                $product->shortdesc = $article->oxarticles__oxshortdesc->rawValue;
                $product->artnum = GN2_Utilities::MailingWorksUtf8Encode($article->oxarticles__oxartnum->rawValue);
                $link = $article->getLink();
                $product->url = preg_replace('/\?force_sid.*/', '', $link);

                //$product->longdesc = $article->getLongDesc();
                $product->longdesc = GN2_Utilities::MailingWorksUtf8Encode($env->getArticleLongDesc($article));

                /* Product Pics */
                $product->pictures = array();
                $picture = "";

                for ($i = 0; $i < 12; $i++) {
                    $lastPicture = $picture;
                    $picture = $article->getPictureUrl($i + 1);
                    if (strpos($picture, 'nopic.jpg') === false
                        && $lastPicture != $picture
                    ) {
                        $product->pictures[] = $picture;
                    }
                }

                /* Product Thumbnails */
                $product->thumbnails = array();
                $picture = "";

                for ($i = 0; $i < 12; $i++) {
                    $lastPicture = $picture;
                    $picture = $article->getThumbnailUrl($i + 1);
                    if (strpos($picture, 'nopic.jpg') === false
                        && $lastPicture != $picture
                    ) {
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

                // Tags are not supported in oxid 6 anymore
                $product->tags = ""; //
                //$product->tags = $article->getTags();

                $oManufacturer = $article->getManufacturer();
                $sManufacturer = $oManufacturer->oxmanufacturers__oxtitle->value;
                if ($sManufacturer == null) {
                    $sManufacturer = "";
                }
                $product->manufacturer = $sManufacturer;

                $product->category = array();
                $sCategoryIds = $article->getCategoryIds();
                if (is_array($sCategoryIds)) {
                    foreach ($sCategoryIds as $sCategoryId) {
                        $oCategory = oxNew(\OxidEsales\Eshop\Application\Model\Category::class);
                        $oCategory->load($sCategoryId);

                        $sCategory = $oCategory->oxcategories__oxtitle->value;
                        $oCategoryParent = $oCategory->getParentCategory();
                        $sCategoryParent
                            = $oCategoryParent->oxcategories__oxtitle->value;

                        if ($sCategoryParent == "" || $sCategoryParent == null) {
                            $sCategoryParent = $sCategory;
                            $sCategory = "";
                        }

                        $categoryEntry = array();
                        $categoryEntry["unterkategorie"] = $sCategory;
                        $categoryEntry["vaterkategorie"] = $sCategoryParent;

                        $product->category[] = $categoryEntry;
                    }
                }


                $product->inBasketLink = $article->getToBasketLink() . "&amp;am=1";
                /*
                Textzusatz ist n�tig, da ansonsten kein Artikel
                in Warenkorb getan wird.
                */

                $nStock = $article->oxarticles__oxstock->value;
                $product->stock = $nStock;
                $fOrgPrice = $article->getFTPrice();
                if ($fOrgPrice == null) {
                    $fOrgPrice = $article->getFPrice();
                }
                if (strpos($fOrgPrice, ",") > 0 && strpos($fOrgPrice, ".") > 0) {
                    /*
                    das Komma, kommt nach dem Punkt
                    Punkt (Tausender) l�schen
                    Komma (Dezimalstelle) durch Punkt ersetzen
                     */
                    if (strpos($fOrgPrice, ",") > strpos($fOrgPrice, ".")) {
                        $fOrgPrice = str_replace(".", "", $fOrgPrice);
                        $fOrgPrice = str_replace(",", ".", $fOrgPrice);
                    } else {
                        $fOrgPrice = str_replace(",", "", $fOrgPrice);
                    }
                } else {
                    /*
                    wenn nur das Komma vorhanden ist
                    Komma (Dezimalstelle) durch Punkt ersetzen
                     */
                    if (strpos($fOrgPrice, ",") > 0) {
                        $fOrgPrice = str_replace(",", ".", $fOrgPrice);
                    }
                }
                $fOrgPrice = floatval($fOrgPrice);
                $fOrgPrice = number_format($fOrgPrice, 2, ".", "");

                if ($product->price != $fOrgPrice) {
                    $product->orgPrice = $fOrgPrice;
                } else {
                    $product->orgPrice = '';
                }

                /*
                 * getArticleRatingCount() nicht verwendet,
                 * da es diese Funktion erst ab Oxid 4.6.0 gibt
                $sRatingCount = $article->getArticleRatingCount();
                */
                $sRatingCount = $article->oxarticles__oxratingcnt->value;
                $product->ratingCount = $sRatingCount;
                $sRatingAverage = $article->getArticleRatingAverage();
                $product->ratingAverage = $sRatingAverage;

                $product->reviews = array();
                $reviews = $article->getReviews();
                if (is_array($reviews)) {
                    foreach ($reviews as $review) {
                        $nReviewActive = $review->oxreviews__oxactive->value;
                        if ($nReviewActive == 0) {
                            $reviewEntry = array();
                            $reviewEntry['creationdate']
                                = $review->oxreviews__oxcreate->value;
                            $reviewEntry['text']
                                = $review->oxreviews__oxtext->value;
                            $reviewEntry['firstname']
                                = $review->oxuser__oxfname->value;
                            $reviewEntry['rating']
                                = $review->oxreviews__oxrating->value;

                            $product->reviews[]
                                = $reviewEntry;
                        }
                    }
                }

                $product->accessoires = array();
                if (is_array($article->getAccessoires())) {
                    foreach ($article->getAccessoires() as $accessory) {
                        $accessoryEntry = array();
                        $accessoryEntry['title']
                            = $accessory->oxarticles__oxtitle->rawValue;
                        $accessoryEntry['price']
                            = $accessory->getFPrice();
                        $accessoryEntry['thumb']
                            = $accessory->getIconUrl();
                        $accessoryEntry['url_de']
                            = $accessory->getMainLink();
                        $accessoryEntry['artnum']
                            = $accessory->oxarticles__oxartnum->value;

                        $product->accessoires[]
                            = $accessoryEntry;
                    }
                }
                /* new return values (August 2013) */

                $data[] = $product;
                $rows->fetchRow();
            }
        }

        $dataResult = new GN2_NewsletterConnect_Data_Result;
        $dataResult->setMeta('rows', $total);
        $dataResult->setResult($data);
        return $dataResult;
    }

}
