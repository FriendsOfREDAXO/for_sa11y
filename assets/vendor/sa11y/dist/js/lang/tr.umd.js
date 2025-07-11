
/*!
  * Sa11y, the accessibility quality assurance assistant.
  * @version 4.1.9
  * @author Adam Chaboryk
  * @license GPL-2.0-or-later
  * @copyright © 2020 - 2025 Toronto Metropolitan University.
  * @contact adam.chaboryk@torontomu.ca
  * GitHub: git+https://github.com/ryersondmp/sa11y.git | Website: https://sa11y.netlify.app
  * For all acknowledgements, please visit: https://sa11y.netlify.app/acknowledgements/
  * The above copyright notice shall be included in all copies or substantial portions of the Software.
**/
(function (global, factory) {
  typeof exports === 'object' && typeof module !== 'undefined' ? module.exports = factory() :
  typeof define === 'function' && define.amd ? define(factory) :
  (global = typeof globalThis !== 'undefined' ? globalThis : global || self, global.Sa11yLangTr = factory());
})(this, (function () { 'use strict';

  /*! WARNING: This is a machine-generated translation and may contain errors or inaccuracies. */
  var tr = {
    // Turkish
    strings: {
      LANG_CODE: 'tr',
      MAIN_TOGGLE_LABEL: 'Erişilebilirliği Kontrol Edin',
      CONTAINER_LABEL: 'Erişilebilirlik Denetleyicisi',
      ERROR: 'Hata',
      ERRORS: 'Hatalar',
      WARNING: 'Uyarı',
      WARNINGS: 'Uyarılar',
      GOOD: 'İyi',
      ON: 'Açık',
      OFF: 'Kapalı',
      ALERT_TEXT: 'Uyarı',
      ALERT_CLOSE: 'Kapat',
      OUTLINE: 'Anahat',
      READABILITY_DESC: 'Okuma zorluğunu ölçmeye yardımcı olmak için <strong>Anahat</strong> sekmesinde okunabilirlik puanını gösterir.',
      TITLE: 'Başlık',
      ALT: 'ALT',
      IMAGES: 'Görseller',
      EDIT: 'Düzenle',
      NO_IMAGES: 'Resim bulunamadı.',
      DECORATIVE: 'Dekoratif',
      MISSING: 'Eksik',
      PAGE_ISSUES: 'Sayfa Sorunları',
      SETTINGS: 'Ayarlar',
      DEVELOPER_CHECKS: 'Geliştirici kontrolleri',
      DEVELOPER_DESC: 'HTML öznitelikleri, formlar gibi düzeltilmesi için kodlama bilgisi gerektirebilecek sorunları kontrol eder.',
      DARK_MODE: 'Karanlık mod',
      SHORTCUT_SR: 'Konuya geç. Klavye kısayolu: Alt S',
      SKIP_TO_ISSUE: 'Konuya geç',
      NEW_TAB: 'Yeni sekme açar',
      LINKED: 'Bağlı',
      PANEL_HEADING: 'Erişilebilirlik kontrolü',
      NO_ERRORS_FOUND: 'Hata bulunamadı.',
      WARNINGS_FOUND: 'uyarılar bulundu.',
      TOTAL_FOUND: 'toplam sorun bulundu.',
      NOT_VISIBLE: 'Görüntülemeye çalıştığınız öğe görünmüyor; gizli veya bir akordeon ya da sekme bileşeninin içinde olabilir. İşte bir önizleme:',
      MISSING_ROOT: '<code>%(root)</code> hedef alanı mevcut olmadığı için sayfanın tamamı erişilebilirlik açısından kontrol edildi.',
      MISSING_READABILITY_ROOT: 'Okunabilirlik puanı, <code>%(fallback)</code> içerik alanına dayanmaktadır çünkü hedef alan <code>%(root)</code> mevcut değildir.',
      HEADING_NOT_VISIBLE: 'Başlık görünür değildir; gizli veya bir akordeon ya da sekme bileşeninin içinde olabilir.',
      SKIP_TO_PAGE_ISSUES: 'Sayfa Sorunlarına Geç',
      CONSOLE_ERROR: 'Üzgünüz, ancak bu sayfadaki erişilebilirlik denetleyicisinde bir sorun var. Lütfen <a href="%(link)">bu form</a> aracılığıyla veya <a href="%(link)">GitHub</a> üzerinden bildirebilir misiniz?',
      APPEARANCE: 'Görünüm',
      MOVE_PANEL: 'Paneli taşı',

      // Dismiss
      PANEL_DISMISS_BUTTON: 'Göster %(dismissCount) reddedilmiş',
      DISMISS: 'Göz ardı et',
      DISMISS_ALL: 'Hepsini göz ardı et',
      DISMISSED: 'Göz ardı edildi',
      DISMISS_REMINDER: 'Uyarıların yalnızca <strong>geçici olarak</strong> göz ardı edildiğini lütfen unutmayın. Tarayıcı geçmişinizi ve çerezlerinizi temizlemek, tüm sayfalardaki daha önce göz ardı edilen uyarıları geri getirecektir.',

      // Export
      DATE: 'Tarih',
      PAGE_TITLE: 'Sayfa başlığı',
      RESULTS: 'Sonuçlar',
      EXPORT_RESULTS: 'Sonuçları dışa aktar',
      GENERATED: '%(tool) ile oluşturulan sonuçlar.',
      PREVIEW: 'Önizleme',
      ELEMENT: 'Öğe',
      PATH: 'Yol',

      // Colour filters
      COLOUR_FILTER: 'Renk filtresi',
      PROTANOPIA: 'Protanopia',
      DEUTERANOPIA: 'Deuteranopia',
      TRITANOPIA: 'Tritanopia',
      MONOCHROMACY: 'Monokromasi',
      COLOUR_FILTER_MESSAGE: 'Algılanması veya diğer renklerden ayırt edilmesi zor olan unsurları kontrol edin.',
      RED_EYE: 'Kırmızı kör.',
      GREEN_EYE: 'Yeşil kör.',
      BLUE_EYE: 'Mavi kör.',
      MONO_EYE: 'Kırmızı, mavi ve yeşil kör.',
      COLOUR_FILTER_HIGH_CONTRAST: 'Renk filtreleri yüksek kontrast modunda çalışmaz.',

      // Alternative text stop words
      SUS_ALT_STOPWORDS: [
        'görüntü',
        'grafik',
        'resim',
        'fotoğraf',
      ],
      PLACEHOLDER_ALT_STOPWORDS: [
        'alt',
        'görüntü',
        'fotoğraf',
        'dekoratif',
        'yer tutucu',
        'yer tutucu resim',
        'ara parça',
      ],
      PARTIAL_ALT_STOPWORDS: [
        'tıklayın',
        'buraya tıklayın',
        'daha fazlası için buraya tıklayın',
        'daha fazla bi̇lgi̇ i̇çi̇n tiklayin',
        'buraya tıklayarak',
        'kontrol et',
        'burada ayrıntılı',
        'i̇ndir',
        'buradan indirin',
        'buradan indirebilirsiniz',
        'öğrenmek',
        'daha fazla bi̇lgi̇ edi̇ni̇n',
        'daha fazlasını öğrenin',
        'form',
        'burada',
        'bilgi',
        'link',
        'öğrenmek',
        'daha fazla öğren',
        'daha fazla bilgi edinin',
        'öğrenmek',
        'daha fazla',
        'sayfa',
        'kağıt',
        'daha fazla oku',
        'okuyun',
        'bunu okuyun',
        'bu',
        'bu sayfa',
        'bu web sitesi',
        'görünüm',
        'bizi görüntüleyin',
        'web sitesi',
      ],
      CLICK: ['click', 'tıklama'],
      NEW_WINDOW_PHRASES: [
        'dış',
        'yeni sekme',
        'yeni pencere',
        'açılır pencere',
      ],
      FILE_TYPE_PHRASES: ['belge', 'elektronik tablo', 'hesaplama sayfası', 'sıkıştırılmış dosya', 'arşivlenmiş dosya', 'çalışma sayfası', 'powerpoint', 'sunum', 'yükle', 'video', 'ses', 'pdf'],

      // Readability
      READABILITY: 'Okunabilirlik',
      AVG_SENTENCE: 'Cümle başına ortalama kelime:',
      COMPLEX_WORDS: 'Karmaşık kelimeler:',
      TOTAL_WORDS: 'Kelimeler:',
      VERY_DIFFICULT: 'Çok zor',
      DIFFICULT: 'Zor',
      FAIRLY_DIFFICULT: 'Oldukça zor',
      READABILITY_NO_CONTENT: 'Okunabilirlik puanı hesaplanamıyor. Paragraf <code>&lt;p&gt;</code> veya liste içeriği <code>&lt;li&gt;</code> bulunamadı.',
      READABILITY_NOT_ENOUGH: 'Okunabilirlik puanını hesaplamak için yeterli içerik yok.',

      // Headings
      HEADING_SKIPPED_LEVEL: 'Başlıklar seviyeleri atlamamalı veya <strong>Başlık %(PREV_LEVEL)</strong> seviyesinden <strong {C}>Başlık %(LEVEL)</strong> seviyesine geçmemelidir, çünkü bu içerik düzenini ve hiyerarşisini bozar ve takip etmeyi zorlaştırır. <hr> Eğer <strong {C}>%(HEADING)</strong>, <strong>%(PREV_HEADING)</strong> bölümünün altına giriyorsa, bunun yerine bir <strong>Başlık %(LEVEL)</strong> olarak formatlamayı düşünün.',
      HEADING_EMPTY: 'Boş başlık bulundu! Düzeltmek için bu satırı silin veya biçimini <strong {C}>Heading %(level)</strong> yerine <strong>Normal</strong> veya <strong>Paragraph</strong> olarak değiştirin.',
      HEADING_LONG: 'Başlık uzun! Başlıklar içeriği düzenlemek ve yapıyı aktarmak için kullanılmalıdır. Kısa, bilgilendirici ve benzersiz olmalıdırlar. Lütfen başlıkları %(MAX_LENGTH) karakterden az tutun (bir cümleden fazla olmamalıdır). <hr> <strong {B}>%(HEADING_LENGTH) Karakter</strong>',
      HEADING_FIRST: 'Bir sayfadaki ilk başlık genellikle Başlık 1 veya Başlık 2 olmalıdır. Başlık 1, ana içerik bölümünün başlangıcı olmalıdır ve sayfanın genel amacını açıklayan ana başlıktır. <a href="https://www.w3.org/WAI/tutorials/page-structure/headings/">Başlık Yapısı hakkında daha fazla bilgi edinin.</a>',
      HEADING_MISSING_ONE: 'Eksik Başlık 1. Başlık 1, ana içerik alanının başlangıcı olmalıdır ve sayfanın genel amacını açıklayan ana başlıktır. <a href="https://www.w3.org/WAI/tutorials/page-structure/headings/">Başlık Yapısı hakkında daha fazla bilgi edinin.</a>',
      HEADING_EMPTY_WITH_IMAGE: 'Başlığın metni yoktur, ancak bir resim içerir. Bu bir başlık değilse, biçimini <strong {C}>Başlık %(level)</strong> yerine <strong>Normal</strong> veya <strong>Paragraf</strong> olarak değiştirin. Aksi takdirde, dekoratif değilse lütfen resme alt metin ekleyin.',
      PANEL_HEADING_MISSING_ONE: 'Başlık 1 eksik!',
      PANEL_NO_HEADINGS: 'Başlık bulunamadı.',

      // Links
      LINK_EMPTY: 'Herhangi bir metin içermeyen boş bağlantıları kaldırın.',
      LINK_EMPTY_LABELLEDBY: 'Bağlantının <code>aria-labelledby</code> değeri boş veya sayfadaki başka bir öğenin <code>id</code> özniteliği değeriyle eşleşmiyor.',
      LINK_EMPTY_NO_LABEL: 'Bağlantı, ekran okuyucular ve diğer yardımcı teknolojiler tarafından görülebilen ayırt edilebilir bir metne sahip değil. Düzeltmek için: <ul><li>Bağlantının sizi nereye götürdüğünü açıklayan kısa bir metin ekleyin.</li><li>Bu bir <a href="https://a11y-101.com/development/icons-and-links">ikon bağlantısı veya SVG,</a> ise muhtemelen açıklayıcı bir etiket eksiktir.</li><li>Bu bağlantının bir kopyala/yapıştır hatasından kaynaklanan bir hata olduğunu düşünüyorsanız, silmeyi düşünün.</li></ul>',
      LINK_STOPWORD: 'Bağlantı metni, bağlam dışında yeterince açıklayıcı olmayabilir: <strong {C}>%(ERROR)</strong>',
      LINK_STOPWORD_ARIA: 'Erişilebilir bir isim sağlanmış olsa da, görünür bağlantı metnini gözden geçirmeyi düşünün. &quot;<strong {C}>%(ERROR)</strong>&quot; gibi ifadeler anlamlı değildir.',
      LINK_TIP: '<hr> <strong>İpucu!</strong> Bağlantının amacını tanımlayan, genellikle sayfa veya belge başlığı olan açık ve benzersiz bağlantı metni kullanın.',
      LINK_CLICK_HERE: '"tıklayın" veya "buraya tıklayın" ifadesi, fare mekaniğine odaklanır, ancak birçok kişi fare kullanmaz veya bu web sitesini mobil cihazda görüntülüyor olabilir. Görevle ilgili bir fiil kullanmayı düşünün.',
      DUPLICATE_TITLE: 'Bağlantılar ve resimler üzerindeki <code>title</code> özelliği, ek bilgi sağlamak amacıyla kullanılır ve metin veya alternatif metinden <strong>farklı</strong> olmalıdır. Başlık metni, bir öğenin üzerine gelindiğinde görüntülenir, ancak klavye veya dokunmatik girişle erişilemez. <a href="https://www.a11yproject.com/posts/title-attributes/">Title özelliğinden tamamen kaçınmayı düşünün.</a>',
      LINK_SYMBOLS: 'Bağlantı metninde, eyleme çağrı olarak sembollerin kullanılmasından, bunlar yardımcı teknolojilerden gizlenmedikçe kaçının. Ekran okuyucular sembolleri yüksek sesle okuyabilir, bu da kafa karıştırıcı olabilir. Bunları kaldırmayı düşünün: <strong {C}>%(ERROR)</strong>',
      LINK_URL: "Bağlantı metni olarak kullanılan daha uzun, daha az anlaşılır URL'lerin yardımcı teknoloji ile dinlenmesi zor olabilir. Çoğu durumda, URL yerine insan tarafından okunabilir metin kullanmak daha iyidir. Kısa URL'ler (bir sitenin ana sayfası gibi) uygundur.",
      LINK_DOI: 'Web sayfaları veya yalnızca çevrimiçi kaynaklar için <a href="https://apastyle.apa.org/style-grammar-guidelines/paper-format/accessibility/urls#:~:text=descriptive%20links">APA Stil kılavuzu,</a> çalışmanın URL\'sini veya DOI\'sini başlığının etrafına sararak açıklayıcı bağlantılar kullanılmasını önerir. Bağlantı metni olarak kullanılan daha uzun, daha az anlaşılır URL\'lerin yardımcı teknoloji ile erişildiğinde anlaşılması zor olabilir.',
      LINK_NEW_TAB: 'Bağlantı uyarı vermeden yeni bir sekmede veya pencerede açılır. Bunu yapmak, özellikle görsel içeriği algılamakta zorluk çeken kişiler için kafa karıştırıcı olabilir. İkinci olarak, bir kişinin deneyimini kontrol etmek veya onun yerine karar vermek her zaman iyi bir uygulama değildir. Bağlantı metninde bağlantının yeni bir pencerede açıldığını belirtin. <hr> <strong>İpucu!</strong> En iyi uygulamaları öğrenin: <a href="https://www.nngroup.com/articles/new-browser-windows-and-tabs/">bağlantıları yeni tarayıcı pencerelerinde ve sekmelerinde açma.</a>',
      LINK_FILE_EXT: 'Bağlantı, uyarı vermeden bir PDF veya indirilebilir dosyaya (örn. MP3, Zip, Word Doc) işaret ediyor. Bağlantı metni içinde dosya türünü belirtin. Büyük bir dosya ise, dosya boyutunu da eklemeyi düşünün. <hr> <strong>Örnek:</strong> Yönetici Raporu (PDF, 3MB)',
      LINK_IDENTICAL_NAME: 'Bağlantı, farklı bir sayfaya işaret etmesine rağmen başka bir bağlantıyla aynı metne sahip. Aynı metne sahip birden fazla bağlantı, ekran okuyucu kullanan kişiler için kafa karışıklığına neden olabilir. <strong>Aşağıdaki bağlantıyı diğer bağlantılardan ayırt etmeye yardımcı olmak için daha açıklayıcı hale getirmeyi düşünün.</strong> <hr> <strong {B}>Erişilebilir ad</strong> <strong {C}>%(TEXT)</strong>',

      // Images
      MISSING_ALT_LINK_HAS_TEXT: 'Görüntü, çevresindeki metinle birlikte bir bağlantı olarak kullanılıyor, ancak alt özniteliği dekoratif veya boş olarak işaretlenmelidir.',
      MISSING_ALT_LINK: 'Resim bağlantı olarak kullanılıyor ancak alt metni eksik! Lütfen alt metnin bağlantının sizi nereye götüreceğini açıkladığından emin olun.',
      MISSING_ALT: 'Eksik alt metin! Görsel bir hikaye, ruh hali veya önemli bir bilgi aktarıyorsa, görseli tanımladığınızdan emin olun.',
      LINK_ALT_FILE_EXT: 'Alternatif metin, dosya uzantıları veya resim boyutlarını içermemelidir. Alt metnin, görüntünün gerçek bir tanımını değil, bağlantının hedefini açıkladığından emin olun. Kaldırın: <strong {C}>%(ERROR)</strong> <hr> {ALT} {L} <strong {C}>%(ALT_TEXT)</strong>',
      LINK_PLACEHOLDER_ALT: 'Bağlantılı bir resim içinde tanımlayıcı olmayan veya yer tutucu alt metin bulundu. Alt metnin, görüntünün gerçek bir tanımını değil, bağlantının hedefini açıkladığından emin olun. Aşağıdaki alt metni değiştirin. <hr> {ALT} {L} <strong {C}>%(ALT_TEXT)</strong>',
      LINK_SUS_ALT: 'Yardımcı teknolojiler zaten bunun bir resim olduğunu gösterir, bu nedenle &quot;<strong {C}>%(ERROR)</strong>&quot; gereksiz olabilir. Alt metnin, resmin gerçek bir tanımını değil, bağlantının hedefini açıkladığından emin olun. <hr> {ALT} {L} <strong {C}>%(ALT_TEXT)</strong>',
      ALT_FILE_EXT: 'Alternatif metin, dosya uzantıları veya resim boyutlarını içermemelidir. Görsel bir hikaye, ruh hali veya önemli bir bilgi aktarıyorsa, görseli tanımladığınızdan emin olun. Kaldırın: <strong {C}>%(ERROR)</strong> <hr> {ALT} <strong {C}>%(ALT_TEXT)</strong>',
      ALT_PLACEHOLDER: 'Tanımlayıcı olmayan veya yer tutucu alt metin bulundu. Aşağıdaki alt metni daha anlamlı bir metinle değiştirin. <hr> {ALT} <strong {C}>%(ALT_TEXT)</strong>',
      SUS_ALT: 'Yardımcı teknolojiler zaten bunun bir resim olduğunu belirtmektedir, bu nedenle &quot;<strong {C}>%(ERROR)</strong>&quot; gereksiz olabilir. <hr> {ALT} <strong {C}>%(ALT_TEXT)</strong>',
      LINK_IMAGE_NO_ALT_TEXT: 'Bağlantı içindeki resim dekoratif olarak işaretlenmiş ve bağlantı metni yok. Lütfen resme bağlantının hedefini açıklayan alt metin ekleyin.',
      LINK_IMAGE_TEXT: 'Bağlantı, çevresindeki metni açıklayıcı bir etiket olarak kullanmasına rağmen görüntü dekoratif olarak işaretlenmiştir.',
      LINK_IMAGE_LONG_ALT: 'Bağlantılı bir görseldeki alt metin açıklaması <strong>çok uzun</strong>. Bağlantılı görsellerdeki alt metin, görselin birebir açıklamasını değil, bağlantının sizi nereye götürdüğünü açıklamalıdır. <strong>Alt metin olarak bağlantı verilen sayfanın başlığını kullanmayı düşünün.</strong> <hr> {ALT} {L} <strong {B}>%(altLength) Karakter</strong> <strong {C}>%(ALT_TEXT)</strong>',
      LINK_IMAGE_ALT: 'Resim bağlantısı alt metin içeriyor. <strong>Alt metin bağlantının sizi nereye götürdüğünü açıklıyor mu?</strong> Bağlantı verilen sayfanın başlığını alt metin olarak kullanmayı düşünün. <hr> {ALT} {L} <strong {C}>%(ALT_TEXT)</strong>',
      LINK_IMAGE_ALT_AND_TEXT: 'Resim bağlantısı <strong>hem alt metin hem de çevresindeki bağlantı metnini içerir.</strong> Bu resim dekoratifse ve başka bir sayfaya işlevsel bir bağlantı olarak kullanılıyorsa, resmi dekoratif veya boş olarak işaretlemeyi düşünün - çevresindeki bağlantı metni yeterli olmalıdır. <hr> {ALT} <strong {C}>%(ALT_TEXT)</strong> <hr> <strong {B}>Erişilebilir ad</strong> {L} <strong {C}>%(TEXT)</strong>',
      IMAGE_FIGURE_DECORATIVE: 'Resim <strong>dekoratif</strong> olarak işaretlenmiştir ve yardımcı teknoloji tarafından göz ardı edilecektir. <hr> Bir <strong>başlık</strong> verilmiş olsa da, çoğu durumda görselin alt metni de olmalıdır. <ul><li>Alt metin, görselde ne olduğuna dair kısa bir açıklama sağlamalıdır.</li><li>Alt yazı genellikle görseli çevreleyen içerikle ilişkilendirmek için bağlam sağlamalı veya belirli bir bilgi parçasına dikkat çekmelidir.</li></ul> Daha fazla bilgi edinin: <a href="https://thoughtbot.com/blog/alt-vs-figcaption#the-figcaption-element">alt versus figcaption.</a>',
      IMAGE_FIGURE_DUPLICATE_ALT: 'Hem alt hem de başlık metni için aynı kelimeleri kullanmayın. Ekran okuyucular bilgileri iki kez duyuracaktır. <ul><li>Alt metin görselde ne olduğuna dair kısa bir açıklama sağlamalıdır.</li><li>Alt yazı genellikle görseli çevreleyen içerikle ilişkilendirmek için bağlam sağlamalı veya belirli bir bilgi parçasına dikkat çekmelidir.</li></ul> Daha fazla bilgi edinin: <a href="https://thoughtbot.com/blog/alt-vs-figcaption#the-figcaption-element">alt versus figcaption.</a> <hr> {ALT} <strong {C}>%(ALT_TEXT)</strong>',
      IMAGE_DECORATIVE: 'Görüntü <strong>dekoratif</strong> olarak işaretlenir ve yardımcı teknoloji tarafından göz ardı edilir. Görsel bir hikaye, ruh hali veya önemli bir bilgi aktarıyorsa alt metin eklediğinizden emin olun.',
      IMAGE_DECORATIVE_CAROUSEL: 'Görüntü dekoratif olarak işaretlenmiş, ancak bir döngü veya galerideki tüm görüntüler herkes için eşdeğer bir deneyim sağlamak için açıklayıcı alt metin içermelidir.',
      IMAGE_ALT_TOO_LONG: 'Alt metin açıklaması <strong>çok uzun</strong>. Alt metin kısa, ancak bir <em>tweet</em> gibi anlamlı olmalıdır (yaklaşık 100 karakter). Bu karmaşık bir görsel veya grafikse, görselin uzun açıklamasını aşağıdaki metne veya bir akordeon bileşenine koymayı düşünün. <hr> {ALT} <strong {B}>%(altLength) Karakter</strong> <strong {C}>%(ALT_TEXT)</strong>',
      IMAGE_PASS: '{ALT} %(ALT_TEXT)',
      LABELS_MISSING_IMAGE_INPUT: 'Resim düğmesinin alt metni eksik. Lütfen erişilebilir bir ad sağlamak için alt metin ekleyin. Örneğin: <em>Arama</em> veya <em>Gönder</em>.',
      LABELS_INPUT_RESET: 'Sıfırla düğmeleri özellikle gerekmedikçe <strong>kullanılmamalıdır</strong> çünkü yanlışlıkla etkinleştirilmeleri kolaydır. <hr> <strong>İpucu!</strong> <a href="https://www.nngroup.com/articles/reset-and-cancel-buttons/">Reset ve İptal düğmelerinin neden kullanılabilirlik sorunları oluşturduğunu öğrenin.</a>',
      LABELS_ARIA_LABEL_INPUT: 'Girdinin erişilebilir bir adı vardır, ancak lütfen görünür bir etiket olduğundan da emin olun. <hr> <strong {B}>Erişilebilir ad</strong> <strong {C}>%(TEXT)</strong>',
      LABELS_NO_FOR_ATTRIBUTE: "Bu girdiyle ilişkilendirilmiş bir etiket yok. Etikete, bu girdinin <code>id</code>'siyle eşleşen bir <code>for</code> niteliği ekleyin. <hr> <strong {B}>ID</strong> <strong {C}>#%(id)</strong>",
      LABELS_MISSING_LABEL: 'Bu girdiyle ilişkilendirilmiş bir etiket yok. Lütfen bu girdiye bir <code>id</code> ekleyin ve etikete eşleşen bir <code>for</code> niteliği ekleyin.',
      LABELS_PLACEHOLDER: 'Kaybolan yer tutucu metin, insanların bir alana hangi bilginin ait olduğunu hatırlamalarını zorlaştırır ve hataları tanımlamayı ve düzeltmeyi zor hale getirir. Bunun yerine, form alanından önce kalıcı olarak görünür bir ipucu kullanmayı düşünün. <hr> Daha fazla bilgi: <a href="https://www.nngroup.com/articles/form-design-placeholders/">Form alanlarındaki yer tutucular zararlıdır.</a>',

      // Embedded content
      EMBED_VIDEO: 'Lütfen <strong>tüm videolarda altyazı olduğundan emin olun.</strong> Tüm ses ve video içerikleri için altyazı sağlanması zorunlu bir A Düzeyi gerekliliğidir. Altyazılar, işitme engelli veya işitme güçlüğü çeken kişileri destekler.',
      EMBED_AUDIO: "Lütfen tüm podcast'ler için bir <strong>transkript sağladığınızdan emin olun.</strong> Ses içeriği için transkript sağlamak zorunlu bir Seviye A gerekliliğidir. Transkriptler işitme engelli veya işitme güçlüğü çeken kişileri destekler, ancak herkese fayda sağlayabilir. Transkripti aşağıya veya bir akordeon panel içine yerleştirmeyi düşünün.",
      EMBED_DATA_VIZ: 'Bunun gibi veri görselleştirme araçları, gezinmek için klavye veya ekran okuyucu kullanan kişiler için genellikle sorunludur ve az gören veya renk körlüğü olan kişiler için önemli zorluklar yaratabilir. Aynı bilgilerin widget\'ın altında alternatif (metin veya tablo) bir formatta sunulması önerilir. <hr> <a href="https://www.w3.org/WAI/tutorials/images/complex">karmaşık görüntüler hakkında daha fazla bilgi edinin.</a>',
      EMBED_MISSING_TITLE: 'Gömülü içerik, içeriğini açıklayan erişilebilir bir ad gerektirir. Lütfen <code>iframe</code> öğesinde benzersiz bir <code>title</code> veya <code>aria-label</code> özniteliği sağlayın. <a href="https://web.dev/learn/accessibility/more-html#iframes">iFrames.</a>',
      EMBED_GENERAL: 'Gömülü içerik kontrol edilemiyor. Lütfen resimlerin alt metni, videoların alt yazısı, metinlerin yeterli kontrastı ve etkileşimli bileşenlerin <a href="https://webaim.org/techniques/keyboard/">klavye ile erişilebilir olduğundan emin olun.</a>',
      EMBED_UNFOCUSABLE: 'Odaklanılamayan öğeler içeren <code>&lt;iframe&gt;</code>, <code>tabindex="-1"</code> olmamalıdır. Gömülü içerik klavye ile erişilebilir olmayacak.',

      // QA
      QA_BAD_LINK: 'Kötü bağlantı bulundu. Bağlantı bir geliştirme ortamına işaret ediyor gibi görünüyor. <hr> {L} <strong {C}>%(LINK)</strong>',
      QA_IN_PAGE_LINK: 'Kırık aynı sayfa bağlantısı. Bağlantı hedefi, bu sayfadaki herhangi bir öğeyle eşleşmiyor.',
      QA_STRONG_ITALICS: 'Kalın ve italik etiketlerinin anlamsal bir anlamı vardır ve paragrafların tamamını vurgulamak için <strong>kullanılmamalıdır</strong>. Kalınlaştırılmış metin, bir kelime veya cümleye güçlü bir <strong>vurgu</strong> yapmak için kullanılmalıdır. İtalik yazılar özel isimleri (kitap ve makale başlıkları gibi), yabancı kelimeleri ve alıntıları vurgulamak için kullanılmalıdır. Uzun alıntılar blok alıntı olarak biçimlendirilmelidir.',
      QA_PDF: 'PDF\'ler erişilebilirlik açısından kontrol edilemiyor. PDF\'ler web içeriği olarak kabul edilir ve erişilebilir hale getirilmelidir. PDF\'ler genellikle ekran okuyucu kullanan kişiler (eksik yapısal etiketler veya eksik form alanı etiketleri) ve az gören kişiler (metin büyütüldüğünde yeniden akmıyor) için sorunlar içerir. <ul><li>Bu bir form ise, alternatif olarak erişilebilir bir HTML formu kullanmayı düşünün.</li><li>Bu bir belge ise, bir web sayfasına dönüştürmeyi düşünün.</li></ul>Aksi takdirde, lütfen Acrobat DC\'de erişilebilirlik için <a href="https://helpx.adobe.com/acrobat/using/create-verify-pdf-accessibility.html">PDF\'yi kontrol edin.</a>',
      QA_DOCUMENT: 'Belge erişilebilirlik açısından kontrol edilemiyor. Bağlantılı belgeler web içeriği olarak kabul edilir ve erişilebilir hale getirilmelidir. Lütfen bu belgeyi manuel olarak inceleyin. <ul><li><a href="https://support.google.com/docs/answer/6199477?hl=tr">Google Workspace belgenizi veya sunumunuzu daha erişilebilir hale getirin.</a></li><li><a href="https://support.microsoft.com/tr/office/create-accessible-office-documents-868ecfcd-4f00-4224-b881-a65537a7c155">Ofis belgelerinizi daha erişilebilir hale getirin.</a></li></ul>',
      QA_BLOCKQUOTE: 'Bu bir başlık mı? <strong {C}>%(TEXT)</strong> <hr> Blok tırnaklar yalnızca alıntılar için kullanılmalıdır. Bunun bir başlık olması amaçlanıyorsa, bu blok alıntıyı anlamsal bir başlığa (örneğin Başlık 2 veya Başlık 3) değiştirin.',
      QA_FAKE_HEADING: "Bu bir başlık mı? <strong {C}>%(TEXT)</strong> <hr>Bir satır kalın veya büyük metin bir başlık gibi görünebilir, ancak ekran okuyucu kullanan biri bunun önemli olduğunu anlayamaz veya içeriğine atlayamaz. Kalın veya büyük metin asla anlamsal başlıkların (Başlık 2'den Başlık 6'ya) yerini almamalıdır.",
      QA_FAKE_LIST: 'Bir liste oluşturmaya mı çalışıyorsunuz? Olası liste öğesi bulundu: <strong {C}>%(firstPrefix)</strong> <hr> Bunun yerine madde işareti veya sayı biçimlendirme düğmelerini kullanarak anlamsal listeler kullandığınızdan emin olun. Anlamsal bir liste kullanıldığında, yardımcı teknolojiler toplam öğe sayısı ve listedeki her bir öğenin göreli konumu gibi bilgileri iletebilir. <a href="https://www.w3.org/WAI/tutorials/page-structure/content/#lists">anlamsal listeler hakkında daha fazla bilgi edinin.</a>',
      QA_UPPERCASE: 'Büyük harfler bulundu. Bazı ekran okuyucular büyük harfle yazılan metni kısaltma olarak yorumlayabilir ve her harfi ayrı ayrı okuyabilir. Ayrıca, bazı kişiler büyük harfleri okumayı daha zor bulabilir ve bu durum BAĞIRMA görüntüsü verebilir.',
      QA_UNDERLINE: 'Altı çizili metin bağlantılarla karıştırılabilir. <code>&lt;strong&gt;</code><strong>strong importance</strong><code>&lt;/strong&gt;</code> veya <code>&lt;em&gt;</code><em>emphasis</em><code>&lt;/em&gt;</code> gibi farklı bir stil kullanmayı düşünün.',
      QA_SUBSCRIPT: 'Alt simge ve üst simge biçimlendirme seçenekleri yalnızca tipografik kurallar veya standartlar için metnin konumunu değiştirmek amacıyla kullanılmalıdır. Yalnızca sunum veya görünüm amacıyla <strong>kullanılmamalıdır</strong>. Tüm cümlelerin biçimlendirilmesi okunabilirlik sorunları yaratır. Uygun kullanım durumları arasında üslerin, dördüncü yerine 4<sup>üncü</sup> gibi sıra sayılarının ve kimyasal formüllerin (örneğin H<sub>2</sub>O) gösterilmesi yer alır.',
      QA_NESTED_COMPONENTS: 'Etkileşimli düzen bileşenlerini iç içe kullanmaktan kaçının, örneğin akordeonları sekmelerin içine veya sekmeleri akordeonların içine yerleştirmek gibi. Bu, gezinmeyi karmaşıklaştırabilir, bilişsel yükü artırabilir ve kişilerin içeriği gözden kaçırmasına neden olabilir.',
      QA_JUSTIFY: 'Hem sol hem de sağ kenar boşluklarına hizalanan metinleri kullanmaktan kaçının. Kelimeler arasındaki düzensiz boşluklar nedeniyle bu, bazı insanlar için zor olabilir. Daha iyi okunabilirlik için sola hizalanmış metin kullanın.',
      QA_SMALL_TEXT: 'Küçük metin, özellikle görme sorunu yaşayanlar için okumak daha zordur. Daha iyi okunabilirlik sağlamak için varsayılandan daha küçük yazı tipi boyutlarını kullanmaktan kaçının.',

      // Shared
      ACC_NAME: '<strong {B}>Erişilebilir ad</strong> %(TEXT)',
      ACC_NAME_TIP: '<hr><strong>İpucu!</strong> "Erişilebilir ad", yardımcı teknolojiyi kullanan kişilere iletilen son etikettir ve ARIA tarafından hesaplanır. Bu, bağlantının veya düğmenin amacını anlamalarına yardımcı olur.',
      HIDDEN_FOCUSABLE: 'Bağlantı veya düğme <code>aria-hidden=&quot;true&quot;</code> değerine sahip ancak hâlâ klavye ile odaklanabilir durumda. Bir kopya bağlantı veya düğmeyi gizlemeyi düşünüyorsanız, <code>tabindex=&quot;-1&quot;</code> ekleyin. Aksi takdirde, odak alabilen öğelerde <code>aria-hidden=&quot;true&quot;</code> kullanılmamalıdır. <hr> <a href="https://developer.mozilla.org/en-US/docs/Web/Accessibility/ARIA/Attributes/aria-hidden">aria-hidden özelliği</a> hakkında daha fazla bilgi edinin.',

      // Developer
      DUPLICATE_ID: '<strong>Yinelenen kimlik</strong> bulundu. Yinelenen kimlik hatalarının, içerikle etkileşime girmeye çalışan yardımcı teknolojiler için sorunlara neden olduğu bilinmektedir. Lütfen aşağıdaki kimliği kaldırın veya değiştirin. <hr> <strong {B}>ID</strong> <strong {C}>#%(id)</strong>',
      UNCONTAINED_LI: 'Tüm <code>&lt;li&gt;</code> liste öğeleri, <code>&lt;ul&gt;</code> sırasız veya <code>&lt;ol&gt;</code> sıralı öğeler içinde yer almalıdır. Bu yapı, ekran okuyucularının listeyi ve öğelerini doğru bir şekilde duyurmasına yardımcı olur.',
      TABINDEX_ATTR: 'Öğenin <code>tabindex</code> değeri 0’dan büyük olmamalıdır.',

      // Meta checks
      META_LANG: 'Sayfa dili bildirilmedi! Lütfen <a href="https://www.w3.org/International/questions/qa-html-language-declarations">declare language on HTML tag.</a>',
      META_TITLE: 'Sayfa başlığı eksik! Lütfen bir <a href="https://developer.mozilla.org/tr/docs/Web/HTML/Element/title">sayfa başlığı sağlayın.</a>',
      META_SCALABLE: '<a href="https://developer.mozilla.org/en-US/docs/Web/HTML/Viewport_meta_tag">Görünüm meta etiketi</a> içindeki <code>user-scalable="no"</code> parametresini kaldırarak büyütmeye izin verin.',
      META_MAX: '<a href="https://developer.mozilla.org/en-US/docs/Web/HTML/Viewport_meta_tag">Görünüm meta etiketi</a> içindeki <code>maximum-scale</code> parametresinin 2\'den küçük olmadığından emin olun.',
      META_REFRESH: 'Sayfa bir meta etiketi kullanılarak otomatik olarak yenilenmemelidir.',

      // Buttons
      BTN_EMPTY: 'Düğme, amacını açıklayan erişilebilir bir ada sahip değil.',
      BTN_EMPTY_LABELLEDBY: 'Düğmenin boş bir <code>aria-labelledby</code> değeri var veya sayfadaki başka bir öğenin <code>id</code> değeri ile eşleşmiyor.',
      BTN: 'düğme',
      BTN_TIP: 'Bir <a href="https://www.sarasoueidan.com/blog/accessible-icon-buttons/">erişilebilir düğme</a> nasıl yapılacağını öğrenin.',
      BTN_ROLE_IN_NAME: 'Bir düğmenin adında "düğme" kelimesini içermeyin. Ekran okuyucular zaten öğenin rolünü adıyla birlikte iletir.',
      LABEL_IN_NAME: 'Bu öğe için görünen metin, erişilebilir ad ile farklı görünüyor, bu da yardımcı teknoloji kullanıcıları için kafa karışıklığına neden olabilir. Lütfen gözden geçirin: <hr> <strong {B}>Erişilebilir Ad</strong> <strong {C}>%(TEXT)</strong>',

      // Tables
      TABLES_MISSING_HEADINGS: 'Eksik tablo başlıkları! Erişilebilir tablolar, başlık hücrelerini ve aralarındaki ilişkiyi tanımlayan veri hücrelerini gösteren HTML işaretlemesine ihtiyaç duyar. Bu bilgi, yardımcı teknoloji kullanan kişilere bağlam sağlar. Tablolar yalnızca tablo verileri için kullanılmalıdır. <hr> <a href="https://www.w3.org/WAI/tutorials/tables/">erişilebilir tablolar hakkında daha fazla bilgi edinin.</a>',
      TABLES_SEMANTIC_HEADING: 'Heading 2 veya Heading 3 gibi anlamsal başlıklar yalnızca içerik bölümleri için kullanılmalıdır; HTML tablolarında <strong>değil</strong>. Bunun yerine tablo başlıklarını <code>&lt;th&gt;</code> öğesini kullanarak belirtin. <hr> <a href="https://www.w3.org/WAI/tutorials/tables/">erişilebilir tablolar hakkında daha fazla bilgi edinin.</a>',
      TABLES_EMPTY_HEADING: 'Boş tablo başlığı bulundu! Tablo başlıkları <strong>asla</strong> boş olmamalıdır. İlişkilerini aktarmak için satır ve/veya sütun başlıklarını belirlemek önemlidir. Bu bilgi, yardımcı teknoloji kullanan kişilere bağlam sağlar. Lütfen tabloların yalnızca tablo halindeki veriler için kullanılması gerektiğini unutmayın. <hr> <a href="https://www.w3.org/WAI/tutorials/tables/">erişilebilir tablolar hakkında daha fazla bilgi edinin.</a>',

      // Contrast
      CONTRAST_NORMAL: 'Normal boyuttaki metin en az %(RATIO) kontrast oranına sahip olmalıdır.',
      CONTRAST_LARGE: 'Büyük boyuttaki metin en az %(RATIO) kontrast oranına sahip olmalıdır.',
      CONTRAST_ERROR: 'Metin ile arka plan arasındaki kontrast yetersiz, bu da okumayı zorlaştırabilir.',
      CONTRAST_WARNING: 'Bu metnin kontrastı bilinmiyor ve manuel olarak kontrol edilmelidir. Metin ve arka planın güçlü bir kontrasta sahip olduğundan emin olun.',
      CONTRAST_ERROR_GRAPHIC: 'Grafik, arka planla yeterli kontrasta sahip değil, bu da görünürlüğü zorlaştırıyor.',
      CONTRAST_WARNING_GRAPHIC: 'Bu grafiğin kontrastı bilinmiyor ve manuel inceleme gerektiriyor.',
      CONTRAST_TIP_GRAPHIC: 'Grafikler ve kullanıcı arayüzü öğeleri en az 3:1 kontrast oranına sahip olmalıdır.',
      CONTRAST_OPACITY: 'Daha iyi görünürlük için opaklığı artırın.',
      CONTRAST_APCA: 'Bu kontrast herhangi bir metin boyutu için yeterli değildir. Bu renk ve metin boyutu kombinasyonunu kullanmayı düşünün mü?',
      CONTRAST_COLOR: 'Bunun yerine bu rengi kullanmayı düşünün?',
      CONTRAST_SIZE: 'Bu renk kombinasyonu için metin boyutunu büyütmeyi düşünün?',
      CONTRAST_PLACEHOLDER: 'Bu giriş alanındaki yer tutucu metin, arka planla yeterli kontrasta sahip değil, bu da okunmasını zorlaştırıyor.',
      CONTRAST_PLACEHOLDER_UNSUPPORTED: 'Bu yer tutucu metnin kontrastı bilinmiyor ve manuel olarak incelenmesi gerekiyor. Metin ve arka planın güçlü zıt renklere sahip olduğundan emin olun.',
      CONTRAST_INPUT: 'Bu giriş alanındaki metin, arka planla yeterli kontrasta sahip değil, bu da okunmasını zorlaştırıyor.',
      CONTRAST: 'Kontrast',
      UNKNOWN: 'Bilinmiyor',
      FG: 'Ön plan',
      BG: 'Arka plan',
      NO_SUGGESTION: 'Yalnızca metin rengini değiştirerek erişilebilir bir kombinasyon bulunamıyor. Arka plan rengini değiştirmeyi deneyin.',
    },
  };

  return tr;

}));
