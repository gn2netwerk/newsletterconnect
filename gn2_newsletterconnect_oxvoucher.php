<?php
class gn2_newsletterconnect_oxvoucher extends gn2_newsletterconnect_oxvoucher_parent
{
    public function save()
    {
        if (!isAdmin()) {
            $user = $this->getUser();
            $this->oxvouchers__oxuserid->rawValue = md5("MOS:".$user->oxuser__oxusername->value);
        }
        return parent::save();
    }
}