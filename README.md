# FOR Sa11y - REDAXO AddOn

FOR Sa11y adds ["Sa11y"](https://sa11y.netlify.app) to the REDAXO frontend, when a user is logged in.  
 
Sa11y visually highlights accessibilty content issues at the source with a simple tooltip on how to fix them. 

The AddOn helps to reduce accessibility issues on the website as an editorial assistent. 

![Sa11y, the accessibility quality assurance tool.](https://ryersondmp.github.io/sa11y/github-banner.png)

## HowTo: 

- Install the AddOn 
- Add this to your Template at the end or beginning of `<body>`: 

```php
  <?=\FriendsOfRedaxo\Sa11y\Sa11y::get()?>
```

## Custom props and config-settings 

On the config page you can add or overwrite the props. 
[https://sa11y.netlify.app/developers/props/](https://sa11y.netlify.app/developers/props/)

### Example

```js
panelPosition: "top-right",
aboutContent: '<h2>Hilfe</h2><p>Fragen? Wenden Sie sich bitte an die REDAXO Community</p>',
delayCheck: 1000,
showGoodImageButton: false,
showGoodLinkButton: false,
```


## For Developers: Update of the vendor

It is not necessary to update the vendor yourself. A GitHub action takes care of this.  


## AddOn-Lead: 
[Thomas Skerbis](https://github.com/skerbis) 

## Credits:
- Many thanks to: [Adam Chaboryk](https://github.com/adamchaboryk), creator of Sa11y
- [Digital Media Projects team at TMU](https://github.com/ryersondmp)
- German Translation:  @elricco @ansichtsache

## Vendor: 
- Licensed under: Sa11y - License & GNU General Public License
- Link to license: https://github.com/ryersondmp/sa11y/blob/master/LICENSE.md
