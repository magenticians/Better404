Attempt at porting [MagentoBetter404](https://github.com/astorm/MagentoBetter404) to Magento 2.

Currently globally listens for `layout_generate_blocks_after` events, checks if the status of the response is 404 and then replaces the content of the `cms_page` block with a custom block.

### Notes
~~**??** Maybe the whole NoRoute (`NoRouteHandler`) system of Magento 2 should be hooked into to achieve this instead.~~ -> see `noroute` branch