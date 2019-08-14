# CMS Commands for Magento2

A Magento2 Module providing CMS-related command-line tools.

## Installation

This module can be installed via composer and the Magento2 command-line tool.
For example:

    composer require do-it-well/magento2-module-cms-command
    ./bin/magento module:enable DIW_CmsCommand
    ./bin/magento setup:upgrade

## Commands

 - **cms:block:list** list available CMS block IDs
 - **cms:block:dump** dump (as JSON) either all CMS block data, or (if
   specified) the CMS block data of a specific `block_id`
 - **cms:block:load** load (from a JSON object, or array of JSON objects), CMS block data
 - **cms:page:list** list available CMS page IDs
 - **cms:page:dump** dump (as JSON) either all CMS page data, or (if
   specified) the CMS page data of a specific `page_id`
 - **cms:page:load** load (from a JSON object, or array of JSON objects), CMS page data

## What It Does

The `cms:*:dump` commands dump either a single model, or each of a collection of
models, and directly output the result of `$model->getData()` as a JSON object
to STDOUT.

The `cms:*:load` commands read a JSON object (or each object from a JSON array
of objects). If a relevant `*_id` attribute is set within the JSON object, that
object is loaded; otherwise, an empty model is created. In either case, the
decoded JSON object is passed to `$model->setData()` directly, and the model is
saved. ie: if the `*_id` field is not set, a new page/block will be created.

As should be obvious, this method of dumping / loading CMS data is very basic,
and there are many situations in which these methods would not be appropriate or
safe. No guarantees are made. You should perform both dumps and loads only with
a complete understanding of the limitations of this implementation.

## Examples

**You can dump a specific page or block as a single json object to STDOUT, by identifier**

    $ bin/magento cms:page:dump home
    {"page_id":"2","title":"My eCommerce Site","page_layout":...

 **...or by numeric id**

    $ bin/magento cms:page:dump 2
    {"page_id":"2","title":"My eCommerce Site","page_layout":...

**You can also dump *all* pages / blocks, as a json array of json objects, by omitting the id/identifier**

    $ bin/magento cms:page:dump
    [
    {"page_id":"1","title":"404 Not Found","page_layout":...},
    {"page_id":"2","title":"My eCommerce Site","page_layout":...},
    ...

**The same format can also be used to re-import a dumped page or block**

    $ bin/magento cms:page:dump home > home.json
    ... edit home.json ...
    $ bin/magento cms:page:load < home.json

**...or a list of multiple pages or blocks**

    $ bin/magento cms:page:dump > pages.json
    ... edit pages.json ...
    $ bin/magento cms:page:load < pages.json

## License

All module code within this repository is licensed under the MIT license. See
the MIT-LICENSE.txt file for details.

## Support

If you encounter any problems with this module, you may open an issue on GitHub
at https://github.com/do-it-well/magento2-module-cms/issues

Premium support, assistance in module installation or configuration, or other
development services, can be obtained by contacting
[Do It Well Limited](https://do-it-well.co.uk/)
