# FOR Sa11y - REDAXO AddOn

FOR Sa11y adds ["Sa11y"](https://sa11y.netlify.app) to the REDAXO frontend, when a user is logged in.  
 
Sa11y visually highlights accessibilty content issues at the source with a simple tooltip on how to fix them. 

The AddOn helps to reduce accessibility issues on the website as an editorial assistent. 


## HowTo: 

- Install the AddOn 
- Add this to your Template at the end or beginning of `<body>`: 

```php
  <?=\FriendsOfRedaxo\Sa11y\Sa11y::get()?>
```

## For Developers: Update of the vendor

- Download current release
- Copy to the assets folder without /tests and /src
- Start pull request

## AddOn-Lead: 
[Thomas Skerbis](https://github.com/skerbis) 

## Credits:
- Many thanks to: [Adam Chaboryk](https://github.com/adamchaboryk), creator of Sa11y
- [Digital Media Projects team at TMU](https://github.com/ryersondmp)
- German Translation:  @elricco @ansichtsache

## Vendor: 
- Licensed under: Sa11y - License & GNU General Public License
- Link to license: https://github.com/ryersondmp/sa11y/blob/master/LICENSE.md
