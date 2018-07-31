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

namespace GN2\NewsletterConnect\Application\Model;

/**
 * Class Voucher
 * @package GN2\NewsletterConnect\Application\Model
 */
class Voucher extends Voucher_parent
{
    /**
     * @return mixed
     */
    public function save()
    {
        if (!isAdmin()) {
            $user = $this->getUser();
            $this->oxvouchers__oxuserid->rawValue = md5("MOS:".$user->oxuser__oxusername->value);
        }
        return parent::save();
    }
}