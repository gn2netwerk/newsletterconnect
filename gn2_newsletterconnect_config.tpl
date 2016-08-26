[{include file="headitem.tpl"}]

<style type="text/css">
    .gn2mos { padding: 10px 20px; }
    .gn2mos dl { float:left; width:100%; }
    .gn2mos dt { float:left; clear:left; width:280px; }
    .gn2mos dt span { font-style:italic;font-weight: normal; display:block; }
    .gn2mos dd { float:left; margin:0 0 10px 10px;}
    .gn2mos dd input.text { width: 350px;border:1px solid #cccccc;padding:2px; }
    .gn2mos dd textarea { width:350px;height:100px; border:1px solid #cccccc;padding:2px; }
</style>

<div class="gn2mos">
    <form name="gn2mosform" id="gn2mosform" action="[{ $oViewConf->getSelfLink() }]" method="post">
        [{$oViewConf->getHiddenSid()}]
        <input type="hidden" name="cl" value="gn2_newsletterconnect_config">
        <input type="hidden" name="fnc" value="save">

        <h1><img style="width:85px;height:85px;vertical-align: middle;margin-right:30px;" src="../modules/gn2_newsletterconnect/gn2_newsletterconnect.png">gn2 :: NewsletterConnect</h1>

        <h2>[{oxmultilang ident='GN2_NEWSLETTERCONNECT_MAIN'}]</h2>
        <dl>
            <dt>[{oxmultilang ident='GN2_NEWSLETTERCONNECT_API_BASEURL'}]</dt>
            <dd><input type="text" name="config[api_baseurl]" class="text" value="[{$config.service_Mailingwork.api_baseurl}]"></dd>

            <dt>[{oxmultilang ident='GN2_NEWSLETTERCONNECT_API_USERNAME'}]</dt>
            <dd><input type="text" name="config[api_username]" class="text" value="[{$config.service_Mailingwork.api_username}]"></dd>

            <dt>[{oxmultilang ident='GN2_NEWSLETTERCONNECT_API_PASSWORD'}]</dt>
            <dd><input type="password" name="config[api_password]" class="text" value="[{$config.service_Mailingwork.api_password}]"></dd>

            <dt>[{oxmultilang ident='GN2_NEWSLETTERCONNECT_API_SIGNUPSETUP_GENERAL'}]</dt>
            <dd><input type="text" name="config[api_signupsetup]" class="text" value="[{$config.service_Mailingwork.api_signupsetup}]"></dd>

            <dt>[{oxmultilang ident='GN2_NEWSLETTERCONNECT_API_SIGNOFFSETUP_GENERAL'}]</dt>
            <dd><input type="text" name="config[api_signoffsetup]" class="text" value="[{$config.service_Mailingwork.api_signoffsetup}]"></dd>

            <dt>[{oxmultilang ident='GN2_NEWSLETTERCONNECT_API_SIGNUPSETUP_ACCOUNT'}]</dt>
            <dd><input type="text" name="config[api_signupsetup_account]" class="text" value="[{$config.service_Mailingwork.api_signupsetup_account}]"></dd>

            <dt>[{oxmultilang ident='GN2_NEWSLETTERCONNECT_API_SIGNOFFSETUP_ACCOUNT'}]</dt>
            <dd><input type="text" name="config[api_signoffsetup_account]" class="text" value="[{$config.service_Mailingwork.api_signoffsetup_account}]"></dd>

        </dl>

        <h2>[{oxmultilang ident='GN2_NEWSLETTERCONNECT_API_CONFIG'}]</h2>

        <dl>
            <dt>[{oxmultilang ident='GN2_NEWSLETTERCONNECT_API_IPS'}]<span>[{oxmultilang ident='GN2_NEWSLETTERCONNECT_YOURIP'}] [{php}]echo $_SERVER['REMOTE_ADDR'][{/php}]</span></dt>
            <dd><textarea name="config[api_ips]">[{$config.service_Mailingwork.api_ips}]</textarea></dd>

            <dt>[{oxmultilang ident='GN2_NEWSLETTERCONNECT_VOUCHERSERIES'}]</dt>
            <dd>
                <select name="config[voucher_series]">
                    [{foreach from=$voucherSeries key=key item=item}]
                        <option value="[{$item.0}]"[{if $config.service_Mailingwork.voucher_series eq $item.0}] selected="selected"[{/if}]>[{$item.1}]</option>
                    [{/foreach}]
                </select>
            </dd>
        </dl>

        <p><button type="submit">[{oxmultilang ident='GENERAL_SAVE'}]</button></p>
    </form>

    </br>
    <form name="gn2mosformAboExport" id="gn2mosformAboExport" action="[{ $oViewConf->getSelfLink() }]" method="post">
        [{$oViewConf->getHiddenSid()}]
        <input type="hidden" name="cl" value="gn2_newsletterconnect_config">
        <input type="hidden" name="fnc" value="exportSubscribers">
        <input type="hidden" name="transfermethod" value="packet">

        <h2 title="[{oxmultilang ident='GN2_NEWSLETTERCONNECT_EXPORT_TITLE'}]">[{oxmultilang ident='GN2_NEWSLETTERCONNECT_EXPORT_HEADER'}]</h2>
        <dl>
            <dt>[{oxmultilang ident='GN2_NEWSLETTERCONNECT_TOTAL_SUBSCRIBERS'}]</dt>
            <dd>[{$totalSubscribers}]</dd>
            <dd></dd>

            <dt>[{oxmultilang ident='GN2_NEWSLETTERCONNECT_OPTIN_SUBSCRIBERS'}]</dt>
            <dd>[{$activeSubscribers}]</dd>
            <dd><input title="[{oxmultilang ident='GN2_NEWSLETTERCONNECT_CHECKBOX_TITLE'}]" type="checkbox" id="activeSubscription" name="activeSubscription" value="activeSubscription" checked="checked"></dd>

            <dt>[{oxmultilang ident='GN2_NEWSLETTERCONNECT_UNCONFIRMED_SUBSCRIBERS'}]</dt>
            <dd>[{$unconfirmedSubscribers}]</dd>
            <dd><input title="[{oxmultilang ident='GN2_NEWSLETTERCONNECT_CHECKBOX_TITLE'}]" type="checkbox" id="unconfirmedSubscription" name="unconfirmedSubscription" value="unconfirmedSubscription" ></dd>

            <dt>[{oxmultilang ident='GN2_NEWSLETTERCONNECT_OPTOUT_SUBSCRIBERS'}]</dt>
            <dd>[{$inactiveSubscribers}]</dd>
            <dd><input title="[{oxmultilang ident='GN2_NEWSLETTERCONNECT_CHECKBOX_TITLE'}]" type="checkbox" id="inactiveSubscription" name="inactiveSubscription" value="inactiveSubscription" ></dd>

            <dt>[{oxmultilang ident='GN2_NEWSLETTERCONNECT_NOT_SUBSCRIBERS'}]</dt>
            <dd>[{$notSubscribed}]</dd>
            <dd><input title="[{oxmultilang ident='GN2_NEWSLETTERCONNECT_CHECKBOX_TITLE'}]" type="checkbox" id="noSubscription" name="noSubscription" value="noSubscription" ></dd>

            <dt>[{oxmultilang ident='GN2_NEWSLETTERCONNECT_EXPORT_OXID_STATUS'}]</dt>
            <dd>&nbsp;</dd>
            <dd><input title="[{oxmultilang ident='GN2_NEWSLETTERCONNECT_OXID_STATUS_TITLE'}]" type="checkbox" id="export_status" name="export_status" value="export_status" ></dd>

            <dt>[{oxmultilang ident='GN2_NEWSLETTERCONNECT_LISTID'}]</dt>
            <dd><input type="text" title="[{oxmultilang ident='GN2_NEWSLETTERCONNECT_LISTID_TITLE'}]" name="export_listId" value=""></dd>
            <dd></dd>
        </dl>


        <fieldset >
            <legend>[{oxmultilang ident="GN2_NEWSLETTERCONNECT_IMPORTART_LEGEND" }]</legend>
            <div>
                <input type="radio" id="type_add" name="importMode" value="add"> <label for="type_add">[{oxmultilang ident="GN2_NEWSLETTERCONNECT_MODE_ADD_LABEL" }] </br> [{oxmultilang ident="GN2_NEWSLETTERCONNECT_MODE_ADD_DESC" }]</label>
            </div>
            <div>&nbsp;</div>

            <div>
                <input type="radio" id="type_replace" name="importMode" value="replace"> <label for="type_replace">[{oxmultilang ident="GN2_NEWSLETTERCONNECT_MODE_REPLACE_LABEL" }] </br> [{oxmultilang ident="GN2_NEWSLETTERCONNECT_MODE_REPLACE_DESC" }]</label>
            </div>
            <div>&nbsp;</div>

            <div>
                <input type="radio" id="type_update" name="importMode" value="update"> <label for="type_update">[{oxmultilang ident="GN2_NEWSLETTERCONNECT_MODE_UPDATE_LABEL" }] </br> [{oxmultilang ident="GN2_NEWSLETTERCONNECT_MODE_UPDATE_DESC" }]</label>
            </div>
            <div>&nbsp;</div>

            <div>
                <input type="radio" id="type_update_add" name="importMode" value="update_add" checked="checked"> <label for="type_update_add">[{oxmultilang ident="GN2_NEWSLETTERCONNECT_MODE_UPDATE_ADD_LABEL" }] </br> [{oxmultilang ident="GN2_NEWSLETTERCONNECT_MODE_UPDATE_ADD_DESC" }]</label>
            </div>

        </fieldset>

        [{if ($gn2_ExportStatus)}]
            <p>Export</br> [{$gn2_ExportStatus}]</p>
        [{/if}]
        [{if ($gn2_ExportReportData)}]
            <p>Export</br> [{$gn2_ExportReportData}]</p>
            </br>
        [{/if}]

        <p><button title="[{oxmultilang ident='GN2_NEWSLETTERCONNECT_EXPORT_BUTTON_TITLE'}]" type="submit" onClick="Javascript:document.gn2mosformAboExport.transfermethod.value='packet'"" >[{oxmultilang ident='GN2_NEWSLETTERCONNECT_EXPORT_BUTTON'}] </button></p>
        <p><button title="[{oxmultilang ident='GN2_NEWSLETTERCONNECT_EXPORT_CSVMETHOD_BUTTON_TITLE'}]" type="submit" onClick="Javascript:document.gn2mosformAboExport.transfermethod.value='csv'"">[{oxmultilang ident='GN2_NEWSLETTERCONNECT_EXPORT_CSVMETHOD_BUTTON'}] </button></p>


    </form>
</div>

</body>
</html>