Fix feed articles guid's in Tiny Tiny RSS (Plugin)
==================================================

[Tiny Tiny RSS](http://www.tt-rss.org) plugin
Features:
 * can be activated on a "per feed" base by a checkbox in the feed's preferences
 * forces tt-rss to re-generate all article's guids
 * this can solve duplicate article problems in tt-rss

## How does it work?
* deletes all article's guid elements right after fetching and before storing or processing them
* this forces tt-rss to re-generate the guid from the article link
* _this has the side effect that article links will no longer become overwritten by guids_

## Installation

 * Unpack the [zip-File](https://github.com/Niehztog/ttrss_plugin-fix_article_guid/archive/master.zip)
 * Move the folder `"fix_article_guid"` to your plugins directory
 * Enable the `fix_article_guid` plugin in the Tiny Tiny RSS Preferences and reload.
 * In order to activate and customize the plugin for a feed, right click the Feed, select `"Edit Feed"` and then `"Plugins"`. Here you can decide whether guids should be regenerated on a "per feed" base..

Please report any problems you might encounter using github's [issue tracker](https://github.com/Niehztog/ttrss_plugin-fix_article_guid/issues).

## Legal

Copyright Niehztog

>    This program is free software: you can redistribute it and/or modify
>    it under the terms of the GNU General Public License as published by
>    the Free Software Foundation, either version 3 of the License, or
>    (at your option) any later version.
>
>    This program is distributed in the hope that it will be useful,
>    but WITHOUT ANY WARRANTY; without even the implied warranty of
>    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
>    GNU General Public License for more details.
>
>    You should have received a copy of the GNU General Public License
>    along with this program.  If not, see <http://www.gnu.org/licenses/>.
