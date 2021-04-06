[{include file="headitem.tpl"}]

<style type="text/css">
    .gn2mos { padding: 10px 20px; }
    .gn2mos dl { float:left; width:100%; }
    .gn2mos dt { float:left; clear:left; width:280px; }
    .gn2mos dt span { font-style:italic;font-weight: normal; display:block; }
    .gn2mos dd { float:left; margin:0 0 10px 10px;}
    .gn2mos dd.spacer { width: 100%; }
    .gn2mos dd.cell { width: 50px; text-align: right; }
    .gn2mos dd input.text { width: 350px;border:1px solid #cccccc;padding:2px; }
    .gn2mos dd input.num { width: 50px;border:1px solid #cccccc;padding:2px; }
    .gn2mos dd textarea { width:350px;height:100px; border:1px solid #cccccc;padding:2px; }
    .gn2mos .notice { border: 1px solid #cccccc; padding: 10px; margin: 5px 0 20px; background: #efefef; }
    .gn2mos .notice h3 { margin-top: 0; }
    .gn2mos .notice p { margin-bottom: 0; }
    .gn2mos fieldset { padding: 10px 15px; }
    .gn2mos fieldset dl { border-top: none; padding: 0; margin: 0; }
    .gn2mos legend { }
    .gn2mos div.spacer { display: block; width: 100%; height: 20px; }
</style>

<div class="gn2mos">
    <form name="gn2mosform" id="gn2mosform" action="[{$oViewConf->getSelfLink()}]" method="post">
        [{$oViewConf->getHiddenSid()}]
        <input type="hidden" name="cl" value="admin_newsletterconnect">
        <input type="hidden" name="fnc" value="save">

        <h1><img style="width:85px;height:85px;vertical-align: middle;margin-right:30px;" src="../modules/gn2/newsletterconnect/gn2_newsletterconnect.png">gn2 :: NewsletterConnect</h1>

        [{if $gn2_ExportStatus || $gn2_ExportReportData}]
            <div class="notice">
                [{if $gn2_ExportStatus}]
                    <h3>Export [{$gn2_ExportStatus|strtolower}]</h3>
                [{/if}]

                [{if $gn2_ExportReportData}]
                    [{$gn2_ExportReportData}]
                [{/if}]
            </div>
        [{/if}]

        <h2>[{oxmultilang ident='GN2_NEWSLETTERCONNECT_API_CONFIG'}]</h2>
        <dl>
            <dt>[{oxmultilang ident='GN2_NEWSLETTERCONNECT_API_BASEURL'}]</dt>
            <dd><input type="text" name="config[api_baseurl]" class="text" value="[{$config.api_baseurl}]">[{oxinputhelp ident="GN2_NEWSLETTERCONNECT_API_BASEURL_HELP"}]</dd>

            <dt>[{oxmultilang ident='GN2_NEWSLETTERCONNECT_API_USERNAME'}]</dt>
            <dd><input type="text" name="config[api_username]" class="text" value="[{$config.api_username}]">[{oxinputhelp ident="GN2_NEWSLETTERCONNECT_API_USERNAME_HELP"}]</dd>

            <dt>[{oxmultilang ident='GN2_NEWSLETTERCONNECT_API_PASSWORD'}]</dt>
            <dd><input type="password" name="config[api_password]" class="text" value="[{$config.api_password}]">[{oxinputhelp ident="GN2_NEWSLETTERCONNECT_API_PASSWORD_HELP"}]</dd>

            <dt>[{oxmultilang ident='GN2_NEWSLETTERCONNECT_API_SIGNUPSETUP_GENERAL'}]</dt>
            <dd><input type="text" name="config[api_signupsetup]" class="num" value="[{$config.api_signupsetup}]">[{oxinputhelp ident="GN2_NEWSLETTERCONNECT_API_SIGNUPSETUP_GENERAL_HELP"}]</dd>

            <dt>[{oxmultilang ident='GN2_NEWSLETTERCONNECT_API_SIGNOFFSETUP_GENERAL'}]</dt>
            <dd><input type="text" name="config[api_signoffsetup]" class="num" value="[{$config.api_signoffsetup}]">[{oxinputhelp ident="GN2_NEWSLETTERCONNECT_API_SIGNOFFSETUP_GENERAL_HELP"}]</dd>

            <dt>[{oxmultilang ident='GN2_NEWSLETTERCONNECT_API_SIGNUPSETUP_ACCOUNT'}]</dt>
            <dd><input type="text" name="config[api_signupsetup_account]" class="num" value="[{$config.api_signupsetup_account}]">[{oxinputhelp ident="GN2_NEWSLETTERCONNECT_API_SIGNUPSETUP_ACCOUNT_HELP"}]</dd>

            <dt>[{oxmultilang ident='GN2_NEWSLETTERCONNECT_API_SIGNOFFSETUP_ACCOUNT'}]</dt>
            <dd><input type="text" name="config[api_signoffsetup_account]" class="num" value="[{$config.api_signoffsetup_account}]">[{oxinputhelp ident="GN2_NEWSLETTERCONNECT_API_SIGNOFFSETUP_ACCOUNT_HELP"}]</dd>
        </dl>

        <hr>

        <dl>
            <dt>
                [{oxmultilang ident='GN2_NEWSLETTERCONNECT_API_IPS'}] [{oxinputhelp ident="GN2_NEWSLETTERCONNECT_API_IPS_HELP"}]
            </dt>
            <dd><textarea name="config[api_ips]">[{$config.api_ips}]</textarea></dd>

            <dt>[{oxmultilang ident='GN2_NEWSLETTERCONNECT_VOUCHERSERIES'}] [{oxinputhelp ident="GN2_NEWSLETTERCONNECT_VOUCHERSERIES_HELP"}]</dt>
            <dd>
                <select name="config[voucher_series]">
                    [{foreach from=$voucherSeries key=key item=item}]
                    <option value="[{$item.0}]"[{if $config.voucher_series eq $item.0}] selected="selected"[{/if}]>[{$item.1}]</option>
                    [{/foreach}]
                </select>
            </dd>
        </dl>

        <p>
            <input class="edittext" type="submit" value=" [{oxmultilang ident="GENERAL_SAVE"}]">
        </p>
    </form>

    </br>
    <form name="gn2mosformAboExport" id="gn2mosformAboExport" action="[{$oViewConf->getSelfLink()}]" method="post">
        [{$oViewConf->getHiddenSid()}]
        <input type="hidden" name="cl" value="admin_newsletterconnect">
        <input type="hidden" name="fnc" value="exportSubscribers">
        <input type="hidden" name="transfermethod" value="packet">

        <h2 title="[{oxmultilang ident='GN2_NEWSLETTERCONNECT_EXPORT_TITLE'}]">[{oxmultilang ident='GN2_NEWSLETTERCONNECT_EXPORT_HEADER'}]</h2>

        <fieldset>
            <legend>[{oxmultilang ident="GN2_NEWSLETTERCONNECT_SELECT_SUBSCRIBER_TYPE"}]</legend>

            <dl>
                <dt>[{oxmultilang ident='GN2_NEWSLETTERCONNECT_TOTAL_SUBSCRIBERS'}]</dt>
                <dd class="cell">[{$totalSubscribers}]</dd>

                <dd class="spacer"></dd>

                <dt>[{oxmultilang ident='GN2_NEWSLETTERCONNECT_OPTIN_SUBSCRIBERS'}]</dt>
                <dd class="cell">[{$activeSubscribers}]</dd>
                <dd><input title="[{oxmultilang ident='GN2_NEWSLETTERCONNECT_CHECKBOX_TITLE'}]" type="checkbox" id="activeSubscription" name="activeSubscription" value="activeSubscription" checked="checked"></dd>

                <dt>[{oxmultilang ident='GN2_NEWSLETTERCONNECT_UNCONFIRMED_SUBSCRIBERS'}]</dt>
                <dd class="cell">[{$unconfirmedSubscribers}]</dd>
                <dd><input title="[{oxmultilang ident='GN2_NEWSLETTERCONNECT_CHECKBOX_TITLE'}]" type="checkbox" id="unconfirmedSubscription" name="unconfirmedSubscription" value="unconfirmedSubscription" ></dd>

                <dt>[{oxmultilang ident='GN2_NEWSLETTERCONNECT_OPTOUT_SUBSCRIBERS'}]</dt>
                <dd class="cell">[{$inactiveSubscribers}]</dd>
                <dd><input title="[{oxmultilang ident='GN2_NEWSLETTERCONNECT_CHECKBOX_TITLE'}]" type="checkbox" id="inactiveSubscription" name="inactiveSubscription" value="inactiveSubscription" ></dd>

                <dt>[{oxmultilang ident='GN2_NEWSLETTERCONNECT_NOT_SUBSCRIBERS'}]</dt>
                <dd class="cell">[{$notSubscribed}]</dd>
                <dd><input title="[{oxmultilang ident='GN2_NEWSLETTERCONNECT_CHECKBOX_TITLE'}]" type="checkbox" id="noSubscription" name="noSubscription" value="noSubscription" ></dd>

                <dd class="spacer"></dd>

                <dt>[{oxmultilang ident='GN2_NEWSLETTERCONNECT_EXPORT_OXID_STATUS'}]</dt>
                <dd class="cell">&nbsp;</dd>
                <dd><input title="[{oxmultilang ident='GN2_NEWSLETTERCONNECT_OXID_STATUS_TITLE'}]" type="checkbox" id="export_status" name="export_status" value="export_status" ></dd>

                <dd class="spacer"></dd>

                <dt>[{oxmultilang ident='GN2_NEWSLETTERCONNECT_LISTID'}]</dt>
                <dd><input type="text" title="[{oxmultilang ident='GN2_NEWSLETTERCONNECT_LISTID_TITLE'}]" name="export_listId" value=""></dd>
                <dd></dd>
            </dl>
        </fieldset>

        <div class="spacer"></div>

        <fieldset>
            <legend>[{oxmultilang ident="GN2_NEWSLETTERCONNECT_IMPORTART_LEGEND"}]</legend>
            <div>
                <input type="radio" id="type_add" name="importMode" value="add"> <label for="type_add"><strong>[{oxmultilang ident="GN2_NEWSLETTERCONNECT_MODE_ADD_LABEL"}]</strong></br> [{oxmultilang ident="GN2_NEWSLETTERCONNECT_MODE_ADD_DESC"}]</label>
            </div>
            <div>&nbsp;</div>

            <div>
                <input type="radio" id="type_replace" name="importMode" value="replace"> <label for="type_replace"><strong>[{oxmultilang ident="GN2_NEWSLETTERCONNECT_MODE_REPLACE_LABEL"}]</strong></br> [{oxmultilang ident="GN2_NEWSLETTERCONNECT_MODE_REPLACE_DESC"}]</label>
            </div>
            <div>&nbsp;</div>

            <div>
                <input type="radio" id="type_update" name="importMode" value="update"> <label for="type_update"><strong>[{oxmultilang ident="GN2_NEWSLETTERCONNECT_MODE_UPDATE_LABEL"}]</strong></br> [{oxmultilang ident="GN2_NEWSLETTERCONNECT_MODE_UPDATE_DESC"}]</label>
            </div>
            <div>&nbsp;</div>

            <div>
                <input type="radio" id="type_update_add" name="importMode" value="update_add" checked="checked"> <label for="type_update_add"><strong>[{oxmultilang ident="GN2_NEWSLETTERCONNECT_MODE_UPDATE_ADD_LABEL"}]</strong></br> [{oxmultilang ident="GN2_NEWSLETTERCONNECT_MODE_UPDATE_ADD_DESC"}]</label>
            </div>
        </fieldset>

        <p>
            <button title="[{oxmultilang ident='GN2_NEWSLETTERCONNECT_EXPORT_BUTTON_TITLE'}]" type="submit" onClick="Javascript:document.gn2mosformAboExport.transfermethod.value='packet'">[{oxmultilang ident='GN2_NEWSLETTERCONNECT_EXPORT_BUTTON'}] </button>&nbsp;&nbsp;<button title="[{oxmultilang ident='GN2_NEWSLETTERCONNECT_EXPORT_CSVMETHOD_BUTTON_TITLE'}]" type="submit" onClick="Javascript:document.gn2mosformAboExport.transfermethod.value='csv'">[{oxmultilang ident='GN2_NEWSLETTERCONNECT_EXPORT_CSVMETHOD_BUTTON'}] </button>
        </p>

    </form>
</div>

</body>
</html>