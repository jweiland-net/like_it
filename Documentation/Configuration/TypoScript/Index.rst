..  include:: /Includes.rst.txt


..  _typoscript:

==========
TypoScript
==========

First of all: do you want to implement `like_it` in your own extension or do
you need `like_it` for a foreign extension?

Own Extension
=============

We need to add some lines of TypoScript

..  rst-class:: bignums

1.  Locate File

    Open the `setup.typoscript` file of your extension. In most cases you should
    find it in `[yourExt]/Configuration/TypoScript/setup.typoscript`.

2.  Locate view Part

    Find the part, where you define the template paths for fluid. In most cases
    you should find it here `plugin.tx_[yourExt].view`. See example
    based on `maps2`:

    ..  code-block:: typoscript

        plugin.tx_maps2 {
          view {
            templateRootPaths {
              0 = EXT:maps2/Resources/Private/Templates/
              10 = {$plugin.tx_maps2.view.templateRootPath}
            }
            partialRootPaths {
              0 = EXT:maps2/Resources/Private/Partials/
              10 = {$plugin.tx_maps2.view.partialRootPath}
            }
            layoutRootPaths {
              0 = EXT:maps2/Resources/Private/Layouts/
              10 = {$plugin.tx_maps2.view.layoutRootPath}
            }
          }
        }

3.  Register Partial

    `like_it` comes with a special fluid partial containing all the stuff
    it needs. You have to add that partial path to your TypoScript
    configuration.
    Please replace `extkey` with the extension key of your extension. If your
    extension key contains underscores: remove them before pasting your
    extension key here.

    ..  code-block:: typoscript

        plugin.tx_extkey {
          view {
            templateRootPaths {
              0 = EXT:extkey/Resources/Private/Templates/
              10 = {$plugin.tx_extkey.view.templateRootPath}
            }
            partialRootPaths {
              0 = EXT:extkey/Resources/Private/Partials/
              10 = {$plugin.tx_extkey.view.partialRootPath}
              20 = EXT:like_it/Resources/Private/Partials/
            }
            layoutRootPaths {
              0 = EXT:extkey/Resources/Private/Layouts/
              10 = {$plugin.tx_extkey.view.layoutRootPath}
            }
          }
        }

4.  Add Partial to Your Template

    With `like_it` you can just like ONE record. It's not possible to
    like a collection of records. So, please locate and open the template
    file for single or detail view of a record. Path
    `[yourExt]/Resources/Private/Templates/` is a good start to find
    the templates for detail view. Could be `Detail.html`, `Single.html` or
    `Properties.html`. Search a good place to call the `like_it` partial:

    ..  code-block:: html

        <f:render partial="Rating" arguments="{table: 'tablename_of_your_record', uid: object.uid}"/>

5.  Add Dependency

    As your extension now needs `like_it` as dependency you should add it into
    your `ext_emconf.php`:

    ..  code-block:: php

        ...
        'constraints' => [
            'depends' => [
                'typo3' => '10.4.36-11.5.99',
                'like_it' => '2.0.0-2.99.99',
            ],
            'conflicts' => [
            ],
            'suggests' => [
            ],
        ],
        ...

    And please update your `composer.json`:

    ..  code-block:: php

        ...
        "require": {
            "typo3/cms-core": "^10.4.36 || ^11.5.23",
            "jweiland/like-it": "^2.0"
        },
        ...

6.  Done

    Now you're ready. Please test the integration, check the database
    entries of `tx_likeit_like` and check browser development console
    for any warnings and/or errors.

Foreign Extension
=================

We need to add some lines of TypoScript into your site-package extension
to overwrite the TypoScript of the foreign extension. We prefer using
a site-package extension, but of cause, you can add these lines of
TypoScript also into TypoScript template records (`sys_template`).

..  rst-class:: bignums

1.  Overwrite Foreign Fluid Template

    Search for a detail view template file in the foreign extension and
    paste it into a directory somewhere in `Resources/Private/...` of
    your site-package extension.

2.  Overwrite Template Path

    You have to inform Fluid to search for templates in path of your
    site-package extension first. Here an example how it can looks like for
    extension `events2`:

    ..  code-block:: typoscript

        plugin.tx_events2 {
          view {
            templateRootPaths {
              # Choose a value higher than the value of foreign extension.
              # In most cases 100 is enough.
              # If you're unsure check value in foreign extension or with
              # TypoScript Object Browser
              100 = EXT:site_package/Resources/Private/Extensions/Events2/Templates/
            }
          }
        }

3.  Register Partial

    `like_it` comes with a special fluid partial containing all the stuff
    it needs. You have to add that partial path to your TypoScript
    configuration.

    ..  code-block:: typoscript

        plugin.tx_events2 {
          view {
            templateRootPaths {
              # see code from above
            }
            partialRootPaths {
              # Choose a value higher than the value of foreign extension.
              # In most cases 100 is enough.
              # If you're unsure check value in foreign extension or with
              # TypoScript Object Browser
              100 = EXT:like_it/Resources/Private/Partials/
            }
          }
        }

4.  Add Partial to Overwritten Template

    With `like_it` you can just like ONE record. It's not possible to
    like a collection of records. Search a good place to call the `like_it`
    partial in your overwritten detail view template:

    ..  code-block:: html

        <f:render partial="Rating" arguments="{table: 'tablename_of_foreign_record', uid: object.uid}"/>

    For `news` it could look like:

    ..  code-block:: html

        <f:render partial="Rating" arguments="{table: 'tx_news_domain_model_news', uid: newsItem.uid}"/>

5.  Add dependency

    As your site-package now needs `like_it` as dependency you should add it
    into your `ext_emconf.php`:

    ..  code-block:: php

        ...
        'constraints' => [
            'depends' => [
                'typo3' => '10.4.36-11.5.99',
                'like_it' => '2.0.0-2.99.99',
            ],
            'conflicts' => [
            ],
            'suggests' => [
            ],
        ],
        ...

    And please update your `composer.json`:

    ..  code-block:: php

        ...
        "require": {
            "typo3/cms-core": "^10.4.36 || ^11.5.23",
            "jweiland/like-it": "^2.0"
        },
        ...

6.  Done

    Now you're ready. Please test the integration, check the database
    entries of `tx_likeit_like` and check browser development console
    for any warnings and/or errors.
