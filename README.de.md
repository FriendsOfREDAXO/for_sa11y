# FOR Sa11y - REDAXO AddOn

FOR Sa11y fügt ["Sa11y"](https://sa11y.netlify.app) zum REDAXO-Frontend hinzu, wenn ein Benutzer eingeloggt ist.  
 
Sa11y hebt Probleme mit der Barrierefreiheit von Inhalten visuell an der Quelle hervor und gibt einfache Tooltips mit Hinweisen zur Behebung. 

Das AddOn hilft dabei, Probleme mit der Barrierefreiheit auf der Website zu reduzieren und dient als redaktionelle Hilfestellung. 

![Sa11y, the accessibility quality assurance tool.](https://ryersondmp.github.io/sa11y/assets/github-banner.png) 

## HowTo: 

- AddOn installieren 
- Folgenden Code am Ende oder Anfang von `<body>` einfügen: 

```php
  <?=\FriendsOfRedaxo\Sa11y\Sa11y::get()?>
```

## Custom props und Konfigurationseinstellungen 

Auf der Konfigurationsseite können Sie die Props hinzufügen oder überschreiben. 
[https://sa11y.netlify.app/developers/props/](https://sa11y.netlify.app/developers/props/)

### Beispiel

```js
panelPosition: "top-right",
aboutContent: '<h2>Hilfe</h2><p>Fragen? Wenden Sie sich bitte an die REDAXO Community</p>',
delayCheck: 1000,
showGoodImageButton: false,
showGoodLinkButton: false,
```


## Für Entwickler: Aktualisierung des Vendors

Es ist nicht notwendig, den Vendor selbst zu aktualisieren. Eine GitHub-Action übernimmt dies.  


## AddOn-Lead: 
[Thomas Skerbis](https://github.com/skerbis) 

## Credits:
- Many thanks to: [Adam Chaboryk](https://github.com/adamchaboryk), creator of Sa11y
- [Digital Media Projects team at TMU](https://github.com/ryersondmp)
- German Translation:  @elricco @ansichtsache

## Vendor: 
- Licensed under: Sa11y - License & GNU General Public License
- Link to license: https://github.com/ryersondmp/sa11y/blob/master/LICENSE.md
