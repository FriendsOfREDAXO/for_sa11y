
/*!
  * Sa11y, the accessibility quality assurance assistant.
  * @version 3.1.5
  * @author Adam Chaboryk
  * @license GPL-2.0-or-later
  * @copyright © 2020 - 2024 Toronto Metropolitan University.
  * @contact adam.chaboryk@torontomu.ca
  * GitHub: git+https://github.com/ryersondmp/sa11y.git | Website: https://sa11y.netlify.app
  * For all acknowledgements, please visit: https://sa11y.netlify.app/acknowledgements/
  * The above copyright notice shall be included in all copies or substantial portions of the Software.
**/
(function (global, factory) {
  typeof exports === 'object' && typeof module !== 'undefined' ? module.exports = factory() :
  typeof define === 'function' && define.amd ? define(factory) :
  (global = typeof globalThis !== 'undefined' ? globalThis : global || self, global.Sa11yLangZh = factory());
})(this, (function () { 'use strict';

  /*! WARNING: This is a machine-generated translation and may contain errors or inaccuracies. */
  var zh = {
    // Chinese (simplified)
    strings: {
      LANG_CODE: 'zh',
      MAIN_TOGGLE_LABEL: '检查可及性',
      CONTAINER_LABEL: '可访问性检查器',
      ERROR: '误差',
      ERRORS: '错误',
      WARNING: '警告',
      WARNINGS: '警告',
      GOOD: '良好',
      ON: '在',
      OFF: '关闭',
      ALERT_TEXT: '警报',
      ALERT_CLOSE: '关闭',
      OUTLINE: '页面概要',
      PAGE_ISSUES: '页码问题',
      SETTINGS: '设置',
      CONTRAST: '对比',
      FORM_LABELS: '表格标签',
      LINKS_ADVANCED: '链接 (高级) ',
      DARK_MODE: '黑暗模式',
      SHORTCUT_SCREEN_READER: '跳到问题。键盘快捷方式: Alt S',
      SHORTCUT_TOOLTIP: '跳到问题',
      NEW_TAB: '打开新标签',
      PANEL_HEADING: '无障碍检查',
      PANEL_STATUS_NONE: '没有发现错误。',
      PANEL_ICON_WARNINGS: '发现的警告。',
      PANEL_ICON_TOTAL: '发现的总问题。',
      NOT_VISIBLE_ALERT: '你试图查看的项目是不可见的；它可能是隐藏的或在一个手风琴或标签组件内。这里有一个预览: ',
      ERROR_MISSING_ROOT_TARGET: '由于目标区域<code>%(root)</code>不存在, 全页面被检查为可访问性。',
      HEADING_NOT_VISIBLE_ALERT: '标题是不可见的；它可能是隐藏的或在手风琴或标签组件内。',
      SKIP_TO_PAGE_ISSUES: '跳到页面问题',
      CONSOLE_ERROR_MESSAGE: '对不起, 本页面的可访问性检查器有问题。您能否<a href="%(link)">通过此表格</a>或<a href="%(link)">GitHub</a>报告?',

      // Dismiss
      PANEL_DISMISS_BUTTON: '显示%(dismissCount)被驳回的警告',
      DISMISS: '解散',
      DISMISSED: '撤消的警告',
      DISMISS_REMINDER: '请注意, 警告只被<strong>暂时</strong>驳回。清除你的浏览器历史记录和cookies将恢复所有页面上所有以前被驳回的警告。',

      // Export
      DATE: '日期',
      PAGE_TITLE: '页面标题',
      RESULTS: '结果',
      EXPORT_RESULTS: '导出结果',
      GENERATED: '使用 %(tool) 生成的结果。',
      PREVIEW: '预览',
      ELEMENT: '元素',
      PATH: '路径',

      // Colour filters
      COLOUR_FILTER: '彩色过滤器',
      PROTANOPIA: '原住民',
      DEUTERANOPIA: '氘代苯丙胺(Deuteranopia)',
      TRITANOPIA: 'Tritanopia',
      MONOCHROMACY: '单色系',
      COLOUR_FILTER_MESSAGE: '检查是否有难以察觉或难以与其他颜色区分的元素。',
      RED_EYE: '红盲。',
      GREEN_EYE: '绿色盲区。',
      BLUE_EYE: '蓝盲。',
      MONO_EYE: '红、蓝、绿三色盲。',
      COLOUR_FILTER_HIGH_CONTRAST_MESSAGE: '彩色滤镜在高对比度模式下不工作。',

      // Alternative text stop words
      SUSPICIOUS_ALT_STOPWORDS: [
        '形象',
        '图形',
        '图片',
        '照片',
      ],
      PLACEHOLDER_ALT_STOPWORDS: [
        '备选',
        '形象',
        '照片',
        '装饰性',
        '照片',
        '占位符',
        '占位图片',
        '垫片',
      ],
      PARTIAL_ALT_STOPWORDS: [
        '点击',
        '点击这里',
        '点击这里了解更多',
        '查阅',
        '在此详细说明',
        '在此详细说明。',
        '下载',
        '在此下载',
        '在这里下载',
        '查出',
        '了解更多',
        '形式',
        '这里',
        '信息',
        '链接',
        '学习',
        '了解更多',
        '学会',
        '更多',
        '页',
        '纸',
        '阅读更多',
        '阅读',
        '阅读此文',
        '这个',
        '本页',
        '这一页',
        '本网站',
        '观点',
        '查看我们的',
        '网站',
      ],
      WARNING_ALT_STOPWORDS: [
        '点击这里',
      ],
      NEW_WINDOW_PHRASES: [
        '外来的',
        '新标签',
        '新窗口',
        '弹出式',
        '弹出',
      ],
      FILE_TYPE_PHRASES: ['文档', '电子表格', '计算表', '压缩文件', '归档文件', '工作表', '幻灯片', '演示文稿', '安装', '视频', '音频', 'pdf'],
      LANG_READABILITY: '可读性',
      LANG_AVG_SENTENCE: '每句的平均字数: ',
      LANG_COMPLEX_WORDS: '复杂的词语: ',
      LANG_TOTAL_WORDS: '言语: ',
      LANG_VERY_DIFFICULT: '非常困难',
      LANG_DIFFICULT: '困难的',
      LANG_FAIRLY_DIFFICULT: '相当困难',
      LANG_GOOD: '良好',
      READABILITY_NO_P_OR_LI_MESSAGE: '无法计算可读性得分。没有找到段落<code>&lt;p&gt;</code>或列表内容<code>&lt;li&gt;</code>。',
      READABILITY_NOT_ENOUGH_CONTENT_MESSAGE: '没有足够的内容来计算可读性得分。',
      HEADING_NON_CONSECUTIVE_LEVEL: '使用了非连续的标题级别。标题不应跳级, 或从<strong>标题% (prevLevel) </strong>到<strong {r}>标题% (level) </strong>。',
      HEADING_EMPTY: '发现空的标题!要解决这个问题, 请删除这一行或将其格式从<strong {r}>标题%(level)</strong>改为<strong>正常</strong>或<strong>段落</strong>。',
      HEADING_LONG: '标题很长!标题应被用来组织内容和传达结构。它们应该是简短的、信息丰富的和独特的。请将标题保持在160个字符以内 (不超过一个句子) 。<hr> 字符数: <strong {r}>%(headingLength)</strong>。',
      HEADING_FIRST: '一个页面的第一个标题通常应该是标题1或标题2。标题1应该是主要内容部分的开始, 也是描述页面整体目的的主要标题。了解更多关于<a href="https://www.w3.org/WAI/tutorials/page-structure/headings/">标题结构。</a>的信息。',
      HEADING_MISSING_ONE: '缺少标题1。标题1应该是主要内容区的开始, 是描述页面整体目的的主要标题。了解更多关于<a href="https://www.w3.org/WAI/tutorials/page-structure/headings/">标题结构.</a>的信息。',
      HEADING_EMPTY_WITH_IMAGE: '标题没有文字, 但包含一个图像。如果这不是一个标题, 请将其格式从<strong {r}>标题%(level)</strong>改为<strong>正常</strong>或<strong>段落</strong>。否则, 如果图片不是装饰性的, 请为其添加alt文本。',
      PANEL_HEADING_MISSING_ONE: '缺少标题1!',
      PANEL_NO_HEADINGS: '未找到标题.',
      LINK_EMPTY: '删除没有任何文字的空链接。',
      LINK_EMPTY_LABELLEDBY: '链接具有<code>aria-labelledby</code>的值为空或不与页面上另一个元素的<code>id</code>属性值匹配。',
      LINK_EMPTY_LINK_NO_LABEL: '链接没有可识别的文字, 对屏幕阅读器和其他辅助技术是可见的。要解决这个问题: <ul><li>添加一些简明的文字, 描述该链接带你到哪里。</li><li>如果它是一个<a href="https://a11y-101.com/development/icons-and-links">图标链接或SVG,</a>它可能缺少一个描述性的标签。</li><li>如果你认为这个链接是一个由于复制/粘贴错误造成的错误, 考虑删除它。</li></ul>。',
      LINK_LABEL: '<strong>链接标签: </strong> %(sanitizedText)',
      LINK_STOPWORD: '链接文本可能没有足够的描述性, 脱离了上下文: <strong {r}>%(error)</strong><hr><strong>提示！</strong>链接文本应始终清晰、独特和有意义。避免使用诸如&quot;点击这里&quot;或&quot;了解更多&quot等常见的词语；',
      LINK_BEST_PRACTICES: '考虑替换链接文本: <strong {r}>%(error)</strong><hr><ul><li>&quot;Click here&quot;将重点放在鼠标操作上, 而许多人并不使用鼠标, 或者可能在移动设备上浏览本网站。考虑使用一个与任务相关的不同动词。</li><li>避免使用HTML符号作为行动呼吁, 除非它们对辅助技术是隐藏的。',
      LINK_URL: '用作链接文本的较长的、不太容易理解的URL可能难以用辅助技术听懂。在大多数情况下, 最好使用人类可读的文本来代替URL。<hr><strong>提示！</strong>链接文本应该总是清晰、独特和有意义的, 这样它就可以脱离上下文而被理解。',
      LINK_DOI: '对于网页或纯在线资源，<a href="https://apastyle.apa.org/style-grammar-guidelines/paper-format/accessibility/urls#:~:text=descriptive%20links">APA风格指南</a>建议使用描述性链接，将作品的URL或DOI包裹在其标题上。使用较长的、不易理解的URL作为链接文本，在使用辅助技术访问时可能难以理解。',
      NEW_TAB_WARNING: '链接在新的标签或窗口中打开, 没有警告。这样做可能会使人迷失方向, 特别是对那些对视觉内容有感知困难的人来说。其次, 控制别人的体验或为他们做决定并不总是一种好的做法。在链接文本中指出该链接在新窗口中打开<hr><strong>提示！</strong>学习最佳做法: <a href="https://www.nngroup.com/articles/new-browser-windows-and-tabs/">在新的浏览器窗口和标签中打开链接。</a>',
      FILE_TYPE_WARNING: '链接指向PDF或可下载的文件 (如MP3、Zip、Word Doc) , 而没有警告。在链接文本中指出文件类型。如果是大文件, 可以考虑包括文件大小。<hr><strong>示例: </strong>执行报告 (PDF, 3MB) 。',
      LINK_IDENTICAL_NAME: '链接的文字与另一个链接相同, 但它指向不同的页面。<hr>考虑使以下链接更具描述性, 以帮助将其与其他链接区分开来: <strong {r}>%(sanitizedText)</strong>。',
      MISSING_ALT_LINK_BUT_HAS_TEXT_MESSAGE: '图片被用作带有周围文本的链接, 尽管alt属性应被标记为装饰性或空。',
      MISSING_ALT_LINK_MESSAGE: '图像被用作链接，但缺少替代文本！请确保替代文本描述了链接将您带到的位置。',
      MISSING_ALT_MESSAGE: '缺少替代文本！如果图像传达了故事、情绪或重要信息 - 请务必描述图像。',
      LINK_ALT_HAS_FILE_EXTENSION: '在alt文本中发现文件扩展名。确保alt文本描述链接的目的地, 而不是图片的字面描述。删除: <strong {r}>%(error)</strong>.<hr><strong>替代文字:</strong>%(altText)',
      LINK_IMAGE_PLACEHOLDER_ALT_MESSAGE: '发现链接图片中的非描述性或占位符的alt文本。确保alt文本描述了链接的目的地, 而不是图像的字面描述。替换以下alt文本: <strong {r}>%(altText)</strong>。',
      LINK_IMAGE_SUS_ALT_MESSAGE: '辅助技术已经表明这是一张图片, 所以&quot;<strong {r}>%(error)</strong>&quot; 可能是多余的。确保alt文本描述了链接的目的地, 而不是图像的字面描述。<hr> <strong>alt文本: </strong>%(altText)',
      ALT_HAS_FILE_EXTENSION: '在alt文本内找到文件扩展名。如果图片传达了一个故事、情绪或重要信息--一定要描述图片。删除: <strong {r}>%(error)</strong>.<hr><strong>替代文字:</strong>%(altText)',
      ALT_PLACEHOLDER_MESSAGE: '发现非描述性或占位符的alt文本。用更有意义的内容替换下面的alt文本: <strong {r}>%(altText)</strong>。',
      ALT_HAS_SUS_WORD: '辅助技术已经表明这是一张图片, 所以&quot;<strong {r}>%(error)</strong>&quot; 可能是多余的。<hr> <strong>替代文字:</strong> %(altText)',
      LINK_HIDDEN_FOCUSABLE: '链接具有<code>aria-hidden=&quot;true&quot;</code>，但仍可通过键盘聚焦。如果您打算隐藏多余或重复的链接，也请添加<code>tabindex=&quot;-1&quot;</code>。',
      LINK_IMAGE_NO_ALT_TEXT: '链接中的图像被标记为装饰性的, 没有链接文本。请在图片上添加描述链接目的地的alt文本。',
      LINK_IMAGE_HAS_TEXT: '图片被标记为装饰性的, 尽管链接是使用周围的文字作为描述性的标签。',
      LINK_IMAGE_LONG_ALT: '链接图片的alt文本描述<strong>太长</strong>。链接图片的alt文本应该描述链接的位置, 而不是图片的字面描述。<strong>考虑使用它所链接的页面的标题作为alt文本。</strong> <hr> <strong>alt文本 (<span {r}>% (altLength) </span>字符) : </strong> % (altText) 。',
      LINK_IMAGE_ALT_WARNING: '图片链接包含alt文本。符号文本是否描述了该链接的位置？可以考虑使用它所链接的页面的标题作为alt文本。 <hr> <strong>替代文字:</strong> %(altText)',
      LINK_IMAGE_ALT_AND_TEXT_WARNING: '图片链接包含<strong>alt文本和周围的链接文本。</strong>如果该图片是装饰性的, 并被用作另一个页面的功能链接, 请考虑将该图片标记为装饰性或无效--周围的链接文本应该足够了。<hr> <strong>标题文本: </strong> %(altText) <hr> <strong>链接标签: </strong> %(sanitizedText)',
      IMAGE_FIGURE_DECORATIVE: '图片被标记为<strong>装饰性</strong>, 将被辅助技术所忽略。<hr> 虽然提供了一个<strong>标题</strong>, 但在大多数情况下, 图像也应该有alt文本。<ul><li>alt文本应该对图像中的内容进行简明的描述。</li><li>标题通常应该提供背景, 将图像与周围的内容联系起来, 或者对某一特定的信息给予关注: <a href="https://thoughtbot.com/blog/alt-vs-figcaption#the-figcaption-element">alt与figcaption.</a>',
      IMAGE_FIGURE_DUPLICATE_ALT: '不要在alt和标题文本中使用完全相同的词。屏幕阅读器会将信息公布两次。<ul><li>alt文本应提供对图片中内容的简明描述。</li><li>标题通常应提供背景, 将图片与周围的内容联系起来, 或对某一特定信息给予关注。</li></ul>了解更多: <a href="https://thoughtbot.com/blog/alt-vs-figcaption#the-figcaption-element">alt与figcaption.</a> <hr> <strong>替代文字:</strong> %(altText)',
      IMAGE_DECORATIVE: '图片被标记为<strong>装饰性</strong>, 将被辅助技术所忽略。如果图片传达了一个故事、情绪或重要的信息--请务必添加alt文本。',
      IMAGE_ALT_TOO_LONG: 'Alt文本描述<strong>太长</strong>。Alt文本应该是简洁的, 但又像<em>tweet</em>一样有意义 (大约100个字符) 。如果这是一张复杂的图片或图表, 可以考虑将图片的长篇描述放在下面的文字或手风琴组件中。<hr> <strong>标题文本 (<span {r}>%(altLength)</span>字符) : </strong> %(altText)',
      IMAGE_PASS: '<strong>替代文本: </strong>%(altText)',
      LABELS_MISSING_IMAGE_INPUT_MESSAGE: '图片按钮缺少alt文本。请添加alt文本, 提供一个可访问的名称。比如说: <em>Search</em>或<em>Submit</em>。',
      LABELS_INPUT_RESET_MESSAGE: '除非特别需要, 否则不应<strong></strong>使用重置按钮, 因为它们很容易被错误激活。<hr> <strong>提示！</strong>了解为什么<a href="https://www.nngroup.com/articles/reset-and-cancel-buttons/">复位和取消按钮会带来可用性问题。</a>',
      LABELS_ARIA_LABEL_INPUT_MESSAGE: '输入有一个无障碍名称, 但请确保也有一个可见的标签。<hr> <strong>输入标签：</strong> %(sanitizedText)',
      LABELS_NO_FOR_ATTRIBUTE_MESSAGE: '没有与此输入相关的标签。给标签添加一个<code>for</code>属性, 该属性与该输入的<code>id</code>相匹配。<hr> 这个输入的ID是: <strong>id=&#34;% (id) &#34;</strong>',
      LABELS_MISSING_LABEL_MESSAGE: '没有与此输入相关的标签。请为这个输入添加一个<code>id</code>, 并为标签添加一个匹配的<code>for</code>属性。',
      EMBED_VIDEO: '请确保<strong>所有视频都有闭合字幕。</strong>为所有音频和视频内容提供字幕是一项强制性的A级要求。字幕支持聋哑人或听力困难的人。',
      EMBED_AUDIO: '请确保为所有播客提供<strong>文字记录。</strong>为音频内容提供文字记录是一项强制性的A级要求。转录支持聋哑人或听力困难的人, 但也能使所有人受益。考虑将文字记录放在下面或放在一个手风琴面板内。',
      EMBED_DATA_VIZ: '像这样的数据可视化部件对于使用键盘或屏幕阅读器导航的人来说往往是有问题的, 而且对于低视力或色盲的人来说也会带来很大的困难。建议在小组件下方以替代 (文本或表格) 的形式提供相同的信息。<hr> 了解更多关于<a href="https://www.w3.org/WAI/tutorials/images/complex">复杂图像的信息。</a>',
      EMBED_MISSING_TITLE: '嵌入内容需要一个描述其内容的可访问名称。请在<code>iframe</code>元素上提供一个独特的<code>title</code>或<code>aria-label</code>属性。了解更多关于<a href="https://web.dev/learn/accessibility/more-html#iframes">iFrames.</a>的信息。',
      EMBED_GENERAL_WARNING: '无法检查嵌入式内容。请确保图像有alt文本, 视频有标题, 文本有足够的对比度, 互动组件是<a href="https://webaim.org/techniques/keyboard/">键盘可访问的。</a>',
      EMBED_UNFOCUSABLE: '带有无法聚焦元素的 <code>&lt;iframe&gt;</code> 不应具有 <code>tabindex="-1"</code>。嵌入内容将无法通过键盘访问。',
      QA_BAD_LINK: '发现坏的链接。链接似乎指向一个开发环境。<hr> 这个链接指向: <br> <strong {r}>%(el)</strong>',
      QA_IN_PAGE_LINK: '破损的同页链接。链接目标与此页面上的任何元素都不匹配。',
      QA_BAD_ITALICS: '粗体和斜体标签具有语义, 不应<strong></strong>用于突出整个段落。加粗的文字应该用于对一个词或短语进行强烈的<strong>强调</strong>。斜体字应该用来突出专有名词 (即书名和文章标题) 、外国词、引号。长篇引语应采用块状引语的格式。',
      QA_PDF: '无法检查PDF的可访问性。PDF被认为是网络内容, 也必须做到无障碍。对于使用屏幕阅读器的人 (缺失结构标签或缺失表格字段标签) 和低视力的人 (文本在放大时不回流) 来说, PDF经常包含一些问题。<ul><li>如果这是一个表格, 请考虑使用可访问的HTML表格作为替代。</li><li>如果这是一个文档, 请考虑将其转换为网页。</li></ul>否则, 请在Acrobat DC中检查<a href="https://helpx.adobe.com/acrobat/using/create-verify-pdf-accessibility.html">PDF的可访问性。</a>',
      QA_DOCUMENT: '无法检查文件的可访问性。链接文件被认为是网络内容, 也必须做到无障碍。请手动审查该文件。<ul><li>使您的<a href="https://support.google.com/docs/answer/6199477?hl=zh">Google Workspace文档或演示文稿更易于访问。</a></li><li>使您的<a href="https://support.microsoft.com/zh/office/create-accessible-office-documents-868ecfcd-4f00-4224-b881-a65537a7c155">Office文档更易于访问。</a></ul>。',
      QA_PAGE_LANGUAGE: '页面语言未声明!请<a href="https://www.w3.org/International/questions/qa-html-language-declarations">在HTML标签上声明语言。</a>',
      QA_PAGE_TITLE: '缺少页面标题!请提供一个<a href="https://developer.mozilla.org/zh/docs/Web/HTML/Element/title">页面标题。</a>',
      QA_BLOCKQUOTE_MESSAGE: '这是一个标题吗？<strong {r}>%(sanitizedText)</strong> <hr> 方块引号应该只用于引号。如果这是一个标题, 请将这个区块引号改为语义标题 (例如标题2或标题3) 。',
      QA_FAKE_HEADING: '这是一个标题吗？<strong {r}>%(boldtext)</strong> <hr> 一行粗体或大字体可能看起来像一个标题, 但使用屏幕阅读器的人无法看出它的重要性或跳到它的内容。粗体或大字体永远不应取代语义标题 (标题2至标题6) 。',
      QA_SHOULD_BE_LIST: '您是否试图创建一个列表？找到了可能的列表项: <strong {r}>%(firstPrefix)</strong> <hr> 请确保使用语义列表, 用子弹或数字格式按钮代替。当使用语义列表时, 辅助技术能够传达信息, 如项目的总数和每个项目在列表中的相对位置。了解更多关于<a href="https://www.w3.org/WAI/tutorials/page-structure/content/#lists">语义列表的信息。</a>',
      QA_UPPERCASE_WARNING: '发现全大写。一些屏幕阅读器可能会将所有大写字母的文本解释为缩写, 并会单独阅读每个字母。此外, 有些人觉得全大写的文字更难读, 而且可能给人一种大喊大叫的感觉。',
      QA_DUPLICATE_ID: '发现<strong>重复的ID</strong>。众所周知, 当辅助技术试图与内容互动时, 重复的ID错误会给辅助技术带来问题。<hr> 请删除或更改以下ID: <strong {r}>%(id)</strong>。',
      QA_TEXT_UNDERLINE_WARNING: '带下划线的文本可能会与链接相混淆。考虑使用不同的风格, 如<code>&lt;strong&gt;</code><strong>strong重要性</strong><code>&lt;/strong&gt;</code>或<code>&lt;em&gt;</code><em>emphasis</em><code>&lt；/em&gt；</code>。',
      QA_SUBSCRIPT_WARNING: '下标和上标格式化选项只能用于改变文字的位置, 以符合排版习惯或标准。它不应该<strong></strong>仅仅用于演示或外观目的。对整个句子进行格式化会带来可读性问题。适当的使用情况包括显示指数、序数, 如4<sup>th</sup>而不是第四, 以及化学公式 (如H<sub>2</sub>O) 。',
      TABLES_MISSING_HEADINGS: '缺少表头!可访问的表格需要HTML标记, 表明标题单元和数据单元, 定义它们的关系。这种信息为使用辅助技术的人提供了背景。表格应该只用于表格式的数据。<hr> 了解更多关于<a href="https://www.w3.org/WAI/tutorials/tables/">无障碍表格的信息。</a>',
      TABLES_SEMANTIC_HEADING: '语义标题, 如Heading 2或Heading 3, 只能用于内容的章节；<strong>不能</strong>用于HTML表格。使用<code>&lt;th&gt;</code>元素来表示表格的标题。<hr> 了解更多关于<a href="https://www.w3.org/WAI/tutorials/tables/">可访问的表格。</a>',
      TABLES_EMPTY_HEADING: '发现空的表头!表头应该<strong>永远不会</strong>是空的。指定行和/或列的标题以表达它们的关系是很重要的。这一信息为使用辅助技术的人提供了背景。请记住, 表格应该只用于表格式数据。<hr> 了解更多关于<a href="https://www.w3.org/WAI/tutorials/tables/">可访问的表格。</a>',
      CONTRAST_ERROR: '这个文本与背景的对比度不够。普通文本的对比度至少应该是4.5:1, 大文本的对比度应该是3:1。<hr> 以下文本的对比度为<strong {r}>%(cratio)</strong>: <strong {r}>%(sanitizedText) </strong>',
      CONTRAST_WARNING: '该文本的对比度不明, 需要人工审查。确保文字和背景有强烈的颜色对比。正常文本的对比度应至少为4.5:1, 大型文本为3:1。<hr> <strong>请审查: </strong> %(sanitizedText)',
      CONTRAST_INPUT_ERROR: '该输入的文字与背景的对比度不够。普通文本的对比度应该至少为4.5:1, 大文本的对比度应该为3:1。<hr> 对比度: <strong {r}>%(cratio)</strong>',
    },
  };

  return zh;

}));
