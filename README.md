# ys-site-icon-extension
WordPressのテーマカスタマイザー項目「サイトアイコン」を拡張するプラグイン

WordPress 4.3から追加された「サイトアイコン」で出力されるlinkタグをファビコンとapple touch iconで分けて指定できるようになります。

## テーマカスタマイザー設定項目で出力されるHTMLタグ（link）

サイトアイコン（WordPress標準設定項目）
：ファビコン
```
<link rel="icon" href="[画像URL]" sizes="32x32" />
<link rel="icon" href="[画像URL]" sizes="192x192" />
```

apple touch icon（本プラグインにて追加）
：apple touch icon
```
<link rel="apple-touch-icon-precomposed" href="[画像URL]" />
<meta name="msapplication-TileImage" content="[画像URL]" />
```