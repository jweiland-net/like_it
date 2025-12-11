..  include:: /Includes.rst.txt


..  _upgrade:

=======
Upgrade
=======

If you update EXT:like_it to a newer version, please read this section
carefully!

Update to Version 4.0.0
=======================

We have removed TYPO3 12 compatibility.

No active items to track.

Update to Version 3.0.0
=======================

We have removed TYPO3 10 and 11 compatibility.

ViewHelper AmountOfLikes has beed removed. All numbers of likes are collected
via an AJAX call. Please try to keep the CSS classes in sync with templates
of EXT:like_it.

Update to Version 2.0.0
=======================

With TYPO3 v10 the fluid widget integration was deprecated and will be remove.
That's why we have to completely restructure our `like_it` extension.

Please remove this namespace from template:

..  code-block:: html

    {namespace jw=JWeiland\LikeIt\ViewHelpers}

Please remove following line:

..  code-block:: html

    <jw:widget.rating uid="{data.uid}" table="tt_content" />

and replace it with our new partial integration:

..  code-block:: html

    <f:render partial="Rating" arguments="{table: 'tablename_of_foreign_record', uid: object.uid}"/>

Read the :ref:`Configure like-it <configuration>` how to register loading
the new partial.
