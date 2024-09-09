<?php

namespace App\DataFixtures\ORM;

use App\Entity\Email\TransactionalEmailTemplate;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class LoadTransactionalEmailTemplateData extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $manager->persist($templateA = new TransactionalEmailTemplate());
        $templateA->identifier = 'template-a';
        $templateA->setContent(
            <<<'HTML'
                    <!DOCTYPE HTML PUBLIC "-//W3C//DTD XHTML 1.0 Transitional //EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd"><html xmlns="http://www.w3.org/1999/xhtml" xmlns:v="urn:schemas-microsoft-com:vml" xmlns:o="urn:schemas-microsoft-com:office:office"><head><!--[if gte mso 9]><xml><o:officedocumentsettings><o:allowpng><o:pixelsperinch>96</o:pixelsperinch></o:officedocumentsettings></xml><![endif]--><meta http-equiv="Content-Type" content="text/html; charset=UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1"><meta name="x-apple-disable-message-reformatting"><!--[if !mso]><!--><meta http-equiv="X-UA-Compatible" content="IE=edge"><!--<![endif]--><title></title><style type="text/css">@media only screen and (min-width:520px){.u-row{width:500px!important}.u-row .u-col{vertical-align:top}.u-row .u-col-100{width:500px!important}}@media (max-width:520px){.u-row-container{max-width:100%!important;padding-left:0!important;padding-right:0!important}.u-row .u-col{min-width:320px!important;max-width:100%!important;display:block!important}.u-row{width:100%!important}.u-col{width:100%!important}.u-col>div{margin:0 auto}}body{margin:0;padding:0}table,td,tr{vertical-align:top;border-collapse:collapse}p{margin:0}.ie-container table,.mso-container table{table-layout:fixed}*{line-height:inherit}a[x-apple-data-detectors=true]{color:inherit!important;text-decoration:none!important}table,td{color:#000}</style></head><body class="clean-body u_body" style="margin:0;padding:0;-webkit-text-size-adjust:100%;background-color:#f9f9f9;color:#000"><!--[if IE]><div class="ie-container"><![endif]--><!--[if mso]><div class="mso-container"><![endif]--><table style="border-collapse:collapse;table-layout:fixed;border-spacing:0;mso-table-lspace:0;mso-table-rspace:0;vertical-align:top;min-width:320px;Margin:0 auto;background-color:#f9f9f9;width:100%" cellpadding="0" cellspacing="0"><tbody><tr style="vertical-align:top"><td style="word-break:break-word;border-collapse:collapse!important;vertical-align:top"><!--[if (mso)|(IE)]><table width="100%" cellpadding="0" cellspacing="0" border="0"><tr><td align="center" style="background-color:#f9f9f9"><![endif]--><div class="u-row-container" style="padding:0;background-color:transparent"><div class="u-row" style="margin:0 auto;min-width:320px;max-width:500px;overflow-wrap:break-word;word-wrap:break-word;word-break:break-word;background-color:transparent"><div style="border-collapse:collapse;display:table;width:100%;height:100%;background-color:transparent"><!--[if (mso)|(IE)]><table width="100%" cellpadding="0" cellspacing="0" border="0"><tr><td style="padding:0;background-color:transparent" align="center"><table cellpadding="0" cellspacing="0" border="0" style="width:500px"><tr style="background-color:transparent"><![endif]--><!--[if (mso)|(IE)]><td align="center" width="500" style="width:500px;padding:0;border-top:0 solid transparent;border-left:0 solid transparent;border-right:0 solid transparent;border-bottom:0 solid transparent" valign="top"><![endif]--><div class="u-col u-col-100" style="max-width:320px;min-width:500px;display:table-cell;vertical-align:top"><div style="height:100%;width:100%!important"><!--[if (!mso)&(!IE)]><!--><div style="box-sizing:border-box;height:100%;padding:0;border-top:0 solid transparent;border-left:0 solid transparent;border-right:0 solid transparent;border-bottom:0 solid transparent"><!--<![endif]--><table style="font-family:arial,helvetica,sans-serif" role="presentation" cellpadding="0" cellspacing="0" width="100%" border="0"><tbody><tr><td style="overflow-wrap:break-word;word-break:break-word;padding:10px;font-family:arial,helvetica,sans-serif" align="left"><table width="100%" cellpadding="0" cellspacing="0" border="0"><tr><td style="padding-right:0;padding-left:0" align="center"><img align="center" border="0" src="https://s3public.parti-renaissance.fr/app/1725574717006-11830" alt="" title="" style="outline:0;text-decoration:none;-ms-interpolation-mode:bicubic;clear:both;display:inline-block!important;border:none;height:auto;float:none;width:100%;max-width:480px" width="480"></td></tr></table></td></tr></tbody></table><table class="template-email-block" style="font-family:arial,helvetica,sans-serif" role="presentation" cellpadding="0" cellspacing="0" width="100%" border="0"><tbody><tr><td style="overflow-wrap:break-word;word-break:break-word;padding:0;font-family:arial,helvetica,sans-serif" align="left"><div style="font-size:14px;line-height:140%;text-align:center;word-wrap:break-word"><p style="line-height:140%">[block à remplacer]</p></div></td></tr></tbody></table><table style="font-family:arial,helvetica,sans-serif" role="presentation" cellpadding="0" cellspacing="0" width="100%" border="0"><tbody><tr><td style="overflow-wrap:break-word;word-break:break-word;padding:10px;font-family:arial,helvetica,sans-serif" align="left"><table height="0px" align="center" border="0" cellpadding="0" cellspacing="0" width="100%" style="border-collapse:collapse;table-layout:fixed;border-spacing:0;mso-table-lspace:0;mso-table-rspace:0;vertical-align:top;border-top:1px solid #bbb;-ms-text-size-adjust:100%;-webkit-text-size-adjust:100%"><tbody><tr style="vertical-align:top"><td style="word-break:break-word;border-collapse:collapse!important;vertical-align:top;font-size:0;line-height:0;mso-line-height-rule:exactly;-ms-text-size-adjust:100%;-webkit-text-size-adjust:100%"><span>&#160;</span></td></tr></tbody></table></td></tr></tbody></table><table style="font-family:arial,helvetica,sans-serif" role="presentation" cellpadding="0" cellspacing="0" width="100%" border="0"><tbody><tr><td style="overflow-wrap:break-word;word-break:break-word;padding:10px;font-family:arial,helvetica,sans-serif" align="left"><div style="font-size:14px;line-height:140%;text-align:left;word-wrap:break-word"><p style="line-height:140%">Footer</p></div></td></tr></tbody></table><!--[if (!mso)&(!IE)]><!--></div><!--<![endif]--></div></div><!--[if (mso)|(IE)]><![endif]--><!--[if (mso)|(IE)]><![endif]--></div></div></div><!--[if (mso)|(IE)]><![endif]--></td></tr></tbody></table><!--[if mso]><![endif]--><!--[if IE]><![endif]--></body></html>
                HTML
        );
        $templateA->setJsonContent(
            <<<'JSON'
                    {"counters":{"u_row":2,"u_column":2,"u_content_text":3,"u_content_image":1,"u_content_divider":1},"body":{"id":"HekC4J8Kwr","rows":[{"id":"LZJwgK-s1Q","cells":[1],"columns":[{"id":"lnAHYbBcdx","contents":[{"id":"TVS5MxyTe7","type":"image","values":{"containerPadding":"10px","anchor":"","src":{"author":{"name":"Belle Co","url":"https://www.pexels.com/@belle-co-99483"},"id":23923109,"platform":{"name":"Pexels","url":"https://pexels.com"},"url":"https://s3public.parti-renaissance.fr/app/1725574717006-11830","width":940,"height":627,"preview":{"width":525,"height":350,"url":"https://images.pexels.com/photos/1000445/pexels-photo-1000445.jpeg?auto=compress&cs=tinysrgb&h=350"},"contentType":"image/jpeg","size":31896},"textAlign":"center","altText":"","action":{"name":"web","values":{"href":"","target":"_blank"}},"displayCondition":null,"_meta":{"htmlID":"u_content_image_1","htmlClassNames":"u_content_image"},"selectable":true,"draggable":true,"duplicatable":true,"deletable":true,"hideable":true}},{"id":"ClQb-fFqh4","type":"text","values":{"text":"<p style=\"line-height: 140%;\">[block à remplacer]</p>","_meta":{"htmlID":"u_content_text_2","htmlClassNames":"template-email-block"},"anchor":"","fontSize":"14px","hideable":true,"deletable":true,"draggable":true,"linkStyle":{"inherit":true,"linkColor":"#0000ee","linkUnderline":true,"linkHoverColor":"#0000ee","linkHoverUnderline":true},"textAlign":"center","_languages":{},"lineHeight":"140%","selectable":true,"_styleGuide":null,"hideDesktop":false,"duplicatable":true,"containerPadding":"0px","displayCondition":null}},{"id":"VEQ5gj33AK","type":"divider","values":{"width":"100%","border":{"borderTopWidth":"1px","borderTopStyle":"solid","borderTopColor":"#BBBBBB"},"textAlign":"center","containerPadding":"10px","anchor":"","displayCondition":null,"_meta":{"htmlID":"u_content_divider_1","htmlClassNames":"u_content_divider"},"selectable":true,"draggable":true,"duplicatable":true,"deletable":true,"hideable":true}},{"id":"QTs0PTrmp1","type":"text","values":{"containerPadding":"10px","anchor":"","fontSize":"14px","textAlign":"left","lineHeight":"140%","linkStyle":{"inherit":true,"linkColor":"#0000ee","linkHoverColor":"#0000ee","linkUnderline":true,"linkHoverUnderline":true},"displayCondition":null,"_meta":{"htmlID":"u_content_text_3","htmlClassNames":"u_content_text"},"selectable":true,"draggable":true,"duplicatable":true,"deletable":true,"hideable":true,"text":"<p style=\"line-height: 140%;\">Footer</p>","_languages":{}}}],"values":{"_meta":{"htmlID":"u_column_1","htmlClassNames":"u_column"},"border":{},"padding":"0px","backgroundColor":""}}],"values":{"displayCondition":null,"columns":false,"backgroundColor":"","columnsBackgroundColor":"","backgroundImage":{"url":"","fullWidth":true,"repeat":"no-repeat","size":"custom","position":"center"},"padding":"0px","anchor":"","hideDesktop":false,"_meta":{"htmlID":"u_row_1","htmlClassNames":"u_row"},"selectable":true,"draggable":true,"duplicatable":true,"deletable":true,"hideable":true,"_styleGuide":null}}],"headers":[],"footers":[],"values":{"popupPosition":"center","popupWidth":"600px","popupHeight":"auto","borderRadius":"10px","contentAlign":"center","contentVerticalAlign":"center","contentWidth":"500px","fontFamily":{"label":"Arial","value":"arial,helvetica,sans-serif"},"textColor":"#000000","popupBackgroundColor":"#FFFFFF","popupBackgroundImage":{"url":"","fullWidth":true,"repeat":"no-repeat","size":"cover","position":"center"},"popupOverlay_backgroundColor":"rgba(0, 0, 0, 0.1)","popupCloseButton_position":"top-right","popupCloseButton_backgroundColor":"#DDDDDD","popupCloseButton_iconColor":"#000000","popupCloseButton_borderRadius":"0px","popupCloseButton_margin":"0px","popupCloseButton_action":{"name":"close_popup","attrs":{"onClick":"document.querySelector('.u-popup-container').style.display = 'none';"}},"language":{},"backgroundColor":"#F9F9F9","preheaderText":"","linkStyle":{"body":true,"linkColor":"#0000ee","linkHoverColor":"#0000ee","linkUnderline":true,"linkHoverUnderline":true},"backgroundImage":{"url":"","fullWidth":true,"repeat":"no-repeat","size":"custom","position":"center"},"_meta":{"htmlID":"u_body","description":"","htmlClassNames":"u_body"},"_styleGuide":null}},"schemaVersion":16}
                JSON
        );

        $manager->persist($templateB = new TransactionalEmailTemplate());
        $templateB->parent = $templateA;
        $templateB->identifier = 'template-b';
        $templateB->subject = 'Hey, {{first_name}} !';
        $templateB->setContent(
            <<<'HTML'
                <!DOCTYPE HTML PUBLIC "-//W3C//DTD XHTML 1.0 Transitional //EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd"><html xmlns="http://www.w3.org/1999/xhtml" xmlns:v="urn:schemas-microsoft-com:vml" xmlns:o="urn:schemas-microsoft-com:office:office"><head><!--[if gte mso 9]><xml><o:officedocumentsettings><o:allowpng><o:pixelsperinch>96</o:pixelsperinch></o:officedocumentsettings></xml><![endif]--><meta http-equiv="Content-Type" content="text/html; charset=UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1"><meta name="x-apple-disable-message-reformatting"><!--[if !mso]><!--><meta http-equiv="X-UA-Compatible" content="IE=edge"><!--<![endif]--><title></title><style type="text/css">@media only screen and (min-width:520px){.u-row{width:500px!important}.u-row .u-col{vertical-align:top}.u-row .u-col-100{width:500px!important}}@media (max-width:520px){.u-row-container{max-width:100%!important;padding-left:0!important;padding-right:0!important}.u-row .u-col{min-width:320px!important;max-width:100%!important;display:block!important}.u-row{width:100%!important}.u-col{width:100%!important}.u-col>div{margin:0 auto}}body{margin:0;padding:0}table,td,tr{vertical-align:top;border-collapse:collapse}p{margin:0}.ie-container table,.mso-container table{table-layout:fixed}*{line-height:inherit}a[x-apple-data-detectors=true]{color:inherit!important;text-decoration:none!important}table,td{color:#000}</style><!--[if !mso]><!--><link href="https://assets.parti-renaissance.fr/styles/font-maax.css" rel="stylesheet" type="text/css"><!--<![endif]--></head><body class="clean-body u_body" style="margin:0;padding:0;-webkit-text-size-adjust:100%;background-color:#f9f9f9;color:#000"><!--[if IE]><div class="ie-container"><![endif]--><!--[if mso]><div class="mso-container"><![endif]--><table style="border-collapse:collapse;table-layout:fixed;border-spacing:0;mso-table-lspace:0;mso-table-rspace:0;vertical-align:top;min-width:320px;Margin:0 auto;background-color:#f9f9f9;width:100%" cellpadding="0" cellspacing="0"><tbody><tr style="vertical-align:top"><td style="word-break:break-word;border-collapse:collapse!important;vertical-align:top"><!--[if (mso)|(IE)]><table width="100%" cellpadding="0" cellspacing="0" border="0"><tr><td align="center" style="background-color:#f9f9f9"><![endif]--><div class="u-row-container" style="padding:0;background-color:transparent"><div class="u-row" style="margin:0 auto;min-width:320px;max-width:500px;overflow-wrap:break-word;word-wrap:break-word;word-break:break-word;background-color:transparent"><div style="border-collapse:collapse;display:table;width:100%;height:100%;background-color:transparent"><!--[if (mso)|(IE)]><table width="100%" cellpadding="0" cellspacing="0" border="0"><tr><td style="padding:0;background-color:transparent" align="center"><table cellpadding="0" cellspacing="0" border="0" style="width:500px"><tr style="background-color:transparent"><![endif]--><!--[if (mso)|(IE)]><td align="center" width="500" style="width:500px;padding:0;border-top:0 solid transparent;border-left:0 solid transparent;border-right:0 solid transparent;border-bottom:0 solid transparent" valign="top"><![endif]--><div class="u-col u-col-100" style="max-width:320px;min-width:500px;display:table-cell;vertical-align:top"><div style="height:100%;width:100%!important"><!--[if (!mso)&(!IE)]><!--><div style="box-sizing:border-box;height:100%;padding:0;border-top:0 solid transparent;border-left:0 solid transparent;border-right:0 solid transparent;border-bottom:0 solid transparent"><!--<![endif]--><table style="font-family:Maax,sans-serif" role="presentation" cellpadding="0" cellspacing="0" width="100%" border="0"><tbody><tr><td style="overflow-wrap:break-word;word-break:break-word;padding:10px;font-family:Maax,sans-serif" align="left"><div style="font-size:14px;line-height:140%;text-align:left;word-wrap:break-word"><p style="line-height:140%">Bonjour {{first_name}},</p><p style="line-height:140%">\u{a0}</p><p style="line-height:140%">Vous avez oublié votre mot de passe ...</p></div></td></tr></tbody></table><!--[if (!mso)&(!IE)]><!--></div><!--<![endif]--></div></div><!--[if (mso)|(IE)]><![endif]--><!--[if (mso)|(IE)]><![endif]--></div></div></div><!--[if (mso)|(IE)]><![endif]--></td></tr></tbody></table><!--[if mso]><![endif]--><!--[if IE]><![endif]--></body></html>
                HTML
        );
        $templateB->setJsonContent(
            <<<'JSON'
                {"counters":{"u_row":1,"u_column":1,"u_content_text":1},"body":{"id":"HekC4J8Kwr","rows":[{"id":"LZJwgK-s1Q","cells":[1],"columns":[{"id":"lnAHYbBcdx","contents":[{"id":"DaCe0498w9","type":"text","values":{"containerPadding":"10px","anchor":"","fontSize":"14px","textAlign":"left","lineHeight":"140%","linkStyle":{"inherit":true,"linkColor":"#0000ee","linkHoverColor":"#0000ee","linkUnderline":true,"linkHoverUnderline":true},"displayCondition":null,"_meta":{"htmlID":"u_content_text_1","htmlClassNames":"u_content_text"},"selectable":true,"draggable":true,"duplicatable":true,"deletable":true,"hideable":true,"text":"<p style=\\"line-height: 140%;\\">Bonjour {{first_name}},</p>\\n<p style=\\"line-height: 140%;\\">\u{a0}</p>\\n<p style=\\"line-height: 140%;\\">Vous avez oublié votre mot de passe ...</p>","_languages":{}}}],"values":{"_meta":{"htmlID":"u_column_1","htmlClassNames":"u_column"},"border":{},"padding":"0px","backgroundColor":""}}],"values":{"displayCondition":null,"columns":false,"backgroundColor":"","columnsBackgroundColor":"","backgroundImage":{"url":"","fullWidth":true,"repeat":"no-repeat","size":"custom","position":"center"},"padding":"0px","anchor":"","hideDesktop":false,"_meta":{"htmlID":"u_row_1","htmlClassNames":"u_row"},"selectable":true,"draggable":true,"duplicatable":true,"deletable":true,"hideable":true,"_styleGuide":null}}],"headers":[],"footers":[],"values":{"popupPosition":"center","popupWidth":"600px","popupHeight":"auto","borderRadius":"10px","contentAlign":"center","contentVerticalAlign":"center","contentWidth":"500px","fontFamily":{"label":"Maax","value":"Maax,sans-serif","url":"https://assets.parti-renaissance.fr/styles/font-maax.css","weights":null,"defaultFont":false},"textColor":"#000000","popupBackgroundColor":"#FFFFFF","popupBackgroundImage":{"url":"","fullWidth":true,"repeat":"no-repeat","size":"cover","position":"center"},"popupOverlay_backgroundColor":"rgba(0, 0, 0, 0.1)","popupCloseButton_position":"top-right","popupCloseButton_backgroundColor":"#DDDDDD","popupCloseButton_iconColor":"#000000","popupCloseButton_borderRadius":"0px","popupCloseButton_margin":"0px","popupCloseButton_action":{"name":"close_popup","attrs":{"onClick":"document.querySelector('.u-popup-container').style.display = 'none';"}},"language":{},"backgroundColor":"#F9F9F9","preheaderText":"","linkStyle":{"body":true,"linkColor":"#0000ee","linkHoverColor":"#0000ee","linkUnderline":true,"linkHoverUnderline":true},"backgroundImage":{"url":"","fullWidth":true,"repeat":"no-repeat","size":"custom","position":"center"},"_meta":{"htmlID":"u_body","description":"","htmlClassNames":"u_body"},"_styleGuide":null}},"schemaVersion":16}
                JSON
        );

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            LoadAdminData::class,
        ];
    }
}
