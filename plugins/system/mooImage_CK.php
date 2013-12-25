<?php

/**
 * @copyright	Copyright (C) 2010 Cédric KEIFLIN alias ced1870
 * http://www.ck-web-creation-alsace.com
 * http://www.joomlack.fr.nf
 * @license		GNU/GPL
 * Version 1.3
 * */
// TODO : créer classe pour mooombre


jimport('joomla.plugin.plugin');

class plgSystemmooImage_CK extends JPlugin {

    function plgSystemmooImage_CK(&$subject, $config) {
        parent :: __construct($subject, $config);
    }

    function onAfterRender() {

        $mainframe = & JFactory::getApplication();
        $document = & JFactory::getDocument();
        $doctype = $document->getType();

        // si pas en frontend, on sort
        if ($mainframe->isAdmin()) {
            return false;
        }


        // si pas HTML, on sort
        if ($doctype !== 'html') {
            return;
        }

        $base = JURI::base();

        // récupère les paramètres du plugin
        $plugin = & JPluginHelper::getPlugin('system', 'mooImage_CK');
        $pluginParams = new JParameter($plugin->params);

        $usemoopuffy = $pluginParams->def('usemoopuffy', '1');
        $classmoopuffy = $pluginParams->def('classmoopuffy', 'moopuffy');
        $dureemoopuffy = $pluginParams->def('dureemoopuffy', '500');
        $ratiomoopuffy = $pluginParams->def('ratiomoopuffy', '1.3');

        $usemooopacite = $pluginParams->def('usemooopacite', '1');
        $classmooopacite = $pluginParams->def('classmooopacite', 'moopuffy');
        $dureemooopacite1 = $pluginParams->def('dureemooopacite1', '500');
        $dureemooopacite2 = $pluginParams->def('dureemooopacite2', '500');
        $opacitemooopacite = $pluginParams->def('opacitemooopacite', '0.5');

        $usemooshake = $pluginParams->def('usemooshake', '1');
        $classmooshake = $pluginParams->def('classmooshake', 'mooshake');
        $dureemooshake = $pluginParams->def('dureemooshake', '100');

        $usemoocorners = $pluginParams->def('usemoocorners', '1');
        $classmoocorners = $pluginParams->def('classmoocorners', 'moocorners');
        $rayonmoocorners = $pluginParams->def('rayonmoocorners', '10');

        $usemooreflect = $pluginParams->def('usemooreflect', '1');


        /* $usemooombre = $pluginParams->def('usemooombre', '1');
          $classmooombre = $pluginParams->def('classmooombre', 'mooombre');
          $dureemooombre = $pluginParams->def('dureemooombre', '100'); */


        $variable = '';

        // charge l'effet moopuffy si nécessaire
        if ($usemoopuffy == '1') {
            $variable .= "<script type=\"text/javascript\" src=\"" . $base . "plugins/system/mooImage_CK/mooPuffy_CK.js\"></script>\n";

            $variable .= "<script type=\"text/javascript\">\n" .
                    "window.addEvent('domready', function() {" .
                    "new Moopuffy_ck($$('." . $classmoopuffy . "'),{" .
                    "mooDuree:" . $dureemoopuffy . "," .
                    "ratio:" . $ratiomoopuffy .
                    "});" .
                    "});\n" .
                    "</script>\n";
        }

        // charge l'effet mooopacite si nécessaire
        if ($usemooopacite == '1') {
            $variable .= "<script type=\"text/javascript\" src=\"" . $base . "plugins/system/mooImage_CK/mooOpacite_CK.js\"></script>\n";

            $variable .= "<script type=\"text/javascript\">\n" .
                    "window.addEvent('domready', function() {" .
                    "new mooOpacite_ck($$('." . $classmooopacite . "'),{" .
                    "mooDuree1:" . $dureemooopacite1 . "," .
                    "mooDuree2:" . $dureemooopacite2 . "," .
                    "opacite:" . $opacitemooopacite .
                    "});" .
                    "});\n" .
                    "</script>\n";
        }

        // charge l'effet mooshake si nécessaire
        if ($usemooshake == '1') {
            $variable .= "<script type=\"text/javascript\" src=\"" . $base . "plugins/system/mooImage_CK/mooShake_CK.js\"></script>\n";

            $variable .= "<script type=\"text/javascript\">\n" .
                    "window.addEvent('domready', function() {" .
                    "new MooShake_ck($$('." . $classmooshake . "'),{" .
                    "mooDuree:" . $dureemooshake .
                    "});" .
                    "});\n" .
                    "</script>\n";
        }

        // charge l'effet moocorners si nécessaire
        if ($usemoocorners == '1') {
            $variable .= "<script type=\"text/javascript\" src=\"" . $base . "plugins/system/mooImage_CK/mooCorners_CK.js\"></script>\n";
            $variable .= "<script type=\"text/javascript\" src=\"" . $base . "plugins/system/mooImage_CK/curvyCorners_CK.js\"></script>\n";

            $variable .= "<script type=\"text/javascript\">\n" .
                    "window.addEvent('domready', function() {" .
                    " var settings = {" .
                    "tl: { radius: " . $rayonmoocorners . " }," .
                    "tr: { radius: " . $rayonmoocorners . " }, " .
                    "bl: { radius: " . $rayonmoocorners . " }, " .
                    "br: { radius: " . $rayonmoocorners . " }," .
                    "antiAlias: true }; " .
                    "curvyCorners(settings, \"." . $classmoocorners . "\");});" .
                    "</script>\n";
        }

        // charge l'effet mooreflect si nécessaire
        if ($usemooreflect == '1') {
            $variable .= "<script type=\"text/javascript\" src=\"" . $base . "plugins/system/mooImage_CK/mooReflection_CK.js\"></script>\n";
        }

        // charge l'effet mooombre si nécessaire
        /* if ($usemooombre == '1') {
          $variable .= "<script type=\"text/javascript\" src=\"" . $base . "plugins/system/mooImage_CK/mooOmbre_CK.js\"></script>\n";
          } */


        // renvoie les données dans la page
        $body = JResponse::getBody();
        $body = str_replace('</head>', $variable . '</head>', $body);
        JResponse::setBody($body);
    }

}