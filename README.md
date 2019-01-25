DigiKey physical authentication with Digi-ID
===========================

Installation
============
* Create a MySQL database, import struct.sql into it ( mysql -u digibyte -p digikey < ./struct.sql )
* Configure database information and server url in config.php
* After the first user has signed up with their Digi-ID, navigate to /admin.php on that same device and your Digi-ID will be elevated to becoming an Admin user, allowing you to approve / deny other permission requests

Notes
=====
* GMP PHP extension is required

