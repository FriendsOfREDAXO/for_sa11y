# FOR [Sa11y](https://sa11y.netlify.app)

![Sa11y, the accessibility quality assurance tool.](https://ryersondmp.github.io/sa11y/assets/github-banner.png)

FOR sa11y stellt, wenn Benutzer eingeloggt sind, ["Sa11y"](https://sa11y.netlify.app) im Frontend bereit.  

Sa11y hebt Probleme mit der Barrierefreiheit von Inhalten visuell an der Quelle hervor und gibt Hilfestellungen zur Behebung der Probleme.

Das AddOn hilft dabei, Probleme mit der Barrierefreiheit auf der Website zu entdecken und zu reduzieren. Es sollte beachtet werden, dass es sich hierbei nur um ein Hilfsmittel handelt und es daher kein zuverlässiges Werkzeug zur Überprüfung der Zugänglichkeit einer Website ist. Hierfür sollten Experten zu Rate gezogen werden. Als redaktionelle Hilfestellung und zum ersten Auffinden von Problemen ist es jedoch sehr nützlich. 

## HowTo: 

- AddOn installieren
- Folgenden Code ame Ende oder Anfang von `<body>` einfügen: 

```php
    <?=\FriendsOfRedaxo\Sa11y\Sa11y::get()?>
```

AddOn-Lead: 
[Thomas Skerbis](https://github.com/skerbis) 

Credits:
- Many thanks to: [Adam Chaboryk](https://github.com/adamchaboryk), creator of Sa11y
- [Digital Media Projects team at TMU](https://github.com/ryersondmp)
- German Translation:  @elricco @ansichtsache

Vendor: 
- Licensed under: Sa11y - License & GNU General Public License
- Link to license: https://github.com/ryersondmp/sa11y/blob/master/LICENSE.md
