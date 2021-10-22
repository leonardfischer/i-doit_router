# i-doit "Router" Add-on

A small "Router" Add-on that implements additional URL routes for easy access to certain pages in i-doit.
For example this Add-on will provide a route `/open-object/*` that will redirect you to the first found object.

## Routes

When describing the routes I'll just use the absolute path - so, instead of `http://your-idoit.int/open-object/server-abc` I will write `/open-object/server-abc`.

### Open objects via name `/open-object/<object title>`

Opening the URL `/open-object/client001` will trigger the internal logic to look for an object called exactly "client001" and redirect to that object.

If "client001" can not be found, the logic will continue to look for objects with names that begin with "client001" (for example "client00123").

If this also fails the search will continue to look for objects that have "client001" somewhere in their name (for example "my client00123 laptop").

**Good to know** - it is important to know that in case of multiple search results only the first will be used for redirection.
That means: if you have multiple objects called "client001" the first found object will be used. There is no specific order to the results.

### Open objects via primary IP-address `/open-object-by-ip/<ip address>`

Opening the URL `/open-object-by-ip/127.0.0.1` or `/open-object-by-ip/2001:0db8:85a3:0000:0000:8a2e:0370:7334` will trigger the internal
logic to look for an object with the according primary IP-address. In case of IPv6 addresses it does not matter if you use the short or
long form (short: `::1`, or long: `0000:0000:0000:0000:0000:0000:0000:0001`).

### Open objects via inventory number `/open-inventory/<inventory no>`

Opening the URL `/open-inventory/123` will trigger the internal logic to look for an object with the according inventory number.
The logic will use the same rules as `/open-object/<object title>`: The first iteration will check for an exact match,
the second for a matching start and the third for "any" match inside the inventory number.

### Change the tenant `/change-tenant/<tenant id>`

When opening the URL `/change-tenant/2` the internal logic will switch to the tenant with the ID `2`.
Prior this was only possible by selecting a different tenant in the i-doit GUI.

**Good to know** - it is only possible to change the tenant, if the same user exists in the "target" tenant.
This behaviour is defined by the i-doit framework and can not be bypassed.  

This route can pack some **additional parameters**:

The route `/change-tenant/<tenant-id>?open-object=<object-title>` will change the tenant and try to open the passed object by its name.
Internally this route will re-route to `/open-object/<object-title>` after the tenant has been changed.
This means that the same rules apply as mentioned above. 

The route `/change-tenant/<tenant-id>?open-object-id=<object-id>` will change the tenant and try to open the passed object by its ID.
We can not rely on the `open-object` parameter since it is possible that objects with numbers as name exist.  

## Ideas for improvement

It should be possible to implement a GUI for users to define their own routes with the help of placeholders.

If you have any ideas for further routes let me know :)

## Migrating from 1.2.0

Because the add-on identifier changed in version 1.2.1 from `router` to `lfischer_router` it is necessary to **uninstall** the add-on and
then simply installing it again (but of course the current version). Otherwise you would have two "router" add-ons ;) 
