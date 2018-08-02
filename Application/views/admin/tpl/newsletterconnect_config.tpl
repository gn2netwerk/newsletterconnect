[{include file="headitem.tpl" title="GENERAL_ADMIN_TITLE"|oxmultilangassign}]
<script type="text/javascript">
<!--
function editThis( sID )
{
    var oTransfer = top.basefrm.edit.document.getElementById( "transfer" );
    oTransfer.oxid.value = sID;
    oTransfer.cl.value = top.basefrm.list.sDefClass;

    //forcing edit frame to reload after submit
    top.forceReloadingEditFrame();

    var oSearch = top.basefrm.list.document.getElementById( "search" );
    oSearch.oxid.value = sID;
    oSearch.actedit.value = 0;
    oSearch.submit();
}
//-->
</script>

[{ if $readonly}]
    [{assign var="readonly" value="readonly disabled"}]
[{else}]
    [{assign var="readonly" value=""}]
[{/if}]

<form name="transfer" id="transfer" action="[{ $oViewConf->getSelfLink() }]" method="post">
    [{$oViewConf->getHiddenSid()}]
    <input type="hidden" name="oxid" value="[{ $oxid }]">
    <input type="hidden" name="oxidCopy" value="[{ $oxid }]">
    <input type="hidden" name="cl" value="newsletterconnect_config">
    <input type="hidden" name="editlanguage" value="[{ $editlanguage }]">
</form>

<form name="myedit" id="myedit" action="[{ $oViewConf->getSelfLink() }]" method="post" enctype="multipart/form-data">
[{ $oViewConf->getHiddenSid() }]
<input type="hidden" name="cl" value="newsletterconnect_config">
<input type="hidden" name="fnc" value="">
<input type="hidden" name="oxid" value="[{ $oxid }]">
<input type="hidden" name="voxid" value="[{ $oxid }]">
<input type="hidden" name="oxparentid" value="[{ $oxparentid }]">
<input type="hidden" name="editval[oxarticles__oxid]" value="[{ $oxid }]">

<div style="float:left;padding:0 20px 20px 0">


[{*
    <table>
        <tr>
            <td class="listheader first" height="15">[{ oxmultilang ident="ARTICLE_MEDIA_TITLE" }] </td>
            <td class="listheader">[{ oxmultilang ident="ARTICLE_MEDIA_URL" }] </td>

            <td class="listheader">[{ oxmultilang ident="ARTICLE_MEDIA_ONLINE_STATUS" }] </td>
            <td class="listheader">[{ oxmultilang ident="ARTICLE_MEDIA_DE" }] </td>
            <td class="listheader">[{ oxmultilang ident="ARTICLE_MEDIA_EN" }] </td>
            <td class="listheader">[{ oxmultilang ident="ARTICLE_MEDIA_NL" }] </td>
            <td class="listheader last">[{ oxmultilang ident="ARTICLE_MEDIA_SE" }] </td>
        </tr>

        [{foreach from=$mediaUrls item="mediaUrl"}]
            <tr>
                <td>[{$mediaUrl->oxmediaurls__oxdesc}]</td>
                <td>[{$mediaUrl->oxmediaurls__oxurl}]</td>

                <td><input type="hidden" class="editinput" name="editval[mediaFiles][[{$mediaUrl->oxmediaurls__oxid->value}]][title]" value="[{$mediaUrl->oxmediaurls__oxdesc}]"></td>
                <td><input type="checkbox" class="editinput" name="editval[mediaFiles][[{$mediaUrl->oxmediaurls__oxid->value}]][active_de]" value="1" [{if $mediaUrl->oxmediaurls__oxactive_de->value}]checked[{/if}] [{$readonly}]></td>
                <td><input type="checkbox" class="editinput" name="editval[mediaFiles][[{$mediaUrl->oxmediaurls__oxid->value}]][active_en]" value="1" [{if $mediaUrl->oxmediaurls__oxactive_en->value}]checked[{/if}] [{$readonly}]></td>
                <td><input type="checkbox" class="editinput" name="editval[mediaFiles][[{$mediaUrl->oxmediaurls__oxid->value}]][active_nl]" value="1" [{if $mediaUrl->oxmediaurls__oxactive_nl->value}]checked[{/if}] [{$readonly}]></td>
                <td><input type="checkbox" class="editinput" name="editval[mediaFiles][[{$mediaUrl->oxmediaurls__oxid->value}]][active_se]" value="1" [{if $mediaUrl->oxmediaurls__oxactive_se->value}]checked[{/if}] [{$readonly}]></td>
            </tr>
        [{/foreach}]
    </table>

    <br style="clear:both;margin-bottom:20px;">
    <input type="submit" class="edittext" name="save" value="[{ oxmultilang ident="ARTICLE_REVIEW_SAVE" }]" onClick="Javascript:document.myedit.fnc.value='save'">
    *}]

</div>

</form>

[{include file="bottomnaviitem.tpl"}]
[{include file="bottomitem.tpl"}]
