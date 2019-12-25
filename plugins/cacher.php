<?php namespace EvolutionCMS\Redis\Plugins;

use Event;
use EvolutionCMS\Models\SiteContent;
use Illuminate\Support\Facades\Cache;

Event::listen('evolution.OnBeforeLoadDocumentObject', function($params) {
    $evo = EvolutionCMS();
    $key = 'documentObject' . $params['identifier'];
    if ($evo->getConfig('enable_cache')) {
        $documentObject = Cache::get($key);
    } else {
        $documentObject = null;
    }
    if (is_null($documentObject)) {
        if (is_array($evo->documentObject)) {
            $documentObject = $evo->documentObject;
        } else {
            $documentObject = SiteContent::find($params['identifier'])->toArray();
        }
        if ($documentObject === null) {
            $documentObject = array();
        } else {
            $db = $evo->getDatabase();
            $rs = $db->select("tv.*, IF(tvc.value!='',tvc.value,tv.default_text) as value", $db->getFullTableName("site_tmplvars") . " tv
                    INNER JOIN " . $db->getFullTableName("site_tmplvar_templates") . " tvtpl ON tvtpl.tmplvarid = tv.id
                    LEFT JOIN " . $db->getFullTableName("site_tmplvar_contentvalues") . " tvc ON tvc.tmplvarid=tv.id AND tvc.contentid = '{$documentObject['id']}'", "tvtpl.templateid = '{$documentObject['template']}'");
            $tmplvars = array();
            while ($row = $db->getRow($rs)) {
                $tmplvars[$row['name']] = array(
                    $row['name'],
                    $row['value'],
                    $row['display'],
                    $row['display_params'],
                    $row['type']
                );
            }
            $documentObject = array_merge($documentObject, $tmplvars);
        }
        if ($evo->getConfig('enable_cache')) {
            Cache::forever($key, $documentObject);
        }
    }
    return $documentObject;
});

