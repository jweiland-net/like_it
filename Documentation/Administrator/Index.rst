..  include:: /Includes.rst.txt


..  _admin-manual:

====================
Administrator Manual
====================

You need to create a custom template, partial or layout for your target content element / plugin / etc. You can use
the TYPO3 way templateRootPaths, partialRootPaths, layoutRootPaths for that. Take a look into the TYPO3 documentation
if you don´t know how to override extension fluid templates.

Inside the custom fluid template / partial / layout it´s required to insert the namespace for the ViewHelper.
Put the following line at the top (before HTML tag!) of your fluid file

..  code-block:: html

    {namespace jw=JWeiland\LikeIt\ViewHelpers}

Now you´re ready to select a position where the like button should be rendered. Then you need to find the variable for the current UID (identifier) and table. If you´re extending Text content elements you can use:

..  code-block:: html

    <jw:widget.rating uid="{data.uid}" table="tt_content" />

Another example would be extending EXT:news:

..  code-block:: html

    <jw:widget.rating uid="{newsItem.uid}" table="tx_news_domain_model_news" />

Save your changes. Then insert the static template from EXT:like_it into your template and include jQuery!
Now you can clear caches and are ready to like content.
