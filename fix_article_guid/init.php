<?php
class fix_article_guid extends Plugin {

    const SETTING_NAME_FIX_GUID = 'fix_guid';
    
    private $host;

    private $feed_id;

    function about() {
        return array(1.0,
            "Fix broken/duplicate guid's in feeds by forcing tt-rss to re-generate them",
            "Niehztog",
            false);
    }

    function init($host) {
        $this->host = $host;

        $host->add_hook($host::HOOK_FEED_FETCHED, $this); //only required to store feed id for next hook
        $host->add_hook($host::HOOK_FEED_PARSED, $this);
        $host->add_hook($host::HOOK_PREFS_EDIT_FEED, $this);
        $host->add_hook($host::HOOK_PREFS_SAVE_FEED, $this);
    }

    function hook_feed_fetched($feed_data, $fetch_url, $owner_uid, $feed) {
        $this->feed_id = $feed;
        return $feed_data;
    }

    /**
     * Removes all article guid's
     *
     * @param $feed FeedParser
     */
    function hook_feed_parsed($feed) {

        $fix_guids = $this->read_setting(self::SETTING_NAME_FIX_GUID);
        $fix_guids_value = array_search($this->feed_id, $fix_guids);
        if($fix_guids_value === false) {
            return;
        }

        /* @var $items FeedItem_Common[] */
        $items = $feed->get_items();
        foreach($items as $item) {
            /* @var $elem DOMNode */
            $elem = $item->get_element();
            $guid = $elem->getElementsByTagName("guid")->item(0);
            if($guid) {
                $elem->removeChild($guid);
            }
        }
    }

    function hook_prefs_edit_feed($feed_id)
    {
        print "<header>" . __("Article GUID's") . "</header>";
        print "<section>";

        $this->render_fix_guid_option($feed_id);

        print "</section>";
    }

    function hook_prefs_save_feed($feed_id)
    {
        $this->save_fix_guid_option($feed_id);
    }

    /**
     * @param $feed_id
     */
    private function render_fix_guid_option($feed_id)
    {
        $fix_guids = $this->read_setting(self::SETTING_NAME_FIX_GUID);

        $key = array_search($feed_id, $fix_guids);
        $checked = $key !== FALSE ? "checked" : "";

        print "<fieldset>";

        print "<label class='checkbox'><input dojoType='dijit.form.CheckBox' type='checkbox' id='fix_guids_enabled'
			name='fix_guids_enabled' $checked>&nbsp;" . __('Fix/regenerate GUID\'s') . "</label>";

        print "</fieldset>";
    }

    /**
     * @param $feed_id
     */
    private function save_fix_guid_option($feed_id)
    {
        $fix_guids = $this->host->get($this, self::SETTING_NAME_FIX_GUID);
        if (!is_array($fix_guids)) $fix_guids = array();

        $enable = checkbox_to_sql_bool($_POST["fix_guids_enabled"]);
        $key = array_search($feed_id, $fix_guids);

        if ($enable) {
            if ($key === FALSE) {
                array_push($fix_guids, $feed_id);
            }
        } else {
            if ($key !== FALSE) {
                unset($fix_guids[$key]);
            }
        }

        $this->host->set($this, self::SETTING_NAME_FIX_GUID, $fix_guids);
    }

    /**
     * @return array|bool
     */
    private function read_setting($name)
    {
        $value = $this->host->get($this, $name);
        if (!is_array($value)) $value = array();
        return $value;
    }

    function api_version() {
        return 2;
    }

}
?>
