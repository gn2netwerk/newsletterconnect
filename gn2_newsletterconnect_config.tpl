[{include file="headitem.tpl"}]

<style type="text/css">
    .gn2mos { padding: 10px 20px; }
    .gn2mos dl { float:left; width:100%; }
    .gn2mos dt { float:left; clear:left; width:180px; }
    .gn2mos dd { float:left; margin:0 0 10px 10px;}
    .gn2mos dd input.text { width: 350px; }
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

            <dt>[{oxmultilang ident='GN2_NEWSLETTERCONNECT_API_SIGNUPSETUP'}]</dt>
            <dd><input type="text" name="config[api_signupsetup]" class="text" value="[{$config.service_Mailingwork.api_signupsetup}]"></dd>
        </dl>

        <h2>[{oxmultilang ident='GN2_NEWSLETTERCONNECT_VOUCHERS'}]</h2>

        <dl>
            <dt>[{oxmultilang ident='GN2_NEWSLETTERCONNECT_SAVEVOUCHERS'}]</dt>
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
</div>

</body>
</html>