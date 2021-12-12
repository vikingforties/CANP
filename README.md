# CANP ver 4.0

![CANP banner](https://raw.githubusercontent.com/vikingforties/CANP/master/CANPbanner.PNG)

| Credits: | Copyright (c) Pete Logan, 2021
| --- | --- 
| Licence: | MIT 
| Status: | released 
| Tags: | paragliding, RAF 

Web App to submit Civil Aircraft Notification Procedure emails to the RAF. Intended for the UK free flight community to use.

Flying mid week in the UK should involve warning the RAF about intended active free flying sites. 
CANP is a procedure to do this and this web app is for streamlining that process. No more phoning in and waiting. 

Index.html takes in user options from a drop down lists and validates the pilot choices in javascript. It then submits to send_canp_email.php.
This further validates content server side and uses the PEAR PHP mail library to send on. The path to the PEAR lib is up to you and the css files
should be dropped into a /styles directory.

I used a bunch of nice formatting tools like bootstrap.css, awesome fonts and HTML5 to make this a simple and good looking user experience.

It can be viewed in action here: http://canp.logans.me.uk


Disclaimer
==========

This is software supplied as is. THERE IS NO WARRANTY FOR THIS PROGRAM.

The author shall not be responsible for loss of data under any circumstance.

Please make regular backups of data.


Runtime Requirements
--------------------
* ISP preferrably with CPanel
* PHP 
* MySQL or MariaDB


Licence
=======

This program is free software: you can redistribute it and/or modify
it under the terms of the MIT License. 
[![License](http://img.shields.io/:license-mit-blue.svg?style=flat-square)](http://badges.mit-license.org)

- **[MIT license](http://opensource.org/licenses/mit-license.php)**

Credits
=======
* Mail library from PEAR - https://pear.php.net/package/Mail/
* Bootstrap - https://getbootstrap.com/
* Font Awesome - https://fontawesome.com/

