[![phpBB](https://www.phpbb.com/theme/images/logos/blue/160x52.png)](http://www.phpbb.com)

# [3.2][RC] PayForLink 1.0.1
This extension is a complement by the EXTENSION [Ultimate Points by dmzx &amp; posey](http://www.dmzx-web.net/viewtopic.php?f=66&t=2415) with which we have the ability to create external links, accessible only if the points balance of the user is positive.

Once accessed, points are deducted of user account and add points for 50% of the cost to the creator of the link.

## Requirements:
* phpBB >=3.2.0
* PHP >=5.3.3
* [Ultimate Points Extension](http://www.dmzx-web.net/viewtopic.php?f=66&t=2415) >=1.1.8 (Need/Recommended)

## Install
1. Download the latest release.
2. In the `ext` directory of your phpBB board, copy the `Picaron/PayForLink` folder. It must be so: `/ext/Picaron/PayForLink`
3. Navigate in the ACP to `Customise -> Manage extensions`.
4. Look for `Pay For Link` under the Disabled Extensions list, and click its `Enable` link.
5. Configure by navigating in the ACP -> POSTING -> Post settings -> Extension: `Pay For Link`

## Uninstall
1. Navigate in the ACP to `Customise -> Extension Management -> Extensions`.
2. Look for `Pay For Link` under the Enabled Extensions list, and click its `Disable` link.
3. To permanently uninstall, click `Delete Data` and then delete the `/ext/Picaron/PayForLink` folder.

## License
[GNU General Public License v2](http://opensource.org/licenses/GPL-2.0)
