<?php

namespace GN2\NewsletterConnect\Application\Controller\Admin;

use OxidEsales\Eshop\Application\Controller\Admin\AdminDetailsController;
use OxidEsales\Eshop\Application\Model\Article;
use OxidEsales\Eshop\Core\DatabaseProvider;
use OxidEsales\Eshop\Core\Exception\DatabaseErrorException;
use OxidEsales\Eshop\Core\Registry;
use OxidEsales\Eshop\Core\Request;

class newsletterconnect_config extends AdminDetailsController
{
    /**
     * save()
     * Saves the entry to the database
     *
     * @return void
     * @throws \OxidEsales\Eshop\Core\Exception\DatabaseConnectionException
     */
    public function save()
    {
        /** @var Request $request */
        $request = Registry::get(Request::class);
        $db = DatabaseProvider::getDb();
        $editval = $request->getRequestParameter("editval");

        foreach( $editval['mediaFiles'] as $mediaID => $mediaFile ){

            if( $mediaID != "" ){

                $active_de = 0; $active_en = 0; $active_nl = 0; $active_se = 0;

                if( array_key_exists('active_de', $mediaFile) ){ $active_de = 1; }
                if( array_key_exists('active_en', $mediaFile) ){ $active_en = 1; }
                if( array_key_exists('active_nl', $mediaFile) ){ $active_nl = 1; }
                if( array_key_exists('active_se', $mediaFile) ){ $active_se = 1; }

                $qry = "UPDATE `oxmediaurls` SET
                OXACTIVE_DE = $active_de,
                OXACTIVE_EN = $active_en,
                OXACTIVE_NL = $active_nl,
                OXACTIVE_SE = $active_se
                ";
                $qry .= " WHERE OXID = " . $db->quote($mediaID) . ";";

                try {
                    $db->execute($qry);
                } catch (DatabaseErrorException $e) {

                }
            }
        }
    }

    /**
     * render()
     * Loads and assigns additional variables for smarty
     *
     * @return string template name
     */
    function render()
    {
        parent::render();

        return "newsletterconnect_config.tpl";
    }
}