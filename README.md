Linssen Excerpt
======================================================================

ExpressionEngine plugin that allows you to use the search excerpt as defined in the weblog preferences.

Requirements
----------------------------------------------------------------------
- ExpressionEngine 1.6+
- PHP 5+

Installation
----------------------------------------------------------------------

1. Upload the plugin to your `system/plugins/` directory

Use
----------------------------------------------------------------------
Pass the plugin an entry id and optionally a word limit and trailing string.

Parameters are:
`entry_id`:   The entry id from which you would like the excerpt.
`word_limit`: The number of words it will truncate the excerpt to.
`trailer`:    Any trailing text, can be html. Leave blank to ignore.

`{exp:linssen_excerpt entry_id="{entry_id}" word_limit="10" trailer="... read more"}`

Would result in "Quod littera Investigationes ii parum nunc. Amet suscipit hendrerit wisi… read more"

For use inside an entry loop:

`{exp:weblog:entries weblog="weblog"}
  {exp:linssen_excerpt entry_id="{entry_id}" word_limit="10" trailer="... read more"}
{/exp:weblog:entries}`