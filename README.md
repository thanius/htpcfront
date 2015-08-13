# htpcfront 2.0
A simple button based menu for web based HTPC's (using Google Chrome)

![alt text][channels]

## Features!
- Up to 20 awesome buttons!
- Fancy-ass flip animations and shady fade-insandouts!
- Easy configuration!
- Google search bar for kiosk mode: Type in URL or search term

## Installation
1. Install PHP5 compatible webserver and enable file upload in PHP.ini
2. Install ImageMagick PHP5 plugin
3. Copy or move HTPC folder to web path
4. Shutdown script: Give sudo permissions to www-data in /etc/sudoers:
  - www-data ALL = NOPASSWD: /var/www/html/includes/shutdown.sh
5. Give all permissions to www-data:
```
sudo chown -R www-data:www-data /var/www/html/*
```
6. Give write permissions to 'buttons/' and 'includes/':
```
 sudo chmod 775 buttons/ includes/
```

## Configuration
1. First run:
  - Either) Add your buttons to 'buttons/' folder (360x200 for best result) 
  - Or) Upload them using the awesome image uploader on configuration page

2. Enter URL in field underneath image on button
3. Drag and sort channels in prefered order
4. Click save button

## How to use
* Click on button to be redirected to service of choice
* Click on Google button to search on Google or open URL
* Hover over top right corner to reveal shutdown button
* Click shutdown button to shutdown computer (if configured correctly!)
* Right click shutdown button to enter configuration

![alt text][google]

[channels]: https://github.com/thanius/htpcfront/blob/master/channels.png "Screenshot of channels (default) page"
[google]: https://github.com/thanius/htpcfront/blob/master/google.png "Screenshot of Google search page"
