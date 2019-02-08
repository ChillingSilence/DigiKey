DigiQi physical authentication with Digi-ID
===========================
DigiQi is a framework / demo-site that gives you an authentication method for building access-control.
The DigiQi authentication allows you to easily get started, add a Super-admin user, add additional admins, and authorize / deny users access to a door.

DigiQi can easily be modified to suit multiple entranceways as well and integrate in to existing building infrastructure.

Installation
============
* Create a MySQL database, import struct.sql into it ( mysql -u digibyte -p digikey < ./struct.sql )
* Configure database information and server url in config.php
* After the first user has signed up with their Digi-ID, navigate to /admin.php on that same device and your Digi-ID will be elevated to becoming an Admin user, allowing you to approve / deny other permission requests

Notes
=====
* GMP PHP extension is required

