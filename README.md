# FOR [Sa11y](https://sa11y.netlify.app)

FOR sa11y adds ["Sa11y"](https://sa11y.netlify.app) to the REDAXO frontend, when a user is logged in.  
 
Sa11y visually highlights accessibilty content issues at the source with a simple tooltip on how to fix them. 

HowTo: 

- Install the AddOn 
- Add this to your Template at the and of `<head>`: 

```php
  for_sa11y::get();
```

To let FOR sa11y ignore specific elements of the current page add the following css class to each: 

`sa11y-ignore`

To disable it: Just disable the AddOn 


To do: 

- Settings page
- on / off switch (REDAXO minibar)

AddOn-Lead: 
[Thomas Skerbis](https://github.com/skerbis) 

Credits:
- Many thanks to: [Adam Chaboryk](https://github.com/adamchaboryk), creator of Sa11y
- [Digital Media Projects team at TMU](https://github.com/ryersondmp)

Vendor: 
- Licensed under: Sa11y - License && GNU General Public License
- Link to license: https://github.com/ryersondmp/sa11y/blob/master/LICENSE.md
