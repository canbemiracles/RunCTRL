When using history mode, the URL will look "normal," e.g. http://oursite.com/user/id. Beautiful!

Here comes a problem, though: Since our app is a single page client side app, without a proper server configuration, the users will get a 404 error if they access http://oursite.com/user/id directly in their browser. Now that's ugly.

Not to worry: To fix the issue, all you need to do is add a simple catch-all fallback route to your server. If the URL doesn't match any static assets, it should serve the same index.html page that your app lives in. Beautiful, again!

Example Server Configurations

Apache

<IfModule mod_rewrite.c>
  RewriteEngine On
  RewriteBase /
  RewriteRule ^index\.html$ - [L]
  RewriteCond %{REQUEST_FILENAME} !-f
  RewriteCond %{REQUEST_FILENAME} !-d
  RewriteRule . /index.html [L]
</IfModule>
nginx

location / {
  try_files $uri $uri/ /index.html;
}

////////////////////////////
for details <a href="https://router.vuejs.org/en/essentials/history-mode.html">linl</a>