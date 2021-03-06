# OTGS Icons

The fonts are generated by using [fontastic.me](http://fontastic.me/).

Login: christian.g@icanlocalize.com
Password: otgsawesome

# Manual Todo before updating the icons
fontastic.me does not provide `.woff2` files, which are 30% smaller than `.woff` files. So we have to apply them manually. 

Once you have the new package downloaded by fontastic.me pick the new generated `otgs-icons.woff` file and convert it to `otgs-icons.woff2`. You can use the [Google converter](https://github.com/google/woff2) or an online converter like https://cloudconvert.com/woff-to-woff2 (when using an online converter make sure the file is about 30% smaller - I had one which "compressed" it to a larger file).

Once you have the `.woff2` file put it also in the `./fonts/` directory.

Next pick the new generated `otgs-icons.css` file and...

Replace
```css
@font-face {
  font-family: "otgs-icons";
  src:url("../fonts/otgs-icons.eot");
  src:url("../fonts/otgs-icons.eot?#iefix") format("embedded-opentype"),
    url("../fonts/otgs-icons.woff") format("woff"),
    url("../fonts/otgs-icons.ttf") format("truetype"),
    url("../fonts/otgs-icons.svg#otgs-icons") format("svg");
  font-weight: normal;
  font-style: normal;

}
```

With
```css
@font-face {
  font-family: "otgs-icons";
  src:url("../fonts/otgs-icons.eot");
  src:url("../fonts/otgs-icons.eot?#iefix") format("embedded-opentype"),
    url("../fonts/otgs-icons.woff2") format("woff2"),
    url("../fonts/otgs-icons.woff") format("woff"),
    url("../fonts/otgs-icons.ttf") format("truetype"),
    url("../fonts/otgs-icons.svg#otgs-icons") format("svg");
  font-weight: normal;
  font-style: normal;

}
```
