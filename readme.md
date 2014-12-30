Attempt at porting [MagentoBetter404](https://github.com/astorm/MagentoBetter404) to Magento 2.

Currently globally listens for `layout_generate_blocks_after` events, checks if the status of the response is 404 and then replaces the content of the `cms_page` block with a custom block.

### Notes
- In the original MagentoBetter404 module, the `controller_action_layout_generate_blocks_after` event [is observed](https://github.com/astorm/MagentoBetter404/blob/master/app/code/community/Pulsestorm/Better404/etc/config.xml#L24)
- When fired, the response status [is checked](https://github.com/astorm/MagentoBetter404/blob/master/app/code/community/Pulsestorm/Better404/Model/Observer.php#L32) and if it's a 404, the content block [is replaced](https://github.com/astorm/MagentoBetter404/blob/master/app/code/community/Pulsestorm/Better404/Model/Observer.php#L17-L19) by the custom [404 template](https://github.com/astorm/MagentoBetter404/blob/master/app/design/frontend/base/default/template/pulsestorm_better404/404.phtml).
- At first, this flow was taken over. `layout_generate_blocks_after` is the (changed) name of the event in Magento 2 and checking if a [404 is dealt with](https://github.com/magenticians/Better404/blob/92cbcb09445aa221966255065252c13cce863941/src/Magenticians/Better404/Model/Observer.php#L35) and [replacing the content block](https://github.com/magenticians/Better404/blob/92cbcb09445aa221966255065252c13cce863941/src/Magenticians/Better404/Model/Observer.php#L28-L29) only looks slightly different.
- However, when wading further through the code and [_limited_ documentation](http://devdocs.magento.com/guides/v1.0/architecture/modules/routing.html), the `NoRoute` system is stumbled upon
- How it works: the `DefaultRouter` is sorted (desc) at the lowest order (100) in the `RouterList`. If no other routers (ex. the `BaseRouter`, order 20) can match the request, the `DefaultRouter` kicks in and checks the `NoRouteHandlerList` for handlers
- The default `NoRouteHandler` gets the `web/default/no_route` config value which (by default) points to the CMS (`cms/noroute/index`)
- The `NoRouteHandler` is fed to the `NoRouteHandlerList` in `di.xml` of the `Magento\Core` module
- This means that any module can feed entries to this list! Just make sure the priority (`sortOrder`) is higher than the default `NoRouteHandler` (100)
- From the custom no route handler you can basically do anything you want. The _nicest_ thing is probably to follow the Magento 2 standard and let the no route handler tunnel into a controller...
- Thus the `routes.xml` file defines a `better404` frontname and the controller is called `Noroute\Index`
- The custom `NoRouteHandler` then tunnels the request: ` $request->setModuleName('better404')->setControllerName('noroute')->setActionName('index');`
- And with the additional XML file for the layout instructions, the better 404 template is displayed

~~**??** Maybe the whole NoRoute (`NoRouteHandler`) system of Magento 2 should be hooked into to achieve this instead.~~